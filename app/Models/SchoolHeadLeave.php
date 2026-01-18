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
        'manual_adjustment',
        'used',
        'ctos_earned',
        'remarks',
    ];

    public static function defaultLeaves($soloParent = false, $userSex = null)
    {
        $leaves = [
            'VACATION LEAVE' => 15,
            'SICK LEAVE' => 15,
            'MANDATORY FORCED LEAVE' => 5,
            'SPECIAL PRIVILEGE LEAVE' => 3,
            'REHABILITATION PRIVILEGE' => 180,
            'SOLO PARENT LEAVE' => $soloParent ? 7 : 0,
            'STUDY LEAVE' => 180,
            'VAWC LEAVE' => 10,
            'SPECIAL LEAVE BENEFITS FOR WOMEN' => ($userSex === 'female') ? 60 : 0,
            'SPECIAL EMERGENCY (CALAMITY LEAVE)' => 1000,
            'ADOPTION LEAVE' => ($userSex === 'female') ? 60 : (($userSex === 'male') ? 7 : 0),
        ];

        // Only add Maternity Leave for female users
        if ($userSex === 'female') {
            $leaves['MATERNITY LEAVE'] = $soloParent ? 120 : 105;
        }

        // Only add Paternity Leave for male users
        if ($userSex === 'male') {
            $leaves['PATERNITY LEAVE'] = 7;
        }

        return $leaves;
    }
}
