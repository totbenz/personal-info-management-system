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
        Schema::table('service_credits', function (Blueprint $table) {
            // Add the new columns our model expects
            if (!Schema::hasColumn('service_credits', 'personal_leave_credits')) {
                $table->integer('personal_leave_credits')->default(0);
            }
            if (!Schema::hasColumn('service_credits', 'sick_leave_credits')) {
                $table->integer('sick_leave_credits')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_credits', function (Blueprint $table) {
            $table->dropColumn(['personal_leave_credits', 'sick_leave_credits']);
        });
    }
};
