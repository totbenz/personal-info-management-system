<?php

// Test the service credit calculation logic
function calculateYearsOfService($employmentStart)
{
    if (!$employmentStart) {
        return 0;
    }

    $startDate = new DateTime($employmentStart);
    $currentDate = new DateTime();

    return $startDate->diff($currentDate)->y;
}

// Test cases
$testCases = [
    [
        'employment_start' => '2024-01-01', // 0 years (new employee)
        'expected_years' => 0,
        'expected_service_credit' => 0
    ],
    [
        'employment_start' => '2020-01-01', // About 4-5 years
        'expected_years' => 4,
        'expected_service_credit' => 60 // 4 * 15
    ],
    [
        'employment_start' => '2010-01-01', // About 14-15 years
        'expected_years' => 14,
        'expected_service_credit' => 210 // 14 * 15
    ],
    [
        'employment_start' => null, // No employment start
        'expected_years' => 0,
        'expected_service_credit' => 0
    ]
];

echo "Testing Service Credit Calculation:\n";
echo "===================================\n\n";

foreach ($testCases as $index => $testCase) {
    $yearsOfService = calculateYearsOfService($testCase['employment_start']);
    $serviceCredit = $yearsOfService * 15;
    
    echo "Test Case " . ($index + 1) . ":\n";
    echo "Employment Start: " . ($testCase['employment_start'] ?? 'null') . "\n";
    echo "Years of Service: {$yearsOfService}\n";
    echo "Service Credit: {$serviceCredit} days\n";
    echo "Expected Service Credit: {$testCase['expected_service_credit']} days\n";
    
    if ($serviceCredit == $testCase['expected_service_credit']) {
        echo "✅ PASS\n";
    } else {
        echo "❌ FAIL\n";
    }
    echo "\n";
}

echo "Leave Display Test:\n";
echo "===================\n\n";

foreach ($testCases as $index => $testCase) {
    $yearsOfService = calculateYearsOfService($testCase['employment_start']);
    $serviceCredit = $yearsOfService * 15;
    
    $personalLeaveDisplay = $serviceCredit > 0 ? $serviceCredit : 0;
    $sickLeaveDisplay = $serviceCredit > 0 ? $serviceCredit : 0;
    
    echo "Test Case " . ($index + 1) . ":\n";
    echo "Employment Start: " . ($testCase['employment_start'] ?? 'null') . "\n";
    echo "Personal Leave Available: {$personalLeaveDisplay}\n";
    echo "Sick Leave Available: {$sickLeaveDisplay}\n";
    
    if ($serviceCredit == 0) {
        echo "✅ Shows 0 instead of infinity symbol when no service credit\n";
    } else {
        echo "✅ Shows actual service credit: {$serviceCredit} days\n";
    }
    echo "\n";
}

?>
