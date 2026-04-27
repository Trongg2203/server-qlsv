<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * MealPlansSeeder — Tạo dữ liệu thực đơn 7 ngày mẫu.
 *
 * Mỗi user có 2 thực đơn:
 *  - Plan 1 (cũ hơn): generation_method = 1 (content_based, cold-start)
 *  - Plan 2 (mới hơn): generation_method = 2 (collaborative filtering)
 *
 * Mỗi plan có 21 meal_plan_details (7 ngày × 3 bữa: sáng/trưa/tối).
 * Món ăn được chọn phù hợp với goal của user và bữa ăn trong ngày.
 */
class MealPlansSeeder extends Seeder
{
    const MEAL_BREAKFAST = 1;
    const MEAL_LUNCH     = 2;
    const MEAL_DINNER    = 3;

    const GOAL_CUTTING    = 0;
    const GOAL_BULKING    = 1;
    const GOAL_MAINTAINING = 2;

    // Phân bổ calo theo bữa (%)
    const DIST_BREAKFAST = 0.25;
    const DIST_LUNCH     = 0.40;
    const DIST_DINNER    = 0.35;

    public function run(): void
    {
        // ── Lấy dữ liệu users + goal + calorie calculation ───────
        $users = DB::table('users as u')
            ->join('user_goals as g', 'g.user_id', '=', DB::raw('CAST(u.id AS CHAR)'))
            ->join('calorie_calculations as c', function ($j) {
                $j->on('c.user_id', '=', DB::raw('CAST(u.id AS CHAR)'))
                  ->on('c.goal_id', '=', 'g.id');
            })
            ->select(
                'u.id as user_id',
                'g.id as goal_id',
                'g.goal_type',
                'g.start_date',
                'c.id as calc_id',
                'c.target_calories',
                'c.protein_grams',
                'c.carbs_grams',
                'c.fat_grams'
            )
            ->where('g.status', 0)
            ->get();

        if ($users->isEmpty()) {
            $this->command->warn('MealPlansSeeder: Không có users. Hãy chạy GymUsersSeeder trước.');
            return;
        }

        // ── Lấy foods theo meal_type và dinh dưỡng ───────────────
        $allFoods = DB::table('foods')
            ->select('id', 'name', 'calories', 'protein', 'carbs', 'fat', 'meal_type', 'serving_size')
            ->get();

        // Phân loại foods theo meal_type
        $foodsByMeal = [
            self::MEAL_BREAKFAST => $allFoods->filter(fn($f) => in_array((int)$f->meal_type, [0, 1]))->values(),
            self::MEAL_LUNCH     => $allFoods->filter(fn($f) => in_array((int)$f->meal_type, [0, 2]))->values(),
            self::MEAL_DINNER    => $allFoods->filter(fn($f) => in_array((int)$f->meal_type, [0, 3]))->values(),
        ];

        $planCount   = 0;
        $detailCount = 0;
        $planBatch   = [];
        $detailBatch = [];

        foreach ($users as $user) {
            $goal        = (int) $user->goal_type;
            $targetCal   = (float) $user->target_calories;
            $startDate   = $user->start_date;

            // Plan 1: content_based (bắt đầu ngay sau goal start)
            $plan1Start = $startDate;
            $plan1End   = date('Y-m-d', strtotime($plan1Start . ' +6 days'));

            // Plan 2: collaborative (bắt đầu 5 tuần sau plan 1)
            $plan2Start = date('Y-m-d', strtotime($plan1Start . ' +35 days'));
            $plan2End   = date('Y-m-d', strtotime($plan2Start . ' +6 days'));

            $goalLabel  = ['Cắt giảm mỡ', 'Tăng cơ bắp', 'Duy trì vóc dáng'][$goal];

            $plans = [
                [
                    'name'   => "Thực đơn {$goalLabel} - Tuần 1",
                    'method' => 1, // content_based
                    'start'  => $plan1Start,
                    'end'    => $plan1End,
                    'status' => 1, // completed
                ],
                [
                    'name'   => "Thực đơn {$goalLabel} - Tuần 2 (AI Cộng tác)",
                    'method' => 2, // collaborative
                    'start'  => $plan2Start,
                    'end'    => $plan2End,
                    'status' => 0, // active
                ],
            ];

            foreach ($plans as $planData) {
                $planId = generateRandomString(10);

                $planBatch[] = [
                    'id'                       => $planId,
                    'user_id'                  => (string) $user->user_id,
                    'goal_id'                  => $user->goal_id,
                    'calorie_calculation_id'   => $user->calc_id,
                    'plan_name'                => $planData['name'],
                    'start_date'               => $planData['start'],
                    'end_date'                 => $planData['end'],
                    'target_calories_per_day'  => $targetCal,
                    'generation_method'        => $planData['method'],
                    'status'                   => $planData['status'],
                    'created_at'               => now()->format('Y-m-d H:i:s'),
                ];
                $planCount++;

                // ── 21 meal_plan_details (7 ngày × 3 bữa) ─────
                $usedFoodIds = [];  // tránh trùng món quá nhiều

                for ($day = 1; $day <= 7; $day++) {
                    foreach ([self::MEAL_BREAKFAST, self::MEAL_LUNCH, self::MEAL_DINNER] as $mealType) {
                        $calTarget = $targetCal * $this->mealCalPct($mealType);

                        $food = $this->pickFood(
                            $foodsByMeal[$mealType],
                            $goal,
                            $calTarget,
                            $usedFoodIds
                        );

                        if ($food === null) continue;

                        $usedFoodIds[] = $food->id;
                        $servings      = round($calTarget / max(1, $food->calories), 2);
                        $servings      = max(0.5, min(3.0, $servings));

                        $detailBatch[] = [
                            'id'              => generateRandomString(10),
                            'meal_plan_id'    => $planId,
                            'food_id'         => $food->id,
                            'day_number'      => $day,
                            'meal_type'       => $mealType,
                            'servings'        => $servings,
                            'total_calories'  => round($food->calories * $servings, 2),
                            'total_protein'   => round($food->protein * $servings, 2),
                            'total_carbs'     => round($food->carbs * $servings, 2),
                            'total_fat'       => round($food->fat * $servings, 2),
                        ];
                        $detailCount++;

                        // Flush theo batch
                        if (count($detailBatch) >= 500) {
                            DB::table('meal_plan_details')->insert($detailBatch);
                            $detailBatch = [];
                        }
                    }
                }
            }

            // Flush plans batch
            if (count($planBatch) >= 100) {
                DB::table('meal_plans')->insert($planBatch);
                $planBatch = [];
            }
        }

        // Flush cuối
        if (!empty($planBatch))   DB::table('meal_plans')->insert($planBatch);
        if (!empty($detailBatch)) DB::table('meal_plan_details')->insert($detailBatch);

        $this->command->info("MealPlansSeeder: {$planCount} plans + {$detailCount} details inserted.");
    }

    /* ──────────────────────────────────────────────────────────────
     |  Chọn món ăn phù hợp với goal và bữa ăn
     | ──────────────────────────────────────────────────────────────
     |  Ưu tiên:
     |  - Cutting: protein density cao, calo gần target
     |  - Bulking:  calo cao + protein cao, gần target
     |  - Maintaining: balanced, gần target calo
     └────────────────────────────────────────────────────────────── */
    private function pickFood(
        \Illuminate\Support\Collection $foods,
        int $goal,
        float $calTarget,
        array $usedIds
    ): ?object {
        // Ưu tiên chưa dùng (trong 14 lần gần nhất)
        $recentUsed = array_slice($usedIds, -14);
        $pool       = $foods->filter(fn($f) => !in_array($f->id, $recentUsed))->values();
        if ($pool->isEmpty()) $pool = $foods;

        // Gán score theo goal
        $scored = $pool->map(function ($f) use ($goal, $calTarget) {
            $cal     = max(1, (float) $f->calories);
            $protein = (float) $f->protein;
            $fat     = (float) $f->fat;

            $calDiff = abs($cal - $calTarget);

            if ($goal === self::GOAL_CUTTING) {
                $density = $protein / $cal * 100;
                $score   = $density * 2 - $calDiff * 0.01 - $fat * 0.5;
            } elseif ($goal === self::GOAL_BULKING) {
                $score = $cal * 0.5 + $protein * 2 - $calDiff * 0.01;
            } else {
                $score = -$calDiff * 0.01 + $protein;
            }

            return ['food' => $f, 'score' => $score];
        });

        // Lấy top-5 và chọn ngẫu nhiên 1 trong đó (đa dạng hóa)
        $top5 = $scored->sortByDesc('score')->take(5)->values();
        if ($top5->isEmpty()) return null;

        return $top5[rand(0, $top5->count() - 1)]['food'];
    }

    private function mealCalPct(int $mealType): float
    {
        return match ($mealType) {
            self::MEAL_BREAKFAST => self::DIST_BREAKFAST,
            self::MEAL_LUNCH     => self::DIST_LUNCH,
            self::MEAL_DINNER    => self::DIST_DINNER,
            default              => 0.33,
        };
    }
}
