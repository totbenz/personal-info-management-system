<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'year',
        'personal_leave_credits',
        'sick_leave_credits',
        'status',
        'reason',
        'approved_at',
        'approved_by'
    ];

    protected $dates = [
        'approved_at'
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getTotalCreditsAttribute()
    {
        return $this->personal_leave_credits + $this->sick_leave_credits;
    }
}
