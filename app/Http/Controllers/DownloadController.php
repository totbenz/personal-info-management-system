<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DownloadController extends Controller
{
    public function downloadAll($personnelId)
    {
        try {
            // Fetch the personnel and their service records
            $personnel = Personnel::findOrFail($personnelId);
            $serviceRecords = $personnel->serviceRecords()->with('position')->get();

            Log::info("Starting download all for personnel ID: {$personnelId}");

            // Force ZIP download - try ZIP first regardless of extension check
            try {
                return $this->downloadAllAsZip($personnel, $serviceRecords);
            } catch (\Exception $e) {
                Log::warning("ZIP download failed: " . $e->getMessage());
                // Fallback to HTML page if ZIP fails
                return $this->downloadAllAsHtmlPage($personnel, $serviceRecords, $personnelId);
            }

        } catch (\Exception $e) {
            Log::error("Download all failed for personnel {$personnelId}: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Download failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Download all documents as ZIP file
     */
    private function downloadAllAsZip($personnel, $serviceRecords)
    {
        // Check if ZipArchive class exists
        if (!class_exists('ZipArchive')) {
            throw new \Exception('ZipArchive class not found - ZIP extension not available');
        }

        $tempDir = storage_path('app/temp/downloads_' . $personnel->id . '_' . time());
        
        if (!File::makeDirectory($tempDir, 0755, true)) {
            throw new \Exception('Failed to create temporary directory');
        }

        $files = [];

        try {
            // Generate all PDFs
            $documents = [
                ['view' => 'pdf.service-record', 'name' => preg_replace('/[^A-Za-z0-9_\-\s]/', '_', $personnel->last_name . ' ' . $personnel->first_name) . ' - Service Record.pdf'],
                ['view' => 'pdf.nosa', 'name' => preg_replace('/[^A-Za-z0-9_\-\s]/', '_', $personnel->last_name . ' ' . $personnel->first_name) . ' - NOSA.pdf'],
                ['view' => 'pdf.nosi', 'name' => preg_replace('/[^A-Za-z0-9_\-\s]/', '_', $personnel->last_name . ' ' . $personnel->first_name) . ' - NOSI.pdf']
            ];

            foreach ($documents as $doc) {
                try {
                    Log::info("Generating PDF: " . $doc['name']);
                    $pdf = Pdf::loadView($doc['view'], [
                        'personnel' => $personnel,
                        'serviceRecords' => $serviceRecords
                    ]);
                    
                    $filePath = $tempDir . '/' . $doc['name'];
                    $pdfContent = $pdf->output();
                    
                    if (file_put_contents($filePath, $pdfContent) === false) {
                        Log::warning("Failed to write PDF file: " . $filePath);
                        continue;
                    }
                    
                    if (File::exists($filePath) && File::size($filePath) > 0) {
                        $files[] = ['path' => $filePath, 'name' => $doc['name']];
                        Log::info("Generated successfully: " . $doc['name'] . " (" . File::size($filePath) . " bytes)");
                    } else {
                        Log::warning("PDF file was not created properly: " . $filePath);
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to generate " . $doc['name'] . ": " . $e->getMessage());
                }
            }

            if (empty($files)) {
                throw new \Exception('No documents could be generated');
            }

            // Create ZIP
            $zipPath = $tempDir . '/Personnel_Documents_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $personnel->last_name . '_' . $personnel->first_name) . '.zip';
            $zip = new \ZipArchive;
            
            $result = $zip->open($zipPath, \ZipArchive::CREATE);
            if ($result !== TRUE) {
                throw new \Exception("Failed to create ZIP file. Error code: " . $result);
            }
            
            foreach ($files as $file) {
                if (File::exists($file['path'])) {
                    $zip->addFile($file['path'], $file['name']);
                    Log::info("Added to ZIP: " . $file['name']);
                }
            }
            
            $zip->close();

            if (!File::exists($zipPath) || File::size($zipPath) == 0) {
                throw new \Exception('ZIP file was not created successfully');
            }

            Log::info("ZIP file created successfully: " . $zipPath . " (" . File::size($zipPath) . " bytes)");

            $personnelName = preg_replace('/[^A-Za-z0-9_\-]/', '_', 
                trim($personnel->last_name . '_' . $personnel->first_name));

            return response()->download($zipPath, 
                'Personnel_Documents_' . $personnelName . '.zip')
                ->deleteFileAfterSend(true);

        } finally {
            // Clean up temporary files
            if (File::exists($tempDir)) {
                try {
                    File::deleteDirectory($tempDir);
                    Log::info("Cleaned up temp directory: " . $tempDir);
                } catch (\Exception $e) {
                    Log::warning("Failed to clean up temp directory: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Create an HTML page with download links for all documents
     */
    private function downloadAllAsHtmlPage($personnel, $serviceRecords, $personnelId)
    {
        $personnelName = $personnel->first_name . ' ' . $personnel->last_name;
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Download All Documents - ' . $personnelName . '</title>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
                .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                h1 { color: #333; text-align: center; margin-bottom: 30px; }
                .download-item { margin: 15px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; }
                .download-btn { display: inline-block; padding: 10px 20px; background: #007cba; color: white; text-decoration: none; border-radius: 4px; margin-top: 10px; }
                .download-btn:hover { background: #005a87; }
                .note { background: #e7f3ff; border-left: 4px solid #007cba; padding: 10px; margin: 20px 0; }
                .auto-download { text-align: center; margin: 20px 0; color: #666; }
            </style>
            <script>
                let downloadCount = 0;
                const totalDownloads = 3;
                
                function downloadDocument(url, filename) {
                    const link = document.createElement("a");
                    link.href = url;
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    downloadCount++;
                    if (downloadCount === totalDownloads) {
                        setTimeout(() => {
                            document.getElementById("completion-message").style.display = "block";
                        }, 2000);
                    }
                }
                
                function downloadAll() {
                    downloadDocument("' . route('service-record.download', ['personnelId' => $personnelId]) . '", "Service_Record_' . $personnel->id . '.pdf");
                    setTimeout(() => downloadDocument("' . route('nosa.download', ['personnelId' => $personnelId]) . '", "NOSA_' . $personnel->id . '.pdf"), 1000);
                    setTimeout(() => downloadDocument("' . route('nosi.download', ['personnelId' => $personnelId]) . '", "NOSI_' . $personnel->id . '.pdf"), 2000);
                }
                
                // Auto-start downloads after page loads
                window.onload = function() {
                    setTimeout(downloadAll, 1000);
                }
            </script>
        </head>
        <body>
            <div class="container">
                <h1>Download All Documents</h1>
                <h2>' . $personnelName . '</h2>
                
                <div class="auto-download">
                    <p><strong>Downloads will start automatically...</strong></p>
                    <p>If downloads don\'t start, click the buttons below:</p>
                </div>
                
                <div class="download-item">
                    <h3>📄 Service Record</h3>
                    <p>Complete service record with employment history</p>
                    <a href="' . route('service-record.download', ['personnelId' => $personnelId]) . '" class="download-btn" target="_blank">Download Service Record</a>
                </div>
                
                <div class="download-item">
                    <h3>📋 NOSA (Notice of Salary Adjustment)</h3>
                    <p>Salary adjustment notification document</p>
                    <a href="' . route('nosa.download', ['personnelId' => $personnelId]) . '" class="download-btn" target="_blank">Download NOSA</a>
                </div>
                
                <div class="download-item">
                    <h3>📊 NOSI (Notice of Step Increment)</h3>
                    <p>Step increment notification document</p>
                    <a href="' . route('nosi.download', ['personnelId' => $personnelId]) . '" class="download-btn" target="_blank">Download NOSI</a>
                </div>
                
                <div class="note">
                    <strong>Note:</strong> All documents will be downloaded separately. Check your Downloads folder for the files.
                </div>
                
                <div id="completion-message" style="display: none; text-align: center; background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-top: 20px;">
                    <strong>✅ All downloads completed!</strong><br>
                    Check your Downloads folder for all three documents.
                </div>
                
                <div style="text-align: center; margin-top: 30px;">
                    <button onclick="downloadAll()" class="download-btn">Download All Again</button>
                    <a href="javascript:history.back()" style="margin-left: 10px; color: #666;">← Go Back</a>
                </div>
            </div>
        </body>
        </html>';

        return response($html)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Create a ZIP file from an array of files
     */
    private function createZipFile($files, $zipPath)
    {
        try {
            Log::info("Attempting to create ZIP file at: {$zipPath}");
            
            // Check if ZipArchive class exists
            if (!class_exists('ZipArchive')) {
                Log::warning("ZipArchive class not found - ZIP extension not available");
                return false;
            }

            $zip = new \ZipArchive;
            $result = $zip->open($zipPath, \ZipArchive::CREATE);
            
            if ($result !== TRUE) {
                Log::error("Failed to open ZIP file. Error code: {$result}");
                return false;
            }
            
            foreach ($files as $file) {
                if (!File::exists($file['path'])) {
                    Log::warning("File does not exist: " . $file['path']);
                    continue;
                }
                
                $addResult = $zip->addFile($file['path'], $file['name']);
                if (!$addResult) {
                    Log::warning("Failed to add file to ZIP: " . $file['path']);
                } else {
                    Log::info("Added file to ZIP: " . $file['name']);
                }
            }
            
            $closeResult = $zip->close();
            
            if (!$closeResult) {
                Log::error("Failed to close ZIP file");
                return false;
            }
            
            Log::info("ZIP file created successfully");
            return true;
            
        } catch (\Exception $e) {
            Log::error("ZIP creation failed: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Download a specific document type for a personnel
     */
    public function downloadSpecific($personnelId, $type)
    {
        try {
            $personnel = Personnel::findOrFail($personnelId);
            $serviceRecords = $personnel->serviceRecords()->with('position')->get();

            $fileName = '';
            $viewName = '';

            switch ($type) {
                case 'nosa':
                    $viewName = 'pdf.nosa';
                    $fileName = 'NOSA_' . $personnel->id . '.pdf';
                    break;
                case 'nosi':
                    $viewName = 'pdf.nosi';
                    $fileName = 'NOSI_' . $personnel->id . '.pdf';
                    break;
                case 'service-record':
                    $viewName = 'pdf.service-record';
                    $fileName = 'Service_Record_' . $personnel->id . '.pdf';
                    break;
                default:
                    return response()->json(['error' => 'Invalid document type'], 400);
            }

            $pdf = Pdf::loadView($viewName, [
                'personnel' => $personnel,
                'serviceRecords' => $serviceRecords
            ]);

            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error("Download failed for personnel {$personnelId}, type {$type}: " . $e->getMessage());
            return response()->json(['error' => 'Download failed'], 500);
        }
    }
}
