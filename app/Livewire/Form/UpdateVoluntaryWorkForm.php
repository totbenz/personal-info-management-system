<?php

namespace App\Livewire\Form;

use Livewire\Component;

class UpdateVoluntaryWorkForm extends VoluntaryWorkForm
{
    public function back()
    {
        $this->updateMode = false;
        $this->showMode = true;
        // Optionally emit event for navigation or redirect
        // $this->emit('navigateBack');
    }
    public function render()
    {
        return view('livewire.form.update-voluntary-work-form');
    }
}
