<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServiceRecord;
use App\Models\Position;

class PersonnelServiceRecordsList extends Component
{
    public $serviceRecords;
    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;
    public $isEditMode = false;
    public $editId = null;

    // Form fields
    public $position_id = null;
    public $appointment_status = null;
    public $salary = null;
    public $from_date = null;
    public $to_date = null;
    public $station = null;
    public $branch = null;
    public $lv_wo_pay = null;
    public $separation_date_cause = null;
    public $personnel_id;
    public $positions = [];

    protected $rules = [
        'personnel_id' => 'required|integer|exists:personnels,id',
        'position_id' => 'required|exists:positions,id',
        'appointment_status' => 'required|string|max:255',
        'salary' => 'nullable|numeric',
        'from_date' => 'required|date',
        'to_date' => 'nullable|date|after_or_equal:from_date',
        'station' => 'nullable|string|max:255',
        'branch' => 'nullable|string|max:255',
        'lv_wo_pay' => 'nullable|string|max:255',
        'separation_date_cause' => 'nullable|string|max:255',
    ];

    public function mount($serviceRecords, $personnelId)
    {
        $this->positions = Position::all();
        $serviceRecords->load('position');
        $this->serviceRecords = $serviceRecords;
        $this->personnel_id = $personnelId;
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->isEditMode = false;
        $this->editId = null;
    }

    public function resetForm()
    {
        $this->position_id = '';
        $this->appointment_status = '';
        $this->salary = '';
        $this->from_date = '';
        $this->to_date = '';
        $this->station = '';
        $this->branch = '';
        $this->lv_wo_pay = '';
        $this->separation_date_cause = '';
    }

    public function editRecord($id)
    {
        $record = ServiceRecord::findOrFail($id);
        $this->editId = $id;
        $this->isEditMode = true;
        $this->showModal = true;
        $this->personnel_id = $record->personnel_id;
        $this->position_id = $record->position_id;
        $this->appointment_status = $record->appointment_status;
        $this->salary = $record->salary;
        $this->from_date = $record->from_date;
        $this->to_date = $record->to_date;
        $this->station = $record->station;
        $this->branch = $record->branch;
        $this->lv_wo_pay = $record->lv_wo_pay;
        $this->separation_date_cause = $record->separation_date_cause;
    }

    public function save()
    {
        try {
            $this->validate([
                'position_id' => 'required|exists:position,id',
                'appointment_status' => 'required',
                'salary' => 'required|numeric',
                'from_date' => 'required|date',
                'to_date' => 'nullable|date|after:from_date',
                'station' => 'required',
                'branch' => 'required',
                'lv_wo_pay' => 'nullable|integer',
                'separation_date_cause' => 'nullable'
            ]);

            // Convert empty string to null for nullable date fields
            if ($this->to_date === '' || $this->to_date === null) {
                $this->to_date = null;
            }

            // Get position title before creating/updating record
            $position = \App\Models\Position::find($this->position_id);
            $positionTitle = $position ? $position->title : '';

            if ($this->isEditMode && $this->editId) {
                $record = ServiceRecord::findOrFail($this->editId);
                $record->update([
                    'position_id' => $this->position_id,
                    'designation' => $positionTitle,
                    'appointment_status' => $this->appointment_status,
                    'salary' => $this->salary,
                    'from_date' => $this->from_date,
                    'to_date' => $this->to_date,
                    'station' => $this->station,
                    'branch' => $this->branch,
                    'lv_wo_pay' => $this->lv_wo_pay,
                    'separation_date_cause' => $this->separation_date_cause ?: null,
                ]);
                $msg = 'Service record updated successfully!';
            } else {
                ServiceRecord::create([
                    'personnel_id' => $this->personnel_id,
                    'position_id' => $this->position_id,
                    'designation' => $positionTitle,
                    'appointment_status' => $this->appointment_status,
                    'salary' => $this->salary,
                    'from_date' => $this->from_date,
                    'to_date' => $this->to_date,
                    'station' => $this->station,
                    'branch' => $this->branch,
                    'lv_wo_pay' => $this->lv_wo_pay ? $this->lv_wo_pay : 0,
                    'separation_date_cause' => $this->separation_date_cause ?: null,
                ]);
                $msg = 'Service record added successfully!';
            }
            $this->closeModal();
            // Refresh the list from DB to get latest, eager load position
            $this->serviceRecords = ServiceRecord::with('position')->where('personnel_id', $this->personnel_id)->get();
            session()->flash('success', $msg);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Validation failed. Please check your input.');
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }

    public function deleteRecord()
    {
        if ($this->deleteId) {
            ServiceRecord::where('id', $this->deleteId)->delete();
            $this->deleteId = null;
            $this->showDeleteModal = false;
            // Refresh the list from DB to get latest, eager load position
            $this->serviceRecords = ServiceRecord::with('position')->where('personnel_id', $this->personnel_id)->get();
            session()->flash('success', 'Service record deleted successfully!');
        }
    }

    public function render()
    {
        return view('livewire.personnel-service-records-list', [
            'serviceRecords' => $this->serviceRecords,
            'positions' => $this->positions,
        ]);
    }
}
