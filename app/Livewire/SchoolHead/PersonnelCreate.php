<?php

namespace App\Livewire\SchoolHead;

use App\Models\Personnel;
use App\Models\Position;
use App\Models\SalaryGrade;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PersonnelCreate extends Component
{
    use WithFileUploads;

    // Personal Information
    public $first_name;
    public $middle_name;
    public $last_name;
    public $name_ext;
    public $sex;
    public $civil_status;
    public $citizenship;
    public $blood_type;
    public $height;
    public $weight;
    public $date_of_birth;
    public $place_of_birth;
    public $email;
    public $tel_no;
    public $mobile_no;

    // Work Information
    public $personnel_id;
    public $school_id; // Will be pre-populated with school head's school
    public $position_id;
    public $appointment;
    public $fund_source;
    public $salary_grade_id;
    public $step_increment;
    public $category;
    public $job_status;
    public $employment_start;
    public $employment_end;
    public $pantilla_of_personnel;
    public $is_solo_parent = false;

    // Government Information
    public $tin;
    public $sss_num;
    public $gsis_num;
    public $philhealth_num;
    public $pagibig_num;

    // UI State
    public $activeTab = 'personal';
    public $positions = [];
    public $salaryGrades = [];
    public $steps = [1, 2, 3, 4, 5, 6, 7, 8];

    protected $rules = [
        // Personal Information
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'name_ext' => 'nullable|string|max:10',
        'sex' => 'required|in:male,female',
        'civil_status' => 'required|in:single,married,widowed,divorced,seperated,others',
        'citizenship' => 'required|string|max:255',
        'blood_type' => 'nullable|string|max:5',
        'height' => 'nullable|string|max:10',
        'weight' => 'nullable|string|max:10',
        'date_of_birth' => 'required|date|before:today',
        'place_of_birth' => 'required|string|max:255',
        'email' => 'nullable|email|max:255|unique:personnels,email',
        'tel_no' => 'nullable|string|max:20',
        'mobile_no' => 'nullable|string|max:20',

        // Work Information
        'personnel_id' => 'required|string|max:50|unique:personnels,personnel_id',
        'school_id' => 'required|exists:schools,id', // Changed to required for school head
        'position_id' => 'required|exists:position,id',
        'appointment' => 'required|in:regular,part-time,temporary,contract',
        'fund_source' => 'required|string|max:255',
        'salary_grade_id' => 'required|exists:salary_grades,id',
        'step_increment' => 'required|in:1,2,3,4,5,6,7,8',
        'category' => 'required|in:SDO Personnel,School Head,Elementary School Teacher,Junior High School Teacher,Senior High School Teacher,School Non-teaching Personnel',
        'job_status' => 'required|string|max:255',
        'employment_start' => 'required|date',
        'employment_end' => 'nullable|date|after:employment_start',
        'pantilla_of_personnel' => 'nullable|string|max:255',
        'is_solo_parent' => 'boolean',

        // Government Information - matching database constraints
        'tin' => 'required|string|size:12', // exactly 12 characters
        'sss_num' => 'nullable|string|size:10', // exactly 10 characters
        'gsis_num' => 'nullable|string|size:11', // exactly 11 characters
        'philhealth_num' => 'nullable|string|size:12', // exactly 12 characters
        'pagibig_num' => 'nullable|string|size:12', // exactly 12 characters
    ];

    protected $messages = [
        'personnel_id.unique' => 'This Employee ID already exists.',
        'email.unique' => 'This email is already registered.',
        'date_of_birth.before' => 'Date of birth must be before today.',
        'employment_end.after' => 'Employment end date must be after start date.',
        'tin.size' => 'TIN must be exactly 12 characters.',
        'sss_num.size' => 'SSS number must be exactly 10 characters.',
        'gsis_num.size' => 'GSIS number must be exactly 11 characters.',
        'philhealth_num.size' => 'PhilHealth number must be exactly 12 characters.',
        'pagibig_num.size' => 'Pag-IBIG number must be exactly 12 characters.',
        'name_ext.max' => 'Name extension must not exceed 10 characters (e.g., Jr., Sr., III).',
        'personnel_id.required' => 'Employee ID is required.',
        'first_name.required' => 'First name is required.',
        'last_name.required' => 'Last name is required.',
        'sex.required' => 'Sex is required.',
        'civil_status.required' => 'Civil status is required.',
        'citizenship.required' => 'Citizenship is required.',
        'date_of_birth.required' => 'Date of birth is required.',
        'place_of_birth.required' => 'Place of birth is required.',
        'position_id.required' => 'Position is required.',
        'appointment.required' => 'Appointment type is required.',
        'fund_source.required' => 'Fund source is required.',
        'salary_grade_id.required' => 'Salary grade is required.',
        'step_increment.required' => 'Step increment is required.',
        'category.required' => 'Category is required.',
        'job_status.required' => 'Job status is required.',
        'employment_start.required' => 'Employment start date is required.',
        'tin.required' => 'TIN is required.',
        'school_id.required' => 'School is required.',
    ];

    public function mount()
    {
        // Pre-populate school_id with the school head's school
        $this->school_id = Auth::user()->school->id ?? null;
        $this->loadDropdownData();
    }

    public function loadDropdownData()
    {
        $this->positions = Position::orderBy('title')->get();
        $this->salaryGrades = SalaryGrade::orderBy('grade')->get();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function nextTab()
    {
        $tabs = ['personal', 'work', 'government'];
        $currentIndex = array_search($this->activeTab, $tabs);
        if ($currentIndex < count($tabs) - 1) {
            $this->activeTab = $tabs[$currentIndex + 1];
        }
    }

    public function previousTab()
    {
        $tabs = ['personal', 'work', 'government'];
        $currentIndex = array_search($this->activeTab, $tabs);
        if ($currentIndex > 0) {
            $this->activeTab = $tabs[$currentIndex - 1];
        }
    }

    public function save()
    {
        try {
            // Validate all fields
            $validatedData = $this->validate();

            // Check if we're on the last tab before saving
            if ($this->activeTab !== 'government') {
                $this->dispatch('showError', 'Please complete all sections before saving.');
                return;
            }

            DB::beginTransaction();

            // Create personnel
            $personnel = Personnel::create([
                // Personal Information
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'name_ext' => $this->name_ext,
                'sex' => $this->sex,
                'civil_status' => $this->civil_status,
                'citizenship' => $this->citizenship,
                'blood_type' => $this->blood_type,
                'height' => $this->height,
                'weight' => $this->weight,
                'date_of_birth' => $this->date_of_birth,
                'place_of_birth' => $this->place_of_birth,
                'email' => $this->email,
                'tel_no' => $this->tel_no,
                'mobile_no' => $this->mobile_no,

                // Work Information
                'personnel_id' => $this->personnel_id,
                'school_id' => $this->school_id, // Will always be set for school head
                'position_id' => $this->position_id,
                'appointment' => $this->appointment,
                'fund_source' => $this->fund_source,
                'salary_grade_id' => $this->salary_grade_id,
                'step_increment' => $this->step_increment,
                'category' => $this->category,
                'job_status' => $this->job_status,
                'employment_start' => $this->employment_start,
                'employment_end' => $this->employment_end,
                'pantilla_of_personnel' => $this->pantilla_of_personnel,
                'is_solo_parent' => $this->is_solo_parent,

                // Government Information
                'tin' => $this->tin,
                'sss_num' => $this->sss_num,
                'gsis_num' => $this->gsis_num,
                'philhealth_num' => $this->philhealth_num,
                'pagibig_num' => $this->pagibig_num,
            ]);

            // Create initial service record
            $personnel->createInitialServiceRecord();

            DB::commit();

            $this->dispatch('showSuccess', 'Personnel created successfully!');

            // Redirect to school personnel list
            $this->dispatch('redirectToList', '/personnels');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Collect all validation errors
            $errors = [];
            foreach ($e->validator->errors()->all() as $error) {
                $errors[] = $error;
            }

            // Show all errors in SweetAlert
            $this->dispatch('showValidationErrors', $errors);

            // Switch to the first tab that has errors
            $this->switchToTabWithErrors($e->validator->errors());

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Personnel creation failed: ' . $e->getMessage());
            $this->dispatch('showError', 'Failed to create personnel: ' . $e->getMessage());
        }
    }

    private function switchToTabWithErrors($errors)
    {
        // Check which tab has errors and switch to it
        $personalFields = ['first_name', 'last_name', 'middle_name', 'name_ext', 'sex', 'civil_status',
                          'citizenship', 'blood_type', 'height', 'weight', 'date_of_birth',
                          'place_of_birth', 'email', 'tel_no', 'mobile_no'];

        $workFields = ['personnel_id', 'school_id', 'position_id', 'appointment', 'fund_source',
                      'salary_grade_id', 'step_increment', 'category', 'job_status',
                      'employment_start', 'employment_end', 'pantilla_of_personnel'];

        foreach ($errors->keys() as $field) {
            if (in_array($field, $personalFields)) {
                $this->activeTab = 'personal';
                return;
            }

            if (in_array($field, $workFields)) {
                $this->activeTab = 'work';
                return;
            }
        }

        // Default to government tab if no specific field matches
        $this->activeTab = 'government';
    }

    public function render()
    {
        return view('livewire.school-head.personnel-create')
            ->extends('layouts.app')
            ->section('content');
    }
}
