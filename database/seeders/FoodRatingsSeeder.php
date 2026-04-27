<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * FoodRatingsSeeder — Tạo dữ liệu đánh giá món ăn để train Collaborative Filtering.
 *
 * Chiến lược rating (quan trọng để CF hoạt động):
 * ─────────────────────────────────────────────────────────────────
 * • Mỗi user đánh giá 20 món ăn (tổng ~1.600 records).
 * • Rating KHÔNG random — tương quan với goal của user:
 *     - Cutting (giảm cân): yêu thích món protein cao / calo thấp (4-5⭐),
 *                            không thích món calo cao / nhiều béo (1-2⭐)
 *     - Bulking (tăng cơ):  yêu thích món nhiều calo + protein cao (4-5⭐),
 *                            không thích món ít calo (1-2⭐)
 *     - Maintaining:        đánh giá cân bằng hơn
 * • Thêm nhiễu nhỏ (±1) để không quá deterministic.
 * • Tập Foods được phân tầng: 40% hot foods (được nhiều người đánh giá),
 *   60% diverse foods → tạo user-item matrix có sparsity thực tế.
 * ─────────────────────────────────────────────────────────────────
 */
class FoodRatingsSeeder extends Seeder
{
    const RATINGS_PER_USER  = 20;  // số món mỗi user đánh giá
    const HOT_FOOD_RATIO    = 0.4; // 40% hot foods (pool ~60 món phổ biến)

    const GOAL_CUTTING    = 0;
    const GOAL_BULKING    = 1;
    const GOAL_MAINTAINING = 2;

    public function run(): void
    {
        // ── Lấy danh sách users và goal của họ ──────────────────
        $users = DB::table('users as u')
            ->join('user_goals as g', 'g.user_id', '=', DB::raw('CAST(u.id AS CHAR)'))
            ->select('u.id as user_id', 'g.goal_type')
            ->where('g.status', 0)
            ->get();

        if ($users->isEmpty()) {
            $this->command->warn('FoodRatingsSeeder: Không có users. Hãy chạy GymUsersSeeder trước.');
            return;
        }

        // ── Lấy tất cả foods cùng dữ liệu dinh dưỡng ─────────
        $foods = DB::table('foods')
            ->select('id', 'calories', 'protein', 'carbs', 'fat')
            ->get()
            ->keyBy('id');

        $allFoodIds = $foods->keys()->all();

        if (count($allFoodIds) === 0) {
            $this->command->warn('FoodRatingsSeeder: Không có foods.');
            return;
        }

        // ── Phân tầng foods: hot pool (60 món đầu tiên) ────────
        shuffle($allFoodIds);
        $hotPoolSize = min(60, (int) (count($allFoodIds) * self::HOT_FOOD_RATIO));
        $hotFoodIds  = array_slice($allFoodIds, 0, $hotPoolSize);
        $coldFoodIds = array_slice($allFoodIds, $hotPoolSize);

        // ── Insert ratings theo từng user ───────────────────────
        $totalInserted = 0;
        $batch         = [];
        $batchSize     = 200;

        foreach ($users as $user) {
            $goal = (int) $user->goal_type;

            // Chọn 20 món: 8 hot + 12 diverse (để tạo overlap giữa users)
            $hotPick  = $this->sampleArray($hotFoodIds, min(8, count($hotFoodIds)));
            $coldPick = $this->sampleArray($coldFoodIds, max(0, self::RATINGS_PER_USER - count($hotPick)));
            $selected = array_unique(array_merge($hotPick, $coldPick));

            // Đảm bảo đúng số lượng
            if (count($selected) < self::RATINGS_PER_USER && count($allFoodIds) >= self::RATINGS_PER_USER) {
                $remaining = array_diff($allFoodIds, $selected);
                $selected  = array_merge($selected, $this->sampleArray($remaining, self::RATINGS_PER_USER - count($selected)));
                $selected  = array_slice(array_unique($selected), 0, self::RATINGS_PER_USER);
            }

            foreach ($selected as $foodId) {
                $food   = $foods->get($foodId);
                $rating = $this->computeRating($goal, $food);

                $comment = $this->generateComment($rating, $goal);

                $batch[] = [
                    'id'         => generateRandomString(10),
                    'user_id'    => (string) $user->user_id,
                    'food_id'    => $foodId,
                    'rating'     => $rating,
                    'comment'    => $comment,
                    'created_at' => now()->subDays(rand(0, 90))->format('Y-m-d H:i:s'),
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ];

                if (count($batch) >= $batchSize) {
                    DB::table('food_ratings')->insertOrIgnore($batch);
                    $totalInserted += count($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            DB::table('food_ratings')->insertOrIgnore($batch);
            $totalInserted += count($batch);
        }

        $this->command->info("FoodRatingsSeeder: {$totalInserted} ratings inserted ({$users->count()} users × ~" . self::RATINGS_PER_USER . " ratings).");
    }

    /* ──────────────────────────────────────────────────────────────
     |  Tính rating dựa trên goal + dinh dưỡng món ăn
     | ──────────────────────────────────────────────────────────────
     |  Nguyên tắc: người có goal giống nhau sẽ có rating pattern
     |  tương tự → Collaborative Filtering có thể khai thác.
     └────────────────────────────────────────────────────────────── */
    private function computeRating(int $goal, object $food): int
    {
        $cal     = (float) $food->calories;
        $protein = (float) $food->protein;
        $fat     = (float) $food->fat;
        $carbs   = (float) $food->carbs;

        // Mật độ protein (g protein / 100 kcal)
        $proteinDensity = $cal > 0 ? ($protein / $cal * 100) : 0;

        $base       = 3;
        $adjustment = 0;

        if ($goal === self::GOAL_CUTTING) {
            // Cutting: thích món protein cao, calo thấp
            if ($proteinDensity >= 8)    $adjustment += 2;
            elseif ($proteinDensity >= 5) $adjustment += 1;
            if ($cal > 600)              $adjustment -= 2;
            elseif ($cal > 400)          $adjustment -= 1;
            if ($fat > 20)               $adjustment -= 1;

        } elseif ($goal === self::GOAL_BULKING) {
            // Bulking: thích món nhiều calo + protein cao
            if ($cal >= 400 && $protein >= 20) $adjustment += 2;
            elseif ($cal >= 300)               $adjustment += 1;
            if ($protein < 10)                 $adjustment -= 1;
            if ($cal < 200)                    $adjustment -= 1;

        } else {
            // Maintaining: cân bằng hơn
            if ($proteinDensity >= 6 && $cal <= 400) $adjustment += 1;
            if ($cal > 700)                           $adjustment -= 1;
            if ($fat > 25)                            $adjustment -= 1;
        }

        // Nhiễu nhỏ (xác suất 35%)
        $noiseOptions = [0, 0, 0, 1, -1];
        $adjustment  += $noiseOptions[array_rand($noiseOptions)];

        return max(1, min(5, $base + $adjustment));
    }

    /* ──────────────────────────────────────────────────────────────
     |  Tạo comment ngắn phù hợp với rating
     └────────────────────────────────────────────────────────────── */
    private function generateComment(int $rating, int $goal): ?string
    {
        // 40% có comment, 60% không comment (thực tế)
        if (rand(1, 100) > 40) return null;

        $cutting5 = ['Rất phù hợp cho chế độ giảm cân!', 'Protein cao, calo thấp, tuyệt vời!', 'Ăn ngon mà không sợ tăng cân.', 'Hoàn hảo cho ngày luyện tập.'];
        $cutting4 = ['Khá tốt cho chế độ ăn kiêng.', 'Phù hợp, sẽ ăn lại.', 'Ngon và lành mạnh.'];
        $cutting3 = ['Tạm được, không quá lý tưởng.', 'Ổn thôi.', 'Bình thường.'];
        $cutting2 = ['Hơi nhiều calo so với kỳ vọng.', 'Không phù hợp lắm.', 'Ăn xong cảm thấy nặng bụng.'];
        $cutting1 = ['Quá nhiều calo, không phù hợp giảm cân.', 'Không nên ăn khi đang cutting.'];

        $bulking5 = ['Đỉnh! Nhiều calo và protein.', 'Hoàn hảo cho buổi tập nặng.', 'Nguồn năng lượng tuyệt vời!', 'Tốt cho tăng cơ!'];
        $bulking4 = ['Khá tốt, bổ sung năng lượng tốt.', 'Ăn ngon, giàu dinh dưỡng.', 'Phù hợp cho bulking.'];
        $bulking3 = ['Tạm được nhưng hơi ít calo.', 'Cần ăn thêm để đủ năng lượng.', 'Bình thường.'];
        $bulking2 = ['Ít calo quá, không đủ cho bulking.', 'Không phù hợp giai đoạn tăng cơ.'];
        $bulking1 = ['Quá ít calo và protein.', 'Không đủ dinh dưỡng cho tập nặng.'];

        $main5 = ['Ngon và cân bằng dinh dưỡng!', 'Rất thích món này!', 'Ăn hàng ngày được.', 'Hoàn hảo!'];
        $main4 = ['Khá ngon.', 'Tốt, sẽ ăn lại.', 'Phù hợp với thực đơn.'];
        $main3 = ['Ổn thôi, không quá đặc biệt.', 'Tạm được.', 'Bình thường.'];
        $main2 = ['Không thích lắm.', 'Hơi nhạt.'];
        $main1 = ['Không hợp khẩu vị.', 'Sẽ không ăn lại.'];

        $commentMap = [
            self::GOAL_CUTTING => [1 => $cutting1, 2 => $cutting2, 3 => $cutting3, 4 => $cutting4, 5 => $cutting5],
            self::GOAL_BULKING => [1 => $bulking1, 2 => $bulking2, 3 => $bulking3, 4 => $bulking4, 5 => $bulking5],
            self::GOAL_MAINTAINING => [1 => $main1, 2 => $main2, 3 => $main3, 4 => $main4, 5 => $main5],
        ];

        $pool = $commentMap[$goal][$rating] ?? $main3;
        return $pool[array_rand($pool)];
    }

    /** Lấy ngẫu nhiên $n phần tử từ mảng (không lặp) */
    private function sampleArray(array $arr, int $n): array
    {
        if ($n <= 0 || empty($arr)) return [];
        $keys   = array_rand($arr, min($n, count($arr)));
        if (!is_array($keys)) $keys = [$keys];
        return array_map(fn($k) => $arr[$k], $keys);
    }
}
