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
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('role', ['admin', 'school_head', 'non_teaching', 'teacher'])->default('teacher');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraints in related tables before dropping users
        if (Schema::hasTable('cto_requests')) {
            Schema::table('cto_requests', function (Blueprint $table) {
                $table->dropForeign(['approved_by']);
            });
        }
        if (Schema::hasTable('leave_requests')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }
        if (Schema::hasTable('service_credit_requests')) {
            Schema::table('service_credit_requests', function (Blueprint $table) {
                $table->dropForeign(['approved_by']);
            });
        }
        Schema::dropIfExists('users');
    }
};
