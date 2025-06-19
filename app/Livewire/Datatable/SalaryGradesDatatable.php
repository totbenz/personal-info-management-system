<?php

namespace App\Livewire\Datatable;

use App\Models\SalaryGrade;
use Livewire\Component;
use Livewire\WithPagination;

class SalaryGradesDatatable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';

    public function doSort($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }

    public function render()
    {
        $salaryGrades = SalaryGrade::query()
            ->when($this->search, function ($query) {
                $query->where('grade', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.datatable.salary-grades-datatable', [
            'salaryGrades' => $salaryGrades
        ]);
    }
}
