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
        Schema::create('cto_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_head_id');
            $table->unsignedBigInteger('cto_request_id'); // Reference to the original CTO request
            $table->decimal('days_earned', 8, 2); // Days earned from this CTO request
            $table->decimal('days_remaining', 8, 2); // Days remaining (can be partial)
            $table->date('earned_date'); // Date when CTO was earned (approved)
            $table->date('expiry_date'); // 1 year from earned_date
            $table->boolean('is_expired')->default(false);
            $table->timestamps();

            $table->foreign('school_head_id')->references('id')->on('personnels')->onDelete('cascade');
            $table->foreign('cto_request_id')->references('id')->on('cto_requests')->onDelete('cascade');
            
            // Index for efficient queries
            $table->index(['school_head_id', 'earned_date']);
            $table->index(['expiry_date', 'is_expired']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cto_entries');
    }
};
