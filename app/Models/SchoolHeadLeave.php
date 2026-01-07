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
            'Vacation Leave' => 15, // Base amount, will accrue 1.25/month + 15/year
            'Sick Leave' => 15,     // Base amount, will accrue 1.25/month + 15/year
            'Special Privilege Leave' => 3,
            'Force Leave' => 5,
            'Compensatory Time Off' => 0,
            'Rehabilitation Leave' => 180,
            'Solo Parent Leave' => 7,
            'Study Leave' => 180,
        ];

        // Only add Maternity Leave for female users
        if ($userSex === 'female') {
            $leaves['Maternity Leave'] = $soloParent ? 120 : 105;
        }

        return $leaves;
    }
}
