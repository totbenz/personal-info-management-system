<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SchoolForm extends Component
{
    public $school_id;
    public $division;
    public $district_id;
    public $school_name;
    public $address;
    public $email;
    public $phone;
    public $curricular_classification;

    public function render()
    {
        return view('livewire.school-form');
    }

    public function cancel()
    {
        // Logic to reset form fields or handle cancellation
        $this->reset(); // Example: Reset all form fields
        $this->dispatchBrowserEvent('close-modal', ['id' => 'create-school-modal']);
    }
}