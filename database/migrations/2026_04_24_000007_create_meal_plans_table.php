<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('user_id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('goal_id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('calorie_calculation_id', 10)->collation('utf8mb4_unicode_ci');
            $table->string('plan_name', 255)->collation('utf8mb4_unicode_ci');
            $table->date('start_date');
            $table->date('end_date')->comment('Usually = start_date + 6 days');
            $table->decimal('target_calories_per_day', 7, 2);
            $table->tinyInteger('generation_method')->default(0)
                ->comment('0=hybrid, 1=content_based(cold start), 2=collaborative');
            $table->tinyInteger('status')->default(0)
                ->comment('0=active, 1=completed, 2=replaced');
            $table->timestamp('created_at')->useCurrent();

            $table->primary('id');
            $table->index('user_id', 'idx_mealplan_user');
            $table->index('goal_id', 'idx_mealplan_goal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_plans');
    }
};
