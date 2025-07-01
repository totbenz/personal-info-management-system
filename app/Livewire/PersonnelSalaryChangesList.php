<?php

namespace App\Livewire;

use Livewire\Component;

class PersonnelSalaryChangesList extends Component
{
    public $salaryChanges;

    public function mount($salaryChanges)
    {
        $this->salaryChanges = $salaryChanges;
    }

    public function render()
    {
        return view('livewire.personnel-salary-changes-list', [
            'salaryChanges' => $this->salaryChanges
        ]);
    }
}
