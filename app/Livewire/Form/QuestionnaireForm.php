<?php

namespace App\Livewire\Form;

use Livewire\Component;
use App\Models\Personnel;
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
    public $member_indigenous_group, $indigenous_group_details, $person_with_disability, $disability_id_no, $solo_parent;

    protected $rules = [
        'consanguinity_third_degree_details' => 'required_if:consanguinity_third_degree,1',
        'administrative_offense_details' => 'required_if:found_guilty_administrative_offense,1',
        'criminally_charged_details' => 'required_if:criminally_charged,1',
        'criminally_charged_date_filed' => 'required_if:criminally_charged,1',
        'criminally_charged_status' => 'required_if:criminally_charged,1',
        'convicted_crime_details' => 'required_if:convicted_crime,1',
        'separation_details' => 'required_if:separated_from_service,1',
        'candidate_details' => 'required_if:candidate_last_year,1',
        'resigned_campaign_details' => 'required_if:resigned_to_campaign,1',
        'immigrant_country_details' => 'required_if:immigrant_status,1',
        'indigenous_group_details' => 'required_if:member_indigenous_group,1',
        'disability_id_no' => 'required_if:person_with_disability,1',
        'solo_parent' => 'required',
    ];

    public function mount($id = null)
    {
        $this->personnel = Personnel::findOrFail($id);
        $personnel_detail = $this->personnel->personnelDetail;

        if($personnel_detail != null)
        {
            $this->consanguinity_third_degree = $personnel_detail->consanguinity_third_degree;
            $this->consanguinity_third_degree_details = $personnel_detail->consanguinity_third_degree_details;
            $this->consanguinity_fourth_degree = $personnel_detail->consanguinity_fourth_degree;
            // $this->consanguinity_fourth_degree_details = $personnel_detail->consanguinity_fourth_degree_details;
            $this->found_guilty_administrative_offense = $personnel_detail->found_guilty_administrative_offense;
            $this->administrative_offense_details = $personnel_detail->administrative_offense_details;
            $this->criminally_charged = $personnel_detail->criminally_charged;
            $this->criminally_charged_details = $personnel_detail->criminally_charged_details;
            $this->criminally_charged_date_filed = $personnel_detail->criminally_charged_date_filed;
            $this->criminally_charged_status = $personnel_detail->criminally_charged_status;
            $this->convicted_crime = $personnel_detail->convicted_crime;
            $this->convicted_crime_details = $personnel_detail->convicted_crime_details;
            $this->separated_from_service = $personnel_detail->separated_from_service;
            $this->separation_details = $personnel_detail->separation_details;
            $this->candidate_last_year = $personnel_detail->candidate_last_year;
            $this->candidate_details = $personnel_detail->candidate_details;
            $this->resigned_to_campaign = $personnel_detail->resigned_to_campaign;
            $this->resigned_campaign_details = $personnel_detail->resigned_campaign_details;
            $this->immigrant_status = $personnel_detail->immigrant_status;
            $this->immigrant_country_details = $personnel_detail->immigrant_country_details;
            $this->member_indigenous_group = $personnel_detail->member_indigenous_group;
            $this->indigenous_group_details = $personnel_detail->indigenous_group_details;
            $this->person_with_disability = $personnel_detail->person_with_disability;
            $this->disability_id_no = $personnel_detail->disability_id_no;
            $this->solo_parent = $personnel_detail->solo_parent;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->personnelDetail != null) {
                $this->personnel->personnelDetail()->update([
                    'consanguinity_third_degree' => $this->consanguinity_third_degree,
                    'consanguinity_third_degree_details' => $this->consanguinity_third_degree_details,
                    'consanguinity_fourth_degree' => $this->consanguinity_fourth_degree,
                    'found_guilty_administrative_offense' => $this->found_guilty_administrative_offense,
                    'administrative_offense_details' => $this->administrative_offense_details,
                    'criminally_charged' => $this->criminally_charged,
                    'criminally_charged_details' => $this->personnel->criminally_charged_details,
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
                    'immigrant_country_details' => $this->immigrant_country_details,
                    'disability_id_no' => $this->disability_id_no,
                    'solo_parent' => $this->solo_parent
                ]);
            } else {
                $this->personnel->personnelDetail()->create([
                    'consanguinity_third_degree' => $this->consanguinity_third_degree,
                    'consanguinity_third_degree_details' => $this->consanguinity_third_degree_details,
                    'consanguinity_fourth_degree' => $this->consanguinity_fourth_degree,
                    'found_guilty_administrative_offense' => $this->found_guilty_administrative_offense,
                    'administrative_offense_details' => $this->administrative_offense_details,
                    'criminally_charged' => $this->criminally_charged,
                    'criminally_charged_details' => $this->personnel->criminally_charged_details,
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
                    'immigrant_country_details' => $this->immigrant_country_details,
                    'disability_id_no' => $this->disability_id_no,
                    'solo_parent' => $this->solo_parent
                ]);
            }

            session()->flash('flash.banner', 'Questionnaire saved successfully');
            session()->flash('flash.bannerStyle', 'save');
        } catch (\Throwable $th) {
            session()->flash('flash.banner', 'Failed to save Questionnaire');
            session()->flash('flash.bannerStyle', 'danger');
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


    public function render()
    {
        return view('livewire.form.questionnaire-form');
    }
}
