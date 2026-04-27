<?php

namespace App\Services;

use App\Models\MealPlanModel;
use App\Repositories\CalorieCalculation\ICalorieCalculationRepository;
use App\Repositories\Food\IFoodRepository;
use App\Repositories\FoodRating\IFoodRatingRepository;
use App\Repositories\MealPlan\IMealPlanRepository;
use App\Repositories\MealPlanDetail\IMealPlanDetailRepository;
use App\Repositories\UserGoal\IUserGoalRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MealPlanService extends BaseService
{
    private IMealPlanDetailRepository $detailRepo;
    private IUserGoalRepository $goalRepo;
    private ICalorieCalculationRepository $calorieRepo;
    private IFoodRepository $foodRepo;
    private IFoodRatingRepository $ratingRepo;
    private PythonAiService $pythonAi;

    public function __construct(
        IMealPlanRepository $repo,
        IMealPlanDetailRepository $detailRepo,
        IUserGoalRepository $goalRepo,
        ICalorieCalculationRepository $calorieRepo,
        IFoodRepository $foodRepo,
        IFoodRatingRepository $ratingRepo,
        PythonAiService $pythonAi
    ) {
        $this->repo        = $repo;
        $this->detailRepo  = $detailRepo;
        $this->goalRepo    = $goalRepo;
        $this->calorieRepo = $calorieRepo;
        $this->foodRepo    = $foodRepo;
        $this->ratingRepo  = $ratingRepo;
        $this->pythonAi    = $pythonAi;
    }

    /**
     * Luồng tạo meal plan 7 ngày:
     * 1. Lấy goal + calorie_calculation hiện tại của user
     * 2. Lấy danh sách món ăn từ DB
     * 3. Gọi Python AI để generate meal plan
     * 4. Lưu kết quả vào meal_plans + meal_plan_details
     * 5. Tăng popularity_score của các món được chọn
     * 6. Trả về meal plan đầy đủ
     */
    public function generate(array $options = []): object
    {
        $userId      = auth()->guard('api')->id();
        $goal        = $this->goalRepo->getActiveGoalByUser($userId);
        $calorieCalc = $this->calorieRepo->getLatestByUser($userId);

        abort_if(!$goal, 422, 'Chưa có mục tiêu đang hoạt động.');
        abort_if(!$calorieCalc, 422, 'Chưa tính toán calo. Hãy tính trước.');

        // Lấy tất cả món ăn để gửi lên Python
        $foods = $this->foodRepo->getForAi();

        // Lấy thông tin ratings của user để hỗ trợ Collaborative Filtering
        $userRatings    = $this->ratingRepo->getByUser($userId);
        $ratedFoodIds   = collect($userRatings['data'])->pluck('food_id')->toArray();
        $nUserRatings   = $userRatings['total'];

        // Chuẩn bị payload cho Python AI
        $payload = [
            'user_id'         => (string) $userId,
            'goal_type'       => $goal->goal_type,
            'target_calories' => (float) $calorieCalc->target_calories,
            'protein_grams'   => (float) $calorieCalc->protein_grams,
            'carbs_grams'     => (float) $calorieCalc->carbs_grams,
            'fat_grams'       => (float) $calorieCalc->fat_grams,
            'allergens'       => $options['allergens'] ?? [],
            'disliked_foods'  => $options['disliked_foods'] ?? [],
            'foods'           => $foods['data']->toArray(),
            'rated_food_ids'  => $ratedFoodIds,
            'n_user_ratings'  => $nUserRatings,
        ];

        // Gọi Python AI
        $aiResult = $this->pythonAi->generateMealPlan($payload);

        // Lưu vào DB trong transaction
        DB::beginTransaction();
        try {
            // Mark old plans as replaced
            $this->repo->replaceOldPlans($userId);

            // Tạo meal_plan header
            $startDate = now()->toDateString();
            $endDate   = now()->addDays(6)->toDateString();

            $planId = generateRandomString();
            $plan   = $this->repo->create([
                'id'                     => $planId,
                'user_id'                => $userId,
                'goal_id'                => $goal->id,
                'calorie_calculation_id' => $calorieCalc->id,
                'plan_name'              => 'Thực đơn 7 ngày — ' . now()->format('d/m/Y'),
                'start_date'             => $startDate,
                'end_date'               => $endDate,
                'target_calories_per_day' => $calorieCalc->target_calories,
                'generation_method'      => $aiResult['generation_method'] ?? MealPlanModel::METHOD_CONTENT_BASED,
                'status'                 => MealPlanModel::STATUS_ACTIVE,
                'created_at'             => now(),
            ]);

            // Chèn tất cả details
            $detailRows  = [];
            $usedFoodIds = [];

            foreach ($aiResult['days'] as $dayData) {
                $dayNumber = $dayData['day_number'];
                foreach ($dayData['meals'] as $meal) {
                    $detailRows[] = [
                        'id'             => generateRandomString(),
                        'meal_plan_id'   => $planId,
                        'food_id'        => $meal['food_id'],
                        'day_number'     => $dayNumber,
                        'meal_type'      => $meal['meal_type'],
                        'servings'       => $meal['servings'],
                        'total_calories' => $meal['total_calories'],
                        'total_protein'  => $meal['total_protein'],
                        'total_carbs'    => $meal['total_carbs'],
                        'total_fat'      => $meal['total_fat'],
                    ];
                    $usedFoodIds[] = $meal['food_id'];
                }
            }

            $this->detailRepo->bulkInsert($detailRows);

            // Tăng popularity_score cho các món được chọn
            $this->foodRepo->incrementPopularity(array_unique($usedFoodIds));

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('MealPlanService::generate error', ['error' => $e->getMessage()]);
            throw $e;
        }

        // Trả về meal plan đầy đủ kèm details
        return $this->repo->getActiveByUser($userId);
    }

    public function getMyPlans(): array
    {
        $userId = auth()->guard('api')->id();
        return $this->repo->getByUser($userId);
    }

    public function getActivePlan(): ?object
    {
        $userId = auth()->guard('api')->id();
        return $this->repo->getActiveByUser($userId);
    }
}
