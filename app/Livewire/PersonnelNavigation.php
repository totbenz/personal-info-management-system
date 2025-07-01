<?php

namespace App\Livewire;

use Livewire\Component;

class PersonnelNavigation extends Component
{
    public $formNav = 'personal_information';
    public $personnelId;
    public $showMode = false, $storeMode = false, $updateMode = false;

    public function mount($personnel_id = null)
    {
        if($personnel_id) {
            $this->personnelId = $personnel_id;
        }
        
        // Check if there's an active tab stored in session
        if (session()->has('active_personnel_tab')) {
            $this->formNav = session('active_personnel_tab');
            // Clear the session value after using it
            session()->forget('active_personnel_tab');
        }
    }

    public function edit()
    {
        $this->updateMode = true;
        $this->showMode = false;
    }

    public function back()
    {
        $this->updateMode = false;
        $this->showMode = true;

        return redirect()->back();
    }



    public function setFormNav($section)
    {
        $this->formNav = $section;
    }

    public function render()
    {
        return view('livewire.personnel-navigation');
    }

    // public function removeOldTraining($index)
    // {
    //     try {
    //         $trainingId = $this->old_training_certifications[$index]['id'];
    //         $trainingModel = $this->personnel->trainingCertifications()->findOrFail($trainingId);

    //         // Delete the child from the database
    //         $trainingModel->delete();

    //         session()->flash('flash.banner', 'Work Experience deleted successfully');
    //         session()->flash('flash.bannerStyle', 'success');

    //     } catch (\Throwable $th) {
    //         session()->flash('flash.banner', 'Failed to deleteWork Experience ');
    //         session()->flash('flash.bannerStyle', 'danger');
    //     }
    // }
}
