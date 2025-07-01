<?php

namespace App\Livewire\Form;

use App\Models\Personnel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CivilServiceEligibilityForm extends Component
{
    public $personnel;
    public $old_civil_services = [], $new_civil_services = [];
    public $showMode = false, $updateMode = false;

    protected $rules = [
        'old_civil_services.*.title' => 'required',
        'old_civil_services.*.rating' => 'required',
        'old_civil_services.*.date_of_exam' => 'required',
        'old_civil_services.*.place_of_exam' => 'required',
        'old_civil_services.*.license_num' => 'required',
        'old_civil_services.*.license_date_of_validity' => 'required',
        'new_civil_services.*.title' => 'required',
        'new_civil_services.*.rating' => 'required',
        'new_civil_services.*.date_of_exam' => 'required',
        'new_civil_services.*.place_of_exam' => 'required',
        'new_civil_services.*.license_num' => 'required',
        'new_civil_services.*.license_date_of_validity' => 'required'
    ];

    public function  mount($id, $showMode=true)
    {
        if($id) {
            $this->personnel = Personnel::findOrFail($id);
            $this->old_civil_services = $this->personnel->civilServiceEligibilities;

            $this->old_civil_services = $this->personnel->civilServiceEligibilities()->get()->map(function($child) {
                return [
                    'id' => $child->id,
                    'title' => $child->title,
                    'rating' => $child->rating,
                    'date_of_exam' => $child->date_of_exam,
                    'place_of_exam' => $child->place_of_exam,
                    'license_num' => $child->license_num,
                    'license_date_of_validity' => $child->license_date_of_validity,
                ];
            })->toArray();

            $this->new_civil_services[] = [
                'title' => '',
                'rating' => '',
                'date_of_exam' => '',
                'place_of_exam' => '',
                'license_num' => '',
                'license_date_of_validity' => ''
            ];
        }
    }

    public function addField()
    {
        $this->new_civil_services[] = [
            'title' => '',
            'rating' => '',
            'date_of_exam' => '',
            'place_of_exam' => '',
            'license_num' => '',
            'license_date_of_validity' => ''
        ];
    }

    public function removeNewField($index)
    {
        array_splice($this->new_civil_services, $index, 1);
    }

    public function removeOldField($index)
    {
        try {
            $civilServiceId = $this->old_civil_services[$index]['id'];
            $civilServiceModel = $this->personnel->children()->findOrFail($civilServiceId);

            // Delete the child from the database
            $civilServiceModel->delete();

            session()->flash('flash.banner', 'Civil Service Eligibility deleted successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to delete Civil Service Eligibility');
            session()->flash('flash.bannerStyle', 'success');
        }
        session(['active_personnel_tab' => 'civil_service_eligibility']);

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

    public function resetModes()
    {
        $this->updateMode = false;
        $this->showMode = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->personnel->civilServiceEligibilities()->exists()) {
            foreach ($this->old_civil_services as $civil_service) {
                $this->personnel->civilServiceEligibilities()->where('id', $civil_service['id'])
                    ->update([
                        'title' => $civil_service['title'],
                        'rating' => $civil_service['rating'],
                        'date_of_exam' => $civil_service['date_of_exam'],
                        'place_of_exam' => $civil_service['place_of_exam'],
                        'license_num' => $civil_service['license_num'],
                        'license_date_of_validity' => $civil_service['license_date_of_validity']
                    ]);
            }
        }

        if($this->new_civil_services != null)
        {
            foreach ($this->new_civil_services as $civil_service) {
                $this->personnel->civilServiceEligibilities()->create([
                    'title' => $civil_service['title'],
                    'rating' => $civil_service['rating'],
                    'date_of_exam' => $civil_service['date_of_exam'],
                    'place_of_exam' => $civil_service['place_of_exam'],
                    'license_num' => $civil_service['license_num'],
                    'license_date_of_validity' => $civil_service['license_date_of_validity']
                ]);
            }
        }

        $this->updateMode = false;
        $this->showMode = true;

        session()->flash('flash.banner', 'Civil Service Eligibility saved successfully');
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
        return view('livewire.form.civil-service-eligibility-form');
    }
}
