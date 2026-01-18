<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Personnel;
use App\Models\TeacherLeave;
use App\Models\SchoolHeadLeave;
use App\Models\NonTeachingLeave;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class AccountManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRole = '';
    public $sortColumn = 'id';
    public $sortDirection = 'asc';

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;

    // Form data
    public $accountId = null;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'teacher';
    public $personnel_id = '';
    public $personnel_name = '';
    public $personnelList = [];

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'email' => 'required|email',
        'role' => 'required|in:admin,school_head,teacher,non_teaching',
        'personnel_id' => 'required|exists:personnels,personnel_id',
        'password' => 'required|min:8|confirmed',
    ];

    protected $messages = [
        'personnel_id.exists' => 'The selected personnel ID does not exist.',
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
    ];

    public function mount()
    {
        $this->resetForm();
        $this->fetchPersonnelList();
    }

    public function fetchPersonnelList()
    {
        // Debug: Get all personnel first to check if data exists
        $this->personnelList = Personnel::orderBy('id')
            ->get(['id', 'personnel_id', 'first_name', 'last_name']);

        // If you want to filter later, uncomment the line below:
        // $this->personnelList = Personnel::whereDoesntHave('user')
        //     ->orderBy('personnel_id')
        //     ->get(['personnel_id', 'first_name', 'last_name']);
    }

    public function updatedPersonnelId()
    {
        $personnel = Personnel::where('personnel_id', $this->personnel_id)->first();
        $this->personnel_name = $personnel ? $personnel->first_name . ' ' . $personnel->last_name : '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedRole()
    {
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function resetForm()
    {
        $this->accountId = null;
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'teacher';
        $this->personnel_id = '';
        $this->personnel_name = '';
        $this->resetErrorBag();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();

        $user = User::with('personnel')->find($id);

        if ($user) {
            $this->accountId = $user->id;
            $this->email = $user->email;
            $this->role = $user->role;
            $this->personnel_id = $user->personnel->personnel_id ?? '';
            $this->personnel_name = $user->personnel->fullName() ?? '';
            $this->showEditModal = true;
        }
    }

    public function openDeleteModal($id)
    {
        $this->accountId = $id;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    public function create()
    {
        $this->validate();

        try {
            $personnel = Personnel::where('personnel_id', $this->personnel_id)->first();

            if (!$personnel) {
                $this->dispatch('showError', 'Personnel not found.');
                return;
            }

            // Check if user already exists for this personnel
            if ($personnel->user) {
                $this->dispatch('showError', 'An account already exists for this personnel.');
                return;
            }

            $user = User::create([
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'role' => $this->role,
                'personnel_id' => $personnel->id,
            ]);

            // Initialize leave balances for the new user
            $this->initializeLeaveBalances($user);

            $this->dispatch('showSuccess', 'Account created successfully.');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to create account: ' . $e->getMessage());
        }
    }

    public function update()
    {
        $rules = $this->rules;
        $rules['email'] = 'required|email|unique:users,email,' . $this->accountId;
        $rules['password'] = 'nullable|min:8|confirmed';

        $this->validate($rules);

        try {
            $user = User::find($this->accountId);

            if (!$user) {
                $this->dispatch('showError', 'Account not found.');
                return;
            }

            $updateData = [
                'email' => $this->email,
                'role' => $this->role,
            ];

            if ($this->password) {
                $updateData['password'] = bcrypt($this->password);
            }

            $user->update($updateData);

            $this->dispatch('showSuccess', 'Account updated successfully.');
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to update account: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $user = User::find($this->accountId);

            if (!$user) {
                $this->dispatch('showError', 'Account not found.');
                return;
            }

            $user->delete();

            $this->dispatch('showSuccess', 'Account deleted successfully.');
            $this->closeModal();

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->dispatch('showError', 'Cannot delete this account because it is referenced by other records.');
            } else {
                $this->dispatch('showError', 'Failed to delete account.');
            }
        } catch (\Exception $e) {
            $this->dispatch('showError', 'Failed to delete account.');
        }
    }

    /**
     * Initialize leave balances for a newly created user based on their role
     */
    private function initializeLeaveBalances($user)
    {
        $year = Carbon::now()->year;
        $personnel = $user->personnel;

        if (!$personnel) {
            return;
        }

        // Get personnel details for leave calculation
        $sex = $personnel->sex ?? null;
        $civilStatus = $personnel->civil_status ?? null;
        $soloParent = $personnel->solo_parent ?? false;
        $yearsOfService = $personnel->years_of_service ?? 0;
        $serviceCreditAvailability = $personnel->service_credit_availability ?? 0;

        switch ($user->role) {
            case 'teacher':
                // Use exact same leave types as TeacherLeaveSeeder
                $leaveTypes = [
                    'SICK LEAVE',
                    'MATERNITY LEAVE',
                    'PATERNITY LEAVE',
                    'SOLO PARENT LEAVE',
                    'STUDY LEAVE',
                    'VAWC LEAVE',
                    'REHABILITATION PRIVILEGE',
                    'SPECIAL LEAVE BENEFITS FOR WOMEN',
                    'SPECIAL EMERGENCY (CALAMITY LEAVE)',
                    'ADOPTION LEAVE',
                    'SERVICE CREDIT'
                ];

                foreach ($leaveTypes as $leaveType) {
                    TeacherLeave::firstOrCreate([
                        'teacher_id' => $personnel->id,
                        'leave_type' => $leaveType,
                        'year' => $year,
                    ], [
                        'available' => 0,
                        'used' => 0,
                        'remarks' => 'Initial balance',
                    ]);
                }
                break;

            case 'school_head':
                // Use exact same leave types as SchoolHeadLeaveSeeder
                $leaveTypes = [
                    'VACATION LEAVE',
                    'SICK LEAVE',
                    'MANDATORY FORCED LEAVE',
                    'MATERNITY LEAVE',
                    'PATERNITY LEAVE',
                    'SPECIAL PRIVILEGE LEAVE',
                    'SOLO PARENT LEAVE',
                    'STUDY LEAVE',
                    'VAWC LEAVE',
                    'REHABILITATION PRIVILEGE',
                    'SPECIAL LEAVE BENEFITS FOR WOMEN',
                    'SPECIAL EMERGENCY (CALAMITY LEAVE)',
                    'ADOPTION LEAVE'
                ];

                foreach ($leaveTypes as $leaveType) {
                    SchoolHeadLeave::firstOrCreate([
                        'school_head_id' => $personnel->id,
                        'leave_type' => $leaveType,
                        'year' => $year,
                    ], [
                        'available' => 0,
                        'used' => 0,
                        'ctos_earned' => 0,
                        'remarks' => 'Initial balance',
                    ]);
                }
                break;

            case 'non_teaching':
                // Use exact same leave types as NonTeachingLeaveSeeder
                $leaveTypes = [
                    'VACATION LEAVE',
                    'SICK LEAVE',
                    'MANDATORY FORCED LEAVE',
                    'MATERNITY LEAVE',
                    'PATERNITY LEAVE',
                    'SPECIAL PRIVILEGE LEAVE',
                    'SOLO PARENT LEAVE',
                    'STUDY LEAVE',
                    'VAWC LEAVE',
                    'REHABILITATION PRIVILEGE',
                    'SPECIAL LEAVE BENEFITS FOR WOMEN',
                    'SPECIAL EMERGENCY (CALAMITY LEAVE)',
                    'ADOPTION LEAVE'
                ];

                foreach ($leaveTypes as $leaveType) {
                    NonTeachingLeave::firstOrCreate([
                        'non_teaching_id' => $personnel->id,
                        'leave_type' => $leaveType,
                        'year' => $year,
                    ], [
                        'available' => 0,
                        'used' => 0,
                        'remarks' => 'Initial balance',
                    ]);
                }
                break;
        }
    }

    public function getAccountsProperty()
    {
        return User::with('personnel')
            ->when($this->search, function($query) {
                $query->search($this->search);
            })
            ->when($this->selectedRole, function($query) {
                $query->where('role', $this->selectedRole);
            })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.account-management', [
            'accounts' => $this->accounts,
            'personnelList' => $this->personnelList,
        ]);
    }
}
