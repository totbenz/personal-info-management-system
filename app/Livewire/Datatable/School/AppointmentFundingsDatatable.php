<?php

namespace App\Livewire\Datatable\School;

use App\Models\AppointmentsFunding;
use App\Models\FundedItem;
use App\Models\School;
use Livewire\Component;
use Livewire\WithPagination;

class AppointmentFundingsDatatable extends Component
{
    use WithPagination;
    public $school_id;
    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';

    public function mount($id){
        $this->school_id = $id;


    }

    public function doSort($column){
        if($this->sortColumn == $column){
            $this->sortDirection = $this->sortDirection ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }

    public function render()
    {
        $appointment_fundings = FundedItem::query()
        ->where('school_id', $this->school_id)
        ->search($this->search)
        ->orderBy($this->sortColumn, $this->sortDirection)
        ->paginate(10);

        return view('livewire.datatable.school.school-resources-datatable', [
            'appointment_fundings' => $appointment_fundings,
            'i' => $this->school_id
        ]);
    }
}
