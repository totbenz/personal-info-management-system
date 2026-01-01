<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveMonetization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'personnel_id',
        'user_type',
        'vl_days_used',
        'sl_days_used',
        'total_days',
        'total_amount',
        'status',
        'reason',
        'admin_remarks',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'vl_days_used' => 'integer',
        'sl_days_used' => 'integer',
        'total_days' => 'integer',
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who requested the monetization
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the personnel record
     */
    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    /**
     * Get the admin who approved the request
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope to get pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
