<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Utils\TimeoutPrevention;

class RecoveryController extends Controller
{
    /**
     * Show the recovery page
     */
    public function index()
    {
        return view('recovery.index');
    }

    /**
     * Handle database restoration from uploaded ZIP file
     */
    public function restore(Request $request)
    {
        // Set timeout and memory limits for recovery operations
        TimeoutPrevention::setLimits('recovery');

        // Additional security check - only allow in non-production or with special flag
        if (app()->environment('production') && !config('app.allow_recovery', false)) {
            Log::warning('Recovery attempt blocked in production environment');
            return redirect()->route('recovery.index')
                ->with('error', 'Database recovery is disabled in production environment.');
        }

        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:102400', // Max 100MB
        ]);

        try {
            // Store the uploaded file temporarily
            $uploadedFile = $request->file('backup_file');
            $tempPath = $uploadedFile->store('temp/recovery', 'local');
            $fullPath = storage_path('app/' . $tempPath);

            // Validate ZIP file contains CSV files
            $zip = new \ZipArchive();
            if ($zip->open($fullPath) !== TRUE) {
                Storage::disk('local')->delete($tempPath);
                return redirect()->route('recovery.index')
                    ->with('error', 'Invalid ZIP file. Please ensure the file is not corrupted.');
            }

            $hasCsvFiles = false;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (pathinfo($filename, PATHINFO_EXTENSION) === 'csv') {
                    $hasCsvFiles = true;
                    break;
                }
            }
            $zip->close();

            if (!$hasCsvFiles) {
                Storage::disk('local')->delete($tempPath);
                return redirect()->route('recovery.index')
                    ->with('error', 'ZIP file does not contain any CSV files. Please ensure this is a valid database backup.');
            }

            // Log the restoration attempt
            Log::info('Database recovery initiated', [
                'file' => $uploadedFile->getClientOriginalName(),
                'size' => $uploadedFile->getSize(),
                'path' => $fullPath
            ]);

            // Execute the import command
            $exitCode = Artisan::call('db:csv', [
                'action' => 'import',
                '--file' => $fullPath
            ]);

            // Clean up the temporary file
            Storage::disk('local')->delete($tempPath);

            if ($exitCode === 0) {
                $output = Artisan::output();

                Log::info('Database recovery completed successfully', [
                    'file' => $uploadedFile->getClientOriginalName(),
                    'output' => $output
                ]);

                return redirect()->route('recovery.index')
                    ->with('success', 'Database restored successfully! ' . $output);
            } else {
                $output = Artisan::output();

                Log::error('Database recovery failed', [
                    'file' => $uploadedFile->getClientOriginalName(),
                    'exit_code' => $exitCode,
                    'output' => $output
                ]);

                return redirect()->route('recovery.index')
                    ->with('error', 'Database restoration failed. Exit code: ' . $exitCode . '. ' . $output);
            }

        } catch (\Exception $e) {
            Log::error('Database recovery error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up temp file if it exists
            if (isset($tempPath)) {
                Storage::disk('local')->delete($tempPath);
            }

            return redirect()->route('recovery.index')
                ->with('error', 'An error occurred during restoration: ' . $e->getMessage());
        }
    }
}
