<?php

namespace App\Livewire\Datatable;

use App\Models\Personnel;
use App\Models\School;
use Livewire\Component;
use Maatwebsite\Excel\Excel;
use Livewire\WithPagination;
use App\Exports\PersonnelsIndexExport;

class PersonnelsDatatable extends Component
{
    use WithPagination;
    public $schoolId = null;
    public $selectedSchool = null, $selectedCategory = null, $selectedClassification = null, $selectedPosition = null, $selectedJobStatus = null;
    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';

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
        'salary_grade_id' => 'required',
        'step_increment' => 'required',
        'category' => 'required',
        'job_status' => 'required',
        'employment_start' => 'required',

        // 'tin' => 'required|min:8|max:12',
        // 'sss_num' => 'required|min:10',
        // 'gsis_num' => 'required|min:8',
        // 'philhealth_num' => 'required|min:12',
        // 'pagibig_num' => 'required|min:12',

        'email' => 'required',
        'tel_no' => 'nullable',
        'mobile_no' => 'required',
    ];

    public function mount($schoolId = null)
    {
        // Set the school ID from parameter if provided
        if ($schoolId !== null) {
            $this->schoolId = $schoolId;
            $this->selectedSchool = $schoolId;
        } elseif (auth()->user()->role === 'school_head') {
            $this->selectedSchool = auth()->user()->personnel->school_id;
            $this->schoolId = auth()->user()->personnel->school_id;
        }
    }

    public function doSort($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }

    public function export()
    {
        $excel = app(Excel::class);
        return $excel->download(new PersonnelsIndexExport, 'personnel.xlsx');
    }

    /**
     * Filter personnel by school ID
     * 
     * @param int|null $schoolId The school ID to filter by. If null, uses component's schoolId or current user's school
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterPersonnelsBySchool($schoolId = null)
    {
        $query = Personnel::with(['school', 'position']);
        
        // Priority order: parameter schoolId > component schoolId > user role-based schoolId
        if ($schoolId !== null) {
            $targetSchoolId = $schoolId;
        } elseif ($this->schoolId !== null) {
            $targetSchoolId = $this->schoolId;
        } elseif (auth()->user()->role === 'school_head') {
            $targetSchoolId = auth()->user()->personnel->school_id;
        } elseif ($this->selectedSchool) {
            $targetSchoolId = $this->selectedSchool;
        } else {
            $targetSchoolId = null;
        }
        
        // Apply school filter if we have a school ID
        if ($targetSchoolId) {
            $query->where('school_id', $targetSchoolId);
        }
        
        return $query;
    }

    /**
     * Get personnel filtered by school ID and return a Livewire view
     * 
     * @param int|null $schoolId The school ID to filter by. If null, uses current user's school
     * @return \Illuminate\View\View
     */
    public function getPersonnelsBySchoolView($schoolId = null)
    {
        $query = $this->filterPersonnelsBySchool($schoolId);

        $personnels = $query
            ->when($this->selectedCategory, function ($query) {
                $query->where('category', $this->selectedCategory);
            })
            ->when($this->selectedPosition, function ($query) {
                $query->where('position_id', $this->selectedPosition);
            })
            ->when($this->selectedJobStatus, function ($query) {
                $query->where('job_status', $this->selectedJobStatus);
            })
            ->search($this->search)
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.datatable.personnels-datatable', [
            'personnels' => $personnels
        ]);
    }

    public function render()
    {
        // Use the component's schoolId to filter personnel
        return $this->getPersonnelsBySchoolView($this->schoolId);
    }

    // public function save()
    // {
    //     // $this->validate();

    //     // Find the school
    //     $school = School::findOrFail($this->school_id);

    //     // Prepare data for Personnel
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

    //     $attributes = ['position_id', 'salary_grade', 'appointment', 'school_id', 'district_id', 'job_status'];
    //         $isDirty = false;
    //         foreach ($attributes as $attribute) {
    //             if ($this->personnel->isDirty($attribute)) {
    //                 $isDirty = true;
    //                 break;
    //             }
    //         }

    //         // Update the Personnel
    //         $this->personnel->update($data);

    //         if ($isDirty) {
    //             // Get the current date
    //             $currentDate = now();

    //             // Find the current active service record
    //             $currentServiceRecord = $this->personnel->serviceRecords()->whereNull('to_date')->first();

    //             if ($currentServiceRecord) {
    //                 // Update the end date of the current service record
    //                 $currentServiceRecord->update(['to_date' => $currentDate]);
    //             }

    //             // Create a new service record
    //             $this->personnel->serviceRecords()->create([
    //                 'personnel_id' => $this->personnel->id,
    //                 'from_date' => $currentDate,
    //                 'to_date' => null,
    //                 'designation' => $this->position_id,
    //                 'appointment_status' => $this->appointment,
    //                 'salary' => $this->salary_grade,
    //                 'station' => $school->district_id,
    //                 'branch' => $school->id
    //             ]);
    //         }

    //         session()->flash('flash.banner', 'Personal Information saved successfully');
    //         session()->flash('flash.bannerStyle', 'success');

    //         return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
    // }
}
