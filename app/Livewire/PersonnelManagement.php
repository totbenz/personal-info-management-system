<?php

namespace App\Livewire;

use App\Models\Personnel;
use App\Models\School;
use App\Models\Position;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use App\Exports\PersonnelsIndexExport;

class PersonnelManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $sortColumn = 'id';
    public $sortDirection = 'asc';
    public $schoolId = null;
    public $selectedSchool = null;
    public $selectedCategory = null;
    public $selectedClassification = null;
    public $selectedPosition = null;
    public $selectedJobStatus = null;

    // Modal states
    public $showDeleteModal = false;
    public $personnelIdToDelete = null;

    protected $paginationTheme = 'tailwind';

    public function mount($schoolId = null)
    {
        // Set the school ID from parameter if provided
        if ($schoolId !== null) {
            $this->schoolId = $schoolId;
            $this->selectedSchool = $schoolId;
        } elseif (auth()->user()->role === 'school_head') {
            $this->selectedSchool = auth()->user()->personnel->school_id;
            $this->schoolId = auth()->user()->personnel->school_id;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedSchool()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingSelectedPosition()
    {
        $this->resetPage();
    }

    public function updatingSelectedJobStatus()
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

    public function export()
    {
        $excel = app(Excel::class);

        // Prepare filters to pass to export
        $filters = [
            'schoolId' => $this->schoolId,
            'selectedSchool' => $this->selectedSchool,
            'selectedCategory' => $this->selectedCategory,
            'selectedPosition' => $this->selectedPosition,
            'selectedJobStatus' => $this->selectedJobStatus,
            'search' => $this->search,
            'sortColumn' => $this->sortColumn,
            'sortDirection' => $this->sortDirection,
        ];

        return $excel->download(new PersonnelsIndexExport($filters), 'personnel.xlsx');
    }

    public function openDeleteModal($id)
    {
        $this->personnelIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showDeleteModal = false;
        $this->personnelIdToDelete = null;
    }

    public function delete()
    {
        try {
            $personnel = Personnel::find($this->personnelIdToDelete);

            if (!$personnel) {
                $this->dispatch('showError', 'Personnel not found.');
                return;
            }

            // Check if personnel has an associated user account
            if ($personnel->user) {
                $this->dispatch('showError', 'Cannot delete personnel with associated user account. Please delete the user account first.');
                $this->closeModal();
                return;
            }

            $personnel->delete();

            $this->dispatch('showSuccess', 'Personnel deleted successfully.');
            $this->closeModal();

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->dispatch('showError', 'Cannot delete this personnel because it is referenced by other records.');
            } else {
                $this->dispatch('showError', 'Failed to delete personnel.');
            }
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to delete personnel.');
        }
    }

    public function getPersonnelsProperty()
    {
        $query = Personnel::with(['school', 'position']);

        // Apply school filter based on user role and selection
        if ($this->schoolId) {
            $query->where('school_id', $this->schoolId);
        } elseif (auth()->user()->role === 'school_head') {
            $query->where('school_id', auth()->user()->personnel->school_id);
        } elseif ($this->selectedSchool) {
            $query->where('school_id', $this->selectedSchool);
        }

        // Apply other filters
        $query->when($this->selectedCategory, function ($query) {
                $query->where('category', $this->selectedCategory);
            })
            ->when($this->selectedPosition, function ($query) {
                $query->where('position_id', $this->selectedPosition);
            })
            ->when($this->selectedJobStatus, function ($query) {
                $query->where('job_status', $this->selectedJobStatus);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('personnel_id', 'like', '%' . $this->search . '%')
                      ->orWhere('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('middle_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('mobile_no', 'like', '%' . $this->search . '%');
                });
            });

        return $query->orderBy($this->sortColumn, $this->sortDirection)
                    ->paginate(10);
    }

    public function render()
    {
        return view('livewire.personnel-management', [
            'personnels' => $this->personnels,
            'schools' => School::orderBy('school_name')->get(),
            'positions' => Position::orderBy('title')->get(),
        ]);
    }
}
