<?php

namespace App\Livewire\Form;

use App\Models\Family;
use App\Models\Personnel;
use Livewire\Component;
use App\Livewire\PersonnelNavigation;
use Illuminate\Support\Facades\Auth;

class FamilyForm extends PersonnelNavigation
{
    public $personnel, $father, $mother, $spouse;
    public $old_children = [], $new_children = [];
    public $fathers_first_name, $fathers_middle_name, $fathers_last_name, $fathers_name_ext;
    public $mothers_first_name, $mothers_middle_name, $mothers_last_name;
    public $spouse_first_name, $spouse_middle_name, $spouse_last_name, $spouse_name_ext;
    public $spouse_occupation, $spouse_business_name, $spouse_business_address, $spouse_tel_no;

    public $showMode = true, $updateMode = false;

    protected $rules = [
        'fathers_first_name' => 'required',
        'fathers_middle_name' => 'required',
        'fathers_last_name' => 'required',
        'fathers_name_ext' => 'nullable',
        'mothers_first_name' => 'required',
        'mothers_middle_name' => 'required',
        'mothers_last_name' => 'required',
        'spouse_first_name' => 'nullable',
        'spouse_middle_name' => 'nullable',
        'spouse_last_name' => 'nullable',
        'spouse_name_ext' => 'nullable',
        'spouse_occupation' => 'nullable',
        'spouse_business_name' => 'nullable',
        'spouse_business_address' => 'nullable',
        'spouse_tel_no' => 'nullable',
        'old_children.*.first_name' => 'required',
        'old_children.*.middle_name' => 'required',
        'old_children.*.last_name' => 'required',
        'old_children.*.name_ext' => 'nullable',
        'old_children.*.date_of_birth' => 'required|date',
        'new_children.*.first_name' => 'required',
        'new_children.*.middle_name' => 'required',
        'new_children.*.last_name' => 'required',
        'new_children.*.name_ext' => 'nullable',
        'new_children.*.date_of_birth' => 'required|date',
    ];

    public function mount($id = null, $showMode = true)
    {
        if($id) {
            $this->updateMode = !$showMode;
            $this->showMode = $showMode;
            $this->personnel = Personnel::findOrFail($id);
            $this->personnelId = $this->personnel->id;
            $this->loadFamilyData();
        }
    }

    public function edit()
    {
        $this->updateMode = true;
        $this->showMode = false;
    }

    public function back()
    {
        $this->updateMode = false;
        $this->showMode = true;
    }

    /**
     * Load family data from database
     */
    public function loadFamilyData()
    {
        if (!$this->personnel) {
            return;
        }

        $this->father = $this->personnel->father;
        $this->mother = $this->personnel->mother;
        $this->spouse = $this->personnel->spouse;
        $this->old_children = $this->personnel->children;

        if ($this->father != null) {
            $this->fathers_first_name = $this->father->first_name;
            $this->fathers_middle_name = $this->father->middle_name;
            $this->fathers_last_name = $this->father->last_name;
            $this->fathers_name_ext = $this->father->name_extension;
        }

        if ($this->mother != null) {
            $this->mothers_first_name = $this->mother->first_name;
            $this->mothers_middle_name = $this->mother->middle_name;
            $this->mothers_last_name = $this->mother->last_name;
        }

        if ($this->spouse != null) {
            $this->spouse_first_name = $this->spouse->first_name;
            $this->spouse_middle_name = $this->spouse->middle_name;
            $this->spouse_last_name = $this->spouse->last_name;
            $this->spouse_name_ext = $this->spouse->name_extension;
            $this->spouse_occupation = $this->spouse->occupation;
            $this->spouse_business_name = $this->spouse->employer_business_name;
            $this->spouse_business_address = $this->spouse->business_address;
            $this->spouse_tel_no = $this->spouse->telephone_number;
        }

        $this->old_children = $this->personnel->children()->get()->map(function($child) {
            return [
                'id' => $child->id,
                'first_name' => $child->first_name,
                'middle_name' => $child->middle_name,
                'last_name' => $child->last_name,
                'name_ext' => $child->name_extension,
                'date_of_birth' => $child->date_of_birth,
            ];
        })->toArray();
    }

    /**
     * Live validation on field changes
     */
    public function updated($field)
    {
        // Validate only the specific field that changed
        $this->validateOnly($field);

        // Force UI refresh for live updates
        $this->dispatch('$refresh');
    }

    /**
     * Check if all required fields are filled
     */
    public function isFormValid()
    {
        // Check father fields
        if (empty($this->fathers_first_name) || empty($this->fathers_middle_name) || empty($this->fathers_last_name)) {
            return false;
        }

        // Check mother fields
        if (empty($this->mothers_first_name) || empty($this->mothers_middle_name) || empty($this->mothers_last_name)) {
            return false;
        }

        // Check children fields (if any children exist)
        foreach ($this->old_children as $child) {
            if (empty($child['first_name']) || empty($child['middle_name']) || empty($child['last_name']) || empty($child['date_of_birth'])) {
                return false;
            }
        }

        foreach ($this->new_children as $child) {
            if (empty($child['first_name']) || empty($child['middle_name']) || empty($child['last_name']) || empty($child['date_of_birth'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Count missing required fields
     */
    public function getMissingFieldsCount()
    {
        $count = 0;

        // Count father fields
        if (empty($this->fathers_first_name)) $count++;
        if (empty($this->fathers_middle_name)) $count++;
        if (empty($this->fathers_last_name)) $count++;

        // Count mother fields
        if (empty($this->mothers_first_name)) $count++;
        if (empty($this->mothers_middle_name)) $count++;
        if (empty($this->mothers_last_name)) $count++;

        // Count children fields
        foreach ($this->old_children as $child) {
            if (empty($child['first_name'])) $count++;
            if (empty($child['middle_name'])) $count++;
            if (empty($child['last_name'])) $count++;
            if (empty($child['date_of_birth'])) $count++;
        }

        foreach ($this->new_children as $child) {
            if (empty($child['first_name'])) $count++;
            if (empty($child['middle_name'])) $count++;
            if (empty($child['last_name'])) $count++;
            if (empty($child['date_of_birth'])) $count++;
        }

        return $count;
    }

    /**
     * Specific field update methods for live validation
     */
    public function updatedFathersFirstName() { $this->updated('fathers_first_name'); }
    public function updatedFathersMiddleName() { $this->updated('fathers_middle_name'); }
    public function updatedFathersLastName() { $this->updated('fathers_last_name'); }
    public function updatedFathersNameExt() { $this->validateOnly('fathers_name_ext'); }
    public function updatedMothersFirstName() { $this->updated('mothers_first_name'); }
    public function updatedMothersMiddleName() { $this->updated('mothers_middle_name'); }
    public function updatedMothersLastName() { $this->updated('mothers_last_name'); }

    // Additional live validation methods for spouse fields
    public function updatedSpouseFirstName() { $this->validateOnly('spouse_first_name'); }
    public function updatedSpouseMiddleName() { $this->validateOnly('spouse_middle_name'); }
    public function updatedSpouseLastName() { $this->validateOnly('spouse_last_name'); }
    public function updatedSpouseNameExt() { $this->validateOnly('spouse_name_ext'); }
    public function updatedSpouseOccupation() { $this->validateOnly('spouse_occupation'); }
    public function updatedSpouseBusinessName() { $this->validateOnly('spouse_business_name'); }
    public function updatedSpouseBusinessAddress() { $this->validateOnly('spouse_business_address'); }
    public function updatedSpouseTelNo() { $this->validateOnly('spouse_tel_no'); }

    public function addField()
    {
        $this->new_children[] = [
            'first_name' => '',
            'middle_name' => '',
            'last_name' => '',
            'name_ext' => '',
            'date_of_birth' => ''
        ];
    }

    public function removeNewField($index)
    {
        array_splice($this->new_children, $index, 1);
    }

    public function confirmRemoveOldField($index)
    {
        try {
            $childId = $this->old_children[$index]['id'];
            $childModel = $this->personnel->children()->findOrFail($childId);
            $childModel->delete();

            unset($this->old_children[$index]);

            // Reset the array to remove gaps
            $this->old_children = array_values($this->old_children);
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to delete child information');
            session()->flash('flash.bannerStyle', 'danger');

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
    }

    public function render()
    {
        return view('livewire.form.family-form');
    }

    public function cancel()
    {
        $this->showMode = true;
        $this->updateMode = false;

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
        $this->showMode = false;
    }

    public function save()
    {
        try {
            $this->validate();

            // Save father information
            if ($this->father != null) {
                $this->personnel->father()->update([
                    'relationship' => 'father',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->fathers_first_name,
                    'middle_name' => $this->fathers_middle_name,
                    'last_name' => $this->fathers_last_name,
                    'name_extension' => $this->fathers_name_ext
                ]);
            } else {
                $this->personnel->father()->create([
                    'relationship' => 'father',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->fathers_first_name,
                    'middle_name' => $this->fathers_middle_name,
                    'last_name' => $this->fathers_last_name,
                    'name_extension' => $this->fathers_name_ext
                ]);
            }

            // Save mother information
            if ($this->mother != null) {
                $this->personnel->mother()->update([
                    'relationship' => 'mother',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->mothers_first_name,
                    'middle_name' => $this->mothers_middle_name,
                    'last_name' => $this->mothers_last_name
                ]);
            } else {
                $this->personnel->mother()->create([
                    'relationship' => 'mother',
                    'personnel_id' => $this->personnel->id,
                    'first_name' => $this->mothers_first_name,
                    'middle_name' => $this->mothers_middle_name,
                    'last_name' => $this->mothers_last_name
                ]);
            }

            // Save spouse information (only if spouse fields are filled)
            if (!empty($this->spouse_first_name) || !empty($this->spouse_middle_name) || !empty($this->spouse_last_name)) {
                if ($this->spouse != null) {
                    $this->personnel->spouse()->update([
                        'relationship' => 'spouse',
                        'personnel_id' => $this->personnel->id,
                        'first_name' => $this->spouse_first_name,
                        'middle_name' => $this->spouse_middle_name,
                        'last_name' => $this->spouse_last_name,
                        'name_extension' => $this->spouse_name_ext,
                        'occupation' => $this->spouse_occupation,
                        'employer_business_name' => $this->spouse_business_name,
                        'business_address' => $this->spouse_business_address,
                        'telephone_number' => $this->spouse_tel_no
                    ]);
                } else {
                    $this->personnel->spouse()->create([
                        'relationship' => 'spouse',
                        'personnel_id' => $this->personnel->id,
                        'first_name' => $this->spouse_first_name,
                        'middle_name' => $this->spouse_middle_name,
                        'last_name' => $this->spouse_last_name,
                        'name_extension' => $this->spouse_name_ext,
                        'occupation' => $this->spouse_occupation,
                        'employer_business_name' => $this->spouse_business_name,
                        'business_address' => $this->spouse_business_address,
                        'telephone_number' => $this->spouse_tel_no
                    ]);
                }
            }

            // Save children information
            if ($this->personnel->children()->exists()) {
                foreach ($this->old_children as $child) {
                    $this->personnel->children()->where('id', $child['id'])
                        ->update([
                            'relationship' => 'children',
                            'first_name' => $child['first_name'],
                            'middle_name' => $child['middle_name'],
                            'last_name' => $child['last_name'],
                            'name_extension' => $child['name_ext'],
                            'date_of_birth' => $child['date_of_birth'],
                        ]);
                }
            }

            if($this->new_children != null)
            {
                foreach ($this->new_children as $new_child) {
                    // Skip empty children entries
                    if (empty($new_child['first_name']) && empty($new_child['last_name'])) {
                        continue;
                    }

                    $this->personnel->children()->create([
                        'relationship' => 'children',
                        'first_name' => $new_child['first_name'],
                        'middle_name' => $new_child['middle_name'],
                        'last_name' => $new_child['last_name'],
                        'name_extension' => $new_child['name_ext'],
                        'date_of_birth' => $new_child['date_of_birth'],
                    ]);
                }
            }

            // Refresh data after successful save
            $this->loadFamilyData();

            $this->showMode = true;
            $this->updateMode = false;

            session()->flash('flash.banner', 'Family information saved successfully');
            session()->flash('flash.bannerStyle', 'success');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors - don't change component state
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }

            $this->dispatch('show-error-alert', [
                'message' => implode('; ', $errorMessages)
            ]);
            return;
        } catch (\Exception $e) {
            $this->dispatch('show-error-alert', [
                'message' => 'An error occurred while saving family information.'
            ]);
            return;
        }

        session(['active_personnel_tab' => 'family']);

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
}
