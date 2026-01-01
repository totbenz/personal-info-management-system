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
            'Vacation Leave' => $baseLeaveCredits,
            'Sick Leave' => $baseLeaveCredits,
            'Personal Leave' => $baseLeaveCredits,
            'Force Leave' => 5,
            'Rehabilitation Leave' => 180,
            'Solo Parent Leave' => $soloParent ? 7 : 0,
            'Study Leave' => 180,
            'Compensatory Time Off' => 0,
            'Paternity Leave' => ($userSex === 'female') ? 7 : 0, // Only visible to women
            'VAWC Leave' => 10, // Visible to all
            'Special Leave Benefits for Women' => ($userSex === 'female') ? 60 : 0, // Up to 2 months, only for women
            'Calamity Leave' => 1000, // Unlimited leave
        ];

        // Adoption Leave logic
        // 60 days for female and single male employees, 7 days for male spouse
        if ($userSex === 'female') {
            $leaves['Adoption Leave'] = 60;
        } elseif ($userSex === 'male') {
            if ($civilStatus === 'single') {
                $leaves['Adoption Leave'] = 60;
            } else {
                $leaves['Adoption Leave'] = 7;
            }
        }

        // Only add Maternity Leave for female staff
        if ($userSex === 'female') {
            $leaves['Maternity Leave'] = $soloParent ? 120 : 105;
        }

        return $leaves;
    }
}
