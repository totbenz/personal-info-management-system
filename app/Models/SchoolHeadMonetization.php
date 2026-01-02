<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolHeadMonetization extends Model
{
    use HasFactory;

    protected $table = 'school_head_monetizations';

    protected $fillable = [
        'school_head_id',
        'days_requested',
        'reason',
        'status',
        'request_date',
        'approval_date',
        'vl_available',
        'sl_available',
        'vl_deducted',
        'sl_deducted',
        'rejection_reason',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'approval_date' => 'datetime',
        'days_requested' => 'integer',
        'vl_available' => 'decimal:2',
        'sl_available' => 'decimal:2',
        'vl_deducted' => 'decimal:2',
        'sl_deducted' => 'decimal:2',
    ];

    /**
     * Get the school head that owns the monetization request
     */
    public function schoolHead()
    {
        return $this->belongsTo(Personnel::class, 'school_head_id');
    }

    /**
     * Scope a query to only include pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
