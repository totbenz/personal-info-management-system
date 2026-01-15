<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all records with 1970-01-01 to null
        DB::table('civil_service_eligibility')
            ->where('license_date_of_validity', '1970-01-01')
            ->update(['license_date_of_validity' => null]);

        DB::table('civil_service_eligibility')
            ->where('rating', '')
            ->update(['rating' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this fix
    }
};
