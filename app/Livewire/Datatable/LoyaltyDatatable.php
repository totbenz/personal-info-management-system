<?php

namespace App\Livewire\Datatable;

use App\Models\Personnel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class LoyaltyDatatable extends Component
{
    use WithPagination;

    public $selectedSchool = null, $selectedCategory = null, $selectedClassification = null, $selectedPosition = null, $selectedJobStatus = null;
    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';


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
        $currentYear = Carbon::now()->year;
        $personnels = Personnel::with('school')
                    ->whereNotNull('employment_start')
                    ->whereRaw("TIMESTAMPDIFF(YEAR, employment_start, CURDATE()) % 10 = 0")
                    ->whereRaw("YEAR(employment_start) <= $currentYear")
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

        return view('livewire.datatable.loyalty-datatable', [
            'personnels' => $personnels
        ]);
    }
}
