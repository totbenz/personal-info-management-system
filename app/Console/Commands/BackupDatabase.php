<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use ZipArchive;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--type=full : Type of backup (full, structure, data)} {--compress=true : Compress backup files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create automated database backup with cloud storage support';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $compress = $this->option('compress') === 'true';

        $this->info("Starting {$type} database backup...");

        try {
            $backupPath = $this->createBackup($type, $compress);

            if ($backupPath) {
                $this->info("Backup completed successfully!");
                $this->info("Backup location: {$backupPath}");

                // Upload to cloud storage if configured
                $this->uploadToCloud($backupPath);

                // Clean up old backups
                $this->cleanupOldBackups();

                return 0;
            }

            $this->error("Backup failed!");
            return 1;
        } catch (\Exception $e) {
            $this->error("Backup error: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Create the backup based on type
     */
    private function createBackup($type, $compress)
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupDir = storage_path('app/backups');

        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        switch ($type) {
            case 'structure':
                return $this->backupStructure($backupDir, $timestamp, $compress);
            case 'data':
                return $this->backupData($backupDir, $timestamp, $compress);
            case 'full':
            default:
                return $this->backupFull($backupDir, $timestamp, $compress);
        }
    }

    /**
     * Backup database structure only
     */
    private function backupStructure($backupDir, $timestamp, $compress)
    {
        $filename = "db_structure_{$timestamp}.sql";
        $filepath = $backupDir . '/' . $filename;

        // Get database name
        $dbName = config('database.connections.mysql.database');

        // Create structure-only backup
        $command = "mysqldump --no-data --routines --triggers --single-transaction -u " .
            config('database.connections.mysql.username') .
            " -p" . config('database.connections.mysql.password') .
            " -h " . config('database.connections.mysql.host') .
            " {$dbName} > {$filepath}";

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception("Failed to create structure backup");
        }

        if ($compress) {
            return $this->compressFile($filepath);
        }

        return $filepath;
    }

    /**
     * Backup data only
     */
    private function backupData($backupDir, $timestamp, $compress)
    {
        $filename = "db_data_{$timestamp}.sql";
        $filepath = $backupDir . '/' . $filename;

        // Get database name
        $dbName = config('database.connections.mysql.database');

        // Create data-only backup
        $command = "mysqldump --no-create-info --routines --triggers --single-transaction -u " .
            config('database.connections.mysql.username') .
            " -p" . config('database.connections.mysql.password') .
            " -h " . config('database.connections.mysql.host') .
            " {$dbName} > {$filepath}";

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception("Failed to create data backup");
        }

        if ($compress) {
            return $this->compressFile($filepath);
        }

        return $filepath;
    }

    /**
     * Full database backup
     */
    private function backupFull($backupDir, $timestamp, $compress)
    {
        $filename = "db_full_{$timestamp}.sql";
        $filepath = $backupDir . '/' . $filename;

        // Get database name
        $dbName = config('database.connections.mysql.database');

        // Create full backup
        $command = "mysqldump --routines --triggers --single-transaction -u " .
            config('database.connections.mysql.username') .
            " -p" . config('database.connections.mysql.password') .
            " -h " . config('database.connections.mysql.host') .
            " {$dbName} > {$filepath}";

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new \Exception("Failed to create full backup");
        }

        if ($compress) {
            return $this->compressFile($filepath);
        }

        return $filepath;
    }

    /**
     * Compress backup file
     */
    private function compressFile($filepath)
    {
        $zipPath = $filepath . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Failed to create ZIP file');
        }

        $zip->addFile($filepath, basename($filepath));
        $zip->close();

        // Remove original SQL file
        unlink($filepath);

        return $zipPath;
    }

    /**
     * Upload backup to cloud storage
     */
    private function uploadToCloud($backupPath)
    {
        if (!config('backup.cloud.enabled', false)) {
            $this->info("Cloud backup disabled in config");
            return;
        }

        try {
            $filename = basename($backupPath);
            $cloudPath = 'backups/' . date('Y/m') . '/' . $filename;

            // Upload to configured disk (s3, google, etc.)
            $disk = Storage::disk(config('backup.cloud.disk', 's3'));
            $disk->put($cloudPath, file_get_contents($backupPath));

            $this->info("Backup uploaded to cloud: {$cloudPath}");
        } catch (\Exception $e) {
            $this->warn("Cloud upload failed: " . $e->getMessage());
        }
    }

    /**
     * Clean up old backups
     */
    private function cleanupOldBackups()
    {
        $backupDir = storage_path('app/backups');
        $maxAge = config('backup.retention.days', 30);

        if (!is_dir($backupDir)) {
            return;
        }

        $files = glob($backupDir . '/*');
        $cutoff = Carbon::now()->subDays($maxAge)->timestamp;

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $cutoff) {
                unlink($file);
                $this->info("Cleaned up old backup: " . basename($file));
            }
        }
    }
}
