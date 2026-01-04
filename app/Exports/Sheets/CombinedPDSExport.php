<?php

namespace App\Exports\Sheets;

use App\Exports\Sheets\PersonnelDataC1Sheet;
use App\Exports\Sheets\EducationSheetExport;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CombinedPDSExport implements WithEvents
{
    private $personnel;
    private $tempDir;

    public function __construct($personnel)
    {
        $this->personnel = $personnel;
        $this->tempDir = storage_path('app/temp/exports/' . uniqid());
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                // Create temporary directory
                if (!file_exists($this->tempDir)) {
                    mkdir($this->tempDir, 0755, true);
                }

                // Generate C1 Sheet
                $c1Spreadsheet = $this->generateC1Sheet();
                $c1Path = $this->tempDir . '/PDS_C1_' . str_replace(' ', '_', $this->personnel->full_name) . '.xlsx';
                $c1Writer = new Xlsx($c1Spreadsheet);
                $c1Writer->save($c1Path);

                // Generate Education Sheet
                $educationPath = $this->tempDir . '/Education_Sheet_' . str_replace(' ', '_', $this->personnel->full_name) . '.xlsx';
                $educationExport = new EducationSheetExport($this->personnel);
                $educationSpreadsheet = $this->generateEducationSheet($educationExport);
                $educationWriter = new Xlsx($educationSpreadsheet);
                $educationWriter->save($educationPath);

                // Create ZIP file
                $zipPath = $this->tempDir . '/PDS_Complete_' . str_replace(' ', '_', $this->personnel->full_name) . '.zip';
                $zip = new ZipArchive();

                if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                    $zip->addFile($c1Path, basename($c1Path));
                    $zip->addFile($educationPath, basename($educationPath));
                    $zip->close();
                }

                // Clean up temp files
                @unlink($c1Path);
                @unlink($educationPath);
                @rmdir($this->tempDir);

                // Set the writer to download the ZIP file
                $event->writer->downloadFile($zipPath);

                // Clean up ZIP after download
                register_shutdown_function(function() use ($zipPath) {
                    if (file_exists($zipPath)) {
                        unlink($zipPath);
                    }
                });

                // Prevent Laravel Excel from trying to create its own file
                throw new \Maatwebsite\Excel\Jobs\AppendDataToSheet('Export handled manually');
            },
        ];
    }

    private function generateC1Sheet()
    {
        // Load the C1 template
        $templatePath = public_path('report/macro_enabled_cs_form_no_2122.xlsx');
        $spreadsheet = IOFactory::load($templatePath);

        // Get the first worksheet
        $worksheet = $spreadsheet->getSheet(0);

        // Create C1 sheet instance and populate
        $c1Sheet = new PersonnelDataC1Sheet($this->personnel, $spreadsheet);
        $c1Sheet->populateSheet();

        return $spreadsheet;
    }

    private function generateEducationSheet($educationExport)
    {
        // Load the Education Sheet template
        $templatePath = public_path('report/Education_Sheet.xlsx');
        $spreadsheet = IOFactory::load($templatePath);

        // Get all sheets
        $sheets = $educationExport->sheets();

        // Process each sheet
        foreach ($sheets as $index => $sheet) {
            $worksheet = $spreadsheet->getSheet($index);
            $sheet->fillWorksheet($worksheet);
        }

        return $spreadsheet;
    }

    public function getFileName()
    {
        return 'PDS_Complete_' . str_replace(' ', '_', $this->personnel->full_name) . '_' . date('Y-m-d') . '.zip';
    }
}
