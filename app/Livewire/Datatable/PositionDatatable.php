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
    public $showEditModal = false;
    public $editingPosition;

    public function mount()
    {
        $this->editingPosition = [
            'title' => '',
            'classification' => ''
        ];
    }

    public function doSort($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }

    public function editPosition($id)
    {
        $position = Position::find($id);
        $this->editingPosition = [
            'id' => $position->id,
            'title' => $position->title,
            'classification' => $position->classification
        ];
        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate([
            'editingPosition.title' => 'required|string',
            'editingPosition.classification' => 'required|in:teaching,teaching-related,non-teaching',
        ]);

        $position = Position::find($this->editingPosition['id']);
        $position->update([
            'title' => $this->editingPosition['title'],
            'classification' => $this->editingPosition['classification'],
        ]);

        $this->showEditModal = false;
        session()->flash('message', 'Position updated successfully.');
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
