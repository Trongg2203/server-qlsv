<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Thứ tự bắt buộc (FK dependency):
     *   1. foods data (CSV + supplemental gym foods)
     *   2. product_images
     *   3. users (80 người Việt Nam)
     *   4. user_profiles  ─┐
     *   5. user_goals      ├─ GymUsersSeeder (gộp chung để đảm bảo consistency)
     *   6. calorie_calculations ─┘
     *   7. food_ratings    (dữ liệu train Collaborative Filtering)
     *   8. meal_plans      ─┐
     *   9. meal_plan_details └─ MealPlansSeeder
     */
    public function run(): void
    {
        $this->call([
            // ── Dữ liệu món ăn ────────────────────────────────────
            ImportNutritionCsvSeeder::class,
            SupplementalGymFoodsSeeder::class,
            ProductImagesSeeder::class,

            // ── Dữ liệu người dùng + mục tiêu + tính toán calo ───
            GymUsersSeeder::class,

            // ── Dữ liệu đánh giá (core của Collaborative Filtering)
            FoodRatingsSeeder::class,

            // ── Dữ liệu thực đơn 7 ngày mẫu ─────────────────────
            MealPlansSeeder::class,
        ]);
    }
}
