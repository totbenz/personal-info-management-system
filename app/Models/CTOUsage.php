<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CTOUsage extends Model
{
    use HasFactory;

    protected $table = 'cto_usages';

    protected $fillable = [
        'school_head_id',
        'cto_entry_id',
        'leave_request_id',
        'days_used',
        'used_date',
        'usage_type',
        'notes',
    ];

    protected $casts = [
        'used_date' => 'date',
        'days_used' => 'decimal:2',
    ];

    /**
     * Get the school head that used this CTO
     */
    public function schoolHead(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'school_head_id');
    }

    /**
     * Get the CTO entry that was used
     */
    public function ctoEntry(): BelongsTo
    {
        return $this->belongsTo(CTOEntry::class, 'cto_entry_id');
    }

    /**
     * Get the leave request that used this CTO (if applicable)
     */
    public function leaveRequest(): BelongsTo
    {
        return $this->belongsTo(LeaveRequest::class, 'leave_request_id');
    }

    /**
     * Get usage history for a school head
     */
    public static function getHistoryForSchoolHead(int $schoolHeadId, int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('school_head_id', $schoolHeadId)
            ->with(['ctoEntry.ctoRequest', 'leaveRequest'])
            ->orderBy('used_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}
