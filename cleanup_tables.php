<?php

require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $pdo = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    
    echo "Connected to database successfully!\n";
    
    // Drop the incomplete teacher_leaves table
    $pdo->exec("DROP TABLE IF EXISTS teacher_leaves");
    echo "Dropped teacher_leaves table\n";
    
    // Also drop non_teaching_leaves in case it exists but wasn't showing
    $pdo->exec("DROP TABLE IF EXISTS non_teaching_leaves");
    echo "Dropped non_teaching_leaves table (if it existed)\n";
    
    echo "Tables cleaned up. Ready for fresh migration.\n";
    
} catch (PDOException $e) {
    echo "Database operation failed: " . $e->getMessage() . "\n";
}
