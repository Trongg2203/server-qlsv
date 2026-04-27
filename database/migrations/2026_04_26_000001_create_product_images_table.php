<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('food_id', 10)->collation('utf8mb4_unicode_ci');
            $table->string('directory', 255)->default('foods')
                ->comment('Sub-directory under uploads/');
            $table->string('file_name', 50)
                ->comment('Random base name without prefix or extension');
            $table->string('file_ext', 10)->default('jpg')
                ->comment('Extension without dot');
            $table->tinyInteger('is_primary')->default(1)
                ->comment('1 = primary image for this food');
            $table->integer('sort_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->primary('id');
            $table->index('food_id', 'idx_product_images_food');
            $table->foreign('food_id')
                ->references('id')
                ->on('foods')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
