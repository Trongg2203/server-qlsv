<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm soft deletes
            $table->softDeletes()->after('remember_token');
            // Thêm type (UserType: 0=super_admin, 1=admin, 2=user)
            $table->tinyInteger('type')->default(2)->after('password');
            // Thêm status
            $table->boolean('status')->default(true)->after('type');
            // Thêm phone
            $table->string('phone', 20)->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['type', 'status', 'phone']);
        });
    }
};
