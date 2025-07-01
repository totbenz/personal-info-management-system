<?php

namespace App\Livewire\Form;

use Livewire\Component;
use App\Models\Personnel;
use App\Models\PersonnelDetail;
use App\Livewire\PersonnelNavigation;
use Illuminate\Support\Facades\Auth;

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
        'consanguinity_third_degree' => 'string',
        'consanguinity_fourth_degree' => 'string',
        'found_guilty_administrative_offense' => 'string',
        'criminally_charged' => 'string',
        'convicted_crime' => 'string',
        'separated_from_service' => 'string',
        'candidate_last_year' => 'string',
        'resigned_to_campaign' => 'string',
        'immigrant_status' => 'string',
        'member_indigenous_group' => 'string',
        'person_with_disability' => 'string',
        'solo_parent' => 'string',
    ];

    protected $rules = [
        'consanguinity_third_degree_details' => 'required_if:consanguinity_third_degree,1|nullable|string|max:255',
        'administrative_offense_details' => 'required_if:found_guilty_administrative_offense,1|nullable|string|max:255',
        'criminally_charged_details' => 'required_if:criminally_charged,1|nullable|string|max:255',
        'criminally_charged_date_filed' => 'required_if:criminally_charged,1|nullable|date',
        'criminally_charged_status' => 'required_if:criminally_charged,1|nullable|string|max:255',
        'convicted_crime_details' => 'required_if:convicted_crime,1|nullable|string|max:255',
        'separation_details' => 'required_if:separated_from_service,1|nullable|string|max:255',
        'candidate_details' => 'required_if:candidate_last_year,1|nullable|string|max:255',
        'resigned_campaign_details' => 'required_if:resigned_to_campaign,1|nullable|string|max:255',
        'immigrant_country_details' => 'required_if:immigrant_status,1|nullable|string|max:255',
        'indigenous_group_details' => 'required_if:member_indigenous_group,1|nullable|string|max:255',
        'disability_id_no' => 'required_if:person_with_disability,1|nullable|string|max:255',
        'solo_parent_id_no' => 'required_if:solo_parent,1|nullable|string|max:255',
    ];

    public function mount($id = null)
    {
        $this->personnel = Personnel::findOrFail($id);
        $personnel_detail = $this->personnel->personnelDetail;

        if($personnel_detail != null)
        {
            $this->consanguinity_third_degree = (string) $personnel_detail->consanguinity_third_degree;
            $this->consanguinity_third_degree_details = $personnel_detail->consanguinity_third_degree_details;
            $this->consanguinity_fourth_degree = (string) $personnel_detail->consanguinity_fourth_degree;
            // $this->consanguinity_fourth_degree_details = $personnel_detail->consanguinity_fourth_degree_details;
            $this->found_guilty_administrative_offense = (string) $personnel_detail->found_guilty_administrative_offense;
            $this->administrative_offense_details = $personnel_detail->administrative_offense_details;
            $this->criminally_charged = (string) $personnel_detail->criminally_charged;
            $this->criminally_charged_details = $personnel_detail->criminally_charged_details;
            $this->criminally_charged_date_filed = $personnel_detail->criminally_charged_date_filed;
            $this->criminally_charged_status = $personnel_detail->criminally_charged_status;
            $this->convicted_crime = (string) $personnel_detail->convicted_crime;
            $this->convicted_crime_details = $personnel_detail->convicted_crime_details;
            $this->separated_from_service = (string) $personnel_detail->separated_from_service;
            $this->separation_details = $personnel_detail->separation_details;
            $this->candidate_last_year = (string) $personnel_detail->candidate_last_year;
            $this->candidate_details = $personnel_detail->candidate_details;
            $this->resigned_to_campaign = (string) $personnel_detail->resigned_to_campaign;
            $this->resigned_campaign_details = $personnel_detail->resigned_campaign_details;
            $this->immigrant_status = (string) $personnel_detail->immigrant_status;
            $this->immigrant_country_details = $personnel_detail->immigrant_country_details;
            $this->member_indigenous_group = (string) $personnel_detail->member_indigenous_group;
            $this->indigenous_group_details = $personnel_detail->indigenous_group_details;
            $this->person_with_disability = (string) $personnel_detail->person_with_disability;
            $this->disability_id_no = $personnel_detail->disability_id_no;
            $this->solo_parent = (string) $personnel_detail->solo_parent;
            $this->solo_parent_id_no = $personnel_detail->solo_parent_id_no;
        } else {
            // Initialize with default values if no personnel detail exists
            $this->consanguinity_third_degree = '0';
            $this->consanguinity_fourth_degree = '0';
            $this->found_guilty_administrative_offense = '0';
            $this->criminally_charged = '0';
            $this->convicted_crime = '0';
            $this->separated_from_service = '0';
            $this->candidate_last_year = '0';
            $this->resigned_to_campaign = '0';
            $this->immigrant_status = '0';
            $this->member_indigenous_group = '0';
            $this->person_with_disability = '0';
            $this->solo_parent = '0';
        }
    }

    public function updatedConsanguinityThirdDegree($value)
    {
        if ($value != '1') {
            $this->consanguinity_third_degree_details = null;
        }
    }

    public function updatedConsanguinityFourthDegree($value)
    {
        if ($value != '1') {
            $this->consanguinity_third_degree_details = null;
        }
    }

    public function updatedFoundGuiltyAdministrativeOffense($value)
    {
        if ($value != '1') {
            $this->administrative_offense_details = null;
        }
    }

    public function updatedCriminallyCharged($value)
    {
        if ($value != '1') {
            $this->criminally_charged_details = null;
        }
    }

    public function updatedConvictedCrime($value)
    {
        if ($value != '1') {
            $this->convicted_crime_details = null;
        }
    }

    public function updatedSeparatedFromService($value)
    {
        if ($value != '1') {
            $this->separation_details = null;
        }
    }

    public function updatedCandidateLastYear($value)
    {
        if ($value != '1') {
            $this->candidate_details = null;
        }
    }

    public function updatedResignedToCampaign($value)
    {
        if ($value != '1') {
            $this->resigned_campaign_details = null;
        }
    }

    public function updatedImmigrantStatus($value)
    {
        if ($value != '1') {
            $this->immigrant_country_details = null;
        }
    }

    public function updatedMemberIndigenousGroup($value)
    {
        if ($value != '1') {
            $this->indigenous_group_details = null;
        }
    }

    public function updatedPersonWithDisability($value)
    {
        if ($value != '1') {
            $this->disability_id_no = null;
        }
    }

    public function updatedSoloParent($value)
    {
        if ($value != '1') {
            $this->solo_parent_id_no = null;
        }
    }

    public function save()
    {
        $this->validate();

        // Convert string values to integers for database storage
        $data = [
            'consanguinity_third_degree' => $this->consanguinity_third_degree ? (int) $this->consanguinity_third_degree : 0,
            'consanguinity_third_degree_details' => $this->consanguinity_third_degree_details,
            'consanguinity_fourth_degree' => $this->consanguinity_fourth_degree ? (int) $this->consanguinity_fourth_degree : 0,
            'found_guilty_administrative_offense' => $this->found_guilty_administrative_offense ? (int) $this->found_guilty_administrative_offense : 0,
            'administrative_offense_details' => $this->administrative_offense_details,
            'criminally_charged' => $this->criminally_charged ? (int) $this->criminally_charged : 0,
            'criminally_charged_details' => $this->criminally_charged_details,
            'criminally_charged_date_filed' => $this->criminally_charged_date_filed,
            'criminally_charged_status' => $this->criminally_charged_status,
            'convicted_crime' => $this->convicted_crime ? (int) $this->convicted_crime : 0,
            'convicted_crime_details' => $this->convicted_crime_details,
            'separated_from_service' => $this->separated_from_service ? (int) $this->separated_from_service : 0,
            'separation_details' => $this->separation_details,
            'candidate_last_year' => $this->candidate_last_year ? (int) $this->candidate_last_year : 0,
            'candidate_details' => $this->candidate_details,
            'resigned_to_campaign' => $this->resigned_to_campaign ? (int) $this->resigned_to_campaign : 0,
            'resigned_campaign_details' => $this->resigned_campaign_details,
            'immigrant_status' => $this->immigrant_status ? (int) $this->immigrant_status : 0,
            'immigrant_country_details' => $this->immigrant_country_details,
            'member_indigenous_group' => $this->member_indigenous_group ? (int) $this->member_indigenous_group : 0,
            'indigenous_group_details' => $this->indigenous_group_details,
            'person_with_disability' => $this->person_with_disability ? (int) $this->person_with_disability : 0,
            'disability_id_no' => $this->disability_id_no,
            'solo_parent' => $this->solo_parent ? (int) $this->solo_parent : 0,
            'solo_parent_id_no' => $this->solo_parent_id_no
        ];

        try {
            if ($this->personnel->personnelDetail != null) {
                $this->personnel->personnelDetail->update($data);
            } else {
                $data['personnel_id'] = $this->personnel->id;
                PersonnelDetail::create($data);
            }

            session()->flash('flash.banner', 'Questionnaire saved successfully');
            session()->flash('flash.bannerStyle', 'save');
        } catch (\Throwable $th) {
            \Log::error('Failed to save questionnaire: ' . $th->getMessage());
            session()->flash('flash.banner', 'Failed to save Questionnaire: ' . $th->getMessage());
            session()->flash('flash.bannerStyle', 'danger');
            return; // Don't redirect if there's an error
        }

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

    public function cancel()
    {
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
