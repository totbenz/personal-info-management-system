<?php

namespace App\Livewire\Datatable;

use App\Models\Personnel;
use Livewire\Component;
use Livewire\WithPagination;

class PersonnelDatatable extends Component
{
    use WithPagination;

    public $schoolId;
    public $selectedCategory = null, $selectedClassification = null, $selectedPosition = null, $selectedJobStatus = null;
    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';
    // public $school


    public function doSort($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }

    public function render()
    {
        $personnels = Personnel::with('school')
                    ->where('school_id', $this->schoolId)
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

        return view('livewire.datatable.personnel-datatable', [
            'personnels' => $personnels
        ]);
    }
}
