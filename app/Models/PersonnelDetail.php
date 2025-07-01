<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'consanguinity_third_degree',
        'consanguinity_third_degree_details',
        'consanguinity_fourth_degree',
        'consanguinity_fourth_degree_details',
        'found_guilty_administrative_offense',
        'administrative_offense_details',
        'criminally_charged',
        'criminally_charged_details',
        'criminally_charged_date_filed',
        'criminally_charged_status',
        'convicted_crime',
        'convicted_crime_details',
        'separated_from_service',
        'separation_details',
        'candidate_last_year',
        'candidate_details',
        'resigned_to_campaign',
        'resigned_campaign_details',
        'immigrant_status',
        'immigrant_country_details',
        'member_indigenous_group',
        'indigenous_group_details',
        'person_with_disability',
        'disability_id_no',
        'solo_parent',
        'solo_parent_id_no',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
