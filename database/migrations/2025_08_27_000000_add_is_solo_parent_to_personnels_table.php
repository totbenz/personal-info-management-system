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
        if (!Schema::hasColumn('personnels', 'is_solo_parent')) {
            Schema::table('personnels', function (Blueprint $table) {
                $table->boolean('is_solo_parent')->default(false)->after('leave_of_absence_without_pay_count');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('personnels', 'is_solo_parent')) {
            Schema::table('personnels', function (Blueprint $table) {
                $table->dropColumn('is_solo_parent');
            });
        }
    }
};
