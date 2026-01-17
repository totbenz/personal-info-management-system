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
        Schema::table('teacher_leaves', function (Blueprint $table) {
            if (!Schema::hasColumn('teacher_leaves', 'day_debt')) {
                $table->integer('day_debt')->default(0)->after('ctos_earned');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_leaves', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_leaves', 'day_debt')) {
                $table->dropColumn('day_debt');
            }
        });
    }
};
