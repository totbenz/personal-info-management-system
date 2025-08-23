<?php

namespace App\Console\Commands;

use App\Services\CTOService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireOldCTOs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cto:expire-old {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire CTO entries that are older than 1 year';

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
        $this->info('Starting CTO expiration process...');

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN MODE - No changes will be made');
            
            // Count how many would be expired
            $expiredCount = \App\Models\CTOEntry::where('expiry_date', '<', now()->toDateString())
                ->where('is_expired', false)
                ->where('days_remaining', '>', 0)
                ->count();
                
            $this->info("Would expire {$expiredCount} CTO entries");
            return;
        }

        try {
            $expiredCount = $this->ctoService->expireOldCTOs();
            
            if ($expiredCount > 0) {
                $this->info("Successfully expired {$expiredCount} CTO entries");
                Log::info("CTO expiration command completed", [
                    'expired_count' => $expiredCount,
                    'executed_at' => now()
                ]);
            } else {
                $this->info("No CTO entries needed to be expired");
            }

        } catch (\Exception $e) {
            $this->error("Failed to expire CTO entries: " . $e->getMessage());
            Log::error("CTO expiration command failed", [
                'error' => $e->getMessage(),
                'executed_at' => now()
            ]);
            return 1;
        }

        return 0;
    }
}
