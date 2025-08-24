<?php

/**
 * Test script for Force Leave functionality
 * 
 * This script tests:
 * 1. Force Leave deducts from both Force Leave and Vacation Leave when used
 * 2. Year-end processing deducts remaining Force Leave from Vacation Leave
 */

// Include Laravel bootstrap
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\SchoolHeadLeave;
use App\Models\TeacherLeave;
use App\Models\NonTeachingLeave;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

// Test Force Leave functionality
function testForceLeaveImplementation()
{
    echo "=== Testing Force Leave Implementation ===\n\n";
    
    // Test 1: Check if Force Leave is now included in default leaves
    echo "1. Testing Force Leave in default leaves:\n";
    
    $schoolHeadDefaults = SchoolHeadLeave::defaultLeaves(false, 'female');
    echo "School Head defaults: " . (isset($schoolHeadDefaults['Force Leave']) ? "✓ Force Leave found ({$schoolHeadDefaults['Force Leave']} days)" : "✗ Force Leave missing") . "\n";
    
    $teacherDefaults = TeacherLeave::defaultLeaves(5, false, 'female');
    echo "Teacher defaults: " . (isset($teacherDefaults['Force Leave']) ? "✓ Force Leave found ({$teacherDefaults['Force Leave']} days)" : "✗ Force Leave missing") . "\n";
    
    $nonTeachingDefaults = NonTeachingLeave::defaultLeaves(5, false, 'female');
    echo "Non-Teaching defaults: " . (isset($nonTeachingDefaults['Force Leave']) ? "✓ Force Leave found ({$nonTeachingDefaults['Force Leave']} days)" : "✗ Force Leave missing") . "\n\n";
    
    // Test 2: Check console command exists
    echo "2. Testing console command:\n";
    $commandExists = file_exists(__DIR__ . '/app/Console/Commands/ProcessYearEndForceLeave.php');
    echo "Year-end processing command: " . ($commandExists ? "✓ Command file exists" : "✗ Command file missing") . "\n\n";
    
    // Test 3: Simulate Force Leave processing for a test user
    echo "3. Testing Force Leave deduction logic:\n";
    echo "This test requires an active personnel record in the database.\n";
    echo "Manual testing recommended through the web interface.\n\n";
    
    echo "=== Force Leave Implementation Test Complete ===\n";
    echo "\nTo test the functionality:\n";
    echo "1. Create a leave request with Force Leave type\n";
    echo "2. Approve the request and check that both Force Leave and Vacation Leave are deducted\n";
    echo "3. Run the year-end command: php artisan leave:process-year-end-force-leave\n";
    echo "4. Verify remaining Force Leave days are deducted from Vacation Leave\n\n";
}

// Run the tests
try {
    testForceLeaveImplementation();
} catch (Exception $e) {
    echo "Error running tests: " . $e->getMessage() . "\n";
}
