<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CTORequest extends Model
{
    use HasFactory;

    protected $table = 'cto_requests';

    protected $fillable = [
        'school_head_id',
        'requested_hours',
        'work_date',
        'start_time',
        'end_time',
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
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'morning_in' => 'datetime:H:i',
        'morning_out' => 'datetime:H:i',
        'afternoon_in' => 'datetime:H:i',
        'afternoon_out' => 'datetime:H:i',
        'total_hours' => 'float',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the school head that made this CTO request
     */
    public function schoolHead(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'school_head_id');
    }

    /**
     * Get the personnel (alias for schoolHead for consistency with other models)
     */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'school_head_id');
    }

    /**
     * Get the user associated with this school head
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'school_head_id', 'personnel_id');
    }

    /**
     * Get the user who approved this request
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Calculate CTO days earned from hours
     * 8 hours = 1 day of CTO
     */
    public function getCtoDaysEarnedAttribute(): float
    {
        return round(($this->total_hours ?? $this->requested_hours) / 8, 2);
    }

    /**
     * Get status with styling
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Pending</span>',
            'approved' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>',
            'denied' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Denied</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>',
        };
    }
}
