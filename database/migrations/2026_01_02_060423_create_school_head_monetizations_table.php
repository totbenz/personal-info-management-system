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
        Schema::create('school_head_monetizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_head_id');
            $table->integer('days_requested');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('request_date');
            $table->date('approval_date')->nullable();
            $table->decimal('vl_available', 8, 2)->default(0);
            $table->decimal('sl_available', 8, 2)->default(0);
            $table->decimal('vl_deducted', 8, 2)->default(0);
            $table->decimal('sl_deducted', 8, 2)->default(0);
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('school_head_id')->references('id')->on('personnels')->onDelete('cascade');
            $table->index(['school_head_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_head_monetizations');
    }
};
