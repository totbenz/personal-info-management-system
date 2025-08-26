<?php
// Simple script to create test service credit request data
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating test service credit request data...\n";

try {
    // Check if table exists
    if (!\Illuminate\Support\Facades\Schema::hasTable('service_credit_requests')) {
        echo "ERROR: service_credit_requests table does not exist!\n";
        echo "Run: php artisan migrate\n";
        exit(1);
    }

    // Check current data
    $currentCount = \App\Models\ServiceCreditRequest::count();
    echo "Current service credit requests: $currentCount\n";

    // Find or create a teacher user with personnel
    $teacherUser = \App\Models\User::where('role', 'teacher')->whereHas('personnel')->first();
    
    if (!$teacherUser) {
        echo "No teacher with personnel found. Creating one...\n";
        
        // Get first personnel record
        $personnel = \App\Models\Personnel::first();
        if (!$personnel) {
            echo "ERROR: No personnel records found!\n";
            exit(1);
        }
        
        // Create teacher user
        $teacherUser = \App\Models\User::create([
            'email' => 'test.teacher@school.edu',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'teacher',
            'personnel_id' => $personnel->id,
        ]);
        
        echo "Created teacher user: {$teacherUser->email}\n";
    } else {
        echo "Found teacher user: {$teacherUser->email}\n";
    }

    // Create test service credit requests
    $testRequests = [
        [
            'teacher_id' => $teacherUser->personnel->id,
            'requested_days' => 1.0,
            'work_date' => now()->subDays(2)->toDateString(),
            'morning_in' => '08:00:00',
            'morning_out' => '12:00:00',
            'afternoon_in' => '13:00:00',
            'afternoon_out' => '17:00:00',
            'total_hours' => 8.0,
            'reason' => 'Weekend school event assistance',
            'description' => 'Helped organize school fair during weekend',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'teacher_id' => $teacherUser->personnel->id,
            'requested_days' => 0.5,
            'work_date' => now()->subDays(5)->toDateString(),
            'morning_in' => '08:00:00',
            'morning_out' => '12:00:00',
            'afternoon_in' => null,
            'afternoon_out' => null,
            'total_hours' => 4.0,
            'reason' => 'Emergency cleanup after storm',
            'description' => 'Assisted in school cleanup after typhoon',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ];

    foreach ($testRequests as $requestData) {
        // Check if similar request exists
        $existing = \App\Models\ServiceCreditRequest::where('teacher_id', $requestData['teacher_id'])
            ->where('work_date', $requestData['work_date'])
            ->first();
        
        if (!$existing) {
            $request = \App\Models\ServiceCreditRequest::create($requestData);
            echo "Created service credit request ID: {$request->id}\n";
        } else {
            echo "Request for {$requestData['work_date']} already exists (ID: {$existing->id})\n";
        }
    }

    // Verify data
    $totalRequests = \App\Models\ServiceCreditRequest::count();
    $pendingRequests = \App\Models\ServiceCreditRequest::where('status', 'pending')->count();
    
    echo "\nSummary:\n";
    echo "Total service credit requests: $totalRequests\n";
    echo "Pending requests: $pendingRequests\n";

    // Test the HomeController query
    echo "\nTesting HomeController query...\n";
    $pendingServiceCreditRequests = \App\Models\ServiceCreditRequest::where('status', 'pending')
        ->with(['teacher'])
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    echo "Found {$pendingServiceCreditRequests->count()} pending requests for dashboard\n";
    
    foreach ($pendingServiceCreditRequests as $request) {
        $teacherName = $request->teacher ? 
            ($request->teacher->first_name . ' ' . $request->teacher->last_name) : 
            'Unknown';
        echo "- ID {$request->id}: {$teacherName}, {$request->requested_days} days, {$request->reason}\n";
    }

    echo "\nSUCCESS: Test data created. Check admin dashboard now.\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
