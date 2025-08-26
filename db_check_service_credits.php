<?php
// Simple database check to debug the service credit requests issue
$host = '127.0.0.1';
$dbname = 'laravel';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database Connection Test\n";
    echo "========================\n\n";
    
    // Check if service_credit_requests table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'service_credit_requests'");
    $tableExists = $stmt->rowCount() > 0;
    echo "service_credit_requests table exists: " . ($tableExists ? "YES" : "NO") . "\n";
    
    if ($tableExists) {
        // Check table structure
        $stmt = $pdo->query("DESCRIBE service_credit_requests");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "\nTable Structure:\n";
        foreach ($columns as $column) {
            echo "- {$column['Field']} ({$column['Type']}) " . ($column['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
        }
        
        // Check record count
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM service_credit_requests");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "\nTotal service credit requests: $count\n";
        
        // Check pending requests
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM service_credit_requests WHERE status = 'pending'");
        $pendingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "Pending service credit requests: $pendingCount\n";
        
        if ($count > 0) {
            echo "\nLatest 3 requests:\n";
            $stmt = $pdo->query("SELECT id, teacher_id, status, reason, created_at FROM service_credit_requests ORDER BY id DESC LIMIT 3");
            $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($requests as $request) {
                echo "- ID: {$request['id']}, Teacher: {$request['teacher_id']}, Status: {$request['status']}, Created: {$request['created_at']}\n";
            }
        }
    }
    
    // Check users table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'");
    $teacherCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\nTeacher users: $teacherCount\n";
    
    // Check personnels table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM personnels");
    $personnelCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Personnel records: $personnelCount\n";
    
    // Check if there are teachers with personnel records
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM users u 
        JOIN personnels p ON u.personnel_id = p.id 
        WHERE u.role = 'teacher'
    ");
    $teachersWithPersonnel = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "Teachers with personnel records: $teachersWithPersonnel\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}
?>
