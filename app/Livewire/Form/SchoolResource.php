<?php

namespace App\Livewire\Form;

use App\Models\School;
use Livewire\Component;

class SchoolResource extends Component
{
    public $school;
    // public $school_id, $region, $division, $district, $school_name,
    //        $address, $email, $phone, $curricular_classification;
    public $showMode = false, $storeMode = false, $updateMode = false;

    protected $rules = [
        'school_id' => 'required',
        'region' => 'required',
        'school_name' => 'required',
        'division' => 'required',
        'district' => 'required',
        'address' => 'required',
        'email' => 'required',
        'phone' => 'required',
        'curricular_classification' => 'required'
    ];

    public function mount($id = null)
    {
        if($id)
        {
            $this->school = School::findOrFail($id);

            // if ($this->school) {
            //     $this->school_id = $this->school->school_id;
            //     $this->school_name = $this->school->school_name;
            //     $this->region = $this->school->region;
            //     $this->division = $this->school->division;
            //     $this->district = $this->school->district;
            //     $this->address = $this->school->address;
            //     $this->email = $this->school->email;
            //     $this->phone = $this->school->phone;
            //     $this->curricular_classification = json_encode($this->school->curricular_classification);
            // }
        }
    }

    public function render()
    {
        return view('livewire.form.school-resource');
    }

    public function create()
    {
        $this->storeMode = true;
    }

    public function edit()
    {
        $this->updateMode = true;
    }

    public function cancel()
    {
        $this->storeMode = false;
        $this->updateMode = false;

        return redirect()->back();
    }

    public function store()
    {
        $this->validate();

        try {
            $school = School::create([
                'school_id' => $this->school_id,
                'region' => $this->region,
                'school_name' => $this->school_name,
                'division' => $this->division,
                'district' => $this->district,
                'address' => $this->address,
                'email' => $this->email,
                'phone' => $this->phone,
                'curricular_classification' => $this->curricular_classification
            ]);

            $this->storeMode = false;

            session()->flash('flash.banner', 'School created successfully');
            session()->flash('flash.bannerStyle', 'success');

            return redirect()->route('schools.show', ['school' => $school->id]);
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to create school.');
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $this->school->update([
                'school_id' => $this->school_id,
                'region' => $this->region,
                'school_name' => $this->school_name,
                'division' => $this->division,
                'district' => $this->district,
                'address' => $this->address,
                'email' => $this->email,
                'phone' => $this->phone,
                'curricular_classification' => $this->curricular_classification
            ]);

            $this->updateMode = false;

            session()->flash('flash.banner', 'School updated successfully');
            session()->flash('flash.bannerStyle', 'success');

            return redirect()->route('schools.show', ['school' =>  $this->school->id]);
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to update school.');
            session()->flash('flash.bannerStyle', 'danger');
        }
    }
}

