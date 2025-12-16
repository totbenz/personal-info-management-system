<?php

namespace App\Livewire;

use App\Models\SalaryStep;
use App\Models\SalaryGrade;
use Livewire\Component;
use Livewire\WithPagination;

class SalaryStepsManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedYear;
    public $sortColumn = 'salary_grade_id';
    public $sortDirection = 'asc';

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form data
    public $salaryStepId = null;
    public $salary_grade_id = '';
    public $step = '';
    public $year = '';
    public $salary = '';

    // Matrix editing
    public $editingCell = [
        'salary_grade_id' => null,
        'step' => null,
        'salary' => null,
    ];

    // Year and Grade Management
    public $years = [];
    public $salaryGrades = [];
    public $salaryMatrix = [];
    public $newYear = '';
    public $newGrade = '';
    public $addYearError = '';
    public $addGradeError = '';
    public $isAddingYear = false;
    public $isAddingGrade = false;
    public $showDeleteYearModalVisible = false;
    public $confirmDeleteGradeId = null;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'salary_grade_id' => 'required|exists:salary_grades,id',
        'step' => 'required|integer|min:1|max:8',
        'year' => 'required|integer|min:2000|max:2050',
        'salary' => 'required|numeric|min:0',
        'editingCell.salary' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'salary_grade_id.required' => 'Salary grade is required.',
        'salary_grade_id.exists' => 'Selected salary grade does not exist.',
        'step.required' => 'Step is required.',
        'step.integer' => 'Step must be an integer.',
        'step.min' => 'Step must be at least 1.',
        'step.max' => 'Step must not exceed 8.',
        'year.required' => 'Year is required.',
        'year.integer' => 'Year must be an integer.',
        'year.min' => 'Year must be at least 2000.',
        'year.max' => 'Year must not exceed 2050.',
        'salary.required' => 'Salary is required.',
        'salary.numeric' => 'Salary must be a number.',
        'salary.min' => 'Salary must be at least 0.',
    ];

    public function mount()
    {
        $this->fetchSalaryGrades();
        $this->fetchYears();
        $this->selectedYear = $this->years[0] ?? date('Y');
        $this->updateSalaryMatrix();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedYear()
    {
        $this->resetEditingCell();
        $this->updateSalaryMatrix();
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
        $this->salaryStepId = null;
        $this->salary_grade_id = '';
        $this->step = '';
        $this->year = $this->selectedYear;
        $this->salary = '';
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

        $salaryStep = SalaryStep::find($id);

        if ($salaryStep) {
            $this->salaryStepId = $salaryStep->id;
            $this->salary_grade_id = $salaryStep->salary_grade_id;
            $this->step = $salaryStep->step;
            $this->year = $salaryStep->year;
            $this->salary = $salaryStep->salary;
            $this->showEditModal = true;
        }
    }

    public function openDeleteModal($id)
    {
        $this->salaryStepId = $id;
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
            SalaryStep::create([
                'salary_grade_id' => $this->salary_grade_id,
                'step' => $this->step,
                'year' => $this->year,
                'salary' => $this->salary,
            ]);

            $this->dispatch('showSuccess', 'Salary step created successfully.');
            $this->closeModal();
            $this->updateSalaryMatrix();

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to create salary step: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $this->validate();

        try {
            $salaryStep = SalaryStep::find($this->salaryStepId);

            if (!$salaryStep) {
                $this->dispatch('showError', 'Salary step not found.');
                return;
            }

            $salaryStep->update([
                'salary_grade_id' => $this->salary_grade_id,
                'step' => $this->step,
                'year' => $this->year,
                'salary' => $this->salary,
            ]);

            $this->dispatch('showSuccess', 'Salary step updated successfully.');
            $this->closeModal();
            $this->updateSalaryMatrix();

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to update salary step: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $salaryStep = SalaryStep::find($this->salaryStepId);

            if (!$salaryStep) {
                $this->dispatch('showError', 'Salary step not found.');
                return;
            }

            $salaryStep->delete();

            $this->dispatch('showSuccess', 'Salary step deleted successfully.');
            $this->closeModal();
            $this->updateSalaryMatrix();

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to delete salary step.');
        }
    }

    public function fetchSalaryGrades()
    {
        $this->salaryGrades = SalaryGrade::orderBy('grade')->get();
    }

    public function fetchYears()
    {
        $this->years = SalaryStep::distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
    }

    public function updateSalaryMatrix()
    {
        $grades = SalaryGrade::orderBy('grade')->get();
        $steps = range(1, 8);
        $matrix = [];

        foreach ($grades as $grade) {
            foreach ($steps as $step) {
                $salaryStep = SalaryStep::where('salary_grade_id', $grade->id)
                    ->where('step', $step)
                    ->where('year', $this->selectedYear)
                    ->first();
                $matrix[$grade->id][$step] = $salaryStep ? $salaryStep->salary : 0;
            }
        }

        $this->salaryMatrix = $matrix;
    }

    public function startEditCell($salary_grade_id, $step)
    {
        $this->editingCell = [
            'salary_grade_id' => $salary_grade_id,
            'step' => $step,
            'salary' => $this->salaryMatrix[$salary_grade_id][$step] ?? '',
        ];
    }

    public function saveCell()
    {
        $this->validate([
            'editingCell.salary' => 'nullable|numeric|min:0',
        ]);

        $gradeId = $this->editingCell['salary_grade_id'];
        $step = $this->editingCell['step'];
        $salary = $this->editingCell['salary'] ?? 0;
        $year = $this->selectedYear;

        if (!$gradeId || !$step || !$year) {
            $this->resetEditingCell();
            return;
        }

        $salaryStep = SalaryStep::where('salary_grade_id', $gradeId)
            ->where('step', $step)
            ->where('year', $year)
            ->first();

        if ($salaryStep) {
            $salaryStep->salary = $salary;
            $salaryStep->save();
        } else {
            SalaryStep::create([
                'salary_grade_id' => $gradeId,
                'step' => $step,
                'year' => $year,
                'salary' => $salary,
            ]);
        }

        $this->dispatch('showSuccess', 'Salary updated successfully.');
        $this->resetEditingCell();
        $this->updateSalaryMatrix();
    }

    public function resetEditingCell()
    {
        $this->editingCell = [
            'salary_grade_id' => null,
            'step' => null,
            'salary' => null,
        ];
    }

    public function addYear()
    {
        $this->addYearError = '';

        if (!$this->newYear || !is_numeric($this->newYear) || $this->newYear < 2000 || $this->newYear > 2100) {
            $this->addYearError = 'Enter a valid year (2000-2100).';
            return;
        }

        if (in_array($this->newYear, $this->years)) {
            $this->addYearError = 'Year already exists.';
            return;
        }

        $this->isAddingYear = true;

        try {
            $grades = SalaryGrade::orderBy('grade')->get();

            foreach ($grades as $grade) {
                for ($step = 1; $step <= 8; $step++) {
                    SalaryStep::create([
                        'salary_grade_id' => $grade->id,
                        'step' => $step,
                        'year' => $this->newYear,
                        'salary' => 0,
                    ]);
                }
            }

            $this->fetchYears();
            $this->selectedYear = $this->newYear;
            $this->updateSalaryMatrix();
            $this->newYear = '';
            $this->dispatch('showSuccess', 'Year added successfully.');

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to add year.');
        } finally {
            $this->isAddingYear = false;
        }
    }

    public function addSalaryGrade()
    {
        $this->addGradeError = '';

        if (!$this->newGrade || !is_numeric($this->newGrade)) {
            $this->addGradeError = 'Grade must be a number.';
            return;
        }

        if (SalaryGrade::where('grade', $this->newGrade)->exists()) {
            $this->addGradeError = 'Grade already exists.';
            return;
        }

        $this->isAddingGrade = true;

        try {
            $salaryGrade = SalaryGrade::create(['grade' => $this->newGrade]);

            for ($step = 1; $step <= 8; $step++) {
                SalaryStep::create([
                    'salary_grade_id' => $salaryGrade->id,
                    'step' => $step,
                    'year' => $this->selectedYear,
                    'salary' => 0,
                ]);
            }

            $this->fetchSalaryGrades();
            $this->updateSalaryMatrix();
            $this->newGrade = '';
            $this->dispatch('showSuccess', 'Grade added successfully.');

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to add grade.');
        } finally {
            $this->isAddingGrade = false;
        }
    }

    public function confirmDeleteGrade($gradeId)
    {
        $this->confirmDeleteGradeId = $gradeId;
    }

    public function deleteSalaryGradeConfirmed()
    {
        if ($this->confirmDeleteGradeId) {
            try {
                SalaryGrade::find($this->confirmDeleteGradeId)?->delete();
                $this->fetchSalaryGrades();
                $this->updateSalaryMatrix();
                $this->dispatch('showSuccess', 'Grade deleted successfully.');
            } catch (\Exception $e) {
                $this->dispatch('showError', 'Failed to delete grade.');
            }
            $this->confirmDeleteGradeId = null;
        }
    }

    public function cancelDeleteGrade()
    {
        $this->confirmDeleteGradeId = null;
    }

    public function showDeleteYearModal()
    {
        // Ensure we have a selected year before showing the modal
        if (!$this->selectedYear) {
            $this->dispatch('showError', 'No year selected for deletion.');
            return;
        }

        $this->showDeleteYearModalVisible = true;
    }

    public function cancelDeleteYearModal()
    {
        $this->showDeleteYearModalVisible = false;
    }

    public function deleteSelectedYear()
    {
        if ($this->selectedYear) {
            try {
                SalaryStep::where('year', $this->selectedYear)->delete();
                $this->fetchYears();
                $this->selectedYear = $this->years[0] ?? date('Y');
                $this->updateSalaryMatrix();
                $this->dispatch('showSuccess', 'Year deleted successfully.');
            } catch (\Exception $e) {
                $this->dispatch('showError', 'Failed to delete year.');
            }
            $this->showDeleteYearModal = false;
        }
    }

    public function getSalaryStepsProperty()
    {
        return SalaryStep::with('salaryGrade')
            ->when($this->search, function($query) {
                $query->whereHas('salaryGrade', function($q) {
                    $q->where('grade', 'like', '%' . $this->search . '%');
                })
                ->orWhere('step', 'like', '%' . $this->search . '%')
                ->orWhere('year', 'like', '%' . $this->search . '%')
                ->orWhere('salary', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.salary-steps-management', [
            'salarySteps' => $this->salarySteps,
            'salaryGrades' => $this->salaryGrades,
            'years' => $this->years,
            'selectedYear' => $this->selectedYear,
            'salaryMatrix' => $this->salaryMatrix,
        ]);
    }
}
