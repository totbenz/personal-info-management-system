<?php

namespace App\Livewire\Form;

use Livewire\Component;
use App\Models\Personnel;
use App\Models\PersonnelDetail;
use App\Livewire\PersonnelNavigation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Arr;

class QuestionnaireForm extends PersonnelNavigation
{
    public $personnel;
    public $consanguinity_third_degree, $consanguinity_third_degree_details, $consanguinity_fourth_degree;
    public $found_guilty_administrative_offense, $administrative_offense_details, $criminally_charged, $criminally_charged_details, $criminally_charged_date_filed, $criminally_charged_status;
    public $convicted_crime, $convicted_crime_details, $separated_from_service, $separation_details;
    public $candidate_last_year, $candidate_details, $resigned_to_campaign, $resigned_campaign_details;
    public $immigrant_status, $immigrant_country_details;
    public $member_indigenous_group, $indigenous_group_details, $person_with_disability, $disability_id_no, $solo_parent, $solo_parent_id_no;

    protected $casts = [
        'consanguinity_third_degree' => 'integer',
        'consanguinity_fourth_degree' => 'integer',
        'found_guilty_administrative_offense' => 'integer',
        'criminally_charged' => 'integer',
        'convicted_crime' => 'integer',
        'separated_from_service' => 'integer',
        'candidate_last_year' => 'integer',
        'resigned_to_campaign' => 'integer',
        'immigrant_status' => 'integer',
        'member_indigenous_group' => 'integer',
        'person_with_disability' => 'integer',
        'solo_parent' => 'integer',
    ];

    protected $rules = [
        'consanguinity_third_degree_details' => 'required_if:consanguinity_third_degree,1|required_if:consanguinity_fourth_degree,1|nullable|string|max:255',
        'administrative_offense_details' => 'required_if:found_guilty_administrative_offense,1|nullable|string|max:255',
        'criminally_charged_details' => 'required_if:criminally_charged,1|nullable|string|max:255',
        'convicted_crime_details' => 'required_if:convicted_crime,1|nullable|string|max:255',
        'separation_details' => 'required_if:separated_from_service,1|nullable|string|max:255',
        'candidate_details' => 'required_if:candidate_last_year,1|nullable|string|max:255',
        'resigned_campaign_details' => 'required_if:resigned_to_campaign,1|nullable|string|max:255',
        'immigrant_country_details' => 'required_if:immigrant_status,1|nullable|string|max:255',
        'indigenous_group_details' => 'required_if:member_indigenous_group,1|nullable|string|max:255',
        'disability_id_no' => 'required_if:person_with_disability,1|nullable|string|max:255',
        'solo_parent_id_no' => 'required_if:solo_parent,1|nullable|string|max:255',
    ];

    // Custom validation for mutual exclusivity
    public function validate($rules = null, $messages = [], $attributes = [])
    {
        $validated = parent::validate($rules, $messages, $attributes);

        // Custom validation: cannot have both third and fourth degree as YES
        if ($this->consanguinity_third_degree == 1 && $this->consanguinity_fourth_degree == 1) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'consanguinity_mutual_exclusive' => 'Both third and fourth degree consanguinity cannot be YES. If you are within the third degree, you are automatically within the fourth degree, so only select third degree as YES.'
            ]);
        }

        return $validated;
    }

    protected $messages = [
        'consanguinity_third_degree_details.required_if' => 'Please provide details for third degree consanguinity.',
        'administrative_offense_details.required_if' => 'Please provide details for administrative offense.',
        'criminally_charged_details.required_if' => 'Please provide details for criminal charge.',
        'criminally_charged_date_filed.required_if' => 'Please provide the date filed for criminal charge.',
        'criminally_charged_status.required_if' => 'Please provide the status for criminal charge.',
        'convicted_crime_details.required_if' => 'Please provide details for conviction.',
        'separation_details.required_if' => 'Please provide details for separation from service.',
        'candidate_details.required_if' => 'Please provide details for candidacy.',
        'resigned_campaign_details.required_if' => 'Please provide details for campaign resignation.',
        'immigrant_country_details.required_if' => 'Please provide the country for immigrant status.',
        'indigenous_group_details.required_if' => 'Please provide details for indigenous group.',
        'disability_id_no.required_if' => 'Please provide the disability ID number.',
        'solo_parent_id_no.required_if' => 'Please provide the solo parent ID number.',
    ];

    public function mount($id = null)
    {
        $this->personnel = Personnel::findOrFail($id);
        $personnel_detail = $this->personnel->personnelDetail;

        if($personnel_detail != null)
        {
            $this->consanguinity_third_degree = (int) $personnel_detail->consanguinity_third_degree;
            $this->consanguinity_third_degree_details = $personnel_detail->consanguinity_third_degree_details;
            $this->consanguinity_fourth_degree = (int) $personnel_detail->consanguinity_fourth_degree;
            $this->found_guilty_administrative_offense = (int) $personnel_detail->found_guilty_administrative_offense;
            $this->administrative_offense_details = $personnel_detail->administrative_offense_details;
            $this->criminally_charged = (int) $personnel_detail->criminally_charged;
            $this->criminally_charged_details = $personnel_detail->criminally_charged_details;
            $this->criminally_charged_date_filed = $personnel_detail->criminally_charged_date_filed;
            $this->criminally_charged_status = $personnel_detail->criminally_charged_status;
            $this->convicted_crime = (int) $personnel_detail->convicted_crime;
            $this->convicted_crime_details = $personnel_detail->convicted_crime_details;
            $this->separated_from_service = (int) $personnel_detail->separated_from_service;
            $this->separation_details = $personnel_detail->separation_details;
            $this->candidate_last_year = (int) $personnel_detail->candidate_last_year;
            $this->candidate_details = $personnel_detail->candidate_details;
            $this->resigned_to_campaign = (int) $personnel_detail->resigned_to_campaign;
            $this->resigned_campaign_details = $personnel_detail->resigned_campaign_details;
            $this->immigrant_status = (int) $personnel_detail->immigrant_status;
            $this->immigrant_country_details = $personnel_detail->immigrant_country_details;
            $this->member_indigenous_group = (int) $personnel_detail->member_indigenous_group;
            $this->indigenous_group_details = $personnel_detail->indigenous_group_details;
            $this->person_with_disability = (int) $personnel_detail->person_with_disability;
            $this->disability_id_no = $personnel_detail->disability_id_no;
            $this->solo_parent = (int) $personnel_detail->solo_parent;
            $this->solo_parent_id_no = $personnel_detail->solo_parent_id_no;
        } else {
            // Initialize with default values if no personnel detail exists
            $this->consanguinity_third_degree = 0;
            $this->consanguinity_fourth_degree = 0;
            $this->found_guilty_administrative_offense = 0;
            $this->criminally_charged = 0;
            $this->convicted_crime = 0;
            $this->separated_from_service = 0;
            $this->candidate_last_year = 0;
            $this->resigned_to_campaign = 0;
            $this->immigrant_status = 0;
            $this->member_indigenous_group = 0;
            $this->person_with_disability = 0;
            $this->solo_parent = 0;
        }
    }

    public function updatedConsanguinityThirdDegree($value)
    {
        if ($value == 1) {
            // If third degree is YES, fourth degree must be NO
            $this->consanguinity_fourth_degree = 0;
            $this->consanguinity_third_degree_details = null; // Clear details to force re-entry
        } else {
            $this->consanguinity_third_degree_details = null;
        }
    }

    public function updatedConsanguinityFourthDegree($value)
    {
        if ($value == 1) {
            // If fourth degree is YES, third degree must be NO
            $this->consanguinity_third_degree = 0;
            $this->consanguinity_third_degree_details = null; // Clear details to force re-entry
        } else {
            $this->consanguinity_third_degree_details = null;
        }
    }

    public function updatedFoundGuiltyAdministrativeOffense($value)
    {
        if ($value != 1) {
            $this->administrative_offense_details = null;
        }
    }

    public function updatedCriminallyChargedDateFiled($value)
    {
        \Log::info('criminally_charged_date_filed updated', ['value' => $value, 'type' => gettype($value)]);
    }

    public function updatedCriminallyChargedStatus($value)
    {
        \Log::info('criminally_charged_status updated', ['value' => $value, 'type' => gettype($value)]);
    }

    public function updatedCriminallyCharged($value)
    {
        \Log::info('criminally_charged updated', ['value' => $value, 'type' => gettype($value)]);
        if ($value != 1) {
            $this->criminally_charged_details = null;
        }
    }

    public function updatedConvictedCrime($value)
    {
        if ($value != 1) {
            $this->convicted_crime_details = null;
        }
    }

    public function updatedSeparatedFromService($value)
    {
        if ($value != 1) {
            $this->separation_details = null;
        }
    }

    public function updatedCandidateLastYear($value)
    {
        if ($value != 1) {
            $this->candidate_details = null;
        }
    }

    public function updatedResignedToCampaign($value)
    {
        if ($value != 1) {
            $this->resigned_campaign_details = null;
        }
    }

    public function updatedImmigrantStatus($value)
    {
        if ($value != 1) {
            $this->immigrant_country_details = null;
        }
    }

    public function updatedMemberIndigenousGroup($value)
    {
        if ($value != 1) {
            $this->indigenous_group_details = null;
        }
    }

    public function updatedPersonWithDisability($value)
    {
        if ($value != 1) {
            $this->disability_id_no = null;
        }
    }

    public function updatedSoloParent($value)
    {
        if ($value != 1) {
            $this->solo_parent_id_no = null;
        }
    }

    public function save()
    {
        try {
            // Log ALL form data before validation
            \Log::info('=== QUESTIONNAIRE SAVE ATTEMPT ===');
            \Log::info('All public properties:', [
                'consanguinity_third_degree' => $this->consanguinity_third_degree,
                'consanguinity_fourth_degree' => $this->consanguinity_fourth_degree,
                'consanguinity_third_degree_details' => $this->consanguinity_third_degree_details,
                'found_guilty_administrative_offense' => $this->found_guilty_administrative_offense,
                'administrative_offense_details' => $this->administrative_offense_details,
                'criminally_charged' => $this->criminally_charged,
                'criminally_charged_details' => $this->criminally_charged_details,
                'criminally_charged_date_filed' => $this->criminally_charged_date_filed,
                'criminally_charged_status' => $this->criminally_charged_status,
                'convicted_crime' => $this->convicted_crime,
                'convicted_crime_details' => $this->convicted_crime_details,
                'separated_from_service' => $this->separated_from_service,
                'separation_details' => $this->separation_details,
                'candidate_last_year' => $this->candidate_last_year,
                'candidate_details' => $this->candidate_details,
                'resigned_to_campaign' => $this->resigned_to_campaign,
                'resigned_campaign_details' => $this->resigned_campaign_details,
                'immigrant_status' => $this->immigrant_status,
                'immigrant_country_details' => $this->immigrant_country_details,
                'member_indigenous_group' => $this->member_indigenous_group,
                'indigenous_group_details' => $this->indigenous_group_details,
                'person_with_disability' => $this->person_with_disability,
                'disability_id_no' => $this->disability_id_no,
                'solo_parent' => $this->solo_parent,
                'solo_parent_id_no' => $this->solo_parent_id_no
            ]);

            // Log validation rules
            \Log::info('Validation rules:', $this->rules);

            // Attempt validation with detailed error capture
            try {
                $validated = $this->validate();
                \Log::info('VALIDATION PASSED', ['validated_data' => $validated]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                \Log::error('=== VALIDATION FAILED ===');
                \Log::error('All validation errors:', $e->errors());
                \Log::error('Failed rules:', array_keys($e->errors()));

                // Re-throw to be caught by outer catch
                throw $e;
            }

            // Prepare data for database storage
            $data = [
                'consanguinity_third_degree' => (bool) ($this->consanguinity_third_degree ?? 0),
                'consanguinity_third_degree_details' => $this->consanguinity_third_degree_details,
                'consanguinity_fourth_degree' => (bool) ($this->consanguinity_fourth_degree ?? 0),
                'found_guilty_administrative_offense' => (bool) ($this->found_guilty_administrative_offense ?? 0),
                'administrative_offense_details' => $this->administrative_offense_details,
                'criminally_charged' => (bool) ($this->criminally_charged ?? 0),
                'criminally_charged_details' => $this->criminally_charged_details,
                'convicted_crime' => (bool) ($this->convicted_crime ?? 0),
                'convicted_crime_details' => $this->convicted_crime_details,
                'separated_from_service' => (bool) ($this->separated_from_service ?? 0),
                'separation_details' => $this->separation_details,
                'candidate_last_year' => (bool) ($this->candidate_last_year ?? 0),
                'candidate_details' => $this->candidate_details,
                'resigned_to_campaign' => (bool) ($this->resigned_to_campaign ?? 0),
                'resigned_campaign_details' => $this->resigned_campaign_details,
                'immigrant_status' => (bool) ($this->immigrant_status ?? 0),
                'immigrant_country_details' => $this->immigrant_country_details,
                'member_indigenous_group' => (bool) ($this->member_indigenous_group ?? 0),
                'indigenous_group_details' => $this->indigenous_group_details,
                'person_with_disability' => (bool) ($this->person_with_disability ?? 0),
                'disability_id_no' => $this->disability_id_no,
                'solo_parent' => (bool) ($this->solo_parent ?? 0),
                'solo_parent_id_no' => $this->solo_parent_id_no
            ];

            if ($this->personnel->personnelDetail != null) {
                $this->personnel->personnelDetail->update($data);
            } else {
                $data['personnel_id'] = $this->personnel->id;
                PersonnelDetail::create($data);
            }

            // Sync high-level personnel flag for quick access (if column exists)
            try {
                if (Schema::hasColumn('personnels', 'is_solo_parent')) {
                    $this->personnel->update(['is_solo_parent' => (bool) ($data['solo_parent'] ?? false)]);
                }
            } catch (\Exception $e) {
                \Log::warning('Could not update is_solo_parent flag: ' . $e->getMessage());
            }

            session()->flash('flash.banner', 'Questionnaire saved successfully');
            session()->flash('flash.bannerStyle', 'success');
            session(['active_personnel_tab' => 'questionnaire']);

            // Exit update mode after successful save
            $this->updateMode = false;
            $this->showMode = true;

            // Redirect based on user role
            if(Auth::user()->role === "teacher")
            {
                return redirect()->route('personnel.profile');
            } elseif(Auth::user()->role === "school_head")
            {
                return redirect()->route('school_personnels.show', ['personnel' => $this->personnel->id]);
            } else {
                return redirect()->route('personnels.show', ['personnel' => $this->personnel->id]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            session()->flash('flash.banner', 'Validation failed: ' . implode(', ', Arr::flatten($e->errors())));
            session()->flash('flash.bannerStyle', 'danger');
            return;
        } catch (\Throwable $th) {
            \Log::error('Failed to save questionnaire: ' . $th->getMessage(), [
                'trace' => $th->getTraceAsString(),
                'file' => $th->getFile(),
                'line' => $th->getLine()
            ]);
            session()->flash('flash.banner', 'Failed to save Questionnaire: ' . $th->getMessage());
            session()->flash('flash.bannerStyle', 'danger');

            return; // Don't redirect if there's an error
        }
    }

    public function cancel()
    {
        // Reset to original values
        $this->mount($this->personnel->id);

        // Exit update mode
        $this->updateMode = false;
        $this->showMode = true;

        session(['active_personnel_tab' => 'questionnaire']);

        // Redirect based on user role
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

    public function render()
    {
        return view('livewire.form.questionnaire-form');
    }
}
