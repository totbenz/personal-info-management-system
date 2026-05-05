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

    public function delete($changeId)
    {
        try {
            $salaryChange = \App\Models\SalaryChange::findOrFail($changeId);
            $salaryChange->delete();
            
            // Refresh the salary changes collection
            $this->salaryChanges = $this->salaryChanges->filter(function($change) use ($changeId) {
                return $change->id != $changeId;
            });
            
            session()->flash('success', 'Salary change deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete salary change. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.personnel-salary-changes-list', [
            'salaryChanges' => $this->salaryChanges
        ]);
    }
}
