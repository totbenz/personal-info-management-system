<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore:database
                            {backup : Path to backup file or "latest" for most recent}
                            {--type=auto : Type of backup (auto, full, structure, data)}
                            {--force : Skip confirmation prompts}
                            {--dry-run : Show what would be restored without executing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database from backup file (Emergency Recovery Tool)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('⚠️  DATABASE RESTORE TOOL - USE WITH EXTREME CAUTION ⚠️');
        $this->warn('This will overwrite your current database!');

        if (!$this->option('force') && !$this->option('dry-run')) {
            if (!$this->confirm('Are you absolutely sure you want to restore the database?')) {
                $this->info('Restore cancelled.');
                return 0;
            }

            if (!$this->confirm('Have you verified this is the correct backup file?')) {
                $this->info('Restore cancelled.');
                return 0;
            }
        }

        try {
            $backupPath = $this->resolveBackupPath($this->argument('backup'));
            $type = $this->option('type');

            if (!$backupPath || !file_exists($backupPath)) {
                $this->error("Backup file not found: {$backupPath}");
                return 1;
            }

            $this->info("Restoring from backup: {$backupPath}");
            $this->info("Backup type: {$type}");

            if ($this->option('dry-run')) {
                $this->info("DRY RUN MODE - No changes will be made");
                $this->analyzeBackup($backupPath);
                return 0;
            }

            // Perform the restore
            $this->performRestore($backupPath, $type);

            $this->info("Database restore completed successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Restore failed: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Resolve backup path
     */
    private function resolveBackupPath($backup)
    {
        if ($backup === 'latest') {
            return $this->findLatestBackup();
        }

        // Check if it's a relative path
        if (!file_exists($backup)) {
            $backup = storage_path('app/backups/' . $backup);
        }

        return $backup;
    }

    /**
     * Find the latest backup file
     */
    private function findLatestBackup()
    {
        $backupDir = storage_path('app/backups');

        if (!is_dir($backupDir)) {
            throw new \Exception('Backup directory not found');
        }

        $files = glob($backupDir . '/*.sql*');

        if (empty($files)) {
            throw new \Exception('No backup files found');
        }

        // Sort by modification time, newest first
        usort($files, function ($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        return $files[0];
    }

    /**
     * Analyze backup file content
     */
    private function analyzeBackup($backupPath)
    {
        $this->info("Analyzing backup file...");

        $content = file_get_contents($backupPath);
        $lines = explode("\n", $content);

        $tables = [];
        $dataCount = 0;

        foreach ($lines as $line) {
            if (preg_match('/CREATE TABLE `([^`]+)`/', $line, $matches)) {
                $tables[] = $matches[1];
            }
            if (preg_match('/INSERT INTO/', $line)) {
                $dataCount++;
            }
        }

        $this->info("Tables found: " . count($tables));
        $this->info("Data inserts: " . $dataCount);
        $this->info("File size: " . number_format(filesize($backupPath)) . " bytes");
        $this->info("Modified: " . date('Y-m-d H:i:s', filemtime($backupPath)));

        if (count($tables) > 0) {
            $this->info("Sample tables: " . implode(', ', array_slice($tables, 0, 5)));
        }
    }

    /**
     * Perform the actual restore
     */
    private function performRestore($backupPath, $type)
    {
        $dbName = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Check if backup is compressed
        if (pathinfo($backupPath, PATHINFO_EXTENSION) === 'zip') {
            $backupPath = $this->extractBackup($backupPath);
        }

        $this->info("Starting database restore...");

        // Create restore command
        $command = "mysql -u {$username} -p{$password} -h {$host} {$dbName} < {$backupPath}";

        // Execute restore
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception("Database restore failed with return code: {$returnCode}");
        }

        $this->info("Database restore completed!");

        // Verify restore
        $this->verifyRestore();
    }

    /**
     * Extract compressed backup
     */
    private function extractBackup($zipPath)
    {
        $this->info("Extracting compressed backup...");

        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== TRUE) {
            throw new \Exception('Failed to open ZIP file');
        }

        $extractPath = storage_path('app/temp/restore_' . time());
        if (!file_exists($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zip->extractTo($extractPath);
        $zip->close();

        // Find the SQL file
        $files = glob($extractPath . '/*.sql');
        if (empty($files)) {
            throw new \Exception('No SQL file found in backup');
        }

        return $files[0];
    }

    /**
     * Verify the restore was successful
     */
    private function verifyRestore()
    {
        $this->info("Verifying restore...");

        try {
            // Check if we can connect to database
            DB::connection()->getPdo();

            // Check if tables exist
            $tables = Schema::getAllTables();
            $this->info("Database connection successful");
            $this->info("Tables found: " . count($tables));

            // Check some key tables
            $keyTables = ['users', 'personnels', 'schools'];
            foreach ($keyTables as $table) {
                if (Schema::hasTable($table)) {
                    $count = DB::table($table)->count();
                    $this->info("Table '{$table}': {$count} records");
                } else {
                    $this->warn("Table '{$table}' not found");
                }
            }
        } catch (\Exception $e) {
            $this->warn("Verification warning: " . $e->getMessage());
        }
    }
}
