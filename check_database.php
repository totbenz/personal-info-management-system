<?php
// Check database tables exist
require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Database Connection Test\n";
echo "========================\n\n";

try {
    // Test database connection
    DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    
    // Check if cto_entries table exists
    if (Schema::hasTable('cto_entries')) {
        echo "✓ cto_entries table exists\n";
        
        // Get table columns
        $columns = Schema::getColumnListing('cto_entries');
        echo "✓ Table columns: " . implode(', ', $columns) . "\n";
        
        // Count records
        $count = DB::table('cto_entries')->count();
        echo "✓ Records in cto_entries: {$count}\n";
    } else {
        echo "✗ cto_entries table does NOT exist\n";
    }
    
    // Check if cto_usages table exists
    if (Schema::hasTable('cto_usages')) {
        echo "✓ cto_usages table exists\n";
        
        // Count records
        $count = DB::table('cto_usages')->count();
        echo "✓ Records in cto_usages: {$count}\n";
    } else {
        echo "✗ cto_usages table does NOT exist\n";
    }
    
    // Show all tables
    echo "\nAll tables in database:\n";
    $tables = DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- {$tableName}\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}
