<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

class ExportImportDatabase extends Command
{
    protected $signature = 'db:csv {action : export or import} {--file= : CSV file path for import}';
    protected $description = 'Export database to CSV or import from CSV';

    public function handle()
    {
        $action = $this->argument('action');

        if ($action === 'export') {
            $this->exportDatabase();
        } elseif ($action === 'import') {
            $this->importDatabase();
        } else {
            $this->error('Invalid action. Use "export" or "import"');
            return 1;
        }

        return 0;
    }

    private function exportDatabase()
    {
        $this->info('Starting database export...');

        $tables = $this->getAllTables();
        $exportDir = storage_path('app/exports/db_export_' . now()->format('Y-m-d_H-i-s'));

        if (!file_exists($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        $exportedFiles = [];

        foreach ($tables as $table) {
            $this->info("Exporting table: {$table}");
            $csvFile = $this->exportTableToCsv($table, $exportDir);
            if ($csvFile) {
                $exportedFiles[] = $csvFile;
                $this->info("✓ Exported: {$table}");
            } else {
                $this->warn("✗ Failed: {$table}");
            }
        }

        // Create ZIP file
        $zipPath = $this->createZipFile($exportedFiles, $exportDir);

        if (file_exists($zipPath)) {
            $this->info("✓ Export completed successfully!");
            $this->info("📁 Export location: {$zipPath}");
            $this->info("📊 Tables exported: " . count($exportedFiles));
        } else {
            $this->error("Export failed!");
        }
    }

    private function importDatabase()
    {
        $file = $this->option('file');

        if (!$file) {
            $this->error('Please specify the CSV file path: --file=path/to/export.zip');
            return 1;
        }

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info('Starting database import...');

        // Extract ZIP
        $extractDir = storage_path('app/temp/import_' . time());
        if (!file_exists($extractDir)) {
            mkdir($extractDir, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($file) !== TRUE) {
            $this->error('Failed to open ZIP file');
            return 1;
        }

        $zip->extractTo($extractDir);
        $zip->close();

        // Define import order based on dependencies
        $importOrder = [
            'salary_grades',
            'salary_steps',
            'district',
            'schools',
            'position',
            'personnels',
            'users',
            'addresses',
            'contact_person',
            'family',
            'educations',
            'civil_service_eligibility',
            'work_experiences',
            'voluntary_works',
            'training_certifications',
            'references',
            'assignment_details',
            'award_received',
            'service_records',
            'other_information',
            'personnel_details',
            'events',
            'signatures',
            'leave_requests',
            'school_head_leaves',
            'cto_requests',
            'teacher_leaves',
            'cto_entries',
            'cto_usages',
            'non_teaching_leaves',
            'service_credit_requests'
        ];

        $csvFiles = glob($extractDir . '/*.csv');
        $importedTables = 0;

        // Import tables in dependency order
        foreach ($importOrder as $tableName) {
            $csvFile = $extractDir . '/' . $tableName . '.csv';

            if (!file_exists($csvFile)) {
                $this->warn("CSV file not found for table: {$tableName}");
                continue;
            }

            if ($this->shouldSkipTable($tableName)) {
                $this->warn("Skipping system table: {$tableName}");
                continue;
            }

            $this->info("Importing table: {$tableName}");

            if ($this->importTableFromCsv($tableName, $csvFile)) {
                $this->info("✓ Imported: {$tableName}");
                $importedTables++;
            } else {
                $this->warn("✗ Failed: {$tableName}");
            }
        }

        // Cleanup
        $this->cleanupTemp($extractDir);

        $this->info("✓ Import completed!");
        $this->info("📊 Tables imported: {$importedTables}");
    }

    private function getAllTables()
    {
        $tables = DB::select('SHOW TABLES');
        return array_map(function ($table) {
            return $table->Tables_in_laravel;
        }, $tables);
    }

    private function exportTableToCsv($tableName, $exportDir)
    {
        try {
            $data = DB::table($tableName)->get();

            if ($data->isEmpty()) {
                // Create empty CSV with headers
                $columns = Schema::getColumnListing($tableName);
                $csvContent = $this->generateCsvContent($columns, []);
            } else {
                $columns = array_keys((array) $data->first());
                $csvContent = $this->generateCsvContent($columns, $data);
            }

            $filename = $tableName . '.csv';
            $filePath = $exportDir . '/' . $filename;

            if (file_put_contents($filePath, $csvContent) !== false) {
                return $filePath;
            }

            return null;
        } catch (\Exception $e) {
            $this->warn("Error exporting {$tableName}: " . $e->getMessage());
            return null;
        }
    }

    private function generateCsvContent($columns, $data)
    {
        $output = fopen('php://temp', 'r+');

        // Add BOM for proper UTF-8 encoding
        fwrite($output, "\xEF\xBB\xBF");

        // Write headers
        fputcsv($output, $columns);

        // Write data
        foreach ($data as $row) {
            $rowArray = (array) $row;
            fputcsv($output, $rowArray);
        }

        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);

        return $content;
    }

    private function createZipFile($files, $exportDir)
    {
        $zipPath = $exportDir . '/database_export.zip';
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Failed to create ZIP file');
        }

        foreach ($files as $filePath) {
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
            }
        }

        $zip->close();
        return $zipPath;
    }

    private function shouldSkipTable($tableName)
    {
        $skipTables = ['migrations', 'password_reset_tokens', 'personal_access_tokens'];
        return in_array($tableName, $skipTables);
    }

    private function importTableFromCsv($tableName, $csvFile)
    {
        try {
            if (!Schema::hasTable($tableName)) {
                $this->warn("Table {$tableName} does not exist, skipping...");
                return false;
            }

            $data = $this->readCsvFile($csvFile);

            if (empty($data)) {
                $this->warn("No data found in {$tableName}");
                return true;
            }

            // Clear existing data (disable foreign key checks temporarily)
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table($tableName)->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Clean and prepare data
            $cleanData = [];
            foreach ($data as $row) {
                $cleanRow = [];
                foreach ($row as $key => $value) {
                    // Handle empty values
                    if ($value === '' || $value === null) {
                        $cleanRow[$key] = null;
                    } else {
                        $cleanRow[$key] = $value;
                    }
                }
                $cleanData[] = $cleanRow;
            }

            // Import data in chunks
            $chunks = array_chunk($cleanData, 1000);
            foreach ($chunks as $chunk) {
                DB::table($tableName)->insert($chunk);
            }

            return true;
        } catch (\Exception $e) {
            $this->warn("Error importing {$tableName}: " . $e->getMessage());
            return false;
        }
    }

    private function readCsvFile($filePath)
    {
        $data = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            return $data;
        }

        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return $data;
        }

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                $data[] = array_combine($headers, $row);
            }
        }

        fclose($handle);
        return $data;
    }

    private function cleanupTemp($dir)
    {
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($dir);
        }
    }
}
