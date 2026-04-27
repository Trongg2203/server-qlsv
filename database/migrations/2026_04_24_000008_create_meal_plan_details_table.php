<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_plan_details', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('meal_plan_id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('food_id', 10)->collation('utf8mb4_unicode_ci');
            $table->tinyInteger('day_number')->comment('1-7');
            $table->tinyInteger('meal_type')
                ->comment('1=breakfast, 2=lunch, 3=dinner, 4=snack');
            $table->decimal('servings', 5, 2)->default(1.00);
            $table->decimal('total_calories', 7, 2);
            $table->decimal('total_protein', 6, 2);
            $table->decimal('total_carbs', 6, 2);
            $table->decimal('total_fat', 6, 2);

            $table->primary('id');
            $table->index('meal_plan_id', 'idx_detail_plan');
            $table->index('food_id', 'idx_detail_food');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_plan_details');
    }
};
