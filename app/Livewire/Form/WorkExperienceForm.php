<?php

namespace App\Livewire\Form;

use App\Models\Personnel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WorkExperienceForm extends Component
{
    // ...existing code...

    public function back()
    {
        $this->updateMode = false;
        $this->showMode = true;
    }
    public $personnel;
    public $old_work_experiences = [], $new_work_experiences = [];
    public $showMode = false, $updateMode = false;

    protected $rules = [
        'old_work_experiences.*.title' => 'required',
        'old_work_experiences.*.company' => 'required',
        'old_work_experiences.*.inclusive_from' => 'required',
        'old_work_experiences.*.inclusive_to' => 'required',
        'old_work_experiences.*.monthly_salary' => 'nullable',
        'old_work_experiences.*.paygrade_step_increment' => 'nullable',
        'old_work_experiences.*.appointment' => 'required',
        'old_work_experiences.*.is_gov_service' => 'required|in:0,1',
        'new_work_experiences.*.title' => 'required',
        'new_work_experiences.*.company' => 'required',
        'new_work_experiences.*.inclusive_from' => 'required',
        'new_work_experiences.*.inclusive_to' => 'required',
        'new_work_experiences.*.monthly_salary' => 'required',
        'new_work_experiences.*.paygrade_step_increment' => 'required',
        'new_work_experiences.*.appointment' => 'required',
        'new_work_experiences.*.is_gov_service' => 'required|in:0,1',
    ];

    public function  mount($id, $showMode = true)
    {
        if ($id) {
            $this->personnel = Personnel::findOrFail($id);
            $this->old_work_experiences = $this->personnel->workExperiences;

            $this->old_work_experiences = $this->personnel->workExperiences()->get()->map(function ($work_experience) {
                return [
                    'id' => $work_experience->id,
                    'title' => $work_experience->title,
                    'company' => $work_experience->company,
                    'inclusive_from' => $work_experience->inclusive_from,
                    'inclusive_to' => $work_experience->inclusive_to,
                    'monthly_salary' => $work_experience->monthly_salary,
                    'paygrade_step_increment' => $work_experience->paygrade_step_increment,
                    'appointment' => $work_experience->appointment,
                    'is_gov_service' => $work_experience->is_gov_service,
                ];
            })->toArray();

            $this->new_work_experiences[] = [
                'title' => '',
                'company' => '',
                'inclusive_from' => '',
                'inclusive_to' => '',
                'monthly_salary' => '',
                'paygrade_step_increment' => '',
                'appointment' => '',
                'is_gov_service' => '1' // Default to "Yes"
            ];
        }
    }

    public function addField()
    {
        $this->new_work_experiences[] = [
            'title' => '',
            'company' => '',
            'inclusive_from' => '',
            'inclusive_to' => '',
            'monthly_salary' => '',
            'paygrade_step_increment' => '',
            'appointment' => '',
            'is_gov_service' => '1' // Default to "Yes"
        ];
    }

    public function removeNewField($index)
    {
        array_splice($this->new_work_experiences, $index, 1);
    }

    public function removeOldField($index)
    {
        try {
            $workExperienceId = $this->old_work_experiences[$index]['id'];
            $workExperienceModel = $this->personnel->workExperiences()->findOrFail($workExperienceId);

            // Delete the child from the database
            $workExperienceModel->delete();

            session()->flash('flash.banner', 'Work Experience deleted successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to deleteWork Experience ');
            session()->flash('flash.bannerStyle', 'danger');
        }
        session(['active_personnel_tab' => 'work_experience']);

        if (Auth::user()->role === "teacher") {
            return redirect()->route('personnel.profile');
        } elseif (Auth::user()->role === "school_head") {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    public function edit()
    {
        $this->updateMode = true;
        $this->showMode = false;
    }

    public function cancel()
    {
        $this->resetModes();
        if (Auth::user()->role === "teacher") {
            return redirect()->route('personnel.profile');
        } elseif (Auth::user()->role === "school_head") {
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

    public function save()
    {
        try {
            $this->validate();

            if ($this->personnel->workExperiences()->exists()) {
                foreach ($this->old_work_experiences as $work_experience) {
                    $this->personnel->workExperiences()->where('id', $work_experience['id'])
                        ->update([
                            'title' => $work_experience['title'],
                            'company' => $work_experience['company'],
                            'inclusive_from' => $work_experience['inclusive_from'],
                            'inclusive_to' => $work_experience['inclusive_to'],
                            'monthly_salary' => $work_experience['monthly_salary'],
                            'paygrade_step_increment' => $work_experience['paygrade_step_increment'],
                            'appointment' => $work_experience['appointment'],
                            'is_gov_service' => $work_experience['is_gov_service']
                        ]);
                }
            }

            if ($this->new_work_experiences != null) {
                foreach ($this->new_work_experiences as $work_experience) {
                    // Skip empty entries
                    if (empty($work_experience['title']) && empty($work_experience['company'])) {
                        continue;
                    }

                    $this->personnel->workExperiences()->create([
                        'title' => $work_experience['title'],
                        'company' => $work_experience['company'],
                        'inclusive_from' => $work_experience['inclusive_from'],
                        'inclusive_to' => $work_experience['inclusive_to'],
                        'monthly_salary' => $work_experience['monthly_salary'],
                        'paygrade_step_increment' => $work_experience['paygrade_step_increment'],
                        'appointment' => $work_experience['appointment'],
                        'is_gov_service' => $work_experience['is_gov_service']
                    ]);
                }
            }

            $this->updateMode = false;
            $this->showMode = true;

            session()->flash('flash.banner', 'Work Experience saved successfully');
            session()->flash('flash.bannerStyle', 'success');

        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('flash.banner', 'Please correct the validation errors and try again.');
            session()->flash('flash.bannerStyle', 'danger');
            return;
        } catch (\Exception $e) {
            session()->flash('flash.banner', 'An error occurred while saving work experience.');
            session()->flash('flash.bannerStyle', 'danger');
            return;
        }

        if (Auth::user()->role === "teacher") {
            return redirect()->route('personnel.profile');
        } elseif (Auth::user()->role === "school_head") {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    public function render()
    {
        return view('livewire.form.work-experience-form');
    }
}
