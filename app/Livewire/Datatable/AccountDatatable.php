<?php

namespace App\Livewire\Datatable;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AccountDatatable extends Component
{
    use WithPagination;

    public $selectedRole = null;
    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';


    public function doSort($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }

    public function render()
    {
        $accounts = User::when($this->selectedRole, function ($query) {
                        $query->where('role', $this->selectedRole);
                    })
                    ->search($this->search)
                    ->orderBy($this->sortColumn, $this->sortDirection)
                    ->paginate(10);

        return view('livewire.datatable.account-datatable', [
            'accounts' => $accounts
        ]);
    }
}
