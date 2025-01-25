<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;


class Personnel extends Model
{
    use HasFactory;
    protected $fillable = [
        // Personal Information
        'first_name',
        'middle_name',
        'last_name',
        'name_ext',
        'sex',
        'civil_status',
        'citizenship',
        'blood_type',
        'height',
        'weight',
        'date_of_birth',
        'place_of_birth',
        'email',
        'tel_no',
        'mobile_no',

        // Work Information
        'personnel_id',
        'school_id',
        'position_id',
        'appointment',
        'fund_source',
        'salary_grade',
        'step',
        'category',
        'job_status',
        'classification',
        'employment_start',
        'employment_end',

        // Government Information
        'tin',
        'sss_num',
        'gsis_num',
        'philhealth_num',
        'pagibig_num'
    ];
    protected $dates = [
        'employment_start_date',
    ];

    // Boot method to attach the saved event
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saved(function ($personnel) {
    //         // $original = $personnel->getOriginal();
    //         // $fieldsToCheck = ['position_id', 'appointment', 'salary_grade', 'district', 'school_id'];

    //         // foreach ($fieldsToCheck as $field) {
    //         //     if ($personnel->$field !== $original[$field]) {
    //                 $personnel->createOrUpdateServiceRecord();
    //     //             break; // Break loop if any one of the fields changes
    //     //         }
    //     //     }
    //     });

    //     static::created(function ($personnel) {
    //         $personnel->createInitialServiceRecord();
    //     });
    // }
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($personnel) {
            $personnel->createOrUpdateServiceRecord();
        });

        static::created(function ($personnel) {
            $personnel->createInitialServiceRecord();
        });
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function addresses()
    {
        return $this->hasOne(Address::class);
    }

    public function permanentAddress()
    {
        return $this->hasOne(Address::class)->where('address_type', 'permanent');
    }

    public function residentialAddress()
    {
        return $this->hasOne(Address::class)->where('address_type', 'residential');
    }

    public function contactPerson(): HasOne
    {
        return $this->hasOne(ContactPerson::class);
    }

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    public function father()
    {
        return $this->hasOne(Family::class)->where('relationship', 'father');
    }

    public function mother()
    {
        return $this->hasOne(Family::class)->where('relationship', 'mother');
    }

    public function spouse()
    {
        return $this->hasOne(Family::class)->where('relationship', 'spouse');
    }

    public function children()
    {
        return $this->hasMany(Family::class)->where('relationship', 'children');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function elementaryEducation()
    {
        return $this->hasOne(Education::class)->where('type', 'elementary');
    }

    public function secondaryEducation()
    {
        return $this->hasOne(Education::class)->where('type', 'secondary');
    }

    public function vocationalEducation()
    {
        return $this->hasOne(Education::class)->where('type', 'vocational/trade');
    }

    public function graduateEducation()
    {
        return $this->hasOne(Education::class)->where('type', 'graduate');
    }

    public function graduateStudiesEducation()
    {
        return $this->hasOne(Education::class)->where('type', 'graduate studies');
    }

    public function civilServiceEligibilities(): HasMany
    {
        return $this->hasMany(CivilServiceEligibility::class);
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function voluntaryWorks(): HasMany
    {
        return $this->hasMany(VoluntaryWork::class);
    }

    public function trainingCertifications(): HasMany
    {
        return $this->hasMany(TrainingCertification::class);
    }

    public function otherInformations(): HasMany
    {
        return $this->hasMany(OtherInformation::class);
    }

    public function skills()
    {
        return $this->hasOne(OtherInformation::class)->where('type', 'special_skill');
    }

    public function nonacademicDistinctions()
    {
        return $this->hasOne(OtherInformation::class)->where('type', 'nonacademic_distinction');
    }

    public function associations()
    {
        return $this->hasOne(OtherInformation::class)->where('type', 'association');
    }

    public function personnelDetail() //For Questionnaire
    {
        return $this->hasOne(PersonnelDetail::class);
    }

    public function references(): HasMany
    {
        return $this->hasMany(Reference::class);
    }

    public function assignmentDetails(): HasMany
    {
        return $this->hasMany(AssignmentDetail::class);
    }

    public function createInitialServiceRecord()
    {
        $this->serviceRecords()->create([
            'personnel_id' => $this->id,
            'from_date' => now(),
            'to_date' => null,
            'designation' => $this->position_id,
            'appointment_status' => $this->appointment,
            'salary' => $this->salary_grade,
            'station' => $this->school->district_id,
            'branch' => $this->school_id
        ]);
    }

    public function createOrUpdateServiceRecord()
    {
        $existingRecord = $this->serviceRecords()->orderByDesc('created_at')->first();

        if ($existingRecord) {
            // Update the existing ServiceRecord
            $existingRecord->update([
                'to_date' => now(),
            ]);

            // Create a new ServiceRecord
            $this->createInitialServiceRecord();
        } else {
            // Create the first ServiceRecord
            $this->createInitialServiceRecord();
        }
    }

    public function serviceRecords(): HasMany
    {
        return $this->hasMany(ServiceRecord::class);
    }

    public function createServiceRecord()
    {
        try {
            $this->serviceRecords()->create([
                'personnel_id' => $this->id,
                'from_date' => $this->employment_start,
                'to_date' => $this->employment_end,
                'designation' => $this->position_id,
                'appointment_status' => $this->appointment,
                'salary' => $this->salary_grade, // Assuming salary_grade corresponds to salary in ServiceRecord
                'station' => $this->district_id, // Assuming district corresponds to station in ServiceRecord
                'branch' => $this->school_id, // Assuming school_id corresponds to branch in ServiceRecord
                'lv_wo_pay' => null, // Assuming lv_wo_pay is not available in the Personnel model
                'separation_date_cause' => null, // Assuming separation_date_cause is not available in the Personnel model
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function scopeSearch($query, $value){
        $query->where('personnel_id', "like", "%{$value}%")
              ->orWhere(function ($query) use ($value) {
                    $query->whereRaw("CONCAT(first_name, ' ', SUBSTRING(middle_name, 1, 1), '. ', last_name) LIKE ?", ["%{$value}%"]);
                })
               ->orWhere('school_id', "like", "%{$value}%")
               ->orWhere('category', "like", "%{$value}%");
    }

    public static function getLoyaltyAwardees()
    {
        $currentYear = Carbon::now()->year;
        return self::whereNotNull('employment_start')
            ->whereRaw("TIMESTAMPDIFF(YEAR, employment_start, CURDATE()) % 10 = 0")
            ->whereRaw("YEAR(employment_start) <= $currentYear")
            ->get();
    }

    protected function abbreviateTitle($title)
    {
        $abbreviations = [
            'Teacher I' => 'T-I', 'Teacher II' => 'T-II', 'Teacher III' => 'T-III',
            'Master Teacher I' => 'MT-I', 'Master Teacher II' => 'MT-II',
            'Master Teacher III' => 'MT-III', 'Master Teacher IV' => 'MT-IV',
            'Head Teacher I' => 'HT-I', 'Head Teacher II' => 'HT-II',
            'Head Teacher III' => 'HT-III', 'Head Teacher IV' => 'HT-IV',
            'Head Teacher V' => 'HT-V', 'Head Teacher VI' => 'HT-VI',
            'Special Education Teacher I' => 'SET-I', 'Special Education Teacher II' => 'SET-II',
            'Special Education Teacher III' => 'SET-III', 'Special Education Teacher IV' => 'SET-IV',
            'Special Education Teacher V' => 'SET-V', 'School Principal I' => 'P-I',
            'School Principal II' => 'P-II', 'School Principal III' => 'P-III',
            'School Principal IV' => 'P-IV',
        ];

        return $abbreviations[$title] ?? $title;
    }

    public function getAbbreviatedTitleAttribute()
    {
        return $this->abbreviateTitle($this->position->title ?? '');
    }

    public function getEQDegreePostGraduate()
    {

        $graduateEducation = $this->graduateEducation()->first();
        // dd($this->graduateEducation());
        $graduateStudiesEducation = $this->graduateStudiesEducation()->first();

        if ($graduateStudiesEducation) {
            return $graduateEducation->degree_course . '/' . $graduateStudiesEducation->degree_course;
        } else {
            return $graduateEducation->degree_course ?? 'N/A';
        }
    }

    public function getEQMajor()
    {
        $graduateEducation = $this->graduateEducation()->first();
        $graduateStudiesEducation = $this->graduateStudiesEducation()->first();

        if ($graduateStudiesEducation && $graduateStudiesEducation->major != null) {
            return $graduateStudiesEducation->major;
        } elseif ($graduateEducation && $graduateEducation->major != null) {
            return $graduateEducation->major;
        } else {
            return 'N/A';
        }
    }

    public function getEQMinor()
    {
        $graduateEducation = $this->graduateEducation()->first();
        $graduateStudiesEducation = $this->graduateStudiesEducation()->first();

        if ($graduateStudiesEducation && $graduateStudiesEducation->minor != null) {
            return $graduateStudiesEducation->minor;
        } elseif ($graduateEducation && $graduateEducation->minor != null) {
            return $graduateEducation->minor;
        } else {
            return 'N/A';
        }
    }

    public function fullName()
    {
        return $this->first_name . ' '
                . ($this->middle_name ? $this->middle_name[0] . '. ' : '')
                . $this->last_name . ' '
                . $this->name_ext;
    }


}
