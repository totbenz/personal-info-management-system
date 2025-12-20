<?php

namespace App\Livewire\Form;

use App\Models\Personnel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReferencesForm extends Component
{
    public $personnel;
    public $old_references = [], $new_references = [];
    public $showMode = false, $updateMode = false;

    protected $rules = [
        'old_references.*.full_name' => 'required|string|max:255',
        'old_references.*.address' => 'required|string|max:500',
        'old_references.*.tel_no' => 'required|string|max:20',
        'new_references.*.full_name' => 'nullable|string|max:255',
        'new_references.*.address' => 'nullable|string|max:500',
        'new_references.*.tel_no' => 'nullable|string|max:20'
    ];

    protected $messages = [
        'old_references.*.full_name.required' => 'Reference full name is required.',
        'old_references.*.address.required' => 'Reference address is required.',
        'old_references.*.tel_no.required' => 'Reference phone number is required.',
        'new_references.*.full_name.max' => 'Reference full name cannot exceed 255 characters.',
        'new_references.*.address.max' => 'Reference address cannot exceed 500 characters.',
        'new_references.*.tel_no.max' => 'Reference phone number cannot exceed 20 characters.',
    ];

    public function  mount($id, $showMode = true)
    {
        if ($id) {
            $this->personnel = Personnel::findOrFail($id);
            $this->old_references = $this->personnel->references()->get()->map(function ($reference) {
                return [
                    'id' => $reference->id,
                    'full_name' => $reference->full_name,
                    'address' => $reference->address,
                    'tel_no' => $reference->tel_no
                ];
            })->toArray();

            // Auto-add field only if no existing entries
            if (empty($this->old_references)) {
                $this->new_references[] = [
                    'full_name' => '',
                    'address' => '',
                    'tel_no' => ''
                ];
            } else {
                $this->new_references = [];
            }
        }
    }

    public function addField()
    {
        // Check total references count (old + new)
        $totalReferences = count($this->old_references) + count($this->new_references);

        // Only add if the last field is not empty and total is less than 3
        if (
            $totalReferences < 3 &&
            (empty($this->new_references) ||
                (!empty($this->new_references[count($this->new_references) - 1]['full_name']) &&
                    !empty($this->new_references[count($this->new_references) - 1]['address'])))
        ) {
            $this->new_references[] = [
                'full_name' => '',
                'address' => '',
                'tel_no' => ''
            ];
        } elseif ($totalReferences >= 3) {
            session()->flash('flash.banner', 'Maximum of 3 references allowed.');
            session()->flash('flash.bannerStyle', 'warning');
        } else {
            session()->flash('flash.banner', 'Please fill in the current reference fields before adding a new one.');
            session()->flash('flash.bannerStyle', 'warning');
        }
    }

    public function removeNewField($index)
    {
        array_splice($this->new_references, $index, 1);
    }

    public function removeOldField($index)
    {
        try {
            $referenceId = $this->old_references[$index]['id'];
            $referenceModel = $this->personnel->references()->findOrFail($referenceId);

            // Delete the reference from the database
            $referenceModel->delete();

            // Remove from the array
            unset($this->old_references[$index]);
            $this->old_references = array_values($this->old_references);

            session()->flash('flash.banner', 'Reference deleted successfully!');
            session()->flash('flash.bannerStyle', 'success');
            session(['active_personnel_tab' => 'references']);

            if (Auth::user()->role === "teacher") {
                return redirect()->route('personnel.profile');
            } elseif (Auth::user()->role === "school_head") {
                return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
            } else {
                return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
            }
        } catch (\Throwable $th) {
            Log::error('Failed to delete reference: ' . $th->getMessage(), [
                'personnel_id' => $this->personnel->id ?? 'unknown',
                'reference_id' => $referenceId ?? 'unknown',
                'trace' => $th->getTraceAsString()
            ]);

            session()->flash('flash.banner', 'Failed to delete reference. Please try again.');
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    public function edit()
    {
        $this->updateMode = true;
        $this->showMode = false;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->showMode = true;
        if (Auth::user()->role === "teacher") {
            return redirect()->route('personnel.profile');
        } elseif (Auth::user()->role === "school_head") {
            return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
        } else {
            return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
        }
    }

    public function back()
    {
        $this->updateMode = false;
        $this->showMode = true;
        return redirect()->back()->with('message', 'Back to previous page.');
    }

    public function save()
    {
        try {
            // Check total references count
            $totalReferences = count($this->old_references) + count(array_filter($this->new_references, function($ref) {
                return !empty($ref['full_name']) || !empty($ref['address']) || !empty($ref['tel_no']);
            }));

            if ($totalReferences > 3) {
                session()->flash('flash.banner', 'Maximum of 3 references allowed.');
                session()->flash('flash.bannerStyle', 'danger');
                return;
            }

            $this->validate();

            if ($this->personnel->references()->exists()) {
                foreach ($this->old_references as $reference) {
                    $this->personnel->references()->where('id', $reference['id'])
                        ->update([
                            'full_name' => $reference['full_name'],
                            'address' => $reference['address'],
                            'tel_no' => $reference['tel_no']
                        ]);
                }
            }

            if ($this->new_references != null) {
                foreach ($this->new_references as $reference) {
                    if (!empty($reference['full_name']) && !empty($reference['address'])) {
                        $this->personnel->references()->create([
                            'full_name' => $reference['full_name'],
                            'address' => $reference['address'],
                            'tel_no' => $reference['tel_no']
                        ]);
                    }
                }
            }

            $this->updateMode = false;
            $this->showMode = true;

            session()->flash('flash.banner', 'References saved successfully!');
            session()->flash('flash.bannerStyle', 'success');
            session(['active_personnel_tab' => 'references']);

            if (Auth::user()->role === "teacher") {
                return redirect()->route('personnel.profile');
            } elseif (Auth::user()->role === "school_head") {
                return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
            } else {
                return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('flash.banner', 'Please correct the validation errors.');
            session()->flash('flash.bannerStyle', 'danger');
        } catch (\Exception $e) {
            Log::error('References save error: ' . $e->getMessage(), [
                'personnel_id' => $this->personnel->id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('flash.banner', 'An error occurred while saving references. Please try again.');
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /**
     * Check if a reference field has validation errors
     */
    public function hasError($field)
    {
        return $this->getErrorBag()->has($field);
    }

    /**
     * Get field validation class
     */
    public function getFieldClass($field)
    {
        if ($this->hasError($field)) {
            return 'border-red-500 focus:border-red-500 focus:ring-red-500';
        }
        return 'border-gray-300 focus:border-blue-500 focus:ring-blue-500';
    }

    /**
     * Check if form has any errors
     */
    public function hasAnyErrors()
    {
        return $this->getErrorBag()->any();
    }

    /**
     * Get all error messages as a formatted string
     */
    public function getAllErrorMessages()
    {
        $errorMessages = [];
        foreach ($this->getErrorBag()->getMessages() as $field => $messages) {
            $errorMessages[] = ucfirst(str_replace('_', ' ', $field)) . ': ' . implode(', ', $messages);
        }
        return implode('; ', $errorMessages);
    }

    /**
     * Check if the form is ready to save
     */
    public function isFormReady()
    {
        // Check if old references are valid
        foreach ($this->old_references as $reference) {
            if (empty($reference['full_name']) || empty($reference['address']) || empty($reference['tel_no'])) {
                return false;
            }
        }

        // Check if new references are valid (if any are filled)
        foreach ($this->new_references as $reference) {
            if (!empty($reference['full_name']) || !empty($reference['address']) || !empty($reference['tel_no'])) {
                if (empty($reference['full_name']) || empty($reference['address'])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Real-time validation for a specific field
     */
    public function updated($field)
    {
        $this->resetErrorBag();

        // Validate only the updated field
        try {
            $this->validateOnly($field);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Error will be automatically added to the error bag
        }
    }

    public function render()
    {
        return view('livewire.form.references-form');
    }
}
