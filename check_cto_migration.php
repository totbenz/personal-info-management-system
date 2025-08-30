<?php
// Quick test to check if CTO time segments migration is needed

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "CTO Time Segments Migration Check\n";
echo "=================================\n\n";

try {
    // Check if the new columns exist
    $hasColumns = Schema::hasColumns('cto_requests', [
        'morning_in', 
        'morning_out', 
        'afternoon_in', 
        'afternoon_out', 
        'total_hours'
    ]);
    
    if ($hasColumns) {
        echo "✓ All new CTO time segment columns exist!\n";
        echo "✓ Migration appears to have run successfully.\n\n";
        
        // Check if there are any existing CTO requests
        $ctoCount = DB::table('cto_requests')->count();
        echo "Current CTO requests in database: {$ctoCount}\n";
        
        if ($ctoCount > 0) {
            echo "\nNote: Existing CTO requests will use legacy start_time/end_time fields.\n";
            echo "New requests will use the time segment fields.\n";
        }
        
    } else {
        echo "✗ Migration needs to be run!\n";
        echo "Run: php artisan migrate --force\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error checking database: " . $e->getMessage() . "\n";
    echo "Make sure the database is accessible and run: php artisan migrate --force\n";
}

echo "\nCTO Time Segments Implementation Summary:\n";
echo "- Forms now use morning/afternoon time in/out instead of start/end time\n";
echo "- Hours are calculated automatically from time segments\n";
echo "- At least one complete time pair is required\n";
echo "- System maintains backward compatibility with existing data\n";
