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
        Schema::create('users', function (Blueprint $table) {
            $table->char('id', 10)->collation('utf8mb4_unicode_ci');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->char('salt', 5)->collation('utf8mb4_unicode_ci');
            $table->string('avatar', 500)->nullable();
            $table->string('phone', 20)->nullable();
            $table->tinyInteger('role')->default(0)
                ->comment('0=user, 1=admin');
            $table->tinyInteger('account_status')->default(0)
                ->comment('0=pending, 1=active, 2=rejected');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->char('created_by', 10)->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->char('updated_by', 10)->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->char('deleted_by', 10)->collation('utf8mb4_unicode_ci')->nullable();

            $table->primary('id');
            $table->unique('email', 'uq_users_email');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
