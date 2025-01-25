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
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id');
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->string('designation');
            $table->string('appointment_status');
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('station')->nullable();
            $table->string('branch')->nullable();
            $table->string('lv_wo_pay')->nullable();
            $table->string('separation_date_cause')->nullable();
            $table->timestamps();

            $table->foreign('personnel_id')->references('id')->on('personnels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_records');
    }
};
