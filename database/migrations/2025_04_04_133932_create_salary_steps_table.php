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
        Schema::create('salary_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_grade_id')->constrained('salary_grades')->onDelete('cascade');
            $table->integer('step');
            $table->year('year');
            $table->decimal('salary', 10, 2);
            $table->timestamps();

            $table->unique(['salary_grade_id', 'step', 'year']); // Ensures unique combination of salary_grade_id, step, and year
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_steps');
    }
};
