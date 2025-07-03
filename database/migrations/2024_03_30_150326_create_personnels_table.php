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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            //Personal Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name_ext')->nullable();
            $table->enum('sex', ['male', 'female']);
            $table->enum('civil_status', ['single', 'married', 'widowed', 'divorced', 'seperated', 'others']);
            $table->string('citizenship');
            $table->string('blood_type')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->string('email')->nullable();
            $table->string('tel_no')->nullable();
            $table->string('mobile_no')->nullable();

            // Work Information
            $table->string('personnel_id')->unique();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('position_id');
            $table->enum('appointment', ['regular', 'part-time', 'temporary', 'contract']);
            $table->string('fund_source');
            $table->unsignedBigInteger('salary_grade_id')->default(1);
            $table->foreign('salary_grade_id')->references('id')->on('salary_grades')->onDelete('cascade')->default(1);
            $table->date('salary_changed_at')->nullable();

            $table->enum('step_increment', ['1', '2', '3', '4', '5', '6', '7', '8'])->nullable()->default(1);
            $table->enum('category', ['SDO Personnel','School Head', 'Elementary School Teacher', 'Junior High School Teacher', 'Senior High School Teacher', 'School Non-teaching Personnel']);
            $table->string('job_status');
            $table->integer('leave_of_absence_without_pay_count')->default(0)->nullable();
            $table->date('employment_start');
            $table->date('employment_end')->nullable();

            // Government Information
            $table->string('tin', 12);
            $table->string('sss_num', 10)->nullable();
            $table->string('gsis_num', 11)->nullable();
            $table->string('philhealth_num', 12)->nullable();
            $table->string('pagibig_num', 12)->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('position')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
