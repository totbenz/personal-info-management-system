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
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->boolean('is_cto_based')->default(false)->after('status');
            $table->string('cto_leave_type')->nullable()->after('is_cto_based'); // 'Sick Leave' or 'Vacation Leave'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['is_cto_based', 'cto_leave_type']);
        });
    }
};
