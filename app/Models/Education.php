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

    protected $fillable = [
        'type',
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

    protected $casts = [
        'period_from' => 'integer',
        'period_to' => 'integer',
        'year_graduated' => 'integer',
    ];

    // Validation rules
    public static $rules = [
        'type' => 'required|in:elementary,secondary,vocational/trade,graduate,graduate studies',
        'school_name' => 'required|string|max:255',
        'degree_course' => 'nullable|string|max:255',
        'major' => 'nullable|string|max:255',
        'minor' => 'nullable|string|max:255',
        'period_from' => 'required|integer|min:1900|max:2100',
        'period_to' => 'nullable|integer|min:1900|max:2100|gte:period_from',
        'highest_level_units' => 'nullable|string|max:255',
        'year_graduated' => 'nullable|integer|min:1900|max:2100',
        'scholarship_honors' => 'nullable|string|max:255',
    ];

    // Custom error messages
    public static $messages = [
        'type.in' => 'The education type must be one of: elementary, secondary, vocational/trade, graduate, or graduate studies.',
        'period_to.gte' => 'The end year must be greater than or equal to the start year.',
        'period_from.min' => 'The start year must be at least 1900.',
        'period_from.max' => 'The start year cannot be more than 2100.',
        'year_graduated.min' => 'The graduation year must be at least 1900.',
        'year_graduated.max' => 'The graduation year cannot be more than 2100.',
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
            $education->validatePeriods();
        });

        static::updating(function ($education) {
            $education->validateTypeConstraint();
            $education->validatePeriods();
        });
    }

    protected function validateTypeConstraint()
    {
        $existingEducation = self::where('personnel_id', $this->personnel_id)
            ->where('type', $this->type)
            ->where('id', '!=', $this->id) // Exclude current record when updating
            ->exists();

        if ($existingEducation) {
            throw ValidationException::withMessages([
                'type' => "This type of education ({$this->type}) already exists for the personnel.",
            ]);
        }
    }

    protected function validatePeriods()
    {
        $errors = [];

        // Validate period logic
        if ($this->period_from && $this->period_to) {
            if ($this->period_from > $this->period_to) {
                $errors['period_to'] = 'End year cannot be before start year.';
            }
        }

        // Validate graduation year logic
        if ($this->year_graduated) {
            if ($this->period_from && $this->year_graduated < $this->period_from) {
                $errors['year_graduated'] = 'Graduation year cannot be before the start year.';
            }
            if ($this->period_to && $this->year_graduated > $this->period_to) {
                $errors['year_graduated'] = 'Graduation year cannot be after the end year.';
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Get the abbreviated degree
     */
    public function getAbbreviatedDegreeAttribute()
    {
        if (!$this->degree_course) {
            return null;
        }

        $words = explode(' ', $this->degree_course);
        $abbreviatedDegree = '';

        foreach ($words as $word) {
            $abbreviatedDegree .= strtoupper(substr($word, 0, 1));
        }

        return $abbreviatedDegree;
    }

    /**
     * Get the full degree name with specialization
     */
    public function getFullDegreeNameAttribute()
    {
        if (!$this->degree_course) {
            return null;
        }

        $degree = $this->abbreviated_degree;

        switch (strtoupper($degree)) {
            case 'BSEED':
                return 'Bachelor of Science in Elementary Education';
            case 'BSED':
                return 'Bachelor of Science in Secondary Education';
            case 'BEED':
                return 'Bachelor of Elementary Education';
            case 'MAED':
                return 'Master of Arts in Education (Specialization: Admin & Supervision)';
            case 'PHD':
                return 'Doctor of Philosophy in Education';
            default:
                return $this->degree_course;
        }
    }

    /**
     * Scope to get education by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if education is completed
     */
    public function getIsCompletedAttribute()
    {
        return !empty($this->year_graduated);
    }

    /**
     * Get duration in years
     */
    public function getDurationAttribute()
    {
        if ($this->period_from && $this->period_to) {
            return $this->period_to - $this->period_from + 1;
        }
        return null;
    }
}
