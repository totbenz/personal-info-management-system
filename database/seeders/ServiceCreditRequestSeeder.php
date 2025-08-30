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

        // Always add more service credit requests using the factory
        $newCount = 10;
        \App\Models\ServiceCreditRequest::factory()->count($newCount)->create();
        echo "Added $newCount sample service credit requests using factory\n";

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
