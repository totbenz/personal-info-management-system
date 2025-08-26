<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ServiceCreditRequest;
use App\Models\User;
use App\Models\Personnel;

class CheckServiceCreditSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:service-credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and debug the service credit request system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Service Credit System Check');
        $this->info('==========================');
        
        // Check database table
        $tableExists = \Schema::hasTable('service_credit_requests');
        $this->info('Table exists: ' . ($tableExists ? 'YES' : 'NO'));
        
        if (!$tableExists) {
            $this->error('service_credit_requests table does not exist. Run migrations first.');
            return;
        }
        
        // Check table structure
        $columns = \Schema::getColumnListing('service_credit_requests');
        $this->info('Table columns: ' . implode(', ', $columns));
        
        // Check data
        $totalRequests = ServiceCreditRequest::count();
        $pendingRequests = ServiceCreditRequest::where('status', 'pending')->count();
        $approvedRequests = ServiceCreditRequest::where('status', 'approved')->count();
        
        $this->info("Total requests: $totalRequests");
        $this->info("Pending requests: $pendingRequests");
        $this->info("Approved requests: $approvedRequests");
        
        // Check users
        $teacherUsers = User::where('role', 'teacher')->count();
        $teachersWithPersonnel = User::where('role', 'teacher')->whereHas('personnel')->count();
        $personnelCount = Personnel::count();
        
        $this->info("Teacher users: $teacherUsers");
        $this->info("Teachers with personnel: $teachersWithPersonnel");
        $this->info("Total personnel: $personnelCount");
        
        // Check if we can fetch the data like HomeController does
        try {
            $pendingServiceCreditRequests = ServiceCreditRequest::where('status', 'pending')
                ->with(['teacher'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            
            $this->info("HomeController simulation: Found {$pendingServiceCreditRequests->count()} pending requests");
            
            foreach ($pendingServiceCreditRequests as $request) {
                $teacherName = $request->teacher ? 
                    $request->teacher->first_name . ' ' . $request->teacher->last_name :
                    'Unknown Teacher';
                $this->info("  - Request ID {$request->id}: {$teacherName}, {$request->requested_days} days, Status: {$request->status}");
            }
        } catch (\Exception $e) {
            $this->error("Error fetching data: " . $e->getMessage());
        }
        
        // Provide recommendations
        if ($teachersWithPersonnel === 0) {
            $this->warn('No teachers with personnel records found. Create teacher users with personnel records.');
        }
        
        if ($pendingRequests === 0) {
            $this->warn('No pending service credit requests found. Run the seeder to create test data.');
            $this->info('Run: php artisan db:seed --class=ServiceCreditRequestSeeder');
        }
        
        $this->info('Check complete!');
    }
}
