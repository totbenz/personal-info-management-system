<?php

namespace App\Livewire\Datatable;

use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class PositionDatatable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';
    public $selectedClassification = null;

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
        $positions = Position::search($this->search)
                    ->when($this->selectedClassification, function ($query) {
                        $query->where('classification', $this->selectedClassification);
                    })
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->paginate(10);

        return view('livewire.datatable.position-datatable', [
            'positions' => $positions
        ]);
    }
}
