<?php

namespace App\Livewire\Form;

use App\Models\Personnel;
use Livewire\Component;
use App\Livewire\PersonnelNavigation;
use Illuminate\Support\Facades\Auth;

class TrainingCertificationForm extends PersonnelNavigation
{
    public $personnel;
    public $old_training_certifications = [], $new_training_certifications = [];
    public $showMode = false, $updateMode = false;

    protected $rules = [
        'old_training_certifications.*.training_seminar_title' => 'required',
        'old_training_certifications.*.type' => 'required',
        'old_training_certifications.*.sponsored' => 'required',
        'old_training_certifications.*.inclusive_from' => 'required',
        'old_training_certifications.*.inclusive_to' => 'required',
        'old_training_certifications.*.hours' => 'required',
        'new_training_certifications.*.training_seminar_title' => 'required',
        'new_training_certifications.*.type' => 'required',
        'new_training_certifications.*.sponsored' => 'required',
        'new_training_certifications.*.inclusive_from' => 'required',
        'new_training_certifications.*.inclusive_to' => 'required',
        'new_training_certifications.*.hours' => 'required',
    ];

    public function mount($id = null)
    {
        if($id) {
            $this->personnel = Personnel::findOrFail($id);
            $this->old_training_certifications = $this->personnel->workExperiences;

            $this->old_training_certifications = $this->personnel->trainingCertifications()->get()->map(function($training_certification) {
                return [
                    'id' => $training_certification->id,
                    'training_seminar_title' => $training_certification->training_seminar_title,
                    'type' => $training_certification->type,
                    'inclusive_from' => $training_certification->inclusive_from,
                    'inclusive_to' => $training_certification->inclusive_to,
                    'sponsored' => $training_certification->sponsored,
                    'hours' => $training_certification->hours
                ];
            })->toArray();

            $this->new_training_certifications[] =[
                'training_seminar_title' => '',
                'type' => '',
                'inclusive_from' => '',
                'inclusive_to' => '',
                'sponsored' => '',
                'hours' => ''
            ];
        }
    }

    public function addField()
    {
        $this->new_training_certifications[] = [
            'training_seminar_title' => '',
            'type' => '',
            'inclusive_from' => '',
            'inclusive_to' => '',
            'sponsored' => '',
            'hours' => ''
        ];
    }

    public function removeNewField($index)
    {
        array_splice($this->new_training_certifications, $index, 1);
    }

    public function removeOldField($index)
    {
        try {
            $workExperienceId = $this->old_training_certifications[$index]['id'];
            $workExperienceModel = $this->personnel->trainingCertifications()->findOrFail($workExperienceId);

            // Delete the child from the database
            $workExperienceModel->delete();

            session()->flash('flash.banner', 'Work Experience deleted successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to deleteWork Experience ');
            session()->flash('flash.bannerStyle', 'danger');
        }
        session(['active_personnel_tab' => 'training_certification']);
    }

    public function edit()
    {
        $this->updateMode = true;
        $this->showMode = false;
    }

    public function cancel()
    {
        $this->resetModes();
        if(Auth::user()->role === "teacher")
        {
            return redirect()->route('personnel.profile');
        } elseif(Auth::user()->role === "school_head")
        {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    public function resetModes()
    {
        $this->updateMode = false;
        $this->showMode = true;
    }

    // public function save()
    // {
    //     $this->validate();

    //     if ($this->personnel->trainingCertifications()->exists()) {
    //         foreach ($this->old_training_certifications as $training_certification) {
    //             $this->personnel->trainingCertifications()->where('id', $training_certification['id'])
    //                 ->update([
    //                     'training_seminar_title' => $training_certification->training_seminar_title,
    //                     'type' => $training_certification->type,
    //                     'inclusive_from' => $training_certification->inclusive_from,
    //                     'inclusive_to' => $training_certification->inclusive_to,
    //                     'sponsored' => $training_certification->sponsored,
    //                     'hours' => $training_certification->hours
    //                 ]);
    //         }
    //     }

    //     if($this->new_training_certifications != null)
    //     {
    //         foreach ($this->new_training_certifications as $training_certification) {
    //             $this->personnel->trainingCertifications()->create([
    //                 'training_seminar_title' => $training_certification->training_seminar_title,
    //                 'type' => $training_certification->type,
    //                 'inclusive_from' => $training_certification->inclusive_from,
    //                 'inclusive_to' => $training_certification->inclusive_to,
    //                 'sponsored' => $training_certification->sponsored,
    //                 'hours' => $training_certification->hours
    //             ]);
    //         }
    //     }

    //     $this->updateMode = false;
    //     $this->showMode = true;

    //     session()->flash('flash.banner', 'Work Experience saved successfully');
    //     session()->flash('flash.bannerStyle', 'success');

    //     return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
    // }

    public function save()
    {
        $this->validate();
        if ($this->personnel->trainingCertifications()->exists()) {
            foreach ($this->old_training_certifications as $training_certification) {
                $this->personnel->trainingCertifications()->where('id', $training_certification['id'])
                    ->update([
                        'training_seminar_title' => $training_certification['training_seminar_title'],
                        'type' => $training_certification['type'],
                        'sponsored' => $training_certification['sponsored'],
                        'inclusive_from' => $training_certification['inclusive_from'],
                        'inclusive_to' => $training_certification['inclusive_to'],
                        'hours' => $training_certification['hours']
                    ]);
            }
        }
        if($this->new_training_certifications != null)
        {
            foreach ($this->new_training_certifications as $training_certification) {
                $this->personnel->trainingCertifications()->create([
                    'training_seminar_title' => $training_certification['training_seminar_title'],
                        'type' => $training_certification['type'],
                        'sponsored' => $training_certification['sponsored'],
                        'inclusive_from' => $training_certification['inclusive_from'],
                        'inclusive_to' => $training_certification['inclusive_to'],
                        'hours' => $training_certification['hours']
                ]);
            }
        }

        $this->updateMode = false;
        $this->showMode = true;

        session()->flash('flash.banner', 'Training Certification saved successfully');
        session()->flash('flash.bannerStyle', 'success');
        session(['active_personnel_tab' => 'training_certification']);

        if(Auth::user()->role === "teacher")
        {
            return redirect()->route('personnel.profile');
        } elseif(Auth::user()->role === "school_head")
        {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    public function render()
    {
        return view('livewire.form.training-certification-form');
    }
}
