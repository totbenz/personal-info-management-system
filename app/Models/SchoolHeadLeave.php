<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolHeadLeave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_head_id',
        'leave_type',
        'year',
        'available',
        'used',
        'ctos_earned',
        'remarks',
    ];

    public static function defaultLeaves($soloParent = false)
    {
        return [
            'Vacation Leave' => 15,
            'Sick Leave' => 15,
            'Special Privilege Leave' => 3,
            'Force Leave' => 5,
            'Compensatory Time Off' => 0,
            'Maternity Leave' => $soloParent ? 120 : 105,
            'Rehabilitation Leave' => 180,
            'Solo Parent Leave' => 7,
            'Study Leave' => 180,
        ];
    }
}
