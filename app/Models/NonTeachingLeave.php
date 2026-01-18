<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NonTeachingLeave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'non_teaching_id',
        'leave_type',
        'year',
        'available',
        'used',
        'remarks',
    ];

    /**
     * Get the non-teaching personnel record
     */
    public function nonTeaching()
    {
        return $this->belongsTo(Personnel::class, 'non_teaching_id');
    }

    /**
     * Default leave allocations for non-teaching staff
     */
    public static function defaultLeaves($yearsOfService = 0, $soloParent = false, $userSex = null, $civilStatus = null)
    {
        $baseLeaveCredits = max(15, $yearsOfService * 15); // 15 days per year of service, minimum 15

        $leaves = [
            'VACATION LEAVE' => $baseLeaveCredits,
            'SICK LEAVE' => $baseLeaveCredits,
            'MANDATORY FORCED LEAVE' => 5,
            'SPECIAL PRIVILEGE LEAVE' => 3,
            'REHABILITATION PRIVILEGE' => 180,
            'SOLO PARENT LEAVE' => $soloParent ? 7 : 0,
            'STUDY LEAVE' => 180,
            'VAWC LEAVE' => 10,
            'SPECIAL LEAVE BENEFITS FOR WOMEN' => ($userSex === 'female') ? 60 : 0,
            'SPECIAL EMERGENCY (CALAMITY LEAVE)' => 1000,
            'ADOPTION LEAVE' => ($userSex === 'female') ? 60 : (($userSex === 'male' && $civilStatus === 'single') ? 60 : 7),
        ];

        // Only add Maternity Leave for female staff
        if ($userSex === 'female') {
            $leaves['MATERNITY LEAVE'] = $soloParent ? 120 : 105;
        }

        // Only add Paternity Leave for male staff
        if ($userSex === 'male') {
            $leaves['PATERNITY LEAVE'] = 7;
        }

        return $leaves;
    }
}
