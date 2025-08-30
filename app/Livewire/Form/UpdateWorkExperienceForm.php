<?php

namespace App\Livewire\Form;

use Livewire\Component;

class UpdateWorkExperienceForm extends WorkExperienceForm
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
        return view('livewire.form.update-work-experience-form');
    }
}
