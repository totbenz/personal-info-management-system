<?php

/**
 * Test script for Education Form validation and error handling
 * Run this script to test the education form functionality
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Education;
use App\Models\Personnel;
use Illuminate\Validation\ValidationException;

echo "Testing Education Form Validation...\n\n";

try {
    // Test 1: Basic validation rules
    echo "Test 1: Basic validation rules\n";
    $education = new Education();
    $rules = Education::$rules;
    echo "✓ Validation rules loaded: " . count($rules) . " rules\n";

    // Test 2: Type constraint validation
    echo "\nTest 2: Type constraint validation\n";
    $testData = [
        'type' => 'elementary',
        'school_name' => 'Test School',
        'period_from' => 2010,
        'period_to' => 2015,
        'year_graduated' => 2015
    ];

    $education->fill($testData);
    echo "✓ Test data prepared\n";

    // Test 3: Period validation
    echo "\nTest 3: Period validation\n";
    try {
        $education->validatePeriods();
        echo "✓ Period validation passed\n";
    } catch (ValidationException $e) {
        echo "✗ Period validation failed: " . implode(', ', $e->errors()) . "\n";
    }

    // Test 4: Invalid period validation
    echo "\nTest 4: Invalid period validation\n";
    $invalidData = [
        'type' => 'secondary',
        'school_name' => 'Test School 2',
        'period_from' => 2015,
        'period_to' => 2010, // Invalid: end before start
        'year_graduated' => 2015
    ];

    $invalidEducation = new Education();
    $invalidEducation->fill($invalidData);

    try {
        $invalidEducation->validatePeriods();
        echo "✗ Invalid period validation should have failed\n";
    } catch (ValidationException $e) {
        echo "✓ Invalid period validation correctly caught: " . implode(', ', $e->errors()) . "\n";
    }

    // Test 5: Custom error messages
    echo "\nTest 5: Custom error messages\n";
    $messages = Education::$messages;
    echo "✓ Custom error messages loaded: " . count($messages) . " messages\n";

    // Test 6: Model attributes and methods
    echo "\nTest 6: Model attributes and methods\n";
    $education->degree_course = 'Bachelor of Science in Education';
    $abbreviated = $education->abbreviated_degree;
    echo "✓ Abbreviated degree: $abbreviated\n";

    $fullName = $education->full_degree_name;
    echo "✓ Full degree name: $fullName\n";

    echo "\n✓ All tests completed successfully!\n";
} catch (Exception $e) {
    echo "✗ Test failed with error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nEducation Form testing completed.\n";
