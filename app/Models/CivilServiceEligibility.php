<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CivilServiceEligibility extends Model
{
    use HasFactory;
    protected $table = 'civil_service_eligibility';
    protected $fillable = ['title',
                           'rating',
                           'date_of_exam',
                           'place_of_exam',
                           'license_num',
                           'license_date_of_validity'
                        ];

    protected $casts = [
        'date_of_exam' => 'date',
        'license_date_of_validity' => 'date'
    ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
}
