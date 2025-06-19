<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStep extends Model
{
    use HasFactory;

    // Define fillable attributes for mass assignment
    protected $fillable = [
        'salary_grade_id',
        'step',
        'year',
        'salary',
    ];

    protected $casts = [
        'salary' => 'decimal:2'
    ];

    /**
     * Define the relationship with the SalaryGrade model.
     * A SalaryStep belongs to a SalaryGrade.
     */
    public function salaryGrade()
    {
        return $this->belongsTo(SalaryGrade::class);
    }

    /**
     * Get the formatted salary value with peso sign and thousand separators
     */
    public function getFormattedSalaryAttribute()
    {
        return '₱' . number_format($this->attributes['salary'], 2, '.', ',');
    }

    /**
     * Mutator for the 'salary' attribute.
     * Removes commas from the salary value before saving to the database.
     */
    public function setSalaryAttribute($value)
    {
        $this->attributes['salary'] = str_replace(',', '', $value);
    }

    /**
     * Accessor for the 'created_at' attribute.
     * Formats the created_at timestamp to 'Y-m-d H:i:s'.
     */
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    /**
     * Accessor for the 'updated_at' attribute.
     * Formats the updated_at timestamp to 'Y-m-d H:i:s'.
     */
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
