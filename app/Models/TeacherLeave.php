<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherLeave extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'leave_type',
        'year',
        'available',
        'used',
        'remarks',
    ];

    /**
     * Get the teacher personnel record
     */
    public function teacher()
    {
        return $this->belongsTo(Personnel::class, 'teacher_id');
    }

    /**
     * Default leave allocations for teachers
     */
    public static function defaultLeaves($yearsOfService = 0, $soloParent = false, $userSex = null, $serviceCreditAvailability = 0)
    {
        $baseLeaveCredits = max(15, $yearsOfService * 15); // 15 days per year of service, minimum 15

        $leaves = [
            'SICK LEAVE' => $serviceCreditAvailability, // Always equal to Service Credit availability
            'MATERNITY LEAVE' => ($userSex === 'female') ? ($soloParent ? 120 : 105) : 0,
            'PATERNITY LEAVE' => ($userSex === 'male') ? 7 : 0,
            'SOLO PARENT LEAVE' => $soloParent ? 7 : 0,
            'STUDY LEAVE' => 180,
            'VAWC LEAVE' => 10,
            'REHABILITATION PRIVILEGE' => 180,
            'SPECIAL LEAVE BENEFITS FOR WOMEN' => ($userSex === 'female') ? 60 : 0,
            'SPECIAL EMERGENCY (CALAMITY LEAVE)' => 1000,
            'ADOPTION LEAVE' => ($userSex === 'female') ? 60 : (($userSex === 'male') ? 7 : 0),
            'SERVICE CREDIT' => $serviceCreditAvailability, // Service Credit availability
        ];

        return $leaves;
    }
}
