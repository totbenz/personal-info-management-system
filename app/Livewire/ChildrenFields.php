<?php

namespace App\Livewire;

use App\Models\Personnel;
use App\Livewire\Form\FamilyForm;
use Livewire\Component;

class ChildrenFields extends Component
{
    public $new_children = [[]], $personnel;
    public $children = [];

    public function mount($id)
    {
        $this->personnel = Personnel::findOrFail($id);

        if ($this->personnel->children) {
            foreach ($this->personnel->children as $child) {
                $this->children[] = [
                    'first_name' => $child->first_name,
                    'middle_name' => $child->middle_name,
                    'last_name' => $child->last_name,
                    'name_ext' => $child->name_ext,
                    'date_of_birth' => $child->date_of_birth,
                ];
            }
        }
    }

    public function addNewField()
    {
        $this->new_children[] = [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'name_ext' => '',
            'date_of_birth' => ''
        ];
    }

    public function removeField($index)
    {
        array_splice($this->new_childrens, $index, 1);
    }

    public function render()
    {
        return view('livewire.children-fields');
    }
}
