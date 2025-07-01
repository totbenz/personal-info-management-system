<?php

namespace App\Livewire\Form;

use App\Models\AssignmentDetail;
use App\Models\Personnel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AssignmentDetailsForm extends Component
{
    public $personnel;
    public $old_assignment_details=[], $new_assignment_details=[];
    public $showMode = false, $updateMode = false;

    protected $rules = [
        'old_assignment_details.*.assignment' => 'required|string',
        'old_assignment_details.*.dtr_day' => 'nullable|string',
        'old_assignment_details.*.dtr_from' => 'required',
        'old_assignment_details.*.dtr_to' => 'required',
        'old_assignment_details.*.school_year' => 'required|string',
        'old_assignment_details.*.teaching_minutes_per_week' => 'required|numeric|min:0',

        'new_assignment_details.*.assignment' => 'nullable|string',
        'new_assignment_details.*.dtr_day' => 'nullable|string',
        'new_assignment_details.*.dtr_from' => 'nullable',
        'new_assignment_details.*.dtr_to' => 'nullable',
        'new_assignment_details.*.school_year' => 'nullable|string',
        'new_assignment_details.*.teaching_minutes_per_week' => 'nullable|numeric|min:0'
    ];

    public function  mount($id, $showMode=true)
    {
        if($id) {
            $this->personnel = Personnel::findOrFail($id);
            $this->old_assignment_details = $this->personnel->assignmentDetails()->get()->map(function($assignment_details) {
                return [
                    'id' => $assignment_details->id,
                    'assignment' => $assignment_details->assignment,
                    'dtr_day' => $assignment_details->dtr_day,
                    'dtr_from' => $assignment_details->dtr_from,
                    'dtr_to' => $assignment_details->dtr_to,
                    'school_year' => $assignment_details->school_year,
                    'teaching_minutes_per_week' => $assignment_details->teaching_minutes_per_week,
                ];
            })->toArray();

            $this->new_assignment_details[] = [
                'assignment' =>'',
                'dtr_day' => '',
                'dtr_from' => '',
                'dtr_to' =>'',
                'school_year' => '',
                'teaching_minutes_per_week' => ''
            ];
        }

        $this->showMode = $showMode;
    }

    public function addField()
    {
        $this->new_assignment_details[] = [
            'assignment' =>'',
            'dtr_day' => '',
            'dtr_from' => '',
            'dtr_to' =>'',
            'school_year' => '',
            'teaching_minutes_per_week' => ''
        ];
    }

    public function removeNewField($index)
    {
        array_splice($this->new_assignment_details, $index, 1);
    }

    public function removeOldField($index)
    {
        try {
            $assignmentDetailId = $this->old_assignment_details[$index]['id'];
            $assignmentDetailModel = $this->personnel->assignmentDetails()->findOrFail($assignmentDetailId);

            // Delete the assignment detail from the database
            $assignmentDetailModel->delete();

            // Remove from the local array
            array_splice($this->old_assignment_details, $index, 1);

            session()->flash('flash.banner', 'Assignment Detail deleted successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to delete Assignment Detail');
            session()->flash('flash.bannerStyle', 'danger');
        }
        session(['active_personnel_tab' => 'assignment_details']);
    }

    public function edit()
    {
        $this->updateMode = true;
        $this->showMode = false;
    }

    public function back()
    {
        $this->updateMode = false;
        $this->showMode = true;
    }

    public function resetModes()
    {
        $this->updateMode = false;
        $this->showMode = true;
    }

    public function cancel()
    {
        $this->resetModes();
        if(Auth::user()->role === "teacher")
        {
            return redirect()->route('personnel.profile');
        } elseif(Auth::user()->role === "school_head")
        {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    public function save()
    {
        // Validate the form data
        $this->validate();

        try {
            // Update existing assignment details
            if ($this->personnel->assignmentDetails()->exists()) {
                foreach ($this->old_assignment_details as $assignment_detail) {
                    $this->personnel->assignmentDetails()->where('id', $assignment_detail['id'])
                        ->update([
                            'assignment' => $assignment_detail['assignment'],
                            'dtr_day' => $assignment_detail['dtr_day'],
                            'dtr_from' => $assignment_detail['dtr_from'],
                            'dtr_to' => $assignment_detail['dtr_to'],
                            'school_year' => $assignment_detail['school_year'],
                            'teaching_minutes_per_week' => $assignment_detail['teaching_minutes_per_week']
                        ]);
                }
            }

            // Create new assignment details (only if they have data)
            if($this->new_assignment_details != null)
            {
                foreach ($this->new_assignment_details as $assignment_detail) {
                    // Only create if required fields are not empty
                    if (!empty($assignment_detail['assignment']) && 
                        !empty($assignment_detail['dtr_from']) && 
                        !empty($assignment_detail['dtr_to']) && 
                        !empty($assignment_detail['school_year']) && 
                        !empty($assignment_detail['teaching_minutes_per_week'])) {
                        
                        $this->personnel->assignmentDetails()->create([
                            'assignment' => $assignment_detail['assignment'],
                            'dtr_day' => $assignment_detail['dtr_day'],
                            'dtr_from' => $assignment_detail['dtr_from'],
                            'dtr_to' => $assignment_detail['dtr_to'],
                            'school_year' => $assignment_detail['school_year'],
                            'teaching_minutes_per_week' => $assignment_detail['teaching_minutes_per_week']
                        ]);
                    }
                }
            }

            // Refresh the old assignment details data after save
            $this->old_assignment_details = $this->personnel->assignmentDetails()->get()->map(function($assignment_details) {
                return [
                    'id' => $assignment_details->id,
                    'assignment' => $assignment_details->assignment,
                    'dtr_day' => $assignment_details->dtr_day,
                    'dtr_from' => $assignment_details->dtr_from,
                    'dtr_to' => $assignment_details->dtr_to,
                    'school_year' => $assignment_details->school_year,
                    'teaching_minutes_per_week' => $assignment_details->teaching_minutes_per_week,
                ];
            })->toArray();

            // Clear new assignment details and add one empty entry for future use
            $this->new_assignment_details = [[
                'assignment' =>'',
                'dtr_day' => '',
                'dtr_from' => '',
                'dtr_to' =>'',
                'school_year' => '',
                'teaching_minutes_per_week' => ''
            ]];
            
            $this->updateMode = false;
            $this->showMode = true;

            session()->flash('flash.banner', 'Assignment Details saved successfully');
            session()->flash('flash.bannerStyle', 'success');
            session(['active_personnel_tab' => 'assignment_details']);

            if(Auth::user()->role === "teacher")
            {
                return redirect()->route('personnel.profile');
            } elseif(Auth::user()->role === "school_head")
            {
                return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
            } else {
                return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
            }
        } catch (\Exception $ex) {
            session()->flash('error', 'Something went wrong: ' . $ex->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.form.assignment-details-form');
    }
}
