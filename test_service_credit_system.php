<?php

// Simple test script to verify Service Credit functionality
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ServiceCreditRequest;
use App\Models\Personnel;
use App\Models\User;

echo "Service Credit System Test\n";
echo "==========================\n\n";

// Test 1: Check if ServiceCreditRequest model works
try {
    $totalRequests = ServiceCreditRequest::count();
    echo "✓ ServiceCreditRequest model works. Total requests: {$totalRequests}\n";
} catch (Exception $e) {
    echo "✗ ServiceCreditRequest model error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check pending requests
try {
    $pendingRequests = ServiceCreditRequest::where('status', 'pending')->count();
    echo "✓ Pending service credit requests: {$pendingRequests}\n";
} catch (Exception $e) {
    echo "✗ Pending requests query error: " . $e->getMessage() . "\n";
}

// Test 3: Check if we have teachers in the system
try {
    $teacherUsers = User::where('role', 'teacher')->count();
    echo "✓ Teacher users in system: {$teacherUsers}\n";
    
    $teachersWithPersonnel = User::where('role', 'teacher')
        ->whereHas('personnel')
        ->count();
    echo "✓ Teachers with personnel records: {$teachersWithPersonnel}\n";
    
    if ($teachersWithPersonnel > 0) {
        $teacher = User::where('role', 'teacher')
            ->whereHas('personnel')
            ->with('personnel')
            ->first();
        echo "✓ Sample teacher: {$teacher->email} (Personnel ID: {$teacher->personnel->id})\n";
    }
} catch (Exception $e) {
    echo "✗ Teacher query error: " . $e->getMessage() . "\n";
}

// Test 4: Create a test service credit request if we have teachers
try {
    $teacher = User::where('role', 'teacher')
        ->whereHas('personnel')
        ->with('personnel')
        ->first();
    
    if ($teacher) {
        // Check if test request already exists
        $existingTest = ServiceCreditRequest::where('teacher_id', $teacher->personnel->id)
            ->where('reason', 'LIKE', 'Test request%')
            ->first();
        
        if (!$existingTest) {
            $testRequest = ServiceCreditRequest::create([
                'teacher_id' => $teacher->personnel->id,
                'requested_days' => 1.0,
                'work_date' => now()->subDays(1),
                'morning_in' => '08:00',
                'morning_out' => '12:00',
                'afternoon_in' => '13:00',
                'afternoon_out' => '17:00',
                'total_hours' => 8.0,
                'reason' => 'Test request for debugging',
                'description' => 'This is a test request created to verify the system works',
                'status' => 'pending',
            ]);
            echo "✓ Test service credit request created with ID: {$testRequest->id}\n";
        } else {
            echo "✓ Test request already exists with ID: {$existingTest->id}\n";
        }
    } else {
        echo "⚠ No teachers with personnel records found - cannot create test request\n";
    }
} catch (Exception $e) {
    echo "✗ Test request creation error: " . $e->getMessage() . "\n";
}

// Test 5: Verify HomeController data fetching
try {
    $pendingServiceCreditRequests = ServiceCreditRequest::where('status', 'pending')
        ->with(['teacher'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    echo "✓ HomeController query simulation successful. Found {$pendingServiceCreditRequests->count()} pending requests\n";
    
    foreach ($pendingServiceCreditRequests as $request) {
        $teacherName = $request->teacher ? 
            $request->teacher->first_name . ' ' . $request->teacher->last_name :
            'Unknown Teacher';
        echo "  - Request ID {$request->id}: {$teacherName}, {$request->requested_days} days, Status: {$request->status}\n";
    }
} catch (Exception $e) {
    echo "✗ HomeController simulation error: " . $e->getMessage() . "\n";
}

echo "\nService Credit System Test Complete!\n";
echo "Check the admin dashboard to see if pending requests appear.\n";
