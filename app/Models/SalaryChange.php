<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Personnel;

class SalaryChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'type',
        'previous_salary_grade',
        'current_salary_grade',
        'previous_salary_step',
        'current_salary_step',
        'previous_salary',
        'current_salary',
        'actual_monthly_salary_as_of_date',
        'adjusted_monthly_salary_date',
        'created_at',
        'updated_at',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
