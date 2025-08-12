<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCreditRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'year',
        'requested_personal_leave_credits',
        'requested_sick_leave_credits',
        'justification',
        'status',
        'admin_notes',
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

    public function getTotalRequestedCreditsAttribute()
    {
        return $this->requested_personal_leave_credits + $this->requested_sick_leave_credits;
    }
}
