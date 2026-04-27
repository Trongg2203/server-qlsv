<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->char('user_id', 10)->collation('utf8mb4_unicode_ci');
            $table->date('date_of_birth');
            $table->tinyInteger('gender')->comment('0=female, 1=male');
            $table->decimal('height', 5, 2)->comment('cm, range 100-250');
            $table->decimal('current_weight', 5, 2)->comment('kg, range 30-300');
            $table->tinyInteger('activity_level')->default(1)
                ->comment('1=sedentary 2=lightly 3=moderately 4=very 5=extra');
            $table->timestamps();

            $table->primary('id');
            $table->unique('user_id', 'uq_profile_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
