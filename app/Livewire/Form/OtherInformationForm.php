<?php

namespace App\Livewire\Form;

use App\Models\Family;
use App\Models\Personnel;
use Livewire\Component;
use App\Livewire\PersonnelNavigation;
use Illuminate\Support\Facades\Auth;

class OtherInformationForm extends PersonnelNavigation
{
    public $personnel;
    public $old_skills = [], $new_skills = [];
    public $old_nonacademic_distinctions = [], $new_nonacademic_distinctions = [];
    public $old_associations = [], $new_associations = [];

    // public $showMode = false, $updateMode = false;

    protected $rules = [
        'old_skills.*.name' => 'required',
        'old_nonacademic_distinctions.*.name' => 'required',
        'old_associations.*.name' => 'required',
        'new_skills.*.name' => 'required',
        'new_nonacademic_distinctions.*.name' => 'required',
        'new_associations.*.name' => 'required',
    ];

    public function mount($id = null)
    {
        $this->personnel = Personnel::findOrFail($id);

        $this->old_skills = $this->personnel->skills;
        $this->old_nonacademic_distinctions = $this->personnel->nonacademicDistinctions;
        $this->old_associations = $this->personnel->associations;

        $this->old_skills = $this->personnel->skills()->get()->map(function($skill) {
            return [
                'id' => $skill->id,
                'type' => $skill->type,
                'name' => $skill->name,
            ];
        })->toArray();

        $this->old_nonacademic_distinctions = $this->personnel->nonacademicDistinctions()->get()->map(function($nonacademic_distinction) {
            return [
                'id' => $nonacademic_distinction->id,
                'type' => $nonacademic_distinction->type,
                'name' => $nonacademic_distinction->name,
            ];
        })->toArray();

        $this->old_associations = $this->personnel->associations()->get()->map(function($association) {
            return [
                'id' => $association->id,
                'type' => $association->type,
                'name' => $association->name,
            ];
        })->toArray();
    }

    public function addField($type)
    {
        if($type == "special_skill")
        {
            $this->new_skills[] = [ 'name' => '' ];
        } elseif($type == "nonacademic_distinction"){
            $this->new_nonacademic_distinctions[] = [ 'name' => '' ];
        } elseif($type == "association"){
            $this->new_associations[] = [ 'name' => '' ];
        }
    }

    public function removeNewField($index, $type)
    {
        if($type == "special_skill")
        {
            array_splice($this->new_skills, $index, 1);
        } elseif($type == "nonacademic_distinction"){
            array_splice($this->new_nonacademic_distinctions, $index, 1);
        } elseif($type == "association"){
            array_splice($this->new_associations, $index, 1);
        }
    }

    public function confirmRemoveOldField($index, $type)
    {
        try {
            if($type == "special_skill")
            {
                $skillModel = $this->personnel->skills()->findOrFail($this->old_skills[$index]['id']);

                $u = $skillModel->delete();

                unset($this->old_skills[$index]);

                // Reset the array to remove gaps
                $this->old_skills = array_values($this->old_skills);
            } elseif($type == "nonacademic_distinction"){
                $nonacademicDistinctionsModel = $this->personnel->skills()->findOrFail($this->old_nonacademic_distinctions[$index]['id']);
                $nonacademicDistinctionsModel->delete();

                unset($this->old_nonacademic_distinctions[$index]);

                // Reset the array to remove gaps
                $this->old_nonacademic_distinctions = array_values($this->old_nonacademic_distinctions);
            } elseif($type == "association"){
                $associationModel = $this->personnel->skills()->findOrFail($this->old_associations[$index]['id']);
                $associationModel->delete();

                unset($this->old_associations[$index]);

                // Reset the array to remove gaps
                $this->old_associations = array_values($this->old_associations);
            }
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to delete information');
            session()->flash('flash.bannerStyle', 'danger');

            if(Auth::user()->role === "teacher")
            {
                return redirect()->route('personnel.profile');
            } elseif(Auth::user()->role === "school_head")
            {
                return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
            } else {
                return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
            };
        }
    }

    public function render()
    {
        return view('livewire.form.other-information-form');
    }

    public function save()
    {
        // dd($this);
        $this->validate();

        try {
            // Save for Special Skills


            if ($this->personnel->skills()->exists()) {
                foreach ($this->old_skills as $skill) {
                    // dd($skill);
                    $this->personnel->skills()->where('id', $skill['id'])
                    ->update([
                        'personnel_id' => $this->personnel->id,
                        'type' => 'special_skill',
                        'name' => $skill['name'],
                    ]);
                }
            }

            if($this->new_skills != null)
            {
                foreach ($this->new_skills as $skill) {
                    $this->personnel->skills()->create([
                            'personnel_id' => $this->personnel->id,
                            'type' => 'special_skill',
                            'name' => $skill['name'],
                        ]);
                }
            }

            // Save for Nonacademic Distinctions
            if ($this->personnel->nonacademicDistinctions()->exists()) {
                foreach ($this->old_nonacademic_distinctions as $nonacademic_distinction) {
                    $this->personnel->nonacademicDistinctions()->where('id', $nonacademic_distinction['id'])
                        ->update([
                            'personnel_id' => $this->personnel->id,
                            'type' => 'nonacademic_distinction',
                            'name' => $nonacademic_distinction['name'],
                        ]);
                }
            }

            if($this->new_nonacademic_distinctions != null)
            {
                foreach ($this->new_nonacademic_distinctions as $nonacademic_distinction) {
                    $this->personnel->nonacademicDistinctions()->create([
                            'personnel_id' => $this->personnel->id,
                            'type' => 'nonacademic_distinction',
                            'name' => $nonacademic_distinction['name'],
                        ]);
                }
            }

            // Save for Associations
            if ($this->personnel->associations()->exists()) {
                foreach ($this->old_associations as $association) {
                    $this->personnel->associations()->where('id', $association['id'])
                        ->update([
                            'personnel_id' => $this->personnel->id,
                            'type' => 'association',
                            'name' => $association['name'],
                        ]);
                }
            }

            if($this->new_associations != null)
            {
                foreach ($this->new_associations as $association) {
                    $this->personnel->associations()->create([
                            'personnel_id' => $this->personnel->id,
                            'type' => 'association',
                            'name' => $association['name'],
                        ]);
                }
            }

            $this->showMode = true;
            $this->updateMode = false;

            session()->flash('flash.banner', 'Other information saved successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Throwable $th) {
            session()->flash('flash.banner', $th->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }
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
}
