<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class AssignmentDetail extends Model
{
    use HasFactory;
    protected $fillable = ['assignment',
                           'dtr_day',
                           'dtr_from',
                           'dtr_to',
                           'school_year',
                           'teaching_minutes_per_week'
                        ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}
