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
        Schema::create('non_teaching_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('non_teaching_id');
            $table->string('leave_type');
            $table->year('year');
            $table->decimal('available', 8, 2)->default(0);
            $table->decimal('used', 8, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('non_teaching_id')->references('id')->on('personnels')->onDelete('cascade');
            $table->unique(['non_teaching_id', 'leave_type', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_teaching_leaves');
    }
};
