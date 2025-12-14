<?php

namespace App\Livewire\Form;

use Livewire\Component;

class UpdateEducationForm extends EducationForm
{
    public function render()
    {
        return view('livewire.form.update-education-form');
    }

    // Additional live validation methods for all education fields
    public function updatedElementaryDegreeCourse()
    {
        $this->validateOnly('elementary_degree_course');
    }

    public function updatedElementaryHighestLevelUnits()
    {
        $this->validateOnly('elementary_highest_level_units');
    }

    public function updatedElementaryScholarshipHonors()
    {
        $this->validateOnly('elementary_scholarship_honors');
    }

    public function updatedSecondaryDegreeCourse()
    {
        $this->validateOnly('secondary_degree_course');
    }

    public function updatedSecondaryHighestLevelUnits()
    {
        $this->validateOnly('secondary_highest_level_units');
    }

    public function updatedSecondaryScholarshipHonors()
    {
        $this->validateOnly('secondary_scholarship_honors');
    }

    // Vocational education validation methods
    public function updatedVocationalSchoolName()
    {
        $this->validateOnly('vocational_school_name');
    }

    public function updatedVocationalDegreeCourse()
    {
        $this->validateOnly('vocational_degree_course');
    }

    public function updatedVocationalPeriodFrom()
    {
        $this->validateOnly('vocational_period_from');
    }

    public function updatedVocationalPeriodTo()
    {
        $this->validateOnly('vocational_period_to');
    }

    public function updatedVocationalHighestLevelUnits()
    {
        $this->validateOnly('vocational_highest_level_units');
    }

    public function updatedVocationalYearGraduated()
    {
        $this->validateOnly('vocational_year_graduated');
    }

    public function updatedVocationalScholarshipHonors()
    {
        $this->validateOnly('vocational_scholarship_honors');
    }

    // Graduate education validation methods
    public function updatedGraduateSchoolName()
    {
        $this->validateOnly('graduate_school_name');
    }

    public function updatedGraduateDegreeCourse()
    {
        $this->validateOnly('graduate_degree_course');
    }

    public function updatedGraduateMajor()
    {
        $this->validateOnly('graduate_major');
    }

    public function updatedGraduateMinor()
    {
        $this->validateOnly('graduate_minor');
    }

    public function updatedGraduatePeriodFrom()
    {
        $this->validateOnly('graduate_period_from');
    }

    public function updatedGraduatePeriodTo()
    {
        $this->validateOnly('graduate_period_to');
    }

    public function updatedGraduateHighestLevelUnits()
    {
        $this->validateOnly('graduate_highest_level_units');
    }

    public function updatedGraduateYearGraduated()
    {
        $this->validateOnly('graduate_year_graduated');
    }

    public function updatedGraduateScholarshipHonors()
    {
        $this->validateOnly('graduate_scholarship_honors');
    }

    // Graduate studies validation methods
    public function updatedGraduateStudiesSchoolName()
    {
        $this->validateOnly('graduate_studies_school_name');
    }

    public function updatedGraduateStudiesDegreeCourse()
    {
        $this->validateOnly('graduate_studies_degree_course');
    }

    public function updatedGraduateStudiesMajor()
    {
        $this->validateOnly('graduate_studies_major');
    }

    public function updatedGraduateStudiesMinor()
    {
        $this->validateOnly('graduate_studies_minor');
    }

    public function updatedGraduateStudiesPeriodFrom()
    {
        $this->validateOnly('graduate_studies_period_from');
    }

    public function updatedGraduateStudiesPeriodTo()
    {
        $this->validateOnly('graduate_studies_period_to');
    }

    public function updatedGraduateStudiesHighestLevelUnits()
    {
        $this->validateOnly('graduate_studies_highest_level_units');
    }

    public function updatedGraduateStudiesYearGraduated()
    {
        $this->validateOnly('graduate_studies_year_graduated');
    }

    public function updatedGraduateStudiesScholarshipHonors()
    {
        $this->validateOnly('graduate_studies_scholarship_honors');
    }
}
