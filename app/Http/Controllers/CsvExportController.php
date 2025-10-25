<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Carbon\Carbon;

class CsvExportController extends Controller
{
    /**
     * Export all database tables to CSV files
     */
    public function exportAllTables()
    {
        // Set timeout and memory limits for CSV export operations
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '1024M'); // 1GB

        try {
            $tables = $this->getAllTables();
            $tempDir = storage_path('app/temp/csv_export_' . time());

            Log::info('Starting CSV export. Tables found: ' . count($tables));
            Log::info('Temp directory: ' . $tempDir);

            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
                Log::info('Created temp directory: ' . $tempDir);
            }

            $exportedFiles = [];

            foreach ($tables as $table) {
                Log::info('Exporting table: ' . $table);
                $csvFile = $this->exportTableToCsv($table, $tempDir);
                if ($csvFile) {
                    $exportedFiles[] = $csvFile;
                    Log::info('Successfully exported table: ' . $table . ' to: ' . $csvFile);
                } else {
                    Log::warning('Failed to export table: ' . $table);
                }
            }

            if (empty($exportedFiles)) {
                return response()->json(['error' => 'No tables could be exported'], 500);
            }

            // Create ZIP file
            $zipPath = $this->createZipFile($exportedFiles, $tempDir);

            // Verify ZIP file was created
            if (!file_exists($zipPath)) {
                throw new \Exception('ZIP file was not created successfully at: ' . $zipPath);
            }

            $filename = 'database_export_' . now()->format('Y-m-d_H-i-s') . '.zip';

            // Return ZIP file for download - Laravel will handle cleanup after download
            return response()->download($zipPath, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('CSV export failed: ' . $e->getMessage());
            return response()->json(['error' => 'Export failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all database tables
     */
    private function getAllTables()
    {
        $tables = [];
        $dbTables = DB::select('SHOW TABLES');

        foreach ($dbTables as $table) {
            $tableName = array_values((array) $table)[0];
            // Skip migrations and failed_jobs tables
            if (!in_array($tableName, ['migrations', 'failed_jobs', 'personal_access_tokens', 'password_reset_tokens'])) {
                $tables[] = $tableName;
            }
        }

        return $tables;
    }

    /**
     * Export a single table to CSV file
     */
    private function exportTableToCsv($tableName, $tempDir)
    {
        try {
            $csvContent = $this->generateCsvContent($tableName);
            $filename = $tableName . '.csv';
            $filePath = $tempDir . '/' . $filename;

            if (file_put_contents($filePath, $csvContent) !== false) {
                return $filePath; // Return just the file path, not an array
            }

            return null;
        } catch (\Exception $e) {
            Log::warning("Failed to export table {$tableName}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate CSV content for a table
     */
    private function generateCsvContent($tableName)
    {
        $columns = Schema::getColumnListing($tableName);

        // Use chunking for large tables to avoid memory issues
        $output = fopen('php://temp', 'r+');

        // Add BOM for proper UTF-8 encoding in Excel
        fwrite($output, "\xEF\xBB\xBF");

        // Write headers
        fputcsv($output, $columns);

        // Process data in chunks to handle large tables
        // Check if table has an 'id' column, otherwise use first column for ordering
        $orderByColumn = in_array('id', $columns) ? 'id' : $columns[0];
        DB::table($tableName)->orderBy($orderByColumn)->chunk(1000, function ($rows) use ($output, $columns) {
            foreach ($rows as $row) {
                $csvRow = [];
                foreach ($columns as $column) {
                    $value = $row->$column;

                    // Handle different data types
                    if (is_null($value)) {
                        $csvRow[] = '';
                    } elseif (is_bool($value)) {
                        $csvRow[] = $value ? '1' : '0';
                    } elseif (is_array($value) || is_object($value)) {
                        $csvRow[] = json_encode($value);
                    } elseif (is_string($value) && mb_strlen($value) > 32767) {
                        // Truncate very long strings to avoid CSV issues
                        $csvRow[] = mb_substr($value, 0, 32767) . '...';
                    } else {
                        $csvRow[] = (string) $value;
                    }
                }
                fputcsv($output, $csvRow);
            }
        });

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    /**
     * Create ZIP file from CSV files
     */
    private function createZipFile($files, $tempDir)
    {
        $zipPath = $tempDir . '/database_export.zip';
        Log::info('Creating ZIP file at: ' . $zipPath);
        Log::info('Number of files to add: ' . count($files));

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Failed to create ZIP file');
        }

        foreach ($files as $filePath) {
            Log::info('Adding file to ZIP: ' . $filePath);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
            } else {
                Log::warning('File does not exist: ' . $filePath);
            }
        }

        $zip->close();

        // Verify ZIP was created
        if (file_exists($zipPath)) {
            Log::info('ZIP file created successfully at: ' . $zipPath);
            Log::info('ZIP file size: ' . filesize($zipPath) . ' bytes');
        } else {
            Log::error('ZIP file was not created at: ' . $zipPath);
        }

        return $zipPath;
    }

    /**
     * Clean up temporary files
     */
    private function cleanupTempFiles($tempDir)
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

    /**
     * Get table information for display
     */
    public function getTableInfo()
    {
        // Set timeout and memory limits for table info operations
        set_time_limit(60); // 1 minute
        ini_set('memory_limit', '256M'); // 256MB

        try {
            $tables = $this->getAllTables();
            $tableInfo = [];

            foreach ($tables as $table) {
                $tableInfo[] = [
                    'name' => $table,
                    'columns' => Schema::getColumnListing($table),
                    'row_count' => DB::table($table)->count(),
                ];
            }

            return response()->json($tableInfo);
        } catch (\Exception $e) {
            Log::error('Failed to get table info: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get table information'], 500);
        }
    }
}
