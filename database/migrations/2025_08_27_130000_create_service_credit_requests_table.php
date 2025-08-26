<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_credit_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); // references personnels.id
            $table->decimal('requested_days', 8, 2); // days being requested to credit
            $table->date('work_date')->nullable(); // optional date earned
            $table->string('reason');
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, approved, denied
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('teacher_id')->references('id')->on('personnels')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['teacher_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_credit_requests');
    }
};
