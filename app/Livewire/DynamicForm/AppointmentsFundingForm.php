<?php

namespace App\Livewire\DynamicForm;

use App\Models\AppointmentsFunding;
use App\Models\School;
use Livewire\Component;

class AppointmentsFundingForm extends Component
{
    public $id;
    public $school;
    public $confirmingAppointmentsFundingDeletion = false;

    public function mount($id = null)
    {
        if ($id !== null) {
            $this->school = School::findOrFail($id);
        }
    }

    public function render()
    {
        return view('livewire.dynamic-form.appointments-funding-form');
    }

    public function confirmAppointmentsFundingDeletion($id)
    {
        $this->confirmingAppointmentsFundingDeletion = $id;
    }

    public function deleteAppointmentsFunding()
    {
        try {
            $appointment_funding = AppointmentsFunding::find($this->confirmingAppointmentsFundingDeletion);

            if ($appointment_funding) {
                $appointment_funding->delete();
                session()->flash('flash', ['banner' => 'Appointment Funding data deleted successfully.', 'bannerStyle' => 'success']);
            }
        } catch (\Exception $e) {
            session()->flash('flash', ['banner' => 'Failed to delete Appointment Funding data.', 'bannerStyle' => 'danger']);
        }
        $this->confirmingAppointmentsFundingDeletion = false;
        return redirect()->back();
    }
}
