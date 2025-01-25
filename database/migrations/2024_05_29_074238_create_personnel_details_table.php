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
        Schema::create('personnel_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personnel_id');
            $table->boolean('consanguinity_third_degree');
            $table->text('consanguinity_third_degree_details')->nullable();
            $table->boolean('consanguinity_fourth_degree');
            $table->text('consanguinity_fourth_degree_details')->nullable();
            $table->boolean('found_guilty_administrative_offense');
            $table->text('administrative_offense_details')->nullable();
            $table->boolean('criminally_charged');
            $table->text('criminally_charged_details')->nullable();
            $table->date('criminally_charged_date_filed')->nullable();
            $table->text('criminally_charged_status')->nullable();
            $table->boolean('convicted_crime');
            $table->text('convicted_crime_details')->nullable();
            $table->boolean('separated_from_service');
            $table->text('separation_details')->nullable();
            $table->boolean('candidate_last_year');
            $table->text('candidate_details')->nullable();
            $table->boolean('resigned_to_campaign');
            $table->text('resigned_campaign_details')->nullable();
            $table->boolean('immigrant_status');
            $table->text('immigrant_country_details')->nullable();
            $table->boolean('member_indigenous_group');
            $table->text('indigenous_group_details')->nullable();
            $table->boolean('person_with_disability');
            $table->string('disability_id_no')->nullable();
            $table->boolean('solo_parent');
            $table->timestamps();

            $table->foreign('personnel_id')->references('id')->on('personnels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_details');
    }
};
