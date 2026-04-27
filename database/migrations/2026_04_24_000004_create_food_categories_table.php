<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_categories', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->string('name', 255)->collation('utf8mb4_unicode_ci');
            $table->text('description')->nullable()->collation('utf8mb4_unicode_ci');
            $table->integer('sort_order')->default(0);

            $table->primary('id');
            $table->unique('name', 'uq_food_cat_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_categories');
    }
};
