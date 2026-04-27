<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_ratings', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('user_id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('food_id', 10)->collation('utf8mb4_unicode_ci');
            $table->tinyInteger('rating')->comment('1-5 stars');
            $table->text('comment')->nullable()->collation('utf8mb4_unicode_ci');
            $table->timestamps();

            $table->primary('id');
            $table->unique(['user_id', 'food_id'], 'uq_rating_user_food');
            $table->index('food_id', 'idx_ratings_food');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_ratings');
    }
};
