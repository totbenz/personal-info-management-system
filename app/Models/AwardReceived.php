<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardReceived extends Model
{
    use HasFactory;
    protected $table = 'award_received';
    protected $fillable = [
        'personnel_id',
        'award_name',
        'description',
        'award_date',
        'awarding_body',
        'reward_date',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }
}
