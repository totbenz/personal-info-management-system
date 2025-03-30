<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'personnel_id',
        'from_date',
        'to_date',
        'position_id',
        'appointment_status',
        'salary',
        'station', //district
        'branch',  //school
        'lv_wo_pay',
        'separation_date_cause'
    ];

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
