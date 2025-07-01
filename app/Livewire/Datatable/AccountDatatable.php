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
    public $showEditModal = false;
    public $editingAccount = [
        'id' => '',
        'email' => '',
        'role' => '',
        'personnel' => [
            'personnel_id' => ''
        ]
    ];
    public $showDeleteModal = false;
    public $deleteId = null;
    public $deleteError = null;

    public function mount()
    {
        $this->resetEditingAccount();
    }

    public function resetEditingAccount()
    {
        $this->editingAccount = [
            'id' => '',
            'email' => '',
            'role' => '',
            'personnel' => [
                'personnel_id' => ''
            ]
        ];
    }

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
        $accounts = User::with('personnel')->when($this->selectedRole, function ($query) {
            $query->where('role', $this->selectedRole);
        })
            ->search($this->search)
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        return view('livewire.datatable.account-datatable', [
            'accounts' => $accounts
        ]);
    }

    public function editAccount($id)
    {
        $account = User::with('personnel')->find($id);
        $this->editingAccount = [
            'id' => $account->id,
            'email' => $account->email,
            'role' => $account->role,
            'personnel' => [
                'personnel_id' => $account->personnel->personnel_id,
                'full_name' => $account->personnel->fullName()
            ]
        ];
        $this->showEditModal = true;
    }

    public function save()
    {
        try {
            $this->validate([
                'editingAccount.email' => 'required|email',
                'editingAccount.role' => 'required|in:admin,school_head,teacher',
                'editingAccount.personnel.personnel_id' => 'required'
            ]);

            $account = User::find($this->editingAccount['id']);
            if (!$account) {
                $this->dispatch('show-error-alert', ['message' => 'Account not found.']);
                return;
            }
            $account->update([
                'email' => $this->editingAccount['email'],
                'role' => $this->editingAccount['role']
            ]);

            if ($account->personnel) {
                $account->personnel->update([
                    'personnel_id' => $this->editingAccount['personnel']['personnel_id']
                ]);
            }

            $this->showEditModal = false;
            $this->resetEditingAccount();
            $this->dispatch('show-success-alert', ['message' => 'Account updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('show-error-alert', ['message' => 'Validation failed. Please check your input.']);
        } catch (\Exception $e) {
            $this->dispatch('show-error-alert', ['message' => 'An error occurred while updating the account.']);
        }
    }

    public function setDeleteId($id)
    {
        $this->deleteId = $id;
        $this->deleteError = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteError = null;
    }

    public function deleteAccount()
    {
        $this->deleteError = null;
        try {
            $account = User::find($this->deleteId);
            if ($account) {
                $account->delete();
                $this->dispatch('show-success-alert', ['message' => 'Account deleted successfully.']);
                $this->showDeleteModal = false;
                $this->deleteId = null;
            } else {
                $this->deleteError = 'Account not found.';
                $this->dispatch('show-error-alert', ['message' => 'Account not found.']);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->deleteError = 'Cannot delete this account because it is referenced by other records.';
                $this->dispatch('show-error-alert', ['message' => 'Cannot delete this account because it is referenced by other records.']);
            } else {
                $this->deleteError = 'An error occurred while deleting the account.';
                $this->dispatch('show-error-alert', ['message' => 'An error occurred while deleting the account.']);
            }
        }
    }

    public function store()
    {
        try {
            $this->validate([
                'editingAccount.email' => 'required|email|unique:users,email',
                'editingAccount.role' => 'required|in:admin,school_head,teacher',
                'editingAccount.personnel.personnel_id' => 'required|exists:personnels,personnel_id',
            ]);

            // Find the Personnel by personnel_id
            $personnel = \App\Models\Personnel::where('personnel_id', $this->editingAccount['personnel']['personnel_id'])->first();
            if (!$personnel) {
                $this->dispatch('show-error-alert', ['message' => 'Personnel not found.']);
                return;
            }

            // Create the User and associate with Personnel
            $user = \App\Models\User::create([
                'email' => $this->editingAccount['email'],
                'role' => $this->editingAccount['role'],
                'personnel_id' => $personnel->id,
                // Set a default password or handle password input as needed
                'password' => bcrypt('password123'),
            ]);

            $this->showEditModal = false;
            $this->resetEditingAccount();
            $this->dispatch('show-success-alert', ['message' => 'Account created successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('show-error-alert', ['message' => 'Validation failed. Please check your input.']);
        } catch (\Exception $e) {
            $this->dispatch('show-error-alert', ['message' => 'An error occurred while creating the account.']);
        }
    }
}
