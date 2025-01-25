<?php

namespace App\Livewire\Form;

use App\Models\Log;
use App\Models\Personnel;
use App\Models\School;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\PersonnelNavigation;

class PersonalInformationForm extends PersonnelNavigation
{
    public $personnel;
    public $first_name, $middle_name, $last_name, $name_ext,
           $date_of_birth, $place_of_birth, $civil_status, $sex,
           $citizenship, $blood_type, $height, $weight,
           $tin, $sss_num, $gsis_num, $philhealth_num,
           $pagibig_num,
           $personnel_id, $school_id, $position_id, $appointment, $fund_source, $job_status, $category, $employment_start, $employment_end, $salary_grade, $step, $classification, $position,
           $email, $tel_no, $mobile_no;
    public $showMode = false, $storeMode = false, $updateMode = false;

    protected $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'middle_name' => 'nullable',
        'name_ext' => 'nullable',
        'date_of_birth' => 'required',
        'place_of_birth' => 'required',
        'sex' => 'required',
        'civil_status' => 'required',
        'citizenship' => 'required',
        'height' => 'required',
        'weight' => 'required',
        'blood_type' => 'required',

        'personnel_id' => 'required',
        'school_id' => 'required',
        'position_id' => 'required',
        'appointment' => 'required',
        'fund_source' => 'required',
        'salary_grade' => 'required',
        'step' => 'required',
        'category' => 'required',
        'job_status' => 'required',
        'employment_start' => 'required',

        'tin' => 'required|min:8|max:12',
        'sss_num' => 'required|size:10',
        'gsis_num' => 'required|min:8',
        'philhealth_num' => 'required|min:11',
        'pagibig_num' => 'required|min:11',

        'email' => 'required',
        'tel_no' => 'nullable',
        'mobile_no' => 'required',
    ];

    public function mount($id = null)
    {
        if($id) {
            $this->personnel = Personnel::findOrFail($id);

            if ($this->personnel) {
                $this->first_name = $this->personnel->first_name;
                $this->last_name = $this->personnel->last_name;
                $this->middle_name = $this->personnel->middle_name;
                $this->name_ext = $this->personnel->name_ext;
                $this->date_of_birth = $this->personnel->date_of_birth;
                $this->place_of_birth = $this->personnel->place_of_birth;
                $this->civil_status = $this->personnel->civil_status;
                $this->sex = $this->personnel->sex;
                $this->citizenship = $this->personnel->citizenship;
                $this->blood_type = $this->personnel->blood_type;
                $this->height = $this->personnel->height;
                $this->weight = $this->personnel->weight;

                $this->tin = $this->personnel->tin;
                $this->sss_num = $this->personnel->sss_num;
                $this->gsis_num = $this->personnel->gsis_num;
                $this->philhealth_num = $this->personnel->philhealth_num;
                $this->pagibig_num = $this->personnel->pagibig_num;

                $this->personnel_id = $this->personnel->personnel_id;
                $this->school_id = $this->personnel->school->id;
                $this->position = $this->personnel->position->title;
                $this->position_id = $this->personnel->position->id;
                $this->appointment = $this->personnel->appointment;
                $this->fund_source = $this->personnel->fund_source;
                $this->salary_grade = $this->personnel->salary_grade;
                $this->step = $this->personnel->step;
                $this->category = $this->personnel->category;
                $this->job_status = $this->personnel->job_status;
                $this->employment_start = $this->personnel->employment_start;
                if ($this->personnel->employment_end)
                {
                    $this->employment_end = $this->personnel->employment_end;
                }

                $this->email = $this->personnel->email;
                $this->tel_no = $this->personnel->tel_no;
                $this->mobile_no = $this->personnel->mobile_no;
            }
        }
    }

    public function render()
    {
        return view('livewire.form.personal-information-form');
    }

    public function create()
    {
        $this->storeMode = true;
        $this->showMode = false;
        $this->updateMode = false;
    }

    public function cancel()
    {
        $this->storeMode = false;
        $this->showMode = true;
        $this->updateMode = false;

        if(Auth::user()->role === "teacher")
        {
            return redirect()->route('personnels.profile');
        } elseif(Auth::user()->role === "schoool_head")
        {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        }else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    // public function save()
    // {
    //     $this->validate();

    //     // try {
    //     $school = School::findOrFail($this->school_id);

    //     $data = [
    //         'first_name' => $this->first_name,
    //         'last_name' => $this->last_name,
    //         'middle_name' => $this->middle_name,
    //         'name_ext' => $this->name_ext,
    //         'date_of_birth' => $this->date_of_birth,
    //         'place_of_birth' => $this->place_of_birth,
    //         'sex' => $this->sex,
    //         'civil_status' => $this->civil_status,
    //         'citizenship' => $this->citizenship,
    //         'height' => $this->height,
    //         'weight' => $this->weight,
    //         'blood_type' => $this->blood_type,

    //         'tin' => $this->tin,
    //         'sss_num' => $this->sss_num,
    //         'gsis_num' => $this->gsis_num,
    //         'philhealth_num' => $this->philhealth_num,
    //         'pagibig_num' => $this->pagibig_num,

    //         'personnel_id' => $this->personnel_id,
    //         'school_id' => $school->id,
    //         'position_id' => $this->position_id,
    //         'appointment' => $this->appointment,
    //         'fund_source' => $this->fund_source,
    //         'salary_grade' => $this->salary_grade,
    //         'step' => $this->step,
    //         'category' => $this->category,
    //         'job_status' => $this->job_status,
    //         'employment_start' => $this->employment_start,
    //         'employment_end' => $this->employment_end,

    //         'email' => $this->email,
    //         'tel_no' => $this->tel_no,
    //         'mobile_no' => $this->mobile_no
    //     ];

    //     if ($this->personnel == null) {
    //         $this->personnel = Personnel::create($data);
    //         session()->flash('flash.banner', 'Personnel created successfully');
    //         session()->flash('flash.bannerStyle', 'success');

    //         return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
    //     } else {

    //         $this->personnel->update($data);
    //         session()->flash('flash.banner', 'Personal Information saved successfully');
    //         session()->flash('flash.bannerStyle', 'success');

    //         return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
    //     }

    //     $this->updateMode = false;
    //     $this->storeMode = false;
    //     $this->showMode = true;
    // }

    public function save()
    {
        $this->validate();

        // Find the school
        $school = School::findOrFail($this->school_id);

        // Prepare data for Personnel
        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'name_ext' => $this->name_ext,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
            'sex' => $this->sex,
            'civil_status' => $this->civil_status,
            'citizenship' => $this->citizenship,
            'height' => $this->height,
            'weight' => $this->weight,
            'blood_type' => $this->blood_type,

            'tin' => $this->tin,
            'sss_num' => $this->sss_num,
            'gsis_num' => $this->gsis_num,
            'philhealth_num' => $this->philhealth_num,
            'pagibig_num' => $this->pagibig_num,

            'personnel_id' => $this->personnel_id,
            'school_id' => $school->id,
            'position_id' => $this->position_id,
            'appointment' => $this->appointment,
            'fund_source' => $this->fund_source,
            'salary_grade' => $this->salary_grade,
            'step' => $this->step,
            'category' => $this->category,
            'job_status' => $this->job_status,
            'employment_start' => $this->employment_start,
            'employment_end' => $this->employment_end ?? null,

            'email' => $this->email,
            'tel_no' => $this->tel_no,
            'mobile_no' => $this->mobile_no
        ];

        if ($this->personnel == null) {
            // Create a new Personnel
            $this->personnel = Personnel::create($data);

            // Create an initial ServiceRecord for the new Personnel
            $this->personnel->serviceRecords()->create([
                'personnel_id' => $this->personnel->id,
                'from_date' => now(),
                'to_date' => null,
                'designation' => $this->position_id,
                'appointment_status' => $this->appointment,
                'salary' => $this->salary_grade,
                'station' => $school->district_id,
                'branch' => $school->id
            ]);

            session()->flash('flash.banner', 'Personnel created successfully');
            session()->flash('flash.bannerStyle', 'success');
        } else {
            $this->personnel->update($data);

            session()->flash('flash.banner', 'Personal Information saved successfully');
            session()->flash('flash.bannerStyle', 'success');
        }

        $this->updateMode = false;
        $this->storeMode = false;
        $this->showMode = true;
        // $this->createInitialServiceRecord();

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

    public function createInitialServiceRecord()
    {
        $this->personnel->serviceRecords()->create([
            'personnel_id' => $this->personnel->id,
            'from_date' => now(),
            'to_date' => null,
            'designation' => $this->personnel->position_id,
            'appointment_status' => $this->personnel->appointment,
            'salary' => $this->personnel->salary_grade,
            'station' => $this->personnel->school->district_id,
            'branch' => $this->personnel->school_id
        ]);
    }


    public function logAction($action, $description)
    {
        Log::create([
            'personnel_id' => $this->personnel->id,
            'action' => $action,
            'description' => $description
        ]);
    }
}

