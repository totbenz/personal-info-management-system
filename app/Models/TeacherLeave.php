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
    public static function defaultLeaves($yearsOfService = 0, $soloParent = false, $userSex = null)
    {
        $baseLeaveCredits = max(15, $yearsOfService * 15); // 15 days per year of service, minimum 15

        $leaves = [
            'Vacation Leave' => $baseLeaveCredits,
            'Sick Leave' => $baseLeaveCredits,
            'Personal Leave' => $baseLeaveCredits,
            'Force Leave' => 5,
            'Rehabilitation Leave' => 180,
            'Solo Parent Leave' => $soloParent ? 7 : 0,
            'Study Leave' => 180,
        ];

        // Only add Maternity Leave for female teachers
        if ($userSex === 'female') {
            $leaves['Maternity Leave'] = $soloParent ? 120 : 105;
        }

        return $leaves;
    }
}
