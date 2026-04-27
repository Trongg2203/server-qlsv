<?php

namespace App\Services;

use App\Models\CalorieCalculationModel;
use App\Models\UserGoalModel;
use App\Models\UserProfileModel;
use App\Repositories\CalorieCalculation\ICalorieCalculationRepository;
use App\Repositories\UserGoal\IUserGoalRepository;
use App\Repositories\UserProfile\IUserProfileRepository;

class CalorieCalculationService extends BaseService
{
    private IUserProfileRepository $profileRepo;
    private IUserGoalRepository $goalRepo;

    public function __construct(
        ICalorieCalculationRepository $repo,
        IUserProfileRepository $profileRepo,
        IUserGoalRepository $goalRepo
    ) {
        $this->repo        = $repo;
        $this->profileRepo = $profileRepo;
        $this->goalRepo    = $goalRepo;
    }

    /**
     * Tự động tính và lưu calorie dựa trên profile + goal hiện tại của user.
     * Công thức Mifflin-St Jeor:
     *   Male   BMR = 10W + 6.25H - 5A + 5
     *   Female BMR = 10W + 6.25H - 5A - 161
     */
    public function calculateAndSave(): object
    {
        $userId  = auth()->guard('api')->id();
        $profile = $this->profileRepo->getProfile($userId);
        $goal    = $this->goalRepo->getActiveGoalByUser($userId);

        abort_if(!$profile, 422, 'Chưa có thông tin hồ sơ cá nhân.');
        abort_if(!$goal, 422, 'Chưa có mục tiêu đang hoạt động.');

        $age    = now()->diffInYears($profile->date_of_birth);
        $weight = (float) $profile->current_weight;
        $height = (float) $profile->height;
        $gender = (int) $profile->gender; // 0=Male, 1=Female (theo UserProfileModel)

        // Mifflin-St Jeor: Male +5, Female -161
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age + ($gender === 0 ? 5 : -161);

        // TDEE = BMR × activity multiplier
        // Supports both 0-indexed (seeder: 0-4) and 1-indexed (1-5) activity levels
        $activityMultipliers = [
            0 => 1.2,    // sedentary (0-indexed from seeder)
            1 => 1.375,  // lightly active
            2 => 1.55,   // moderately active
            3 => 1.725,  // very active
            4 => 1.9,    // extremely active
            5 => 1.9,    // extra (1-indexed alias for 4)
        ];
        $multiplier          = $activityMultipliers[$profile->activity_level] ?? 1.2;
        $tdee                = $bmr * $multiplier;

        // Target calories
        [$targetCalories, $macroRatio] = $this->applyGoalAdjustment($tdee, $goal, $gender);

        // Macro grams
        [$proteinG, $carbsG, $fatG] = $this->calcMacroGrams($targetCalories, $macroRatio);

        $record = [
            'id'              => generateRandomString(),
            'user_id'         => $userId,
            'goal_id'         => $goal->id,
            'bmr'             => round($bmr, 2),
            'tdee'            => round($tdee, 2),
            'target_calories' => round($targetCalories, 2),
            'protein_grams'   => round($proteinG, 2),
            'carbs_grams'     => round($carbsG, 2),
            'fat_grams'       => round($fatG, 2),
            'macro_ratio'     => $macroRatio,
            'valid_from'      => now()->toDateString(),
            'created_at'      => now(),
        ];

        return $this->repo->create($record);
    }

    public function getLatest(): ?object
    {
        $userId = auth()->guard('api')->id();
        return $this->repo->getLatestByUser($userId);
    }

    public function getHistory(): array
    {
        $userId = auth()->guard('api')->id();
        return $this->repo->getHistoryByUser($userId);
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function applyGoalAdjustment(float $tdee, object $goal, int $gender): array
    {
        $minCalories = $gender === 0 ? 1500 : 1200; // Male=0 needs 1500, Female=1 needs 1200

        switch ($goal->goal_type) {
            case UserGoalModel::GOAL_CUTTING:
                // Deficit: ~500 kcal/day → ~0.5 kg/week
                $target     = max($minCalories, $tdee - 500);
                $macroRatio = CalorieCalculationModel::MACRO_CUTTING;
                break;

            case UserGoalModel::GOAL_BULKING:
                // Surplus: ~300 kcal/day
                $target     = $tdee + 300;
                $macroRatio = CalorieCalculationModel::MACRO_BULKING;
                break;

            default: // maintaining
                $target     = $tdee;
                $macroRatio = CalorieCalculationModel::MACRO_MAINTAINING;
        }

        return [$target, $macroRatio];
    }

    private function calcMacroGrams(float $calories, int $macroRatio): array
    {
        // Ratios: protein% / carbs% / fat%
        $ratioMap = [
            CalorieCalculationModel::MACRO_CUTTING    => [0.40, 0.30, 0.30],
            CalorieCalculationModel::MACRO_BULKING    => [0.30, 0.45, 0.25],
            CalorieCalculationModel::MACRO_MAINTAINING => [0.30, 0.40, 0.30],
        ];

        [$pRatio, $cRatio, $fRatio] = $ratioMap[$macroRatio] ?? $ratioMap[2];

        // 1g protein = 4 kcal, 1g carbs = 4 kcal, 1g fat = 9 kcal
        $proteinG = ($calories * $pRatio) / 4;
        $carbsG   = ($calories * $cRatio) / 4;
        $fatG     = ($calories * $fRatio) / 9;

        return [$proteinG, $carbsG, $fatG];
    }
}
