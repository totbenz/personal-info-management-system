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

    // For create/edit form
    public $editingSalaryStep = [
        'id' => null,
        'salary_grade_id' => '',
        'step' => '',
        'year' => '',
        'salary' => ''
    ];

    public function mount()
    {
        $this->salaryGrades = SalaryGrade::orderBy('grade')->get();
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
            'salaryGrades' => $this->salaryGrades
        ]);
    }
}
