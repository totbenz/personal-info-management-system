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
    public $showDeleteModal = false;
    public $deleteId = null;
    public $deleteError = null;

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
        try {
            $this->validate([
                'editingPosition.title' => 'required|string',
                'editingPosition.classification' => 'required|in:teaching,teaching-related,non-teaching',
            ]);

            $position = Position::find($this->editingPosition['id']);
            if (!$position) {
                $this->dispatch('show-error-alert', ['message' => 'Position not found.']);
                return;
            }
            $position->update([
                'title' => $this->editingPosition['title'],
                'classification' => $this->editingPosition['classification'],
            ]);

            $this->showEditModal = false;
            $this->dispatch('show-success-alert', ['message' => 'Position updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('show-error-alert', ['message' => 'Validation failed. Please check your input.']);
        } catch (\Exception $e) {
            $this->dispatch('show-error-alert', ['message' => 'An error occurred while updating the position.']);
        }
    }

    public function deletePosition()
    {
        $this->deleteError = null;
        try {
            $position = Position::find($this->deleteId);
            if ($position) {
                $position->delete();
                $this->dispatch('show-success-alert', ['message' => 'Position deleted successfully.']);
                $this->showDeleteModal = false;
                $this->deleteId = null;
            } else {
                $this->deleteError = 'Position not found.';
                $this->dispatch('show-error-alert', ['message' => 'Position not found.']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->deleteError = 'Cannot delete this position because it is referenced by other records.';
                $this->dispatch('show-error-alert', ['message' => 'Cannot delete this position because it is referenced by other records.']);
            } else {
                $this->deleteError = 'An error occurred while deleting the position.';
                $this->dispatch('show-error-alert', ['message' => 'An error occurred while deleting the position.']);
            }
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteError = null;
    }

    public function setDeleteId($id)
    {
        $this->deleteId = $id;
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
