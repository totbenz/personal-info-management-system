<?php

namespace App\Livewire\Form;

use App\Models\Education;
use App\Models\Personnel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EducationForm extends Component
{
    public $personnel, $elementary, $secondary, $vocational, $graduate, $graduate_studies;
    public $elementary_school_name, $elementary_degree_course, $elementary_period_from, $elementary_period_to, $elementary_highest_level_units, $elementary_year_graduated, $elementary_scholarship_honors;
    public $secondary_school_name, $secondary_degree_course, $secondary_period_from, $secondary_period_to, $secondary_highest_level_units, $secondary_year_graduated, $secondary_scholarship_honors;
    public $vocational_school_name, $vocational_degree_course, $vocational_period_from, $vocational_period_to, $vocational_highest_level_units, $vocational_year_graduated, $vocational_scholarship_honors;
    public $graduate_school_name, $graduate_degree_course, $graduate_major, $graduate_minor, $graduate_period_from, $graduate_period_to, $graduate_highest_level_units, $graduate_year_graduated, $graduate_scholarship_honors;
    public $graduate_studies_school_name, $graduate_studies_degree_course, $graduate_studies_major, $graduate_studies_minor, $graduate_studies_period_from, $graduate_studies_period_to, $graduate_studies_highest_level_units, $graduate_studies_year_graduated, $graduate_studies_scholarship_honors;

    public $showMode = false, $storeMode = false, $updateMode = false;

    protected $rules = [
        // Elementary
        'elementary_school_name' => 'required|string|max:255',
        'elementary_degree_course' => 'nullable|string|max:255',
        'elementary_period_from' => 'required|integer|min:1900|max:2100',
        'elementary_period_to' => 'required|integer|min:1900|max:2100|gte:elementary_period_from',
        'elementary_highest_level_units' => 'nullable|string|max:255',
        'elementary_year_graduated' => 'required|integer|min:1900|max:2100',
        'elementary_scholarship_honors' => 'nullable|string|max:255',

        // Secondary
        'secondary_school_name' => 'required|string|max:255',
        'secondary_degree_course' => 'nullable|string|max:255',
        'secondary_period_from' => 'required|integer|min:1900|max:2100',
        'secondary_period_to' => 'required|integer|min:1900|max:2100|gte:secondary_period_from',
        'secondary_highest_level_units' => 'nullable|string|max:255',
        'secondary_year_graduated' => 'required|integer|min:1900|max:2100',
        'secondary_scholarship_honors' => 'nullable|string|max:255',

        // Vocational
        'vocational_school_name' => 'nullable|string|max:255',
        'vocational_degree_course' => 'nullable|string|max:255',
        'vocational_period_from' => 'nullable|integer|min:1900|max:2100',
        'vocational_period_to' => 'nullable|integer|min:1900|max:2100|gte:vocational_period_from',
        'vocational_highest_level_units' => 'nullable|string|max:255',
        'vocational_year_graduated' => 'nullable|integer|min:1900|max:2100',
        'vocational_scholarship_honors' => 'nullable|string|max:255',

        // Graduate
        'graduate_school_name' => 'nullable|string|max:255',
        'graduate_degree_course' => 'nullable|string|max:255',
        'graduate_major' => 'nullable|string|max:255',
        'graduate_minor' => 'nullable|string|max:255',
        'graduate_period_from' => 'nullable|integer|min:1900|max:2100',
        'graduate_period_to' => 'nullable|integer|min:1900|max:2100|gte:graduate_period_from',
        'graduate_highest_level_units' => 'nullable|string|max:255',
        'graduate_year_graduated' => 'nullable|integer|min:1900|max:2100',
        'graduate_scholarship_honors' => 'nullable|string|max:255',

        // Graduate Studies
        'graduate_studies_school_name' => 'nullable|string|max:255',
        'graduate_studies_degree_course' => 'nullable|string|max:255',
        'graduate_studies_major' => 'nullable|string|max:255',
        'graduate_studies_minor' => 'nullable|string|max:255',
        'graduate_studies_period_from' => 'nullable|integer|min:1900|max:2100',
        'graduate_studies_period_to' => 'nullable|integer|min:1900|max:2100|gte:graduate_studies_period_from',
        'graduate_studies_highest_level_units' => 'nullable|string|max:255',
        'graduate_studies_year_graduated' => 'nullable|integer|min:1900|max:2100',
        'graduate_studies_scholarship_honors' => 'nullable|string|max:255',
    ];

    public function mount($id = null, $showMode = true)
    {
        if ($id) {
            $this->personnel = Personnel::findOrFail($id);
            $this->updateMode = !$showMode;
            $this->showMode = $showMode;

            // Load education data
            $this->loadEducationData();
        }
    }

    /**
     * Load education data from database
     */
    public function loadEducationData()
    {
        if (!$this->personnel) {
            return;
        }

        // Refresh the relationships to get latest data
        $this->personnel->load(['educations']);

        $this->elementary = $this->personnel->elementaryEducation;
        $this->secondary = $this->personnel->secondaryEducation;
        $this->vocational = $this->personnel->vocationalEducation;
        $this->graduate = $this->personnel->graduateEducation;
        $this->graduate_studies = $this->personnel->graduateStudiesEducation;

        // Load Elementary Education
        if ($this->elementary) {
            $this->elementary_school_name = $this->elementary->school_name;
            $this->elementary_degree_course = $this->elementary->degree_course;
            $this->elementary_period_from = $this->elementary->period_from;
            $this->elementary_period_to = $this->elementary->period_to;
            $this->elementary_highest_level_units = $this->elementary->highest_level_units;
            $this->elementary_year_graduated = $this->elementary->year_graduated;
            $this->elementary_scholarship_honors = $this->elementary->scholarship_honors;
        }

        // Load Secondary Education
        if ($this->secondary) {
            $this->secondary_school_name = $this->secondary->school_name;
            $this->secondary_degree_course = $this->secondary->degree_course;
            $this->secondary_period_from = $this->secondary->period_from;
            $this->secondary_period_to = $this->secondary->period_to;
            $this->secondary_highest_level_units = $this->secondary->highest_level_units;
            $this->secondary_year_graduated = $this->secondary->year_graduated;
            $this->secondary_scholarship_honors = $this->secondary->scholarship_honors;
        }

        // Load Vocational Education
        if ($this->vocational) {
            $this->vocational_school_name = $this->vocational->school_name;
            $this->vocational_degree_course = $this->vocational->degree_course;
            $this->vocational_period_from = $this->vocational->period_from;
            $this->vocational_period_to = $this->vocational->period_to;
            $this->vocational_highest_level_units = $this->vocational->highest_level_units;
            $this->vocational_year_graduated = $this->vocational->year_graduated;
            $this->vocational_scholarship_honors = $this->vocational->scholarship_honors;
        }

        // Load Graduate Education
        if ($this->graduate) {
            $this->graduate_school_name = $this->graduate->school_name;
            $this->graduate_degree_course = $this->graduate->degree_course;
            $this->graduate_major = $this->graduate->major;
            $this->graduate_minor = $this->graduate->minor;
            $this->graduate_period_from = $this->graduate->period_from;
            $this->graduate_period_to = $this->graduate->period_to;
            $this->graduate_highest_level_units = $this->graduate->highest_level_units;
            $this->graduate_year_graduated = $this->graduate->year_graduated;
            $this->graduate_scholarship_honors = $this->graduate->scholarship_honors;
        }

        // Load Graduate Studies Education
        if ($this->graduate_studies) {
            $this->graduate_studies_school_name = $this->graduate_studies->school_name;
            $this->graduate_studies_degree_course = $this->graduate_studies->degree_course;
            $this->graduate_studies_major = $this->graduate_studies->major;
            $this->graduate_studies_minor = $this->graduate_studies->minor;
            $this->graduate_studies_period_from = $this->graduate_studies->period_from;
            $this->graduate_studies_period_to = $this->graduate_studies->period_to;
            $this->graduate_studies_highest_level_units = $this->graduate_studies->highest_level_units;
            $this->graduate_studies_year_graduated = $this->graduate_studies->year_graduated;
            $this->graduate_studies_scholarship_honors = $this->graduate_studies->scholarship_honors;
        }
    }

    public function create()
    {
        $this->storeMode = true;
        $this->showMode = false;
        $this->updateMode = false;
    }

    public function cancel()
    {
        $this->updateMode = true;
        $this->storeMode = false;
        $this->showMode = false;
        if ($this->updateMode) {
            $this->updateMode = false;
            $this->storeMode = false;
            $this->showMode = true;
        } else {
            $this->updateMode = false;
            $this->storeMode = false;
            $this->showMode = false;
        }

        // Set the active tab to education before redirecting
        session(['active_personnel_tab' => 'education']);

        if (Auth::user()->role === "teacher") {
            return redirect()->to(route('personnel.profile', ['personnel' => $this->personnel->id]) . '#education');
        } elseif (Auth::user()->role === "school_head") {
            return redirect()->to(route('school_personnels.show', ['personnel' => $this->personnel->id]) . '#education');
        } else {
            return redirect()->to(route('personnels.show', ['personnel' => $this->personnel->id]) . '#education');
        }
    }

    public function back()
    {
        $this->updateMode = false;
        $this->storeMode = false;
        $this->showMode = true;
        return redirect()->back()->with('message', 'Back.');
    }

    public function edit()
    {
        // Refresh data before entering edit mode
        $this->loadEducationData();

        $this->updateMode = true;
        $this->storeMode = false;
        $this->showMode = false;
    }

    public function save()
    {
        try {
            // Validate all required fields
            $validated = $this->validate();

            // Additional custom validation
            $this->validateCustomRules();

            // Validate period logic
            $this->validatePeriods();

            // Elementary Education
            $this->saveEducationRecord('elementary', [
                'school_name' => $this->elementary_school_name,
                'degree_course' => $this->elementary_degree_course,
                'period_from' => $this->elementary_period_from,
                'period_to' => $this->elementary_period_to,
                'highest_level_units' => $this->elementary_highest_level_units,
                'year_graduated' => $this->elementary_year_graduated,
                'scholarship_honors' => $this->elementary_scholarship_honors,
            ]);

            // Secondary Education
            $this->saveEducationRecord('secondary', [
                'school_name' => $this->secondary_school_name,
                'degree_course' => $this->secondary_degree_course,
                'period_from' => $this->secondary_period_from,
                'period_to' => $this->secondary_period_to,
                'highest_level_units' => $this->secondary_highest_level_units,
                'year_graduated' => $this->secondary_year_graduated,
                'scholarship_honors' => $this->secondary_scholarship_honors,
            ]);

            // Vocational Education (only if school name is provided)
            if ($this->vocational_school_name) {
                $this->saveEducationRecord('vocational/trade', [
                    'school_name' => $this->vocational_school_name,
                    'degree_course' => $this->vocational_degree_course,
                    'period_from' => $this->vocational_period_from,
                    'period_to' => $this->vocational_period_to,
                    'highest_level_units' => $this->vocational_highest_level_units,
                    'year_graduated' => $this->vocational_year_graduated,
                    'scholarship_honors' => $this->vocational_scholarship_honors,
                ]);
            }

            // Graduate Education (only if school name is provided)
            if ($this->graduate_school_name) {
                $this->saveEducationRecord('graduate', [
                    'school_name' => $this->graduate_school_name,
                    'degree_course' => $this->graduate_degree_course,
                    'major' => $this->graduate_major,
                    'minor' => $this->graduate_minor,
                    'period_from' => $this->graduate_period_from,
                    'period_to' => $this->graduate_period_to,
                    'highest_level_units' => $this->graduate_highest_level_units,
                    'year_graduated' => $this->graduate_year_graduated,
                    'scholarship_honors' => $this->graduate_scholarship_honors,
                ]);
            }

            // Graduate Studies (only if school name is provided)
            if ($this->graduate_studies_school_name) {
                $this->saveEducationRecord('graduate studies', [
                    'school_name' => $this->graduate_studies_school_name,
                    'degree_course' => $this->graduate_studies_degree_course,
                    'major' => $this->graduate_studies_major,
                    'minor' => $this->graduate_studies_minor,
                    'period_from' => $this->graduate_studies_period_from,
                    'period_to' => $this->graduate_studies_period_to,
                    'highest_level_units' => $this->graduate_studies_highest_level_units,
                    'year_graduated' => $this->graduate_studies_year_graduated,
                    'scholarship_honors' => $this->graduate_studies_scholarship_honors,
                ]);
            }

            // Refresh data after successful save
            $this->loadEducationData();

            // Only change mode on successful save
            $this->updateMode = false;
            $this->showMode = true;

            session()->flash('flash.banner', 'Education information saved successfully!');
            session()->flash('flash.bannerStyle', 'success');
            session(['active_personnel_tab' => 'education']);

        } catch (ValidationException $e) {
            // Handle validation errors - don't change component state
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                $errorMessages[] = ucfirst(str_replace('_', ' ', $field)) . ': ' . implode(', ', $messages);
            }

            // Use Livewire's dispatch for better error communication
            $this->dispatch('show-error-alert', message: 'Please correct the following errors: ' . implode('; ', $errorMessages));

            // Also set session flash for banner display
            session()->flash('flash.banner', 'Please correct the following errors: ' . implode('; ', $errorMessages));
            session()->flash('flash.bannerStyle', 'danger');

            // Log validation errors for debugging
            Log::info('Education form validation failed', [
                'personnel_id' => $this->personnel->id ?? 'unknown',
                'errors' => $e->errors()
            ]);

        } catch (\Exception $ex) {
            // Handle other errors - don't change component state
            Log::error('Education save error: ' . $ex->getMessage(), [
                'personnel_id' => $this->personnel->id ?? 'unknown',
                'trace' => $ex->getTraceAsString(),
                'error_type' => get_class($ex),
                'file' => $ex->getFile(),
                'line' => $ex->getLine()
            ]);

            // Provide more specific error messages based on error type
            $errorMessage = 'An error occurred while saving education information.';
            if (str_contains($ex->getMessage(), 'SQLSTATE')) {
                $errorMessage = 'Database error occurred. Please check your input and try again.';
            } elseif (str_contains($ex->getMessage(), 'permission')) {
                $errorMessage = 'Permission denied. Please contact your administrator.';
            }

            // Use Livewire's dispatch for better error communication
            $this->dispatch('show-error-alert', message: $errorMessage . ' Please try again or contact support if the problem persists.');

            // Also set session flash for banner display
            session()->flash('flash.banner', $errorMessage . ' Please try again or contact support if the problem persists.');
            session()->flash('flash.bannerStyle', 'danger');
        }
    }

    /**
     * Save or update an education record
     */
    private function saveEducationRecord($type, $data)
    {
        $data['personnel_id'] = $this->personnel->id;
        $data['type'] = $type;

        // Get the existing education record for this type
        $existingEducation = $this->personnel->educations()->where('type', $type)->first();

        if ($existingEducation) {
            // Update existing record
            $existingEducation->update($data);
        } else {
            // Create new record
            $this->personnel->educations()->create($data);
        }
    }

    /**
     * Validate custom business rules
     */
    private function validateCustomRules()
    {
        $errors = [];

        // Validate that required fields are filled for each education level
        if (empty($this->elementary_school_name)) {
            $errors['elementary_school_name'] = 'Elementary school name is required.';
        }

        if (empty($this->secondary_school_name)) {
            $errors['secondary_school_name'] = 'Secondary school name is required.';
        }

        // Validate that if vocational school is provided, other fields should also be filled
        if (!empty($this->vocational_school_name)) {
            if (empty($this->vocational_period_from) || empty($this->vocational_period_to)) {
                $errors['vocational_period'] = 'Vocational period is required when school name is provided.';
            }
        }

        // Validate that if graduate school is provided, other fields should also be filled
        if (!empty($this->graduate_school_name)) {
            if (empty($this->graduate_period_from) || empty($this->graduate_period_to)) {
                $errors['graduate_period'] = 'Graduate period is required when school name is provided.';
            }
        }

        // Validate that if graduate studies school is provided, other fields should also be filled
        if (!empty($this->graduate_studies_school_name)) {
            if (empty($this->graduate_studies_period_from) || empty($this->graduate_studies_period_to)) {
                $errors['graduate_studies_period'] = 'Graduate studies period is required when school name is provided.';
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Validate period logic
     */
    private function validatePeriods()
    {
        $errors = [];

        // Validate elementary periods
        if ($this->elementary_period_from && $this->elementary_period_to) {
            if ($this->elementary_period_from > $this->elementary_period_to) {
                $errors['elementary_period'] = 'Elementary start year cannot be after end year.';
            }
        }

        // Validate secondary periods
        if ($this->secondary_period_from && $this->secondary_period_to) {
            if ($this->secondary_period_from > $this->secondary_period_to) {
                $errors['secondary_period'] = 'Secondary start year cannot be after end year.';
            }
        }

        // Validate vocational periods
        if ($this->vocational_period_from && $this->vocational_period_to) {
            if ($this->vocational_period_from > $this->vocational_period_to) {
                $errors['vocational_period'] = 'Vocational start year cannot be after end year.';
            }
        }

        // Validate graduate periods
        if ($this->graduate_period_from && $this->graduate_period_to) {
            if ($this->graduate_period_from > $this->graduate_period_to) {
                $errors['graduate_period'] = 'Graduate start year cannot be after end year.';
            }
        }

        // Validate graduate studies periods
        if ($this->graduate_studies_period_from && $this->graduate_studies_period_to) {
            if ($this->graduate_studies_period_from > $this->graduate_studies_period_to) {
                $errors['graduate_studies_period'] = 'Graduate studies start year cannot be after end year.';
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Check if a field has validation errors
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
     * Get field validation icon
     */
    public function getFieldIcon($field)
    {
        if ($this->hasError($field)) {
            return '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
        }
        return '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
    }

    /**
     * Get field error message
     */
    public function getFieldErrorMessage($field)
    {
        if ($this->hasError($field)) {
            $errors = $this->getErrorBag()->get($field);
            return is_array($errors) ? $errors[0] : $errors;
        }
        return '';
    }

    /**
     * Check if form has any errors
     */
    public function hasAnyErrors()
    {
        return $this->getErrorBag()->any();
    }

    /**
     * Check if all required fields are filled
     */
    public function isFormValid()
    {
        // Check elementary required fields
        if (empty($this->elementary_school_name) ||
            empty($this->elementary_period_from) ||
            empty($this->elementary_period_to) ||
            empty($this->elementary_year_graduated)) {
            return false;
        }

        // Check secondary required fields
        if (empty($this->secondary_school_name) ||
            empty($this->secondary_period_from) ||
            empty($this->secondary_period_to) ||
            empty($this->secondary_year_graduated)) {
            return false;
        }

        return true;
    }

    /**
     * Get the count of missing required fields
     */
    public function getMissingFieldsCount()
    {
        $missing = 0;

        // Count missing elementary fields
        if (empty($this->elementary_school_name)) $missing++;
        if (empty($this->elementary_period_from)) $missing++;
        if (empty($this->elementary_period_to)) $missing++;
        if (empty($this->elementary_year_graduated)) $missing++;

        // Count missing secondary fields
        if (empty($this->secondary_school_name)) $missing++;
        if (empty($this->secondary_period_from)) $missing++;
        if (empty($this->secondary_period_to)) $missing++;
        if (empty($this->secondary_year_graduated)) $missing++;

        return $missing;
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
     * Real-time validation for a specific field
     */
    public function updated($field)
    {
        // Only validate the specific field being updated
        // Don't reset the entire error bag as it clears all validation errors
        try {
            $this->validateOnly($field);

            // Clear any existing error for this field if validation passes
            if (!$this->getErrorBag()->has($field)) {
                $this->dispatch('field-validated', field: $field);
            }
        } catch (ValidationException $e) {
            // Error will be automatically added to the error bag
            $this->dispatch('field-invalid', field: $field, message: $e->errors()[$field][0] ?? 'Invalid input');
        }

        // Force re-render to update the UI
        $this->dispatch('$refresh');
    }

    // Specific updated methods for required fields to ensure live updates
    public function updatedElementarySchoolName()
    {
        $this->updated('elementary_school_name');
    }

    public function updatedElementaryPeriodFrom()
    {
        $this->updated('elementary_period_from');
    }

    public function updatedElementaryPeriodTo()
    {
        $this->updated('elementary_period_to');
    }

    public function updatedElementaryYearGraduated()
    {
        $this->updated('elementary_year_graduated');
    }

    public function updatedSecondarySchoolName()
    {
        $this->updated('secondary_school_name');
    }

    public function updatedSecondaryPeriodFrom()
    {
        $this->updated('secondary_period_from');
    }

    public function updatedSecondaryPeriodTo()
    {
        $this->updated('secondary_period_to');
    }

    public function updatedSecondaryYearGraduated()
    {
        $this->updated('secondary_year_graduated');
    }

    /**
     * Check if a specific education section is complete
     */
    public function isSectionComplete($section)
    {
        switch ($section) {
            case 'elementary':
                return !empty($this->elementary_school_name) &&
                    !empty($this->elementary_period_from) &&
                    !empty($this->elementary_period_to) &&
                    !empty($this->elementary_year_graduated);

            case 'secondary':
                return !empty($this->secondary_school_name) &&
                    !empty($this->secondary_period_from) &&
                    !empty($this->secondary_period_to) &&
                    !empty($this->secondary_year_graduated);

            case 'vocational':
                return empty($this->vocational_school_name) ||
                    (!empty($this->vocational_school_name) &&
                        !empty($this->vocational_period_from) &&
                        !empty($this->vocational_period_to));

            case 'graduate':
                return empty($this->graduate_school_name) ||
                    (!empty($this->graduate_school_name) &&
                        !empty($this->graduate_period_from) &&
                        !empty($this->graduate_period_to));

            case 'graduate_studies':
                return empty($this->graduate_studies_school_name) ||
                    (!empty($this->graduate_studies_school_name) &&
                        !empty($this->graduate_studies_period_from) &&
                        !empty($this->graduate_studies_period_to));

            default:
                return false;
        }
    }

    public function render()
    {
        return view('livewire.form.education-form');
    }
}
