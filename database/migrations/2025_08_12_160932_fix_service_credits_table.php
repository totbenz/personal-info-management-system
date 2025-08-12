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
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('service_credits', 'status')) {
                $table->enum('status', ['pending', 'approved', 'denied'])->default('approved');
            }
            
            // Add other columns if they don't exist
            if (!Schema::hasColumn('service_credits', 'reason')) {
                $table->text('reason')->nullable();
            }
            
            if (!Schema::hasColumn('service_credits', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            
            if (!Schema::hasColumn('service_credits', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_credits', function (Blueprint $table) {
            $table->dropColumn(['status', 'reason', 'approved_at', 'approved_by']);
        });
    }
};
