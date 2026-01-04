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
        Schema::table('education_entries', function (Blueprint $table) {
            // School Location Information
            $table->string('school_address')->nullable();
            $table->string('school_city')->nullable();
            $table->string('school_province')->nullable();
            $table->string('school_country')->nullable();

            // Academic Performance
            $table->decimal('gpa', 4, 3)->nullable(); // Grade Point Average (e.g., 3.850)
            $table->string('gpa_scale')->nullable(); // GPA Scale (e.g., "4.0", "5.0")
            $table->string('class_rank')->nullable(); // Class ranking (e.g., "10th out of 200")
            $table->string('academic_status')->nullable(); // Status (e.g., "Graduated with Honors", "Completed")

            // Thesis/Dissertation
            $table->string('thesis_title')->nullable();
            $table->string('thesis_advisor')->nullable();

            // Licenses and Certifications
            $table->string('license_number')->nullable();
            $table->date('license_date')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('board_exam_rating')->nullable(); // Board exam rating if applicable

            // Recognition and Achievements
            $table->text('achievements')->nullable(); // Detailed achievements
            $table->text('extracurricular_activities')->nullable();
            $table->text('leadership_roles')->nullable();
            $table->text('awards')->nullable();

            // Additional Information
            $table->string('language_of_instruction')->nullable();
            $table->text('remarks')->nullable();
            $table->string('education_level')->nullable(); // e.g., "Primary", "Secondary", "Tertiary", "Postgraduate"

            // Dates
            $table->date('enrollment_date')->nullable();
            $table->date('completion_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_entries', function (Blueprint $table) {
            $table->dropColumn([
                'school_address',
                'school_city',
                'school_province',
                'school_country',
                'gpa',
                'gpa_scale',
                'class_rank',
                'academic_status',
                'thesis_title',
                'thesis_advisor',
                'license_number',
                'license_date',
                'license_expiry',
                'board_exam_rating',
                'achievements',
                'extracurricular_activities',
                'leadership_roles',
                'awards',
                'language_of_instruction',
                'remarks',
                'education_level',
                'enrollment_date',
                'completion_date'
            ]);
        });
    }
};
