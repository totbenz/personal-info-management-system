<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SalaryStep;

class SalaryGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade',
    ];
    
    public function salarySteps()
    {
        return $this->hasMany(SalaryStep::class);
    }
}
