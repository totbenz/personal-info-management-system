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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id')->unique();
            $table->unsignedBigInteger('district_id');
            $table->string('school_name');
            $table->string('address');
            $table->string('division');
            $table->string('email');
            $table->string('phone');
            $table->json('curricular_classification');
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('district')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
