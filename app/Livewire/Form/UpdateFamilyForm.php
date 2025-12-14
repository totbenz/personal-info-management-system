<?php

namespace App\Livewire\Form;

use Livewire\Component;

class UpdateFamilyForm extends FamilyForm
{
    public function render()
    {
        return view('livewire.form.update-family-form');
    }

    // Additional live validation methods for spouse fields
    public function updatedSpouseFirstName()
    {
        $this->validateOnly('spouse_first_name');
    }

    public function updatedSpouseMiddleName()
    {
        $this->validateOnly('spouse_middle_name');
    }

    public function updatedSpouseLastName()
    {
        $this->validateOnly('spouse_last_name');
    }

    public function updatedSpouseNameExt()
    {
        $this->validateOnly('spouse_name_ext');
    }

    public function updatedSpouseOccupation()
    {
        $this->validateOnly('spouse_occupation');
    }

    public function updatedSpouseBusinessName()
    {
        $this->validateOnly('spouse_business_name');
    }

    public function updatedSpouseBusinessAddress()
    {
        $this->validateOnly('spouse_business_address');
    }

    public function updatedSpouseTelNo()
    {
        $this->validateOnly('spouse_tel_no');
    }

    public function updatedFathersNameExt()
    {
        $this->validateOnly('fathers_name_ext');
    }
}
