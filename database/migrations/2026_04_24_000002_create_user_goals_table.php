<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_goals', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('user_id', 10)->collation('utf8mb4_unicode_ci');
            $table->tinyInteger('goal_type')
                ->comment('0=cutting, 1=bulking, 2=maintaining');
            $table->decimal('start_weight', 5, 2);
            $table->decimal('target_weight', 5, 2);
            $table->decimal('weekly_change_rate', 3, 2)
                ->comment('kg/week. Cutting max 1.0, Bulking max 0.5');
            $table->date('start_date');
            $table->date('target_date');
            $table->tinyInteger('status')->default(0)
                ->comment('0=active, 1=completed, 2=cancelled');
            $table->timestamps();

            $table->primary('id');
            $table->index('user_id', 'idx_goals_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_goals');
    }
};
