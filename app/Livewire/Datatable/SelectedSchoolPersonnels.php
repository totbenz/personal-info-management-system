<?php

namespace App\Livewire\Datatable;

use App\Models\Personnel;
use App\Models\School;
use Livewire\Component;
use Maatwebsite\Excel\Excel;
use Livewire\WithPagination;
use App\Exports\PersonnelsIndexExport;

class SelectedSchoolPersonnels extends Component
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
        // Pass all current filters to the export class
        $filters = [
            'schoolId' => $this->schoolId,
            'selectedSchool' => $this->selectedSchool,
            'selectedCategory' => $this->selectedCategory,
            'selectedPosition' => $this->selectedPosition,
            'selectedJobStatus' => $this->selectedJobStatus,
            'search' => $this->search,
            'sortColumn' => $this->sortColumn,
            'sortDirection' => $this->sortDirection,
        ];

        // Determine school ID for filename
        $schoolId = $this->schoolId ?? $this->selectedSchool;
        $filename = $schoolId ? "school_id_{$schoolId}_personnels.xlsx" : "personnels.xlsx";

        return $excel->download(new PersonnelsIndexExport($filters), $filename);
    }

    public function viewPersonnel($personnelId)
    {
        // Navigate to the appropriate personnel view based on user role
        if (auth()->user()->role === 'school_head') {
            return redirect()->route('school_personnels.show', ['personnel' => $personnelId]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $personnelId]);
        }
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

    public function render()
    {
        $query = $this->filterPersonnelsBySchool($this->schoolId);

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

        return view('livewire.datatable.selected-school-personnels', [
            'personnels' => $personnels
        ]);
    }
}
