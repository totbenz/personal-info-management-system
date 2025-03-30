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
        Schema::table('service_records', function (Blueprint $table) {
            // Drop the existing 'designation' column if it exists
            if (Schema::hasColumn('service_records', 'designation')) {
                $table->dropColumn('designation');
            }

            // Add the new 'position_id' column if it doesn't already exist
            if (!Schema::hasColumn('service_records', 'position_id')) {
                $table->unsignedBigInteger('position_id')->nullable()->after('to_date');
            }

            // Add a foreign key constraint to 'position_id'
            $table->foreign('position_id')->references('id')->on('position')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            // Drop the foreign key and 'position_id' column
            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');

            // Re-add the 'designation' column
            $table->string('designation')->after('to_date');
        });
    }
};
