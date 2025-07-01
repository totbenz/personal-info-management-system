<?php

namespace App\Livewire\Form;

use App\Models\Education;
use App\Models\Personnel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EducationForm extends Component
{
    public $personnel, $elementary, $secondary, $vocational, $graduate, $graduate_studies;
    public $elementary_school_name, $elementary_degree_course, $elementary_period_from, $elementary_period_to, $elementary_highest_level_units, $elementary_year_graduated, $elementary_scholarship_honors;
    public $secondary_school_name, $secondary_degree_course, $secondary_period_from, $secondary_period_to, $secondary_highest_level_units, $secondary_year_graduated, $secondary_scholarship_honors;
    public $vocational_school_name, $vocational_degree_course, $vocational_period_from, $vocational_period_to, $vocational_highest_level_units, $vocational_year_graduated, $vocational_scholarship_honors;
    public $graduate_school_name, $graduate_degree_course, $graduate_major, $graduate_minor, $graduate_period_from, $graduate_period_to, $graduate_highest_level_units, $graduate_year_graduated, $graduate_scholarship_honors;
    public $graduate_studies_school_name, $graduate_studies_degree_course, $graduate_studies_major, $graduate_studies_minor, $graduate_studies_period_from, $graduate_studies_period_to, $graduate_studies_highest_level_units, $graduate_studies_year_graduated, $graduate_studies_scholarship_honors;

    public $showMode = false, $storeMode = false, $updateMode = false;

    protected $rules = [
        'elementary_school_name' => 'required',
        'elementary_degree_course' => 'nullable',
        // 'elementary_period_from' => 'required|integer|digits:4',
        // 'elementary_period_to' => 'required|integer|digits:4',
        'elementary_highest_level_units' => 'required',
        'elementary_year_graduated' => 'required',
        'elementary_scholarship_honors' => 'nullable',

        // Secondary Education
        'secondary_school_name' => 'required',
        'secondary_degree_course' => 'nullable',
        // 'secondary_period_from' => 'required|integer|digits:4',
        // 'secondary_period_to' => 'required|integer|digits:4',
        'secondary_highest_level_units' => 'required',
        'secondary_year_graduated' => 'required',
        'secondary_scholarship_honors' => 'nullable'
    ];

    public function mount($id = null, $showMode = true)
    {
        if($id) {
            $this->personnel = Personnel::findOrFail($id);
            $this->updateMode = !$showMode;
            $this->showMode = $showMode;

            $this->elementary = $this->personnel->elementaryEducation;
            $this->secondary = $this->personnel->secondaryEducation;
            $this->vocational = $this->personnel->vocationalEducation;
            $this->graduate = $this->personnel->graduateEducation;
            $this->graduate_studies = $this->personnel->graduateStudiesEducation;

            if ($this->personnel) {
                if($this->personnel->elementaryEducation != null)
                {
                    $this->elementary_school_name = $this->elementary->school_name;
                    $this->elementary_degree_course = $this->elementary->degree_course;
                    $this->elementary_period_from = $this->elementary->period_from;
                    $this->elementary_period_to = $this->elementary->period_to;
                    $this->elementary_highest_level_units = $this->elementary->highest_level_units;
                    $this->elementary_year_graduated = $this->elementary->year_graduated;
                    $this->elementary_scholarship_honors = $this->elementary->scholarship_honors;
                }

                if($this->personnel->secondaryEducation != null)
                {
                    $this->secondary_school_name = $this->secondary->school_name;
                    $this->secondary_degree_course = $this->secondary->degree_course;
                    $this->secondary_period_from = $this->secondary->period_from;
                    $this->secondary_period_to = $this->secondary->period_to;
                    $this->secondary_highest_level_units = $this->secondary->highest_level_units;
                    $this->secondary_year_graduated = $this->secondary->year_graduated;
                    $this->secondary_scholarship_honors = $this->secondary->scholarship_honors;
                }

                if($this->personnel->vocationalEducation != null)
                {
                    $this->vocational_school_name = $this->vocational->school_name;
                    $this->vocational_degree_course = $this->vocational->degree_course;
                    $this->vocational_period_from = $this->vocational->period_from;
                    $this->vocational_period_to = $this->vocational->period_to;
                    $this->vocational_highest_level_units = $this->vocational->highest_level_units;
                    $this->vocational_year_graduated = $this->vocational->year_graduated;
                    $this->vocational_scholarship_honors = $this->vocational->scholarship_honors;
                }

                if($this->personnel->graduateEducation != null)
                {
                    $this->graduate_school_name = $this->graduate->school_name;
                    $this->graduate_degree_course = $this->graduate->degree_course;
                    $this->graduate_major = $this->graduate->major;
                    $this->graduate_minor = $this->graduate->minor;
                    $this->graduate_period_from = $this->graduate->period_from;
                    $this->graduate_period_to = $this->graduate->period_to;
                    $this->graduate_highest_level_units = $this->graduate->highest_level_units;
                    $this->graduate_year_graduated = $this->graduate->year_graduated;
                    $this->graduate_scholarship_honors = $this->graduate->scholarship_honors;
                }

                if($this->personnel->graduateStudiesEducation != null)
                {
                    $this->graduate_studies_school_name = $this->graduate_studies->school_name;
                    $this->graduate_studies_degree_course = $this->graduate_studies->degree_course;
                    $this->graduate_studies_major = $this->graduate_studies->major;
                    $this->graduate_studies_minor = $this->graduate_studies->minor;
                    $this->graduate_studies_period_from = $this->graduate_studies->period_from;
                    $this->graduate_studies_period_to = $this->graduate_studies->period_to;
                    $this->graduate_studies_highest_level_units = $this->graduate_studies->highest_level_units;
                    $this->graduate_studies_year_graduated = $this->graduate_studies->year_graduated;
                    $this->graduate_studies_scholarship_honors = $this->graduate_studies->scholarship_honors;
                }
            }
        }
    }

    public function create()
    {
        $this->storeMode = true;
        $this->showMode = false;
        $this->updateMode = false;
    }

    public function cancel()
    {
        $this->updateMode = true;
        $this->storeMode = false;
        $this->showMode = false;
        if($this->updateMode)
        {
            $this->updateMode = false;
            $this->storeMode = false;
            $this->showMode = true;
        } else {
            $this->updateMode = false;
            $this->storeMode = false;
            $this->showMode = false;
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

    public function back()
    {
        $this->updateMode = false;
        $this->storeMode = false;
        $this->showMode = true;
        return redirect()->back()->with('message', 'Back.');
    }

    public function edit()
    {
        $this->updateMode = true;
        $this->storeMode = false;
        $this->showMode = false;
    }

    public function save()
    {
        $this->validate();

        try {
           if($this->elementary){
                $this->personnel->elementaryEducation()->update(
                [
                    'personnel_id' => $this->personnel->id,
                    'type' => 'elementary',
                    'school_name' => $this->elementary_school_name,
                    'degree_course' => $this->elementary_degree_course,
                    'period_from' => $this->elementary_period_from,
                    'period_to' => $this->elementary_period_to,
                    'highest_level_units' => $this->elementary_highest_level_units,
                    'year_graduated' => $this->elementary_year_graduated,
                    'scholarship_honors' => $this->elementary_scholarship_honors,
                ]);
           } else {
                $this->personnel->elementaryEducation()->create(
                [
                    'personnel_id' => $this->personnel->id,
                    'type' => 'elementary',
                    'school_name' => $this->elementary_school_name,
                    'degree_course' => $this->elementary_degree_course,
                    'period_from' => $this->elementary_period_from,
                    'period_to' => $this->elementary_period_to,
                    'highest_level_units' => $this->elementary_highest_level_units,
                    'year_graduated' => $this->elementary_year_graduated,
                    'scholarship_honors' => $this->elementary_scholarship_honors,
                ]);
           }

           if($this->secondary){
                $this->personnel->secondaryEducation()->update([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'secondary',
                    'school_name' => $this->secondary_school_name,
                    'degree_course' => $this->secondary_degree_course,
                    'period_from' => $this->secondary_period_from,
                    'period_to' => $this->secondary_period_to,
                    'highest_level_units' => $this->secondary_highest_level_units,
                    'year_graduated' => $this->secondary_year_graduated,
                    'scholarship_honors' => $this->secondary_scholarship_honors,
                ]);
            } else {
                $this->personnel->secondaryEducation()->create([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'secondary',
                    'school_name' => $this->secondary_school_name,
                    'degree_course' => $this->secondary_degree_course,
                    'period_from' => $this->secondary_period_from,
                    'period_to' => $this->secondary_period_to,
                    'highest_level_units' => $this->secondary_highest_level_units,
                    'year_graduated' => $this->secondary_year_graduated,
                    'scholarship_honors' => $this->secondary_scholarship_honors,
                ]);
            }

            if($this->vocational){
                $this->personnel->vocationalEducation()->update([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'vocational/trade',
                    'school_name' => $this->vocational_school_name,
                    'degree_course' => $this->vocational_degree_course,
                    'period_from' => $this->vocational_period_from,
                    'period_to' => $this->vocational_period_to,
                    'highest_level_units' => $this->vocational_highest_level_units,
                    'year_graduated' => $this->vocational_year_graduated,
                    'scholarship_honors' => $this->vocational_scholarship_honors,
                ]);
            } else {
                $this->personnel->vocationalEducation()->create([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'vocational/trade',
                    'school_name' => $this->vocational_school_name,
                    'degree_course' => $this->vocational_degree_course,
                    'period_from' => $this->vocational_period_from,
                    'period_to' => $this->vocational_period_to,
                    'highest_level_units' => $this->vocational_highest_level_units,
                    'year_graduated' => $this->vocational_year_graduated,
                    'scholarship_honors' => $this->vocational_scholarship_honors,
                ]);
            }
            if($this->graduate){
                $this->personnel->graduateEducation()->update([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'graduate',
                    'school_name' => $this->graduate_school_name,
                    'degree_course' => $this->graduate_degree_course,
                    'major' => $this->graduate_major,
                    'minor' => $this->graduate_minor,
                    'period_from' => $this->graduate_period_from,
                    'period_to' => $this->graduate_period_to,
                    'highest_level_units' => $this->graduate_highest_level_units,
                    'year_graduated' => $this->graduate_year_graduated,
                    'scholarship_honors' => $this->graduate_scholarship_honors,
                ]);
            } else {
                $this->personnel->graduateEducation()->create([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'graduate',
                    'school_name' => $this->graduate_school_name,
                    'degree_course' => $this->graduate_degree_course,
                    'major' => $this->graduate_major,
                    'minor' => $this->graduate_minor,
                    'period_from' => $this->graduate_period_from,
                    'period_to' => $this->graduate_period_to,
                    'highest_level_units' => $this->graduate_highest_level_units,
                    'year_graduated' => $this->graduate_year_graduated,
                    'scholarship_honors' => $this->graduate_scholarship_honors,
                ]);
            }

            if($this->graduate_studies){
                $this->personnel->graduateEducation()->update([
                    'type' => 'graduate studies',
                    'school_name' => $this->graduate_studies_school_name,
                    'degree_course' => $this->graduate_studies_degree_course,
                    'major' => $this->graduate_studies_major,
                    'minor' => $this->graduate_studies_minor,
                    'period_from' => $this->graduate_studies_period_from,
                    'period_to' => $this->graduate_studies_period_to,
                    'highest_level_units' => $this->graduate_studies_highest_level_units,
                    'year_graduated' => $this->graduate_studies_year_graduated,
                    'scholarship_honors' => $this->graduate_studies_scholarship_honors,
                ]);
            } else {
                $this->personnel->graduateStudiesEducation()->create([
                    'personnel_id' => $this->personnel->id,
                    'type' => 'graduate studies',
                    'school_name' => $this->graduate_studies_school_name,
                    'degree_course' => $this->graduate_studies_degree_course,
                    'major' => $this->graduate_studies_major,
                    'minor' => $this->graduate_studies_minor,
                    'period_from' => $this->graduate_studies_period_from,
                    'period_to' => $this->graduate_studies_period_to,
                    'highest_level_units' => $this->graduate_studies_highest_level_units,
                    'year_graduated' => $this->graduate_studies_year_graduated,
                    'scholarship_honors' => $this->graduate_studies_scholarship_honors,
                ]);
            }

            $this->updateMode = false;
            $this->showMode = true;

            session()->flash('flash.banner', 'Education saved successfully');
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
        } catch (\Exception $ex) {
            session()->flash('error', 'Something went wrong: ' . $ex->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.form.education-form');
    }
}
