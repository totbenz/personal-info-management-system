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
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id');
            $table->enum('type', ['elementary','secondary','vocational/trade', 'graduate','graduate studies']);
            $table->string('school_name');
            $table->string('degree_course')->nullable();
            $table->string('major')->nullable();
            $table->string('minor')->nullable();
            $table->integer('period_from');
            $table->integer('period_to')->nullable();
            $table->string('highest_level_units')->nullable();
            $table->integer('year_graduated')->nullable();
            $table->string('scholarship_honors')->nullable();
            $table->timestamps();

            $table->foreign('personnel_id')->references('id')->on('personnels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
