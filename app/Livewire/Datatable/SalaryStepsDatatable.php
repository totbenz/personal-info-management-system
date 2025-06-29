<?php

namespace App\Livewire\Datatable;

use App\Models\SalaryStep;
use App\Models\SalaryGrade;
use Livewire\Component;
use Livewire\WithPagination;

class SalaryStepsDatatable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $salaryGrades;
    public $selectedYear;
    public $years = [];
    public $editingCell = [
        'salary_grade_id' => null,
        'step' => null,
        'salary' => null,
    ];

    // For create/edit form
    public $editingSalaryStep = [
        'id' => null,
        'salary_grade_id' => '',
        'step' => '',
        'year' => '',
        'salary' => ''
    ];

    public $salaryMatrix = [];

    public $confirmDeleteGradeId = null;
    public $confirmDeleteStep = [
        'step' => null,
        'all' => false,
    ];
    public $newGrade;
    public $addGradeError = '';
    public $isAddingGrade = false;

    public $newYear;
    public $addYearError = '';
    public $isAddingYear = false;

    public $confirmDeleteYearModal = false;

    public function mount()
    {
        $this->salaryGrades = SalaryGrade::orderBy('grade')->get();
        $this->years = SalaryStep::distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $this->selectedYear = $this->years[0] ?? date('Y');
        $this->updateSalaryMatrix();
    }

    protected $rules = [
        'editingSalaryStep.salary_grade_id' => 'required|exists:salary_grades,id',
        'editingSalaryStep.step' => 'required|integer|min:1|max:8',
        'editingSalaryStep.year' => 'required|integer|min:2020|max:2050',
        'editingSalaryStep.salary' => 'required|numeric|min:0'
    ];

    public function create()
    {
        $this->resetEditingSalaryStep();
        $this->showCreateModal = true;
    }

    public function edit($salaryStepId)
    {
        $this->resetEditingSalaryStep();
        $salaryStep = SalaryStep::find($salaryStepId);
        $this->editingSalaryStep = [
            'id' => $salaryStep->id,
            'salary_grade_id' => $salaryStep->salary_grade_id,
            'step' => $salaryStep->step,
            'year' => $salaryStep->year,
            'salary' => $salaryStep->salary
        ];
        $this->showEditModal = true;
    }

    public function confirmDelete($salaryStepId)
    {
        $this->editingSalaryStep['id'] = $salaryStepId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $salaryStep = SalaryStep::find($this->editingSalaryStep['id']);
        if ($salaryStep) {
            $salaryStep->delete();
            session()->flash('message', 'Salary Step deleted successfully.');
        }
        $this->showDeleteModal = false;
        $this->resetEditingSalaryStep();
    }

    public function save()
    {
        $this->validate();

        if (!$this->editingSalaryStep['id']) {
            // Create new
            SalaryStep::create([
                'salary_grade_id' => $this->editingSalaryStep['salary_grade_id'],
                'step' => $this->editingSalaryStep['step'],
                'year' => $this->editingSalaryStep['year'],
                'salary' => $this->editingSalaryStep['salary']
            ]);
            session()->flash('message', 'Salary Step created successfully.');
        } else {
            // Update existing
            $salaryStep = SalaryStep::find($this->editingSalaryStep['id']);
            $salaryStep->update([
                'salary_grade_id' => $this->editingSalaryStep['salary_grade_id'],
                'step' => $this->editingSalaryStep['step'],
                'year' => $this->editingSalaryStep['year'],
                'salary' => $this->editingSalaryStep['salary']
            ]);
            session()->flash('message', 'Salary Step updated successfully.');
        }

        $this->resetEditingSalaryStep();
        $this->showCreateModal = false;
        $this->showEditModal = false;
    }

    public function resetEditingSalaryStep()
    {
        $this->editingSalaryStep = [
            'id' => null,
            'salary_grade_id' => '',
            'step' => '',
            'year' => '',
            'salary' => ''
        ];
        $this->resetErrorBag();
    }

    public function doSort($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }

    public function updatedSelectedYear()
    {
        $this->salaryGrades = SalaryGrade::orderBy('grade')->get();
        $this->resetEditingCell();
        $this->updateSalaryMatrix();
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
                $matrix[$grade->id][$step] = $salaryStep ? $salaryStep->salary : null;
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
        $gradeId = $this->editingCell['salary_grade_id'];
        $step = $this->editingCell['step'];
        $salary = $this->editingCell['salary'];
        $year = $this->selectedYear;
        // Enforce 0 for empty/blank salary
        $salary = ($salary === '' || $salary === null) ? 0 : $salary;
        $salaryStep = SalaryStep::where('salary_grade_id', $gradeId)
            ->where('step', $step)
            ->where('year', $year)
            ->first();
        if ($salaryStep) {
            $salaryStep->salary = $salary;
            $salaryStep->save();
        } else {
            \App\Models\SalaryStep::create([
                'salary_grade_id' => $gradeId,
                'step' => $step,
                'year' => $year,
                'salary' => $salary,
            ]);
        }
        $this->resetEditingCell();
        $this->salaryGrades = SalaryGrade::orderBy('grade')->get();
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

    public function confirmDeleteGrade($gradeId)
    {
        $this->confirmDeleteGradeId = $gradeId;
    }

    public function deleteSalaryGradeConfirmed()
    {
        if ($this->confirmDeleteGradeId) {
            SalaryGrade::find($this->confirmDeleteGradeId)?->delete();
            $this->salaryGrades = SalaryGrade::orderBy('grade')->get();
            $this->updateSalaryMatrix();
        }
        $this->confirmDeleteGradeId = null;
    }

    public function cancelDeleteGrade()
    {
        $this->confirmDeleteGradeId = null;
    }

    public function confirmDeleteStep($step)
    {
        $this->confirmDeleteStep = [
            'step' => $step,
            'all' => true,
        ];
    }

    public function deleteSalaryStepConfirmed()
    {
        if ($this->confirmDeleteStep['step']) {
            foreach ($this->salaryGrades as $grade) {
                SalaryStep::where('salary_grade_id', $grade->id)
                    ->where('step', $this->confirmDeleteStep['step'])
                    ->where('year', $this->selectedYear)
                    ->delete();
            }
            $this->updateSalaryMatrix();
        }
        $this->confirmDeleteStep = ['step' => null, 'all' => false];
    }

    public function cancelDeleteStep()
    {
        $this->confirmDeleteStep = ['step' => null, 'all' => false];
    }

    public function addSalaryGrade()
    {
        $grade = $this->newGrade;
        $this->addGradeError = '';
        if (!$grade || !is_numeric($grade)) {
            $this->addGradeError = 'Grade must be a number.';
            return;
        }
        if (SalaryGrade::where('grade', $grade)->exists()) {
            $this->addGradeError = 'Grade already exists.';
            return;
        }
        $this->isAddingGrade = true;
        $salaryGrade = SalaryGrade::create(['grade' => $grade]);
        // Create 8 salary steps for this grade and selected year, salary = 0
        for ($step = 1; $step <= 8; $step++) {
            \App\Models\SalaryStep::create([
                'salary_grade_id' => $salaryGrade->id,
                'step' => $step,
                'year' => $this->selectedYear,
                'salary' => 0,
            ]);
        }
        $this->salaryGrades = SalaryGrade::orderBy('grade')->get();
        $this->updateSalaryMatrix();
        $this->newGrade = '';
        $this->isAddingGrade = false;
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
        $grades = SalaryGrade::orderBy('grade')->get();
        foreach ($grades as $grade) {
            for ($step = 1; $step <= 8; $step++) {
                \App\Models\SalaryStep::create([
                    'salary_grade_id' => $grade->id,
                    'step' => $step,
                    'year' => $this->newYear,
                    'salary' => 0,
                ]);
            }
        }
        $this->years = SalaryStep::distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        $this->selectedYear = $this->newYear;
        $this->updateSalaryMatrix();
        $this->newYear = '';
        $this->isAddingYear = false;
    }

    public function showDeleteYearModal()
    {
        $this->confirmDeleteYearModal = true;
    }

    public function cancelDeleteYearModal()
    {
        $this->confirmDeleteYearModal = false;
    }

    public function deleteSelectedYear()
    {
        if ($this->selectedYear) {
            SalaryStep::where('year', $this->selectedYear)->delete();
            $this->years = SalaryStep::distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
            $this->selectedYear = $this->years[0] ?? date('Y');
            $this->updateSalaryMatrix();
        }
        $this->confirmDeleteYearModal = false;
    }

    public function render()
    {
        $salarySteps = SalaryStep::with('salaryGrade')
            ->when($this->search, function ($query) {
                $query->whereHas('salaryGrade', function ($q) {
                    $q->where('grade', 'like', '%' . $this->search . '%');
                })->orWhere('step', 'like', '%' . $this->search . '%')
                    ->orWhere('year', 'like', '%' . $this->search . '%')
                    ->orWhere('salary', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.datatable.salary-steps-datatable', [
            'salarySteps' => $salarySteps,
            'salaryGrades' => $this->salaryGrades,
            'years' => $this->years,
            'selectedYear' => $this->selectedYear,
            'salaryMatrix' => $this->salaryMatrix,
        ]);
    }
}
