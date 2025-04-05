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
        Schema::table('personnels', function (Blueprint $table) {
            // Change salary_grade to salary_grade_id and add foreign key
            $table->unsignedBigInteger('salary_grade_id')->after('fund_source')->nullable();
            $table->foreign('salary_grade_id')->references('id')->on('salary_grades')->onDelete('cascade');

            // Rename step to step_increment
            $table->renameColumn('step', 'step_increment');

            // Add leave_of_absence_without_pay_count attribute
            $table->integer('leave_of_absence_without_pay_count')->default(0)->after('job_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnels', function (Blueprint $table) {
            // Revert changes
            $table->dropForeign(['salary_grade_id']);
            $table->dropColumn('salary_grade_id');
            $table->enum('salary_grade', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'])->after('fund_source');

            $table->renameColumn('step_increment', 'step');
            $table->dropColumn('leave_of_absence_without_pay_count');
        });
    }
};
