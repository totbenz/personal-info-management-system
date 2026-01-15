<?php

namespace App\Livewire\Personnel;

use App\Models\Personnel;
use App\Models\School;
use Livewire\Component;
use Maatwebsite\Excel\Excel;
use Livewire\WithPagination;
use App\Exports\PersonnelsIndexExport;

class PersonnelTable extends Component
{
    use WithPagination;

    public $schoolId = null;
    public $selectedSchool = null;
    public $selectedCategory = null;
    public $selectedPosition = null;
    public $selectedJobStatus = null;
    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';

    protected $paginationTheme = 'tailwind';

    public function mount($schoolId = null)
    {
        if ($schoolId !== null) {
            $this->schoolId = $schoolId;
            $this->selectedSchool = $schoolId;
        } elseif (auth()->user()->role === 'school_head') {
            $this->selectedSchool = auth()->user()->personnel->school_id;
            $this->schoolId = auth()->user()->personnel->school_id;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedSchool()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingSelectedPosition()
    {
        $this->resetPage();
    }

    public function updatingSelectedJobStatus()
    {
        $this->resetPage();
    }

    public function doSort($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'ASC';
        }
    }

    public function resetFilters()
    {
        $this->reset(['selectedSchool', 'selectedCategory', 'selectedPosition', 'selectedJobStatus', 'search']);
        $this->sortColumn = 'id';
        $this->sortDirection = 'ASC';
        $this->resetPage();
    }

    public function export()
    {
        $excel = app(Excel::class);
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

        $schoolId = $this->schoolId ?? $this->selectedSchool;
        $filename = $schoolId ? "school_id_{$schoolId}_personnels.xlsx" : "personnels.xlsx";

        return $excel->download(new PersonnelsIndexExport($filters), $filename);
    }

    public function viewPersonnel($personnelId)
    {
        if (auth()->user()->role === 'school_head') {
            return redirect()->route('school_personnels.show', ['personnel' => $personnelId]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $personnelId]);
        }
    }

    public function getPersonnelProperty()
    {
        $query = Personnel::with(['school', 'position']);

        // Apply school filter
        if ($this->schoolId) {
            $query->where('school_id', $this->schoolId);
        } elseif ($this->selectedSchool) {
            $query->where('school_id', $this->selectedSchool);
        } elseif (auth()->user()->role === 'school_head') {
            $query->where('school_id', auth()->user()->personnel->school_id);
        }

        // Apply filters
        if ($this->selectedCategory) {
            $query->where('category', $this->selectedCategory);
        }

        if ($this->selectedPosition) {
            $query->where('position_id', $this->selectedPosition);
        }

        if ($this->selectedJobStatus) {
            $query->where('job_status', $this->selectedJobStatus);
        }

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('personnel_id', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        // Apply sorting
        if ($this->sortColumn === 'position_title') {
            $query->join('positions', 'personnels.position_id', '=', 'positions.id')
                  ->orderBy('positions.title', $this->sortDirection)
                  ->select('personnels.*');
        } elseif ($this->sortColumn === 'school_name') {
            $query->join('schools', 'personnels.school_id', '=', 'schools.id')
                  ->orderBy('schools.school_name', $this->sortDirection)
                  ->select('personnels.*');
        } else {
            $query->orderBy($this->sortColumn, $this->sortDirection);
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.personnel.personnel-table', [
            'personnels' => $this->personnel
        ]);
    }
}
