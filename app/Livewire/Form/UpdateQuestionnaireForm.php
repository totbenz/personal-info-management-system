<?php

namespace App\Livewire\Form;

use Livewire\Component;

class UpdateQuestionnaireForm extends QuestionnaireForm
{
    public function mount($id = null)
    {
        parent::mount($id);
    }

    public function render()
    {
        return view('livewire.form.update-questionnaire-form');
    }
}
