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
        Schema::table('personnel_details', function (Blueprint $table) {
            $table->string('solo_parent_id_no')->nullable()->after('solo_parent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnel_details', function (Blueprint $table) {
            $table->dropColumn('solo_parent_id_no');
        });
    }
};
