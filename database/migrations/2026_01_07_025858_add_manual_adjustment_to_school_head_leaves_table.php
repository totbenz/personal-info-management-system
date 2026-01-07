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
        Schema::table('school_head_leaves', function (Blueprint $table) {
            $table->decimal('manual_adjustment', 8, 2)->default(0)->after('available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_head_leaves', function (Blueprint $table) {
            $table->dropColumn('manual_adjustment');
        });
    }
};
