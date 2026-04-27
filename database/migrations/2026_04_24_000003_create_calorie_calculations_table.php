<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calorie_calculations', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('user_id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('goal_id', 10)->collation('utf8mb4_unicode_ci');
            $table->decimal('bmr', 7, 2)->comment('Basal Metabolic Rate (Mifflin-St Jeor)');
            $table->decimal('tdee', 7, 2)->comment('Total Daily Energy Expenditure');
            $table->decimal('target_calories', 7, 2)
                ->comment('TDEE +/- deficit/surplus. Min 1200(female) / 1500(male)');
            $table->decimal('protein_grams', 6, 2);
            $table->decimal('carbs_grams', 6, 2);
            $table->decimal('fat_grams', 6, 2);
            $table->tinyInteger('macro_ratio')
                ->comment('0=cutting(40/30/30) 1=bulking(30/45/25) 2=maintaining(30/40/30)');
            $table->date('valid_from');
            $table->timestamp('created_at')->useCurrent();

            $table->primary('id');
            $table->index('user_id', 'idx_calorie_user');
            $table->index('goal_id', 'idx_calorie_goal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calorie_calculations');
    }
};
