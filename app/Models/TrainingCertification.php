<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingCertification extends Model
{
    use HasFactory;
    protected $fillable = ['training_seminar_title',
                           'type',
                           'sponsored',
                           'inclusive_from',
                           'inclusive_to',
                           'hours'
                        ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}
