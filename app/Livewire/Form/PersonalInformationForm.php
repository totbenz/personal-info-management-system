<?php

namespace App\Livewire\Form;

use App\Models\Log;
use App\Models\Personnel;
use App\Models\School;
use App\Models\SalaryStep;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\PersonnelNavigation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log as LaravelLog;
use Illuminate\Support\Facades\DB;

class PersonalInformationForm extends PersonnelNavigation
{
    public $personnel;
    public $first_name, $middle_name, $last_name, $name_ext,
        $date_of_birth, $place_of_birth, $civil_status, $sex,
        $citizenship, $blood_type, $height, $weight,
        $tin, $sss_num, $gsis_num, $philhealth_num,
        $pagibig_num, $salary,
        $personnel_id, $school_id, $position_id, $appointment, $fund_source, $job_status, $category, $employment_start, $employment_end, $salary_grade_id, $step_increment, $classification, $position, $leave_of_absence_without_pay_count,
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
        'salary' => 'nullable',
        'personnel_id' => 'required',
        'school_id' => 'required',
        'position_id' => 'required',
        'appointment' => 'required',
        'fund_source' => 'required',
        'salary_grade_id' => 'required',
        'step_increment' => 'required',
        'category' => 'required',
        'job_status' => 'required',
        'employment_start' => 'required',
        'employment_end' => 'required',
        'leave_of_absence_without_pay_count' => 'nullable',

        'tin' => 'required|min:8|max:12',
        'sss_num' => 'nullable|size:10',
        'gsis_num' => 'nullable|min:8',
        'philhealth_num' => 'nullable|min:11',
        'pagibig_num' => 'nullable|min:11',

        'email' => 'required',
        'tel_no' => 'nullable',
        'mobile_no' => 'required',
    ];

    public function mount($id = null)
    {
        if ($id) {
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
                // changes in the database table
                $this->salary_grade_id = $this->personnel->salary_grade_id;
                $this->step_increment = $this->personnel->step_increment;
                $this->category = $this->personnel->category;
                $this->job_status = $this->personnel->job_status;
                $this->employment_start = $this->personnel->employment_start;
                $this->leave_of_absence_without_pay_count = $this->personnel->leave_of_absence_without_pay_count;
                if ($this->personnel->employment_end) {
                    $this->employment_end = $this->personnel->employment_end;
                }
                $this->email = $this->personnel->email;
                $this->tel_no = $this->personnel->tel_no;
                $this->mobile_no = $this->personnel->mobile_no;

                // Calculate salary based on current salary_grade_id and step_increment
                $this->calculateSalary();
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

        if (Auth::user()->role === "teacher") {
            return redirect()->route('personnels.profile');
        } elseif (Auth::user()->role === "schoool_head") {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    /**
     * Calculate salary based on salary grade and step increment
     */
    public function calculateSalary()
    {
        try {
            if ($this->salary_grade_id && $this->step_increment) {
                $salaryStep = \App\Models\SalaryStep::where('salary_grade_id', $this->salary_grade_id)
                    ->where('step', $this->step_increment)
                    ->orderByDesc('year')
                    ->first();

                if ($salaryStep) {
                    $this->salary = $salaryStep->salary;
                    LaravelLog::info('Salary calculated successfully', [
                        'salary_grade_id' => $this->salary_grade_id,
                        'step_increment' => $this->step_increment,
                        'calculated_salary' => $this->salary,
                        'year' => $salaryStep->year
                    ]);
                } else {
                    $this->salary = null;
                    LaravelLog::warning('No salary step found for the given parameters', [
                        'salary_grade_id' => $this->salary_grade_id,
                        'step_increment' => $this->step_increment
                    ]);
                }
            } else {
                $this->salary = null;
                LaravelLog::info('Salary calculation skipped - missing required parameters', [
                    'salary_grade_id' => $this->salary_grade_id,
                    'step_increment' => $this->step_increment
                ]);
            }
        } catch (\Exception $e) {
            $this->salary = null;
            LaravelLog::error('Error calculating salary', [
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
        LaravelLog::info('Save method called in PersonalInformationForm', [
            'user_id' => Auth::id(),
            'personnel_id' => $this->personnel ? $this->personnel->id : null,
            'input_salary_grade_id' => $this->salary_grade_id,
            'input_step_increment' => $this->step_increment,
            'input_salary' => $this->salary,
        ]);

        // Calculate salary before validation
        $this->calculateSalary();

        try {
            $this->validate();
            LaravelLog::info('Validation passed in save()', [
                'validated_data' => [
                    'salary_grade_id' => $this->salary_grade_id,
                    'step_increment' => $this->step_increment,
                    'salary' => $this->salary,
                ]
            ]);
        } catch (\Exception $e) {
            LaravelLog::error('Validation failed in save()', [
                'error' => $e->getMessage(),
                'input' => [
                    'salary_grade_id' => $this->salary_grade_id,
                    'step_increment' => $this->step_increment,
                    'salary' => $this->salary,
                ]
            ]);
            throw $e;
        }

        // Find the school
        try {
            $school = School::findOrFail($this->school_id);
            LaravelLog::info('School found', ['school_id' => $school->id]);
        } catch (\Exception $e) {
            LaravelLog::error('School not found', ['school_id' => $this->school_id, 'error' => $e->getMessage()]);
            throw $e;
        }

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
            'salary' => $this->salary, // Use the calculated salary
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
            'salary_grade_id' => $this->salary_grade_id,
            'step_increment' => $this->step_increment,
            'category' => $this->category,
            'job_status' => $this->job_status,
            'employment_start' => $this->employment_start,
            'employment_end' => $this->employment_end ?? null,
            'email' => $this->email,
            'tel_no' => $this->tel_no,
            'mobile_no' => $this->mobile_no,
            'leave_of_absence_without_pay_count' => $this->leave_of_absence_without_pay_count,
        ];
        LaravelLog::info('Prepared data for Personnel', $data);

        if ($this->personnel == null) {
            LaravelLog::info('Creating new Personnel');
            $this->personnel = Personnel::create($data);
            $this->createServiceRecord($school, $this->personnel);
            session()->flash('flash.banner', 'Personnel created successfully');
            session()->flash('flash.bannerStyle', 'success');
        } else {
            LaravelLog::info('Updating existing Personnel', ['personnel_id' => $this->personnel->id]);
            $this->createServiceRecord($school, $this->personnel);
            $original_grade = $this->personnel->salary_grade_id;
            $original_step = $this->personnel->step_increment;
            $original_salary = $this->personnel->salary;

            // Update the Personnel first
            $this->personnel->update($data);

            // Now compare the original and new values
            $new_grade = $this->personnel->salary_grade_id;
            $new_step = $this->personnel->step_increment;
            $new_salary = $this->personnel->salary;

            // Fetch previous and current salary from salary_steps table for all possible transitions
            $previousSalaryStep = null;
            $currentSalaryStep = null;
            $nosaSalaryStep = null;
            $nosiSalaryStep = null;
            try {
                $previousSalaryStep = DB::table('salary_steps')
                    ->where('salary_grade_id', $original_grade)
                    ->where('step', $original_step)
                    ->orderByDesc('year')
                    ->first();
                $currentSalaryStep = DB::table('salary_steps')
                    ->where('salary_grade_id', $new_grade)
                    ->where('step', $new_step)
                    ->orderByDesc('year')
                    ->first();
                $nosaSalaryStep = DB::table('salary_steps')
                    ->where('salary_grade_id', $new_grade)
                    ->where('step', $original_step)
                    ->orderByDesc('year')
                    ->first();
                $nosiSalaryStep = DB::table('salary_steps')
                    ->where('salary_grade_id', $new_grade)
                    ->where('step', $new_step)
                    ->orderByDesc('year')
                    ->first();
            } catch (\Exception $e) {
                LaravelLog::error('Error fetching salary from salary_steps', ['error' => $e->getMessage()]);
            }
            $previous_salary = $previousSalaryStep ? $previousSalaryStep->salary : $original_salary;
            $current_salary = $currentSalaryStep ? $currentSalaryStep->salary : $new_salary;
            $nosa_salary = $nosaSalaryStep ? $nosaSalaryStep->salary : $new_salary;
            $nosi_salary = $nosiSalaryStep ? $nosiSalaryStep->salary : $new_salary;

            $grade_changed = $original_grade != $new_grade;
            $step_changed = $original_step != $new_step;
            $salary_changed = $original_salary != $new_salary;

            if ($grade_changed && $step_changed) {
                // Log NOSA (grade change, step stays original)
                LaravelLog::info('Salary change detected, logging NOSA to salary_changes', [
                    'personnel_id' => $this->personnel->id,
                    'previous_salary_grade' => $original_grade,
                    'current_salary_grade' => $new_grade,
                    'previous_salary_step' => $original_step,
                    'current_salary_step' => $original_step,
                    'previous_salary' => $previous_salary,
                    'current_salary' => $nosa_salary,
                    'type' => 'NOSA',
                ]);
                \App\Models\SalaryChange::create([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'NOSA',
                    'previous_salary_grade' => $original_grade,
                    'current_salary_grade' => $new_grade,
                    'previous_salary_step' => $original_step,
                    'current_salary_step' => $original_step,
                    'previous_salary' => $previous_salary,
                    'current_salary' => $nosa_salary,
                    'actual_monthly_salary_as_of_date' => now(),
                    'adjusted_monthly_salary_date' => now(),
                ]);
                // Log NOSI (step change, grade is new)
                LaravelLog::info('Salary change detected, logging NOSI to salary_changes', [
                    'personnel_id' => $this->personnel->id,
                    'previous_salary_grade' => $new_grade,
                    'current_salary_grade' => $new_grade,
                    'previous_salary_step' => $original_step,
                    'current_salary_step' => $new_step,
                    'previous_salary' => $nosa_salary,
                    'current_salary' => $current_salary,
                    'type' => 'NOSI',
                ]);
                \App\Models\SalaryChange::create([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'NOSI',
                    'previous_salary_grade' => $new_grade,
                    'current_salary_grade' => $new_grade,
                    'previous_salary_step' => $original_step,
                    'current_salary_step' => $new_step,
                    'previous_salary' => $nosa_salary,
                    'current_salary' => $current_salary,
                    'actual_monthly_salary_as_of_date' => now(),
                    'adjusted_monthly_salary_date' => now(),
                ]);
            } elseif ($grade_changed) {
                // Only grade changed
                LaravelLog::info('Salary change detected, logging NOSA to salary_changes', [
                    'personnel_id' => $this->personnel->id,
                    'previous_salary_grade' => $original_grade,
                    'current_salary_grade' => $new_grade,
                    'previous_salary_step' => $original_step,
                    'current_salary_step' => $original_step,
                    'previous_salary' => $previous_salary,
                    'current_salary' => $nosa_salary,
                    'type' => 'NOSA',
                ]);
                \App\Models\SalaryChange::create([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'NOSA',
                    'previous_salary_grade' => $original_grade,
                    'current_salary_grade' => $new_grade,
                    'previous_salary_step' => $original_step,
                    'current_salary_step' => $original_step,
                    'previous_salary' => $previous_salary,
                    'current_salary' => $nosa_salary,
                    'actual_monthly_salary_as_of_date' => now(),
                    'adjusted_monthly_salary_date' => now(),
                ]);
            } elseif ($step_changed) {
                // Only step changed
                LaravelLog::info('Salary change detected, logging NOSI to salary_changes', [
                    'personnel_id' => $this->personnel->id,
                    'previous_salary_grade' => $new_grade,
                    'current_salary_grade' => $new_grade,
                    'previous_salary_step' => $original_step,
                    'current_salary_step' => $new_step,
                    'previous_salary' => $previous_salary,
                    'current_salary' => $current_salary,
                    'type' => 'NOSI',
                ]);
                \App\Models\SalaryChange::create([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'NOSI',
                    'previous_salary_grade' => $new_grade,
                    'current_salary_grade' => $new_grade,
                    'previous_salary_step' => $original_step,
                    'current_salary_step' => $new_step,
                    'previous_salary' => $previous_salary,
                    'current_salary' => $current_salary,
                    'actual_monthly_salary_as_of_date' => now(),
                    'adjusted_monthly_salary_date' => now(),
                ]);
            }

            session()->flash('flash.banner', 'Personal Information saved successfully');
            session()->flash('flash.bannerStyle', 'success');
        }

        $this->updateMode = false;
        $this->storeMode = false;
        $this->showMode = true;

        LaravelLog::info('Save method completed', [
            'personnel_id' => $this->personnel ? $this->personnel->id : null,
            'redirect_role' => Auth::user()->role
        ]);

        if (Auth::user()->role === "teacher") {
            return redirect()->route('personnels.profile');
        } elseif (Auth::user()->role === "schoool_head") {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    public function generateServiceRecordPDF()
    {
        // Redirect to the route for PDF download
        return redirect()->route('service-record.download', ['personnelId' => $this->personnel->id]);
    }

    public function createInitialServiceRecord()
    {
        $this->personnel->serviceRecords()->create([
            'personnel_id' => $this->personnel->id,
            'from_date' => now(),
            'to_date' => null,
            'position_id' => $this->personnel->position_id,
            'appointment_status' => $this->personnel->appointment,
            'salary' => $this->personnel->salary_grade,
            'station' => $this->personnel->school->district_id,
            'branch' => $this->personnel->school_id
        ]);
    }

    public function createServiceRecord($school, $personnel)
    {
        $personnel->serviceRecords()->create([
            'personnel_id' => $personnel->id,
            'from_date' => $personnel->employment_start,
            'to_date' => now(), // Save the current date as the end date for the previous record
            'position_id' => $personnel->position_id,
            'appointment_status' => $personnel->appointment,
            'salary' => $personnel->salary,
            'salary_grade' => $personnel->salary_grade,
            'station' => $school->district_id,
            'branch' => $school->id
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
