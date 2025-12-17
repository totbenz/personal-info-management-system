<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCreditRequest extends Model
{
    use HasFactory, SoftDeletes;

    // Explicit table declaration (clarifies the exact DB table in use)
    protected $table = 'service_credit_requests';

    protected $fillable = [
        'teacher_id',
        'requested_days',
        'work_date',
        'morning_in',
        'morning_out',
        'afternoon_in',
        'afternoon_out',
        'total_hours',
        'reason',
        'description',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'work_date' => 'date',
        'approved_at' => 'datetime',
        'requested_days' => 'float',
    'total_hours' => 'float',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'teacher_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
