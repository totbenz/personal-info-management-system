<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'type',
        'location',
        'created_by',
        'is_all_day',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_all_day' => 'boolean',
    ];

    // Event types
    const TYPE_MEETING = 'meeting';
    const TYPE_TRAINING = 'training';
    const TYPE_INSPECTION = 'inspection';
    const TYPE_CEREMONY = 'ceremony';
    const TYPE_DEADLINE = 'deadline';
    const TYPE_OTHER = 'other';

    // Event statuses
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    public static function getTypes()
    {
        return [
            self::TYPE_MEETING => 'Meeting',
            self::TYPE_TRAINING => 'Training',
            self::TYPE_INSPECTION => 'Inspection',
            self::TYPE_CEREMONY => 'Ceremony',
            self::TYPE_DEADLINE => 'Deadline',
            self::TYPE_OTHER => 'Other',
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeColorAttribute()
    {
        $colors = [
            self::TYPE_MEETING => 'blue',
            self::TYPE_TRAINING => 'green',
            self::TYPE_INSPECTION => 'yellow',
            self::TYPE_CEREMONY => 'purple',
            self::TYPE_DEADLINE => 'red',
            self::TYPE_OTHER => 'gray',
        ];

        return $colors[$this->type] ?? 'gray';
    }

    public function getFormattedTimeAttribute()
    {
        if ($this->is_all_day) {
            return 'All Day';
        }

        if ($this->start_time) {
            return Carbon::parse($this->start_time)->format('g:i A');
        }

        return null;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', Carbon::today())
                    ->where('status', self::STATUS_ACTIVE)
                    ->orderBy('start_date')
                    ->orderBy('start_time');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where('start_date', $date)
                    ->where('status', self::STATUS_ACTIVE);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
