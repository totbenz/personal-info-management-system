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
        Schema::create('cto_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_head_id');
            $table->integer('requested_hours');
            $table->date('work_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('reason');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'denied'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('school_head_id')->references('id')->on('personnels')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cto_requests');
    }
};
