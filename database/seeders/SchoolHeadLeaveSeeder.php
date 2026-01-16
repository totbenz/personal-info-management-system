<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolHeadLeave;
use App\Models\Personnel;
use Carbon\Carbon;

class SchoolHeadLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get specific personnel IDs from UserSeeder
        $personnelIds = [1, 2, 3, 4];
        $year = Carbon::now()->year;

        // Define leave types for school heads
        $leaveTypes = [
            'VACATION LEAVE',
            'SICK LEAVE',
            'MANDATORY FORCED LEAVE',
            'MATERNITY LEAVE',
            'PATERNITY LEAVE',
            'SPECIAL PRIVILEGE LEAVE',
            'SOLO PARENT LEAVE',
            'STUDY LEAVE',
            'VAWC LEAVE',
            'REHABILITATION PRIVILEGE',
            'SPECIAL LEAVE BENEFITS FOR WOMEN',
            'SPECIAL EMERGENCY (CALAMITY LEAVE)',
            'ADOPTION LEAVE'
        ];

        // Only create for personnel_id 2 (school_head role)
        $personnel = Personnel::find(2);
        if ($personnel) {
            foreach ($leaveTypes as $leaveType) {
                SchoolHeadLeave::firstOrCreate([
                    'school_head_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $year,
                ], [
                    'available' => 0,
                    'used' => 0,
                    'ctos_earned' => 0,
                    'remarks' => 'Seeded',
                ]);
            }
        }
    }
}
