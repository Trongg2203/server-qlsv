<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_goals', function (Blueprint $table) {
            // Primary key (CHAR 10)
            $table->char('id', 10)->primary()->comment('ID goal - 10 ký tự ngẫu nhiên');

            // Foreign key user
            $table->char('user_id', 10)->comment('ID người dùng');

            // Goal type
            $table->tinyInteger('goal_type')->comment('0: lose_weight, 1: gain_weight, 2: maintain_weight');

            // Weight
            $table->decimal('start_weight', 5, 2)->comment('Cân nặng bắt đầu (kg)');
            $table->decimal('target_weight', 5, 2)->comment('Cân nặng mục tiêu (kg)');
            $table->decimal('target_bmi', 4, 2)->nullable()->comment('BMI mục tiêu');

            // Weekly change
            $table->decimal('weekly_change_rate', 4, 2)->comment('Tốc độ thay đổi/tuần (kg)');

            // Estimated weeks
            $table->integer('estimated_weeks')->nullable()->comment('Số tuần dự kiến');

            // Dates
            $table->date('start_date')->comment('Ngày bắt đầu');
            $table->date('target_date')->comment('Ngày mục tiêu');

            // Flags
            $table->tinyInteger('is_active')->default(1)->comment('0: không hoạt động, 1: đang hoạt động');
            $table->tinyInteger('is_completed')->default(0)->comment('0: chưa hoàn thành, 1: đã hoàn thành');

            // Status
            $table->tinyInteger('status')->default(0)->comment('0: active, 1: paused, 2: completed, 3: abandoned');

            // Completed time
            $table->timestamp('completed_at')->nullable()->comment('Thời điểm hoàn thành');

            // Audit
            $table->char('created_by', 10)->comment('ID người tạo');
            $table->char('updated_by', 10)->nullable()->comment('ID người cập nhật');
            $table->char('deleted_by', 10)->nullable()->comment('ID người xóa');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable()->comment('Soft delete');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_goals');
    }
};
