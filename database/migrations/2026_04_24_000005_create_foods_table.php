<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foods', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('category_id', 10)->collation('utf8mb4_unicode_ci');
            $table->string('name', 255)->collation('utf8mb4_unicode_ci');
            $table->decimal('serving_size', 7, 2)->comment('gram default per serving');
            $table->string('serving_unit', 50)->default('g');
            $table->decimal('calories', 7, 2);
            $table->decimal('protein', 6, 2);
            $table->decimal('carbs', 6, 2);
            $table->decimal('fat', 6, 2);
            $table->tinyInteger('meal_type')->default(0)
                ->comment('0=any, 1=breakfast, 2=lunch, 3=dinner, 4=snack');
            $table->integer('popularity_score')->default(0)
                ->comment('Increases each time AI picks this food into a meal plan');
            $table->timestamp('created_at')->useCurrent();

            $table->primary('id');
            $table->index('category_id', 'idx_foods_category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
