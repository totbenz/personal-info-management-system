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
            // 'VACATION LEAVE' => $baseLeaveCredits,
            'SICK LEAVE' => $serviceCreditAvailability, // Always equal to Service Credit availability
            'PERSONAL LEAVE' => $baseLeaveCredits,
            'FORCE LEAVE' => 5,
            'REHABILITATION LEAVE' => 180,
            'SOLO PARENT LEAVE' => $soloParent ? 7 : 0,
            'STUDY LEAVE' => 180,
            'SERVICE CREDIT' => $serviceCreditAvailability, // Service Credit availability
        ];

        // Only add Maternity Leave for female teachers
        if ($userSex === 'female') {
            $leaves['MATERNITY LEAVE'] = $soloParent ? 120 : 105;
        }

        return $leaves;
    }
}
