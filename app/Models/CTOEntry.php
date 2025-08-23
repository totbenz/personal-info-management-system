<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class CTOEntry extends Model
{
    use HasFactory;

    protected $table = 'cto_entries';

    protected $fillable = [
        'school_head_id',
        'cto_request_id',
        'days_earned',
        'days_remaining',
        'earned_date',
        'expiry_date',
        'is_expired',
    ];

    protected $casts = [
        'earned_date' => 'date',
        'expiry_date' => 'date',
        'is_expired' => 'boolean',
        'days_earned' => 'decimal:2',
        'days_remaining' => 'decimal:2',
    ];

    /**
     * Get the school head that owns this CTO entry
     */
    public function schoolHead(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'school_head_id');
    }

    /**
     * Get the CTO request that generated this entry
     */
    public function ctoRequest(): BelongsTo
    {
        return $this->belongsTo(CTORequest::class, 'cto_request_id');
    }

    /**
     * Get the usage records for this CTO entry
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CTOUsage::class, 'cto_entry_id');
    }

    /**
     * Check if this CTO entry is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date < now()->toDateString() || $this->attributes['is_expired'];
    }

    /**
     * Mark CTO entry as expired and update remaining days to 0
     */
    public function expire(): void
    {
        $this->update([
            'is_expired' => true,
            'days_remaining' => 0,
        ]);
    }

    /**
     * Use CTO days from this entry
     */
    public function useDays(float $days, ?int $leaveRequestId = null, string $usageType = 'leave', ?string $notes = null): CTOUsage
    {
        if ($days > $this->days_remaining) {
            throw new \InvalidArgumentException("Cannot use {$days} days. Only {$this->days_remaining} days remaining.");
        }

        if ($this->is_expired) {
            throw new \InvalidArgumentException("Cannot use expired CTO entry.");
        }

        // Create usage record
        $usage = CTOUsage::create([
            'school_head_id' => $this->school_head_id,
            'cto_entry_id' => $this->id,
            'leave_request_id' => $leaveRequestId,
            'days_used' => $days,
            'used_date' => now()->toDateString(),
            'usage_type' => $usageType,
            'notes' => $notes,
        ]);

        // Update remaining days
        $this->decrement('days_remaining', $days);

        return $usage;
    }

    /**
     * Get available (non-expired) CTO entries for a school head, ordered by earned date (FIFO)
     */
    public static function getAvailableForSchoolHead(int $schoolHeadId): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('school_head_id', $schoolHeadId)
            ->where('days_remaining', '>', 0)
            ->where('is_expired', false)
            ->where('expiry_date', '>=', now()->toDateString())
            ->orderBy('earned_date', 'asc')
            ->get();
    }

    /**
     * Calculate total available CTO days for a school head
     */
    public static function getTotalAvailableDays(int $schoolHeadId): float
    {
        return static::where('school_head_id', $schoolHeadId)
            ->where('days_remaining', '>', 0)
            ->where('is_expired', false)
            ->where('expiry_date', '>=', now()->toDateString())
            ->sum('days_remaining');
    }

    /**
     * Expire CTO entries that are past their expiry date
     */
    public static function expireOldEntries(): int
    {
        $expiredCount = 0;
        
        $expiredEntries = static::where('expiry_date', '<', now()->toDateString())
            ->where('is_expired', false)
            ->where('days_remaining', '>', 0)
            ->get();

        foreach ($expiredEntries as $entry) {
            $entry->expire();
            $expiredCount++;
        }

        return $expiredCount;
    }
}
