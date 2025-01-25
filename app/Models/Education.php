<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class Education extends Model
{
    use HasFactory;
    protected $table = 'educations';
    protected $fillable = ['type',
                           'school_name',
                           'degree_course',
                           'major',
                           'minor',
                           'period_from',
                           'period_to',
                           'highest_level_units',
                           'year_graduated',
                           'scholarship_honors'
                        ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($education) {
            $education->validateTypeConstraint();
        });

        static::updating(function ($education) {
            $education->validateTypeConstraint();
        });
    }

    protected function validateTypeConstraint()
    {
        $existingEducation = self::where('personnel_id', $this->personnel_id)
            ->where('type', $this->type)
            ->exists();

        if ($existingEducation) {
            throw ValidationException::withMessages([
                'type' => 'This type of education already exists for the personnel.',
            ]);
        }
    }

    function abbreviateDegree($degree) {
        // Split the degree string into words
        $words = explode(' ', $degree);
        $abbreviatedDegree = '';

        // Iterate through each word and get the first letter
        foreach ($words as $word) {
            $abbreviatedDegree .= strtoupper(substr($word, 0, 1)); // Append the first letter in uppercase
        }

        return $abbreviatedDegree;
    }

    // public function getMajorOrSpecialization() {
    //     $degree = $this->abbreviateDegree();
    //     if
    //     switch ($degree) {
    //         case 'BSEED':
    //             return 'Bachelor of Science in Elementary Education';
    //         case 'BSED':
    //             return 'Bachelor of Science in Secondary Education';
    //         case 'BEED':
    //             return 'Bachelor of Elementary Education';
    //         case 'MAED':
    //             return 'Master of Arts in Education (Specialization: Admin & Supervision)';
    //         case 'PHD':
    //             return 'Doctor of Philosophy in Education';
    //         default:
    //             return 'Unknown Degree';
    //     }
    // }
}
