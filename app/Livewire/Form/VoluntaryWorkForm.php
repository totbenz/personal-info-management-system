<?php

namespace App\Livewire\Form;

use App\Models\Personnel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VoluntaryWorkForm extends Component
{
    public $personnel;
    public $old_voluntary_works = [], $new_voluntary_works = [];
    public $showMode = false, $updateMode = false;

    protected $rules = [
        'old_voluntary_works.*.organization' => 'required',
        'old_voluntary_works.*.position' => 'required',
        'old_voluntary_works.*.inclusive_from' => 'required',
        'old_voluntary_works.*.inclusive_to' => 'required',
        'old_voluntary_works.*.hours' => 'required',
        'new_voluntary_works.*.organization' => 'required',
        'new_voluntary_works.*.position' => 'required',
        'new_voluntary_works.*.inclusive_from' => 'required',
        'new_voluntary_works.*.inclusive_to' => 'required',
        'new_voluntary_works.*.hours' => 'required',
    ];

    public function  mount($id, $showMode=true)
    {
        if($id) {
            $this->personnel = Personnel::findOrFail($id);
            // $this->old_voluntary_works = $this->personnel->workExperiences;

            $this->old_voluntary_works = $this->personnel->voluntaryWorks()->get()->map(function($voluntary_work) {
                return [
                    'id' => $voluntary_work->id,
                    'organization' => $voluntary_work->organization,
                    'position' => $voluntary_work->position,
                    'hours' => $voluntary_work->hours,
                    'inclusive_from' => $voluntary_work->inclusive_from,
                    'inclusive_to' => $voluntary_work->inclusive_to,
                ];
            })->toArray();

            $this->new_voluntary_works[] = [
                'organization' => '',
                'position' => '',
                'hours' => '',
                'inclusive_from' => '',
                'inclusive_to' => '',
            ];
        }
    }

    public function addField()
    {
        $this->new_voluntary_works[] = [
            'organization' => '',
            'position' => '',
            'hours' => '',
            'inclusive_from' => '',
            'inclusive_to' => '',
        ];
    }

    public function removeNewField($index)
    {
        array_splice($this->new_voluntary_works, $index, 1);
    }

    public function removeOldField($index)
    {
        try {
            $voluntaryWorkId = $this->old_voluntary_works[$index]['id'];
            $voluntaryWorkModel = $this->personnel->voluntaryWorks()->findOrFail($voluntaryWorkId);

            // Delete the child from the database
            $voluntaryWorkModel->delete();

            session()->flash('flash.banner', 'Voluntary Work deleted successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to delete Voluntary Work');
            session()->flash('flash.bannerStyle', 'danger');
        }
        session(['active_personnel_tab' => 'voluntary_work']);

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
        if ($this->personnel->voluntaryWorks()->exists()) {
            foreach ($this->old_voluntary_works as $voluntary_work) {
                $this->personnel->voluntaryWorks()->where('id', $voluntary_work['id'])
                    ->update([
                        'organization' => $voluntary_work['organization'],
                        'position' => $voluntary_work['position'],
                        'inclusive_from' => $voluntary_work['inclusive_from'],
                        'inclusive_to' => $voluntary_work['inclusive_to'],
                        'hours' => $voluntary_work['hours'],
                    ]);
            }
        }
        if($this->new_voluntary_works != null)
        {
            foreach ($this->new_voluntary_works as $voluntary_work) {
                $this->personnel->voluntaryWorks()->create([
                    'organization' => $voluntary_work['organization'],
                    'position' => $voluntary_work['position'],
                    'inclusive_from' => $voluntary_work['inclusive_from'],
                    'inclusive_to' => $voluntary_work['inclusive_to'],
                    'hours' => $voluntary_work['hours']
                ]);
            }
        }

        $this->updateMode = false;
        $this->showMode = true;

        session()->flash('flash.banner', 'Voluntary Work saved successfully');
        session()->flash('flash.bannerStyle', 'success');
        session(['active_personnel_tab' => 'voluntary_work']);

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
        return view('livewire.form.voluntary-work-form');
    }
}
