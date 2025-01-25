<?php

namespace App\Livewire\Datatable\School;

use App\Models\AppointmentsFunding;
use App\Models\FundedItem;
use App\Models\School;
use Livewire\Component;
use Livewire\WithPagination;

class SchoolResourcesDatatable extends Component
{
    use WithPagination;
    public $school_id;
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

    public function mount($school_id){
        $this->school_id = $school_id;
    }

    public function render()
    {
        $funded_items = FundedItem::query()
        ->where('school_id', $this->school_id);
        // ->search($this->search)
        // ->orderBy($this->sortColumn, $this->sortDirection)
        // ->paginate(10);

        return view('livewire.datatable.school.school-resources-datatable', [
            'funded_items' => $funded_items
        ]);
    }
}
