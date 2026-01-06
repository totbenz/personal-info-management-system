<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationEntry extends Model
{
    use HasFactory;

    protected $table = 'education_entries';

    protected $fillable = [
        'personnel_id',
        'type',
        'sort_order',
        'school_name',
        'degree_course',
        'major',
        'minor',
        'period_from',
        'period_to',
        'highest_level_units',
        'year_graduated',
        'scholarship_honors',
        // School Location Information
        'school_address',
        'school_city',
        'school_province',
        'school_country',
        // Academic Performance
        'gpa',
        'gpa_scale',
        'class_rank',
        'academic_status',
        // Thesis/Dissertation
        'thesis_title',
        'thesis_advisor',
        // Licenses and Certifications
        'license_number',
        'license_date',
        'license_expiry',
        'board_exam_rating',
        // Recognition and Achievements
        'achievements',
        'extracurricular_activities',
        'leadership_roles',
        'awards',
        'remarks',
        'enrollment_date',
        'completion_date',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'period_from' => 'integer',
        'period_to' => 'integer',
        'year_graduated' => 'integer',
        'gpa' => 'decimal:3',
        'license_date' => 'date',
        'license_expiry' => 'date',
        'enrollment_date' => 'date',
        'completion_date' => 'date',
    ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}
