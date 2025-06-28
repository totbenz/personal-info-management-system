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
}
