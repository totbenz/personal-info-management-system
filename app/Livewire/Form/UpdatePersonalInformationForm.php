<?php

namespace App\Livewire\Form;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class UpdatePersonalInformationForm extends PersonalInformationForm
{
    public function mount($id = null)
    {
        parent::mount($id);

        // Calculate initial salary when editing
        if ($this->personnel) {
            $this->calculateSalary();
        }
    }

    public function render()
    {
        return view('livewire.form.update-personal-information-form');
    }

    /**
     * Override the save method to ensure salary is calculated before saving
     */
    public function save()
    {
        // Ensure salary is calculated before saving
        $this->calculateSalary();

        // Call parent save method
        return parent::save();
    }

    /**
     * Override the updatedSalaryGradeId method to ensure immediate calculation
     */
    public function updatedSalaryGradeId()
    {
        $this->calculateSalary();
    }

    /**
     * Override the updatedStepIncrement method to ensure immediate calculation
     */
    public function updatedStepIncrement()
    {
        $this->calculateSalary();
    }

    // Live validation methods for all fields
    public function updatedFirstName()
    {
        $this->validateOnly('first_name');
    }

    public function updatedMiddleName()
    {
        $this->validateOnly('middle_name');
    }

    public function updatedLastName()
    {
        $this->validateOnly('last_name');
    }

    public function updatedNameExt()
    {
        $this->validateOnly('name_ext');
    }

    public function updatedDateOfBirth()
    {
        $this->validateOnly('date_of_birth');
    }

    public function updatedPlaceOfBirth()
    {
        $this->validateOnly('place_of_birth');
    }

    public function updatedCitizenship()
    {
        $this->validateOnly('citizenship');
    }

    public function updatedHeight()
    {
        $this->validateOnly('height');
    }

    public function updatedWeight()
    {
        $this->validateOnly('weight');
    }

    public function updatedTin()
    {
        $this->validateOnly('tin');
    }

    public function updatedSssNum()
    {
        $this->validateOnly('sss_num');
    }

    public function updatedGsisNum()
    {
        $this->validateOnly('gsis_num');
    }

    public function updatedPhilhealthNum()
    {
        $this->validateOnly('philhealth_num');
    }

    public function updatedPagibigNum()
    {
        $this->validateOnly('pagibig_num');
    }

    public function updatedPantillaOfPersonnel()
    {
        $this->validateOnly('pantilla_of_personnel');
    }

    public function updatedPersonnelId()
    {
        $this->validateOnly('personnel_id');
    }

    public function updatedFundSource()
    {
        $this->validateOnly('fund_source');
    }

    public function updatedLeaveOfAbsenceWithoutPayCount()
    {
        $this->validateOnly('leave_of_absence_without_pay_count');
    }

    public function updatedEmail()
    {
        $this->validateOnly('email');
    }

    public function updatedTelNo()
    {
        $this->validateOnly('tel_no');
    }

    public function updatedMobileNo()
    {
        $this->validateOnly('mobile_no');
    }

    public function updatedSeparationCauseInput()
    {
        $this->validateOnly('separation_cause_input');
    }
}
