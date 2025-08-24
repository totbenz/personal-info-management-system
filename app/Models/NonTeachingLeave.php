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
    public static function defaultLeaves($yearsOfService = 0, $soloParent = false, $userSex = null)
    {
        $baseLeaveCredits = max(15, $yearsOfService * 15); // 15 days per year of service, minimum 15

        $leaves = [
            'Vacation Leave' => $baseLeaveCredits,
            'Sick Leave' => $baseLeaveCredits,
            'Personal Leave' => $baseLeaveCredits,
            'Rehabilitation Leave' => 180,
            'Solo Parent Leave' => $soloParent ? 7 : 0,
            'Study Leave' => 180,
        ];

        // Only add Maternity Leave for female staff
        if ($userSex === 'female') {
            $leaves['Maternity Leave'] = $soloParent ? 120 : 105;
        }

        return $leaves;
    }
}
