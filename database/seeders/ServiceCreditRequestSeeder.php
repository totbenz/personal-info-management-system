<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Personnel;
use App\Models\ServiceCreditRequest;
use Illuminate\Support\Facades\Hash;

class ServiceCreditRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure we have at least one teacher user with a personnel record
        $teacherUser = User::where('role', 'teacher')->whereHas('personnel')->first();
        
        if (!$teacherUser) {
            // Create a test teacher personnel if none exists
            $personnel = Personnel::first();
            if (!$personnel) {
                echo "No personnel records found. Please create personnel records first.\n";
                return;
            }
            
            // Find personnel without users or create a new teacher user
            $teacherPersonnel = Personnel::whereDoesntHave('user')->first();
            if (!$teacherPersonnel) {
                $teacherPersonnel = $personnel; // Use the first personnel record
            }
            
            $teacherUser = User::updateOrCreate([
                'email' => 'teacher.test@deped.gov.ph'
            ], [
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'personnel_id' => $teacherPersonnel->id,
            ]);
            
            echo "Created teacher user: {$teacherUser->email} with personnel ID: {$teacherPersonnel->id}\n";
        }
        
        // Create sample service credit requests if none exist
        $existingRequests = ServiceCreditRequest::count();
        
        if ($existingRequests === 0) {
            $requests = [
                [
                    'teacher_id' => $teacherUser->personnel->id,
                    'requested_days' => 1.0,
                    'work_date' => now()->subDays(3),
                    'morning_in' => '07:30',
                    'morning_out' => '11:30',
                    'afternoon_in' => '12:30',
                    'afternoon_out' => '16:30',
                    'total_hours' => 8.0,
                    'reason' => 'School event coordination during weekend',
                    'description' => 'Coordinated and supervised the school science fair event on Saturday',
                    'status' => 'pending',
                ],
                [
                    'teacher_id' => $teacherUser->personnel->id,
                    'requested_days' => 0.5,
                    'work_date' => now()->subDays(7),
                    'morning_in' => '08:00',
                    'morning_out' => '12:00',
                    'afternoon_in' => null,
                    'afternoon_out' => null,
                    'total_hours' => 4.0,
                    'reason' => 'Emergency school cleaning after storm',
                    'description' => 'Helped clean school premises after typhoon damage on Sunday morning',
                    'status' => 'pending',
                ],
                [
                    'teacher_id' => $teacherUser->personnel->id,
                    'requested_days' => 1.0,
                    'work_date' => now()->subDays(14),
                    'morning_in' => '08:00',
                    'morning_out' => '12:00',
                    'afternoon_in' => '13:00',
                    'afternoon_out' => '17:00',
                    'total_hours' => 8.0,
                    'reason' => 'Parent-teacher conference preparation',
                    'description' => 'Prepared materials and setup for quarterly parent-teacher conference',
                    'status' => 'approved',
                    'approved_at' => now()->subDays(10),
                    'approved_by' => 1, // Assuming admin user ID is 1
                    'admin_notes' => 'Approved for extra work during weekend',
                ],
            ];
            
            foreach ($requests as $requestData) {
                ServiceCreditRequest::create($requestData);
            }
            
            echo "Created " . count($requests) . " sample service credit requests\n";
        } else {
            echo "Service credit requests already exist: $existingRequests records\n";
        }
        
        // Summary
        $pendingCount = ServiceCreditRequest::where('status', 'pending')->count();
        $approvedCount = ServiceCreditRequest::where('status', 'approved')->count();
        
        echo "\nService Credit Request Summary:\n";
        echo "- Total: " . ServiceCreditRequest::count() . "\n";
        echo "- Pending: $pendingCount\n";
        echo "- Approved: $approvedCount\n";
        echo "- Teacher users: " . User::where('role', 'teacher')->count() . "\n";
        echo "- Teachers with personnel: " . User::where('role', 'teacher')->whereHas('personnel')->count() . "\n";
    }
}
