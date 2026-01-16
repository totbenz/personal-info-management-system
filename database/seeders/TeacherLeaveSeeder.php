<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeacherLeave;
use App\Models\Personnel;
use Carbon\Carbon;

class TeacherLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get specific personnel IDs from UserSeeder
        $personnelIds = [1, 2, 3, 4];
        $year = Carbon::now()->year;

        // Define leave types for teachers
        $leaveTypes = [
            'SICK LEAVE',
            'MATERNITY LEAVE',
            'PATERNITY LEAVE',
            'SOLO PARENT LEAVE',
            'STUDY LEAVE',
            'VAWC LEAVE',
            'REHABILITATION PRIVILEGE',
            'SPECIAL LEAVE BENEFITS FOR WOMEN',
            'SPECIAL EMERGENCY (CALAMITY LEAVE)',
            'ADOPTION LEAVE',
            'SERVICE CREDIT'
        ];

        // Only create for personnel_id 3 (teacher role)
        $personnel = Personnel::find(3);
        if ($personnel) {
            foreach ($leaveTypes as $leaveType) {
                TeacherLeave::firstOrCreate([
                    'teacher_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $year,
                ], [
                    'available' => 0,
                    'used' => 0,
                    'remarks' => 'Seeded',
                ]);
            }
        }
    }
}
