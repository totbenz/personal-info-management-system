<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PageManager extends Component
{
    public $page = 'admin-home'; 

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.page-manager');
    }
}

