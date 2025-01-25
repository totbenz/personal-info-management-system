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
        'old_assignment_details.*.assignment' => 'required',
        'old_assignment_details.*.dtr_day' => 'nullable',
        'old_assignment_details.*.dtr_from' => 'required',
        'old_assignment_details.*.dtr_to' => 'required',
        'old_assignment_details.*.school_year' => 'required',
        'old_assignment_details.*.teaching_minutes_per_week' => 'required',

        'new_assignment_details.*.assignment' => 'required',
        'new_assignment_details.*.dtr_day' => 'nullable',
        'new_assignment_details.*.dtr_from' => 'required',
        'new_assignment_details.*.dtr_to' => 'required',
        'new_assignment_details.*.school_year' => 'required',
        'new_assignment_details.*.teaching_minutes_per_week' => 'required'
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
        array_splice($this->new_references, $index, 1);
    }

    public function removeOldField($index)
    {
        try {
            $referenceId = $this->old_references[$index]['id'];
            $referenceModel = $this->personnel->references()->findOrFail($referenceId);

            // Delete the child from the database
            $referenceModel->delete();

            session()->flash('flash.banner', 'Reference deleted successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to delete Reference');
            session()->flash('flash.bannerStyle', 'success');
        }

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

    public function edit()
    {
        $this->updateMode = true;
        $this->showMode = false;
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
        $this->validate();

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

        if($this->new_assignment_details != null)
        {
            foreach ($this->new_assignment_details as $assignment_detail) {
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

        $this->updateMode = false;
        $this->showMode = true;

        session()->flash('flash.banner', 'Assignment Details saved successfully');
        session()->flash('flash.bannerStyle', 'success');

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

    public function render()
    {
        return view('livewire.form.assignment-details-form');
    }
}
