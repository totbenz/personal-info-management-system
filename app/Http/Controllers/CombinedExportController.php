<?php

namespace App\Http\Controllers;

use App\Exports\Sheets\CombinedPDSExport;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exports\Sheets\PersonnelDataC1Sheet;
use App\Exports\Sheets\EducationSheetExport;
use ZipArchive;

class CombinedExportController extends Controller
{
    /**
     * Export both C1 and Education sheets as a ZIP file
     */
    public function exportCombinedPDS($personnelId = null)
    {
        Log::info('CombinedExportController::exportCombinedPDS called', [
            'personnelId' => $personnelId,
            'userRole' => Auth::user()->role ?? 'guest',
            'userId' => Auth::id(),
            'requestUri' => request()->getRequestUri()
        ]);

        try {
            // Get personnel
            if ($personnelId) {
                Log::info('Fetching personnel by ID', ['personnelId' => $personnelId]);
                $personnel = Personnel::findOrFail($personnelId);
            } else {
                Log::info('Fetching personnel from authenticated user');
                // For teacher profile export
                if (!Auth::user()->personnel) {
                    Log::error('No personnel associated with authenticated user', ['userId' => Auth::id()]);
                    return redirect()->back()->with('error', 'No personnel record found for your account.');
                }
                $personnel = Personnel::findOrFail(Auth::user()->personnel->id);
            }

            Log::info('Personnel found', [
                'personnelId' => $personnel->id,
                'fullName' => $personnel->full_name
            ]);

            // Check authorization
            $user = Auth::user();
            if (!$this->canExport($user, $personnel)) {
                Log::error('Unauthorized export attempt', [
                    'userId' => $user->id,
                    'userRole' => $user->role,
                    'personnelId' => $personnel->id
                ]);
                abort(403, 'Unauthorized action.');
            }

            // Create temporary directory
            $tempDir = storage_path('app/temp/exports/' . uniqid());
            Log::info('Creating temp directory', ['tempDir' => $tempDir]);

            if (!file_exists($tempDir)) {
                if (!mkdir($tempDir, 0755, true)) {
                    Log::error('Failed to create temp directory', ['tempDir' => $tempDir]);
                    throw new \Exception('Failed to create temporary directory');
                }
            }

            // Generate C1 Sheet
            Log::info('Starting C1 sheet generation');
            $templatePath = public_path('report/macro_enabled_cs_form_no_2122.xlsx');

            if (!file_exists($templatePath)) {
                Log::error('C1 template not found', ['templatePath' => $templatePath]);
                throw new \Exception('C1 template file not found');
            }

            $c1Spreadsheet = IOFactory::load($templatePath);
            $c1Sheet = new PersonnelDataC1Sheet($personnel, $c1Spreadsheet);
            $c1Sheet->populateSheet();

            $c1Path = $tempDir . '/PDS_C1_' . str_replace(' ', '_', $personnel->full_name) . '.xlsx';
            $c1Writer = new Xlsx($c1Spreadsheet);
            $c1Writer->save($c1Path);

            if (!file_exists($c1Path)) {
                Log::error('Failed to save C1 sheet', ['c1Path' => $c1Path]);
                throw new \Exception('Failed to save C1 sheet');
            }
            Log::info('C1 sheet generated successfully', ['c1Path' => $c1Path]);

            // Generate Education Sheet
            Log::info('Starting Education sheet generation');
            $educationTemplatePath = public_path('report/Education_Sheet.xlsx');

            if (!file_exists($educationTemplatePath)) {
                Log::error('Education template not found', ['educationTemplatePath' => $educationTemplatePath]);
                throw new \Exception('Education template file not found');
            }

            $educationSpreadsheet = IOFactory::load($educationTemplatePath);
            $educationExport = new EducationSheetExport($personnel);

            // Get all sheets and populate them
            $sheets = $educationExport->sheets();
            Log::info('Processing education sheets', ['sheetCount' => count($sheets)]);

            foreach ($sheets as $index => $sheet) {
                $worksheet = $educationSpreadsheet->getSheet($index);
                $sheet->fillWorksheet($worksheet);
                Log::info('Processed education sheet', ['index' => $index, 'title' => $sheet->title()]);
            }

            $educationPath = $tempDir . '/Education_Sheet_' . str_replace(' ', '_', $personnel->full_name) . '.xlsx';
            $educationWriter = new Xlsx($educationSpreadsheet);
            $educationWriter->save($educationPath);

            if (!file_exists($educationPath)) {
                Log::error('Failed to save Education sheet', ['educationPath' => $educationPath]);
                throw new \Exception('Failed to save Education sheet');
            }
            Log::info('Education sheet generated successfully', ['educationPath' => $educationPath]);

            // Create ZIP file
            Log::info('Creating ZIP file');
            $zipPath = $tempDir . '/PDS_Complete_' . str_replace(' ', '_', $personnel->full_name) . '.zip';
            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
                Log::error('Failed to create ZIP file', ['zipPath' => $zipPath, 'error' => $zip->getStatusString()]);
                throw new \Exception('Failed to create ZIP file: ' . $zip->getStatusString());
            }

            $c1FileName = 'PDS_C1_' . str_replace(' ', '_', $personnel->full_name) . '.xlsx';
            $educationFileName = 'Education_Sheet_' . str_replace(' ', '_', $personnel->full_name) . '.xlsx';

            if (!$zip->addFile($c1Path, $c1FileName)) {
                Log::error('Failed to add C1 file to ZIP', ['c1Path' => $c1Path]);
                throw new \Exception('Failed to add C1 file to ZIP');
            }

            if (!$zip->addFile($educationPath, $educationFileName)) {
                Log::error('Failed to add Education file to ZIP', ['educationPath' => $educationPath]);
                throw new \Exception('Failed to add Education file to ZIP');
            }

            $zip->close();

            if (!file_exists($zipPath)) {
                Log::error('ZIP file not created', ['zipPath' => $zipPath]);
                throw new \Exception('ZIP file was not created');
            }
            Log::info('ZIP file created successfully', ['zipPath' => $zipPath, 'size' => filesize($zipPath)]);

            // Clean up temp files
            Log::info('Cleaning up temp files');
            @unlink($c1Path);
            @unlink($educationPath);
            @rmdir($tempDir);

            // Download the ZIP file
            $filename = 'PDS_Complete_' . str_replace(' ', '_', $personnel->full_name) . '_' . date('Y-m-d') . '.zip';
            Log::info('Preparing download', ['filename' => $filename, 'zipPath' => $zipPath]);

            $response = Response::download($zipPath, $filename);

            // Clean up ZIP after download
            $response->deleteFileAfterSend(true);

            Log::info('Download response prepared successfully');
            return $response;

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Personnel not found', [
                'personnelId' => $personnelId,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Personnel record not found.');
        } catch (\Exception $e) {
            Log::error('Failed to export PDS', [
                'personnelId' => $personnelId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up any temp files on error
            if (isset($tempDir) && file_exists($tempDir)) {
                $files = glob($tempDir . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        @unlink($file);
                    }
                }
                @rmdir($tempDir);
            }

            return redirect()->back()->with('error', 'Failed to export PDS: ' . $e->getMessage());
        }
    }

    /**
     * Check if user can export the personnel's PDS
     */
    private function canExport($user, $personnel)
    {
        // Admin can export all
        if ($user->role === 'admin') {
            return true;
        }

        // School head can export personnel from their school
        if ($user->role === 'school_head') {
            return $user->school_id === $personnel->school_id;
        }

        // Teacher can only export their own
        if ($user->role === 'teacher') {
            return $user->personnel_id === $personnel->id;
        }

        return false;
    }
}
