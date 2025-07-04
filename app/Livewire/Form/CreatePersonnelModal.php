<?php

namespace App\Livewire\Form;

use App\Models\Personnel;
use App\Models\School;
use App\Models\SalaryStep;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LaravelLog;
use Illuminate\Support\Facades\DB;

class CreatePersonnelModal extends Component
{
    public $first_name, $middle_name, $last_name, $name_ext,
        $date_of_birth, $place_of_birth, $civil_status, $sex,
        $citizenship, $blood_type, $height, $weight,
        $personnel_id, $school_id, $position_id, $appointment, $fund_source, $job_status, $category, $employment_start, $employment_end, $salary_grade_id, $step_increment, $salary,
        $email, $tel_no, $mobile_no,
        $tin, $sss_num, $gsis_num, $philhealth_num, $pagibig_num;
    public $showModal;
    public $isAuthUserSchoolHead;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'name_ext' => 'nullable|string|max:255',
        'date_of_birth' => 'required|date',
        'place_of_birth' => 'required|string|max:255',
        'sex' => 'required|in:male,female',
        'civil_status' => 'required|in:single,married,widowed,divorced,seperated,others',
        'citizenship' => 'required|string|max:255',
        'blood_type' => 'nullable|string|max:10',
        'height' => 'required|numeric|min:0',
        'weight' => 'required|numeric|min:0',
        'personnel_id' => 'required|string|unique:personnels,personnel_id',
        'school_id' => 'required|exists:schools,id',
        'position_id' => 'required|exists:position,id',
        'appointment' => 'required|in:regular,part-time,temporary,contract',
        'fund_source' => 'required|string|max:255',
        'salary_grade_id' => 'required|integer|min:1|max:32',
        'step_increment' => 'nullable|in:1,2,3,4,5,6,7,8',
        'category' => 'required|in:SDO Personnel,School Head,Elementary School Teacher,Junior High School Teacher,Senior High School Teacher,School Non-teaching Personnel',
        'job_status' => 'required|in:active,vacation,terminated,on leave,suspended,resigned,probation',
        'employment_start' => 'required|date',
        'employment_end' => 'nullable|date|after:employment_start',
        'email' => 'nullable|email|max:255',
        'tel_no' => 'nullable|string|max:255',
        'mobile_no' => 'nullable|string|max:255',
        'tin' => 'required|string|max:12',
        'sss_num' => 'nullable|string|max:10',
        'gsis_num' => 'nullable|string|max:11',
        'philhealth_num' => 'nullable|string|max:12',
        'pagibig_num' => 'nullable|string|max:12',
    ];

    public $schoolOptions = [];

    public function mount()
    {
        // Set default values
        $this->sex = 'male';
        $this->civil_status = 'single';
        $this->blood_type = 'A+';
        $this->appointment = 'regular';
        $this->job_status = 'active';
        $this->category = 'Elementary School Teacher';
        $this->fund_source = 'nationally funded';
        $this->salary_grade_id = 1;
        $this->step_increment = 1;
        $this->tin = '';
        $this->sss_num = '';
        $this->gsis_num = '';
        $this->philhealth_num = '';
        $this->pagibig_num = '';
        $this->showModal = false;
        $this->isAuthUserSchoolHead = Auth::check() && Auth::user()->role === 'school_head';

        // If the authenticated user is a school_head, auto-populate school_id and get school name
        if ($this->isAuthUserSchoolHead) {
            $personnel = \App\Models\Personnel::with('school')
                ->where('id', Auth::user()->id)
                ->first();

            if ($personnel && $personnel->school_id) {
                $this->school_id = $personnel->school_id;
                $this->schoolOptions = [[
                    'id' => $personnel->school->id,
                    'school_id' => $personnel->school->school_id,
                    'school_name' => $personnel->school->school_name
                ]];
            }
        } else {
            // Get all unique school_ids from personnels table
            $personnelSchoolIds = \App\Models\Personnel::distinct()->pluck('school_id')->toArray();

            // Fetch schools where id is in personnelSchoolIds
            $this->schoolOptions = School::whereIn('id', $personnelSchoolIds)
                ->select('id', 'school_id', 'school_name')
                ->get()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.form.create-personnel-modal');
    }

    /**
     * Calculate salary based on salary grade and step increment
     */
    public function calculateSalary()
    {
        try {
            if ($this->salary_grade_id && $this->step_increment) {
                $salaryStep = SalaryStep::where('salary_grade_id', $this->salary_grade_id)
                    ->where('step', $this->step_increment)
                    ->orderByDesc('year')
                    ->first();

                if ($salaryStep) {
                    $this->salary = $salaryStep->salary;
                    LaravelLog::info('Salary calculated successfully in create modal', [
                        'salary_grade_id' => $this->salary_grade_id,
                        'step_increment' => $this->step_increment,
                        'calculated_salary' => $this->salary,
                        'year' => $salaryStep->year
                    ]);
                } else {
                    $this->salary = null;
                    LaravelLog::warning('No salary step found for the given parameters in create modal', [
                        'salary_grade_id' => $this->salary_grade_id,
                        'step_increment' => $this->step_increment
                    ]);
                }
            } else {
                $this->salary = null;
                LaravelLog::info('Salary calculation skipped - missing required parameters in create modal', [
                    'salary_grade_id' => $this->salary_grade_id,
                    'step_increment' => $this->step_increment
                ]);
            }
        } catch (\Exception $e) {
            $this->salary = null;
            LaravelLog::error('Error calculating salary in create modal', [
                'error' => $e->getMessage(),
                'salary_grade_id' => $this->salary_grade_id,
                'step_increment' => $this->step_increment
            ]);
        }
    }

    /**
     * Updated when salary grade changes
     */
    public function updatedSalaryGradeId()
    {
        $this->calculateSalary();
    }

    /**
     * Updated when step increment changes
     */
    public function updatedStepIncrement()
    {
        $this->calculateSalary();
    }

    public function save()
    {
        // Debug: log all input values
        LaravelLog::debug('CreatePersonnelModal save() called', [
            'all_inputs' => $this->allInputsForDebug()
        ]);

        // Calculate salary before validation
        $this->calculateSalary();

        try {
            $this->validate();
            LaravelLog::info('Validation passed in create modal save()', [
                'validated_data' => $this->allInputsForDebug()
            ]);
        } catch (\Exception $e) {
            LaravelLog::error('Validation failed in create modal save()', [
                'error' => $e->getMessage(),
                'input' => $this->allInputsForDebug(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('flash.banner', 'Validation failed: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            return;
        }

        // Find the school
        try {
            $school = School::findOrFail($this->school_id);
            LaravelLog::info('School found in create modal', ['school_id' => $school->id]);
        } catch (\Exception $e) {
            LaravelLog::error('School not found in create modal', ['school_id' => $this->school_id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            session()->flash('flash.banner', 'School not found: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            return;
        }

        // Prepare data for Personnel (match migration exactly)
        $data = [
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
            'personnel_id' => $this->personnel_id,
            'school_id' => $school->id,
            'position_id' => $this->position_id,
            'appointment' => $this->appointment,
            'fund_source' => $this->fund_source,
            'salary_grade_id' => $this->salary_grade_id,
            'step_increment' => $this->step_increment,
            'category' => $this->category,
            'job_status' => $this->job_status,
            'employment_start' => $this->employment_start,
            'employment_end' => $this->employment_end ?? null,
            'tin' => $this->tin,
            'sss_num' => $this->sss_num,
            'gsis_num' => $this->gsis_num,
            'philhealth_num' => $this->philhealth_num,
            'pagibig_num' => $this->pagibig_num,
            'salary' => $this->salary,
            'salary_changed_at' => now(),
            'loyalty_award_claim_count' => 0,
        ];
        LaravelLog::info('Prepared data for Personnel in create modal', $data);

        try {
            LaravelLog::info('Creating new Personnel from modal');
            $personnel = Personnel::create($data);

            // Create NOSA and NOSI salary change records for new personnel
            $salaryChanges = [];

            // NOSA record
            $salaryChanges[] = [
                'personnel_id' => $personnel->id,
                'type' => 'NOSA',
                'previous_salary_grade' => 0,
                'current_salary_grade' => $this->salary_grade_id,
                'previous_salary_step' => 0,
                'current_salary_step' => $this->step_increment,
                'previous_salary' => 0,
                'current_salary' => $this->salary,
                'actual_monthly_salary_as_of_date' => now(),
                'adjusted_monthly_salary_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // NOSI record
            $salaryChanges[] = [
                'personnel_id' => $personnel->id,
                'type' => 'NOSI',
                'previous_salary_grade' => 0,
                'current_salary_grade' => $this->salary_grade_id,
                'previous_salary_step' => 0,
                'current_salary_step' => $this->step_increment,
                'previous_salary' => 0,
                'current_salary' => $this->salary,
                'actual_monthly_salary_as_of_date' => now(),
                'adjusted_monthly_salary_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Create both salary change records
            DB::table('salary_changes')->insert($salaryChanges);

            LaravelLog::info('Salary change records created', [
                'salary_changes' => $salaryChanges
            ]);

            session()->flash('flash.banner', 'Personnel created successfully');
            session()->flash('flash.bannerStyle', 'success');

            LaravelLog::info('Personnel created successfully from modal', ['personnel_id' => $personnel->id]);

            // Reset form
            $this->reset();
            $this->mount();

            // Close modal
            $this->dispatch('close-modal', 'create-personnel-modal');
        } catch (\Exception $e) {
            LaravelLog::error('Error creating personnel from modal', [
                'error' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('flash.banner', 'Failed to create personnel: ' . $e->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
        }

        return redirect()->route('personnels.index');
    }

    /**
     * Gather all input values for debugging
     */
    private function allInputsForDebug()
    {
        return [
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
            'personnel_id' => $this->personnel_id,
            'school_id' => $this->school_id,
            'position_id' => $this->position_id,
            'appointment' => $this->appointment,
            'fund_source' => $this->fund_source,
            'salary_grade_id' => $this->salary_grade_id,
            'step_increment' => $this->step_increment,
            'category' => $this->category,
            'job_status' => $this->job_status,
            'employment_start' => $this->employment_start,
            'employment_end' => $this->employment_end,
            'tin' => $this->tin,
            'sss_num' => $this->sss_num,
            'gsis_num' => $this->gsis_num,
            'philhealth_num' => $this->philhealth_num,
            'pagibig_num' => $this->pagibig_num,
            'salary' => $this->salary,
        ];
    }

    /**
     * Create service record for the personnel
     */
    // private function createServiceRecord($school, $personnel)
    // {
    //     try {
    //         $personnel->serviceRecords()->create([
    //             'personnel_id' => $personnel->id,
    //             'from_date' => $personnel->employment_start,
    //             'to_date' => $personnel->employment_end,
    //             'designation' => $personnel->position_id,
    //             'appointment_status' => $personnel->appointment,
    //             'salary' => $personnel->salary,
    //             'station' => $school->district_id,
    //             'branch' => $school->id,
    //             'lv_wo_pay' => null,
    //             'separation_date_cause' => null,
    //         ]);

    //         LaravelLog::info('Service record created for personnel', [
    //             'personnel_id' => $personnel->id,
    //             'service_record_id' => $personnel->serviceRecords()->latest()->first()->id
    //         ]);
    //     } catch (\Exception $e) {
    //         LaravelLog::error('Error creating service record', [
    //             'error' => $e->getMessage(),
    //             'personnel_id' => $personnel->id
    //         ]);
    //     }
    // }

    public function cancel()
    {
        $this->reset();
        $this->mount();
        $this->showModal = false;
        $this->dispatch('close-modal', 'create-personnel-modal');
    }
}
