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
        Schema::create('cto_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_head_id');
            $table->unsignedBigInteger('cto_entry_id'); // Which CTO entry was used
            $table->unsignedBigInteger('leave_request_id')->nullable(); // Which leave request used it
            $table->decimal('days_used', 8, 2); // How many days were used from this CTO entry
            $table->date('used_date'); // Date when CTO was used
            $table->string('usage_type')->default('leave'); // 'leave' or 'manual_adjustment'
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('school_head_id')->references('id')->on('personnels')->onDelete('cascade');
            $table->foreign('cto_entry_id')->references('id')->on('cto_entries')->onDelete('cascade');
            $table->foreign('leave_request_id')->references('id')->on('leave_requests')->onDelete('set null');
            
            // Index for efficient queries
            $table->index(['school_head_id', 'used_date']);
            $table->index(['cto_entry_id', 'used_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cto_usages');
    }
};
