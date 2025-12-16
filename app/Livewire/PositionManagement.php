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
            Position::create([
                'title' => $this->title,
                'classification' => $this->classification,
            ]);

            $this->dispatch('showSuccess', 'Position created successfully.');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to create position: ' . $e->getMessage());
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

            $position->update([
                'title' => $this->title,
                'classification' => $this->classification,
            ]);

            $this->dispatch('showSuccess', 'Position updated successfully.');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to update position: ' . $e->getMessage());
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

            $position->delete();

            $this->dispatch('showSuccess', 'Position deleted successfully.');
            $this->closeModal();

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->dispatch('showError', 'Cannot delete this position because it is referenced by other records.');
            } else {
                $this->dispatch('showError', 'Failed to delete position.');
            }
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to delete position.');
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
