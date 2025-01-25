<?php

namespace App\Livewire\Datatable;

use App\Models\District;
use Livewire\Component;
use Livewire\WithPagination;

class DistrictDatatable extends Component
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

    public function render()
    {
        $districts = District::search($this->search)
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->paginate(10);

        return view('livewire.datatable.district-datatable', [
            'districts' => $districts
        ]);
    }
}
