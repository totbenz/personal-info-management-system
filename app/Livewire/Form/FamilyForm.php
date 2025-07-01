<?php

namespace App\Livewire\Form;

use App\Models\Family;
use App\Models\Personnel;
use Livewire\Component;
use App\Livewire\PersonnelNavigation;
use Illuminate\Support\Facades\Auth;

class FamilyForm extends PersonnelNavigation
{
    public $personnel, $father, $mother, $spouse;
    public $old_children = [], $new_children = [];
    public $fathers_first_name, $fathers_middle_name, $fathers_last_name, $fathers_name_ext;
    public $mothers_first_name, $mothers_middle_name, $mothers_last_name;
    public $spouse_first_name, $spouse_middle_name, $spouse_last_name, $spouse_name_ext;
    public $spouse_occupation, $spouse_business_name, $spouse_business_address, $spouse_tel_no;

    public $showMode = false, $updateMode = false;

    protected $rules = [
        'fathers_first_name' => 'required',
        'fathers_middle_name' => 'required',
        'fathers_last_name' => 'required',
        'fathers_name_ext' => 'nullable',
        'mothers_first_name' => 'required',
        'mothers_middle_name' => 'required',
        'mothers_last_name' => 'required',
        'spouse_first_name' => 'required',
        'spouse_middle_name' => 'required',
        'spouse_last_name' => 'required',
        'spouse_name_ext' => 'nullable',
        'spouse_occupation' => 'required',
        'spouse_business_name' => 'required',
        'spouse_business_address' => 'required',
        'spouse_tel_no' => 'required',
        'old_children.*.first_name' => 'required',
        'old_children.*.middle_name' => 'required',
        'old_children.*.last_name' => 'required',
        'old_children.*.name_ext' => 'nullable',
        'old_children.*.date_of_birth' => 'required|date',
        'new_children.*.first_name' => 'required',
        'new_children.*.middle_name' => 'required',
        'new_children.*.last_name' => 'required',
        'new_children.*.name_ext' => 'nullable',
        'new_children.*.date_of_birth' => 'required|date',
    ];

    public function mount($id = null)
    {
        $this->personnel = Personnel::findOrFail($id);

        $this->father = $this->personnel->father;
        $this->mother = $this->personnel->mother;
        $this->spouse = $this->personnel->spouse;
        $this->old_children = $this->personnel->children;

        if ($this->father != null) {
            $this->fathers_first_name = $this->father->first_name;
            $this->fathers_middle_name = $this->father->middle_name;
            $this->fathers_last_name = $this->father->last_name;
            $this->fathers_name_ext = $this->father->name_ext;
        }

        if ($this->mother != null) {
            $this->mothers_first_name = $this->mother->first_name;
            $this->mothers_middle_name = $this->mother->middle_name;
            $this->mothers_last_name = $this->mother->last_name;
        }

        if ($this->spouse != null) {
            $this->spouse_first_name = $this->spouse->first_name;
            $this->spouse_middle_name = $this->spouse->middle_name;
            $this->spouse_last_name = $this->spouse->last_name;
            $this->spouse_name_ext = $this->spouse->name_ext;
            $this->spouse_occupation = $this->spouse->occupation;
            $this->spouse_business_name = $this->spouse->employer_business_name;
            $this->spouse_business_address = $this->spouse->business_address;
            $this->spouse_tel_no = $this->spouse->telephone_number;
        }

        $this->old_children = $this->personnel->children()->get()->map(function($child) {
            return [
                'id' => $child->id,
                'first_name' => $child->first_name,
                'middle_name' => $child->middle_name,
                'last_name' => $child->last_name,
                'name_ext' => $child->name_ext,
                'date_of_birth' => $child->date_of_birth,
            ];
        })->toArray();

        // $this->new_children[] = [
        //     'first_name' => '',
        //     'middle_name' => '',
        //     'last_name' => '',
        //     'name_ext' => '',
        //     'date_of_birth' => ''
        // ];
    }

    public function addField()
    {
        $this->new_children[] = [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'name_ext' => '',
            'date_of_birth' => ''
        ];
    }

    public function removeNewField($index)
    {
        array_splice($this->new_children, $index, 1);
    }

    public function confirmRemoveOldField($index)
    {
        try {
            $childId = $this->old_children[$index]['id'];
            $childModel = $this->personnel->children()->findOrFail($childId);
            $childModel->delete();

            unset($this->old_children[$index]);

            // Reset the array to remove gaps
            $this->old_children = array_values($this->old_children);
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to delete child information');
            session()->flash('flash.bannerStyle', 'danger');

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

    public function render()
    {
        return view('livewire.form.family-form');
    }

    public function resetModes()
    {
        $this->updateMode = false;
        $this->showMode = false;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->father != null) {
                $this->personnel->father()->update([
                    'relationship' => 'father',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->fathers_first_name,
                    'middle_name' => $this->fathers_middle_name,
                    'last_name' => $this->fathers_last_name,
                    'name_extension' => $this->fathers_name_ext
                ]);
            } else {
                $this->personnel->father()->create([
                    'relationship' => 'father',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->fathers_first_name,
                    'middle_name' => $this->fathers_middle_name,
                    'last_name' => $this->fathers_last_name,
                    'name_extension' => $this->fathers_name_ext
                ]);
            }

            if ($this->mother != null) {
                $this->personnel->mother()->update([
                    'relationship' => 'mother',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->mothers_first_name,
                    'middle_name' => $this->mothers_middle_name,
                    'last_name' => $this->mothers_last_name
                ]);
            } else {
                $this->personnel->mother()->create([
                    'relationship' => 'mother',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->mothers_first_name,
                    'middle_name' => $this->mothers_middle_name,
                    'last_name' => $this->mothers_last_name
                ]);
            }

            if ($this->spouse != null) {
                $this->personnel->spouse()->update([
                    'relationship' => 'spouse',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->spouse_first_name,
                    'middle_name' => $this->spouse_middle_name,
                    'last_name' => $this->spouse_last_name,
                    'name_extension' => $this->spouse_name_ext,
                    'occupation' => $this->spouse_occupation,
                    'employer_business_name' => $this->spouse_business_name,
                    'business_address' => $this->spouse_business_address,
                    'telephone_number' => $this->spouse_tel_no
                ]);
            } else {
                $this->personnel->spouse()->create([
                    'relationship' => 'spouse',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->spouse_first_name,
                    'middle_name' => $this->spouse_middle_name,
                    'last_name' => $this->spouse_last_name,
                    'name_extension' => $this->spouse_name_ext,
                    'occupation' => $this->spouse_occupation,
                    'employer_business_name' => $this->spouse_business_name,
                    'business_address' => $this->spouse_business_address,
                    'telephone_number' => $this->spouse_tel_no
                ]);
            }

            if ($this->personnel->children()->exists()) {
                foreach ($this->old_children as $child) {
                    $this->personnel->children()->where('id', $child['id'])
                        ->update([
                            'relationship' => 'children',
                            'first_name' => $child['first_name'],
                            'middle_name' => $child['middle_name'],
                            'last_name' => $child['last_name'],
                            'name_extension' => $child['name_ext'],
                            'date_of_birth' => $child['date_of_birth'],
                        ]);
                }
            }

            if($this->new_children != null)
            {
                foreach ($this->new_children as $new_child) {
                    $this->personnel->children()->create([
                        'relationship' => 'children',
                        'first_name' => $new_child['first_name'],
                        'middle_name' => $new_child['middle_name'],
                        'last_name' => $new_child['last_name'],
                        'name_extension' => $new_child['name_ext'],
                        'date_of_birth' => $new_child['date_of_birth'],
                    ]);
                }
            }

            $this->showMode = true;
            $this->updateMode = false;

            session()->flash('flash.banner', 'Family information saved successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Exception $ex) {
            session()->flash('flash.banner', 'Failed to save Address and Contact Person');
            session()->flash('flash.bannerStyle', 'danger');
        }
        session(['active_personnel_tab' => 'family']);

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
