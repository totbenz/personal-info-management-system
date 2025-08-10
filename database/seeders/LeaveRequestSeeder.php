<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;

class LeaveRequestSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get some users to create leave requests for
        $users = User::whereIn('role', ['school_head', 'teacher'])->get();
        
        if ($users->count() === 0) {
            $this->command->info('No school_head or teacher users found. Please seed users first.');
            return;
        }

        $leaveTypes = [
            'Vacation Leave',
            'Sick Leave',
            'Special Privilege Leave',
            'Force Leave',
            'Maternity Leave',
            'Paternity Leave',
            'Solo Parent Leave',
            'Study Leave',
            'Emergency Leave'
        ];

        $reasons = [
            'Family vacation and bonding time',
            'Medical treatment and recovery',
            'Personal matters requiring attention',
            'Mandatory government requirement',
            'Child birth and recovery',
            'Father\'s support during childbirth',
            'Child care responsibilities',
            'Professional development training',
            'Family emergency situation',
            'Medical appointment',
            'Wedding celebration',
            'Funeral attendance',
            'Home renovation supervision',
            'Graduate studies enrollment'
        ];

        // Create 8-12 sample leave requests
        for ($i = 0; $i < rand(8, 12); $i++) {
            $user = $users->random();
            $leaveType = $leaveTypes[array_rand($leaveTypes)];
            $reason = $reasons[array_rand($reasons)];
            
            // Random dates within the next 3 months
            $startDate = Carbon::now()->addDays(rand(1, 90));
            $endDate = $startDate->copy()->addDays(rand(1, 7)); // 1-7 days leave
            
            LeaveRequest::create([
                'user_id' => $user->id,
                'leave_type' => $leaveType,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'reason' => $reason,
                'status' => 'pending', // All requests will be pending for testing
                'created_at' => Carbon::now()->subDays(rand(0, 30)), // Created within last 30 days
                'updated_at' => Carbon::now()->subDays(rand(0, 30)),
            ]);
        }

        $this->command->info('Leave requests seeded successfully!');
    }
}
