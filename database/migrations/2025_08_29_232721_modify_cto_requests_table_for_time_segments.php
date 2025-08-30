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
        Schema::table('cto_requests', function (Blueprint $table) {
            // Add new time segment fields
            $table->time('morning_in')->nullable()->after('work_date');
            $table->time('morning_out')->nullable()->after('morning_in');
            $table->time('afternoon_in')->nullable()->after('morning_out');
            $table->time('afternoon_out')->nullable()->after('afternoon_in');
            $table->decimal('total_hours', 5, 2)->nullable()->after('afternoon_out');
            
            // Keep the old fields for now to ensure backward compatibility during transition
            // We'll remove them in a separate migration after data migration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cto_requests', function (Blueprint $table) {
            // Remove the new fields
            $table->dropColumn(['morning_in', 'morning_out', 'afternoon_in', 'afternoon_out', 'total_hours']);
        });
    }
};
