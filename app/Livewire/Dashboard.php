<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Personnel;
use App\Models\School;
use App\Models\District;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard');
    }
}
