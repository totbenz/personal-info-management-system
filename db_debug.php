<?php

require_once __DIR__ . '/vendor/autoload.php';

// Create Laravel application instance
$app = require_once __DIR__ . '/bootstrap/app.php';

// Bootstrap the application
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Checking database connection and tables...\n\n";
    
    // Test basic connection
    $result = DB::select('SELECT DATABASE() as db_name');
    echo "Connected to database: " . $result[0]->db_name . "\n\n";
    
    // Check if cto_entries table exists
    try {
        $result = DB::select("SHOW TABLES LIKE 'cto_entries'");
        if (count($result) > 0) {
            echo "✓ cto_entries table exists\n";
            
            // Show table structure
            $columns = DB::select("DESCRIBE cto_entries");
            echo "Table structure:\n";
            foreach ($columns as $column) {
                echo "  - {$column->Field} ({$column->Type})\n";
            }
        } else {
            echo "✗ cto_entries table does NOT exist\n";
        }
    } catch (Exception $e) {
        echo "Error checking cto_entries: " . $e->getMessage() . "\n";
    }
    
    // Check if cto_usages table exists
    try {
        $result = DB::select("SHOW TABLES LIKE 'cto_usages'");
        if (count($result) > 0) {
            echo "\n✓ cto_usages table exists\n";
        } else {
            echo "\n✗ cto_usages table does NOT exist\n";
        }
    } catch (Exception $e) {
        echo "Error checking cto_usages: " . $e->getMessage() . "\n";
    }
    
    // Show all tables starting with 'cto'
    echo "\nAll tables starting with 'cto':\n";
    $tables = DB::select("SHOW TABLES LIKE 'cto%'");
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            echo "  - {$tableName}\n";
        }
    } else {
        echo "  No tables found starting with 'cto'\n";
    }
    
    // Check migrations table to see if our migrations ran
    echo "\nChecking migrations table for CTO migrations:\n";
    $migrations = DB::select("SELECT migration FROM migrations WHERE migration LIKE '%cto%' ORDER BY batch DESC");
    if (count($migrations) > 0) {
        foreach ($migrations as $migration) {
            echo "  ✓ {$migration->migration}\n";
        }
    } else {
        echo "  No CTO migrations found in migrations table\n";
        
        // Show recent migrations
        echo "\nRecent migrations:\n";
        $recent = DB::select("SELECT migration FROM migrations ORDER BY batch DESC LIMIT 5");
        foreach ($recent as $migration) {
            echo "  - {$migration->migration}\n";
        }
    }
    
} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage() . "\n";
    echo "Please check your database configuration in .env file\n";
}
