<?php

namespace App\Livewire\Datatable;

use App\Models\School;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use App\Exports\SchoolsIndexExport;

class SchoolsDatatable extends Component
{
    use WithPagination;
    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';

    public function doSort($column){
        if($this->sortColumn == $column){
            $this->sortDirection = $this->sortDirection ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }

    public function export()
    {
        $excel = app(Excel::class);
        return $excel->download(new SchoolsIndexExport, 'schools.xlsx');
    }

    public function render()
    {
        return view('livewire.datatable.schools-datatable', [
            'schools' => School::search($this->search)
                         ->orderBy($this->sortColumn, $this->sortDirection)
                         ->paginate(10)
        ]);
    }
}
