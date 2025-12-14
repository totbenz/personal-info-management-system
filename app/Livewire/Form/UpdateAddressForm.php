<?php

namespace App\Livewire\Form;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateAddressForm extends AddressForm
{
    public function render()
    {
        return view('livewire.form.update-address-form');
    }

    // Live validation methods for permanent address fields
    public function updatedPermanentHouseNo()
    {
        $this->validateOnly('permanent_house_no');
    }

    public function updatedPermanentStAddress()
    {
        $this->validateOnly('permanent_st_address');
    }

    public function updatedPermanentSubdivision()
    {
        $this->validateOnly('permanent_subdivision');
    }

    public function updatedPermanentBrgy()
    {
        $this->validateOnly('permanent_brgy');
    }

    public function updatedPermanentCity()
    {
        $this->validateOnly('permanent_city');
    }

    public function updatedPermanentProvince()
    {
        $this->validateOnly('permanent_province');
    }

    public function updatedPermanentRegion()
    {
        $this->validateOnly('permanent_region');
    }

    public function updatedPermanentZipCode()
    {
        $this->validateOnly('permanent_zip_code');
    }

    // Live validation methods for residential address fields
    public function updatedResidentialHouseNo()
    {
        $this->validateOnly('residential_house_no');
    }

    public function updatedResidentialStAddress()
    {
        $this->validateOnly('residential_st_address');
    }

    public function updatedResidentialSubdivision()
    {
        $this->validateOnly('residential_subdivision');
    }

    public function updatedResidentialBrgy()
    {
        $this->validateOnly('residential_brgy');
    }

    public function updatedResidentialCity()
    {
        $this->validateOnly('residential_city');
    }

    public function updatedResidentialProvince()
    {
        $this->validateOnly('residential_province');
    }

    public function updatedResidentialRegion()
    {
        $this->validateOnly('residential_region');
    }

    public function updatedResidentialZipCode()
    {
        $this->validateOnly('residential_zip_code');
    }

    // Live validation methods for contact person fields
    public function updatedContactPersonName()
    {
        $this->validateOnly('contact_person_name');
    }

    public function updatedContactPersonEmail()
    {
        $this->validateOnly('contact_person_email');
    }

    public function updatedContactPersonMobileNo()
    {
        $this->validateOnly('contact_person_mobile_no');
    }
}
