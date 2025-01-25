<?php

namespace App\Livewire\Form\Input;

use App\Models\School;
use Livewire\Component;

class SchoolSearchSelectTag extends Component
{
    public $schools;
    public $search = '';

    // public function mount()
    // {
    //     // Fetch school IDs from the database
    //     $this->schools = School::all();
    // }

    // public function render()
    // {
    //     // Filter schools by search term
    //     $schools = School::where('school_name', 'like', '%' . $this->search . '%')->get();

    //     return view('livewire.form.input.school-search-select-tag', [
    //         'schools' => $schools,
    //     ]);
    // }
    // public $search = '';

    public function updatedSearch($value)
    {
        $this->emit('searchUpdated', $value);
    }

    public function render()
    {
        return view('livewire.form.input.school-search-select-tag');
    }
}
