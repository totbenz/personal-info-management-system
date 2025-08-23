<?php

namespace App\Console\Commands;

use App\Services\CTOService;
use App\Models\CTOEntry;
use App\Models\Personnel;
use Illuminate\Console\Command;

class CTOManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cto:manage 
                            {action : The action to perform (balance|history|expire|summary)}
                            {--school-head= : School head ID for specific operations}
                            {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage CTO entries and balances';

    protected $ctoService;

    public function __construct(CTOService $ctoService)
    {
        parent::__construct();
        $this->ctoService = $ctoService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $schoolHeadId = $this->option('school-head');

        switch ($action) {
            case 'balance':
                return $this->showBalance($schoolHeadId);
            case 'history':
                return $this->showHistory($schoolHeadId);
            case 'expire':
                return $this->expireCTOs();
            case 'summary':
                return $this->showSummary();
            default:
                $this->error("Unknown action: {$action}");
                return 1;
        }
    }

    private function showBalance($schoolHeadId = null)
    {
        if ($schoolHeadId) {
            $personnel = Personnel::find($schoolHeadId);
            if (!$personnel) {
                $this->error("School head not found with ID: {$schoolHeadId}");
                return 1;
            }

            $balance = $this->ctoService->getCTOBalance($schoolHeadId);
            
            $this->info("CTO Balance for {$personnel->full_name}:");
            $this->table(['Metric', 'Value'], [
                ['Total Available', number_format($balance['total_available'], 1) . ' days'],
                ['Total Earned', number_format($balance['total_earned'], 1) . ' days'],
                ['Total Used', number_format($balance['total_used'], 1) . ' days'],
                ['Expired Days', number_format($balance['expired_days'], 1) . ' days'],
            ]);

            if (!empty($balance['entries'])) {
                $this->info("\nCTO Entries:");
                $entries = [];
                foreach ($balance['entries'] as $entry) {
                    $entries[] = [
                        $entry['id'],
                        number_format($entry['days_remaining'], 1),
                        number_format($entry['days_earned'], 1),
                        $entry['earned_date'],
                        $entry['expiry_date'],
                        $entry['days_until_expiry'] . ' days',
                    ];
                }
                $this->table(
                    ['ID', 'Remaining', 'Earned', 'Earned Date', 'Expiry Date', 'Days Left'],
                    $entries
                );
            }
        } else {
            $this->info("Use --school-head=ID to show balance for a specific school head");
        }

        return 0;
    }

    private function showHistory($schoolHeadId = null)
    {
        if (!$schoolHeadId) {
            $this->error("Please specify a school head ID with --school-head=ID");
            return 1;
        }

        $personnel = Personnel::find($schoolHeadId);
        if (!$personnel) {
            $this->error("School head not found with ID: {$schoolHeadId}");
            return 1;
        }

        $history = $this->ctoService->getCTOUsageHistory($schoolHeadId, 20);
        
        if (empty($history)) {
            $this->info("No CTO usage history found for {$personnel->full_name}");
            return 0;
        }

        $this->info("CTO Usage History for {$personnel->full_name}:");
        
        $historyData = [];
        foreach ($history as $usage) {
            $historyData[] = [
                $usage['used_date'],
                number_format($usage['days_used'], 1),
                $usage['usage_type'],
                $usage['cto_earned_date'] ?? 'N/A',
                $usage['notes'] ?? '',
            ];
        }

        $this->table(
            ['Used Date', 'Days Used', 'Type', 'CTO Earned Date', 'Notes'],
            $historyData
        );

        return 0;
    }

    private function expireCTOs()
    {
        if ($this->option('dry-run')) {
            $expiredCount = CTOEntry::where('expiry_date', '<', now()->toDateString())
                ->where('is_expired', false)
                ->where('days_remaining', '>', 0)
                ->count();
                
            $this->info("DRY RUN: Would expire {$expiredCount} CTO entries");
            return 0;
        }

        $expiredCount = $this->ctoService->expireOldCTOs();
        $this->info("Expired {$expiredCount} CTO entries");

        return 0;
    }

    private function showSummary()
    {
        $totalEntries = CTOEntry::count();
        $activeEntries = CTOEntry::where('days_remaining', '>', 0)
            ->where('is_expired', false)
            ->where('expiry_date', '>=', now()->toDateString())
            ->count();
        $expiredEntries = CTOEntry::where('is_expired', true)->count();
        $totalDaysAvailable = CTOEntry::where('days_remaining', '>', 0)
            ->where('is_expired', false)
            ->where('expiry_date', '>=', now()->toDateString())
            ->sum('days_remaining');
        $totalDaysExpired = CTOEntry::where('is_expired', true)->sum('days_remaining');

        $expiringIn30Days = CTOEntry::where('expiry_date', '>=', now()->toDateString())
            ->where('expiry_date', '<=', now()->addDays(30)->toDateString())
            ->where('is_expired', false)
            ->where('days_remaining', '>', 0)
            ->count();

        $this->info("CTO System Summary:");
        $this->table(['Metric', 'Value'], [
            ['Total CTO Entries', $totalEntries],
            ['Active Entries', $activeEntries],
            ['Expired Entries', $expiredEntries],
            ['Total Days Available', number_format($totalDaysAvailable, 1)],
            ['Total Days Expired', number_format($totalDaysExpired, 1)],
            ['Expiring in 30 Days', $expiringIn30Days],
        ]);

        if ($expiringIn30Days > 0) {
            $this->warn("Warning: {$expiringIn30Days} CTO entries will expire in the next 30 days!");
        }

        return 0;
    }
}
