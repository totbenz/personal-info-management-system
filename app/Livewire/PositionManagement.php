<?php

namespace App\Livewire;

use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;

class PositionManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedClassification = '';
    public $sortColumn = 'id';
    public $sortDirection = 'asc';

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form data
    public $positionId = null;
    public $title = '';
    public $classification = 'teaching';

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'title' => 'required|string|max:255',
        'classification' => 'required|in:teaching,teaching-related,non-teaching',
    ];

    protected $messages = [
        'title.required' => 'Position title is required.',
        'title.string' => 'Position title must be a string.',
        'title.max' => 'Position title may not be greater than 255 characters.',
        'classification.required' => 'Classification is required.',
        'classification.in' => 'Classification must be one of: teaching, teaching-related, or non-teaching.',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedClassification()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function resetForm()
    {
        $this->positionId = null;
        $this->title = '';
        $this->classification = 'teaching';
        $this->resetErrorBag();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();

        $position = Position::find($id);

        if ($position) {
            $this->positionId = $position->id;
            $this->title = $position->title;
            $this->classification = $position->classification;
            $this->showEditModal = true;
        }
    }

    public function openDeleteModal($id)
    {
        $this->positionId = $id;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function create()
    {
        $this->validate();

        try {
            // Check if position with same title already exists
            $existingPosition = Position::where('title', $this->title)->first();
            if ($existingPosition) {
                $this->dispatch('showWarning', 'A position with this title already exists!');
                return;
            }

            $position = Position::create([
                'title' => $this->title,
                'classification' => $this->classification,
            ]);

            $this->dispatch('showSuccess', "Position '{$position->title}' created successfully!");
            $this->closeModal();

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->dispatch('showError', 'Database constraint violation. Please check your data.');
            } else {
                $this->dispatch('showError', 'Database error occurred while creating position.');
            }
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to create position. Please try again.');
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $position = Position::find($this->positionId);

            if (!$position) {
                $this->dispatch('showError', 'Position not found.');
                return;
            }

            // Check if another position with the same title exists (excluding current one)
            $existingPosition = Position::where('title', $this->title)
                ->where('id', '!=', $this->positionId)
                ->first();

            if ($existingPosition) {
                $this->dispatch('showWarning', 'Another position with this title already exists!');
                return;
            }

            $position->update([
                'title' => $this->title,
                'classification' => $this->classification,
            ]);

            $this->dispatch('showSuccess', "Position '{$position->title}' updated successfully!");
            $this->closeModal();

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->dispatch('showError', 'Database constraint violation. Please check your data.');
            } else {
                $this->dispatch('showError', 'Database error occurred while updating position.');
            }
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to update position. Please try again.');
        }
    }

    public function delete()
    {
        try {
            $position = Position::find($this->positionId);

            if (!$position) {
                $this->dispatch('showError', 'Position not found.');
                return;
            }

            // Check if position is being used by personnel
            $personnelCount = \App\Models\Personnel::where('position_id', $this->positionId)->count();
            if ($personnelCount > 0) {
                $this->dispatch('showWarning', "Cannot delete position '{$position->title}' because it is assigned to {$personnelCount} personnel record(s). Please reassign or remove personnel first.");
                return;
            }

            $positionTitle = $position->title;
            $position->delete();

            $this->dispatch('showSuccess', "Position '{$positionTitle}' deleted successfully!");
            $this->closeModal();

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->dispatch('showError', 'Cannot delete this position because it is referenced by other records in the database.');
            } else {
                $this->dispatch('showError', 'Database error occurred while deleting position.');
            }
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to delete position. Please try again.');
        }
    }

    public function getPositionsProperty()
    {
        return Position::when($this->search, function($query) {
                $query->search($this->search);
            })
            ->when($this->selectedClassification, function($query) {
                $query->where('classification', $this->selectedClassification);
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.position-management', [
            'positions' => $this->positions,
        ]);
    }
}
