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
        Schema::create('salary_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id');
            $table->enum('type', ['NOSA', 'NOSI']);
            $table->integer('previous_salary_grade')->nullable();
            $table->integer('current_salary_grade');
            $table->integer('previous_salary_step')->nullable();
            $table->integer('current_salary_step');
            $table->decimal('previous_salary', 10, 2)->nullable();
            $table->decimal('current_salary', 10, 2);
            $table->date('actual_monthly_salary_as_of_date')->nullable();
            $table->date('adjusted_monthly_salary_date')->nullable();
            $table->timestamps();

            $table->foreign('personnel_id')->references('id')->on('personnels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_changes');
    }
};
