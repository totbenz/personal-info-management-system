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
}
