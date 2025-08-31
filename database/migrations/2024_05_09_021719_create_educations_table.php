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
            $table->enum('type', ['elementary', 'secondary', 'vocational/trade', 'graduate', 'graduate studies']);
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

            // Foreign key constraint
            $table->foreign('personnel_id')->references('id')->on('personnels')->onDelete('cascade');

            // Unique constraint to prevent duplicate education types per personnel
            $table->unique(['personnel_id', 'type'], 'unique_personnel_education_type');

            // Indexes for better performance
            $table->index('personnel_id');
            $table->index('type');
            $table->index(['period_from', 'period_to']);
            $table->index('year_graduated');

            // Note: Check constraints are not supported in Laravel migrations
            // Data validation should be handled in the model or form validation
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint before dropping the table
        if (Schema::hasTable('educations')) {
            Schema::table('educations', function (Blueprint $table) {
                $table->dropForeign(['personnel_id']);
                $table->dropUnique('unique_personnel_education_type');
            });
        }
        Schema::dropIfExists('educations');
    }
};
