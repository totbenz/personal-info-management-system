<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ImportFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv
                            {zipfile : Path to ZIP file containing CSV exports}
                            {--force : Skip confirmation prompts}
                            {--dry-run : Show what would be imported without executing}
                            {--skip-tables= : Comma-separated list of tables to skip}
                            {--truncate : Truncate existing tables before import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import database from CSV export files (Emergency Recovery Tool)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('⚠️  CSV IMPORT TOOL - EMERGENCY RECOVERY ONLY ⚠️');
        $this->warn('This will import data from CSV files and may overwrite existing data!');

        $zipFile = $this->argument('zipfile');

        if (!$this->option('force') && !$this->option('dry-run')) {
            if (!$this->confirm('Are you absolutely sure you want to import from CSV?')) {
                $this->info('Import cancelled.');
                return 0;
            }

            if (!$this->confirm('Have you verified this is the correct CSV export file?')) {
                $this->info('Import cancelled.');
                return 0;
            }
        }

        try {
            if (!file_exists($zipFile)) {
                $this->error("ZIP file not found: {$zipFile}");
                return 1;
            }

            $this->info("Starting CSV import from: {$zipFile}");

            if ($this->option('dry-run')) {
                $this->info("DRY RUN MODE - No changes will be made");
                $this->analyzeCsvZip($zipFile);
                return 0;
            }

            // Extract and import
            $this->performImport($zipFile);

            $this->info("CSV import completed successfully!");
            return 0;
        } catch (\Exception $e) {
            $this->error("Import failed: " . $e->getMessage());
            Log::error("CSV import failed: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Analyze CSV ZIP file content
     */
    private function analyzeCsvZip($zipFile)
    {
        $this->info("Analyzing CSV ZIP file...");

        $zip = new ZipArchive();
        if ($zip->open($zipFile) !== TRUE) {
            throw new \Exception('Failed to open ZIP file');
        }

        $this->info("ZIP file contains " . $zip->numFiles . " files:");

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            $this->info("  - " . $filename);
        }

        $zip->close();
    }

    /**
     * Perform the actual import
     */
    private function performImport($zipFile)
    {
        $tempDir = storage_path('app/temp/csv_import_' . time());

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        try {
            // Extract ZIP file
            $this->info("Extracting CSV files...");
            $this->extractZip($zipFile, $tempDir);

            // Get list of CSV files
            $csvFiles = glob($tempDir . '/*.csv');

            if (empty($csvFiles)) {
                throw new \Exception('No CSV files found in ZIP');
            }

            $this->info("Found " . count($csvFiles) . " CSV files to import");

            // Import each CSV file
            foreach ($csvFiles as $csvFile) {
                $tableName = pathinfo($csvFile, PATHINFO_FILENAME);

                if ($this->shouldSkipTable($tableName)) {
                    $this->info("Skipping table: {$tableName}");
                    continue;
                }

                $this->info("Importing table: {$tableName}");
                $this->importTableFromCsv($tableName, $csvFile);
            }
        } finally {
            // Clean up temp files
            $this->cleanupTemp($tempDir);
        }
    }

    /**
     * Extract ZIP file
     */
    private function extractZip($zipFile, $extractPath)
    {
        $zip = new ZipArchive();
        if ($zip->open($zipFile) !== TRUE) {
            throw new \Exception('Failed to open ZIP file');
        }

        $zip->extractTo($extractPath);
        $zip->close();
    }

    /**
     * Check if table should be skipped
     */
    private function shouldSkipTable($tableName)
    {
        $skipTables = $this->option('skip-tables');
        if ($skipTables) {
            $skipList = explode(',', $skipTables);
            return in_array($tableName, $skipList);
        }

        // Skip system tables by default
        $systemTables = ['migrations', 'failed_jobs', 'personal_access_tokens', 'password_reset_tokens'];
        return in_array($tableName, $systemTables);
    }

    /**
     * Import a single table from CSV
     */
    private function importTableFromCsv($tableName, $csvFile)
    {
        if (!Schema::hasTable($tableName)) {
            $this->warn("Table '{$tableName}' does not exist, skipping");
            return;
        }

        // Read CSV file
        $csvData = $this->readCsvFile($csvFile);

        if (empty($csvData)) {
            $this->warn("No data found in CSV for table '{$tableName}'");
            return;
        }

        $headers = array_shift($csvData); // First row contains headers
        $this->info("  Headers: " . implode(', ', $headers));
        $this->info("  Rows to import: " . count($csvData));

        // Truncate table if requested
        if ($this->option('truncate')) {
            DB::table($tableName)->truncate();
            $this->info("  Table truncated");
        }

        // Import data in chunks
        $chunkSize = 1000;
        $chunks = array_chunk($csvData, $chunkSize);

        $imported = 0;
        foreach ($chunks as $chunk) {
            $this->importChunk($tableName, $headers, $chunk);
            $imported += count($chunk);
            $this->info("  Imported {$imported}/" . count($csvData) . " rows");
        }

        $this->info("  Table '{$tableName}' import completed");
    }

    /**
     * Read CSV file content
     */
    private function readCsvFile($csvFile)
    {
        $data = [];
        $handle = fopen($csvFile, 'r');

        if ($handle === false) {
            throw new \Exception("Failed to open CSV file: {$csvFile}");
        }

        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        while (($row = fgetcsv($handle)) !== false) {
            $data[] = $row;
        }

        fclose($handle);
        return $data;
    }

    /**
     * Import a chunk of data
     */
    private function importChunk($tableName, $headers, $chunk)
    {
        $insertData = [];

        foreach ($chunk as $row) {
            $rowData = [];

            foreach ($headers as $index => $header) {
                if (isset($row[$index])) {
                    $value = $row[$index];

                    // Handle empty strings and null values
                    if ($value === '') {
                        $value = null;
                    }

                    $rowData[$header] = $value;
                }
            }

            if (!empty($rowData)) {
                $insertData[] = $rowData;
            }
        }

        if (!empty($insertData)) {
            try {
                DB::table($tableName)->insert($insertData);
            } catch (\Exception $e) {
                $this->warn("  Warning: Failed to insert chunk: " . $e->getMessage());
                // Try inserting one by one for better error handling
                foreach ($insertData as $row) {
                    try {
                        DB::table($tableName)->insert($row);
                    } catch (\Exception $e2) {
                        $this->warn("  Warning: Failed to insert row: " . $e2->getMessage());
                    }
                }
            }
        }
    }

    /**
     * Clean up temporary files
     */
    private function cleanupTemp($tempDir)
    {
        if (is_dir($tempDir)) {
            $files = glob($tempDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($tempDir);
        }
    }
}
