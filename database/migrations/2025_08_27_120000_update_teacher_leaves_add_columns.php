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
        if (!Schema::hasColumn('teacher_leaves', 'ctos_earned')) {
            Schema::table('teacher_leaves', function (Blueprint $table) {
                $table->integer('ctos_earned')->default(0)->after('used');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('teacher_leaves', 'ctos_earned')) {
            Schema::table('teacher_leaves', function (Blueprint $table) {
                $table->dropColumn('ctos_earned');
            });
        }
    }
};
