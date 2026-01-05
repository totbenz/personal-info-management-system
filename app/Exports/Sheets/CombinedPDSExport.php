<?php

namespace App\Exports\Sheets;

use App\Exports\Sheets\PersonnelDataC1Sheet;
use App\Exports\Sheets\PersonnelDataC2Sheet;
use App\Exports\Sheets\PersonnelDataC3Sheet;
use App\Exports\Sheets\PersonnelDataC4Sheet;
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
use Illuminate\Support\Facades\Log;
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

                // Generate PDS (C1-C4 all in one file)
                $pdsSpreadsheet = $this->generatePDSSheet();
                $pdsPath = $this->tempDir . '/PDS_' . str_replace(' ', '_', $this->personnel->full_name) . '.xlsx';
                $pdsWriter = new Xlsx($pdsSpreadsheet);
                $pdsWriter->save($pdsPath);

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
                    $zip->addFile($pdsPath, basename($pdsPath));
                    $zip->addFile($educationPath, basename($educationPath));
                    $zip->close();
                }

                // Clean up temp files
                @unlink($pdsPath);
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

    private function generatePDSSheet()
    {
        // Load the PDS template (contains C1-C4 sheets)
        $templatePath = public_path('report/macro_enabled_cs_form_no_2122.xlsx');
        $spreadsheet = IOFactory::load($templatePath);

        Log::info('Generating PDS sheet', [
            'personnelId' => $this->personnel->id,
            'sheetCount' => $spreadsheet->getSheetCount()
        ]);

        // Populate C1 sheet (index 0)
        Log::info('Populating C1 sheet');
        $c1Sheet = new PersonnelDataC1Sheet($this->personnel, $spreadsheet);
        $c1Sheet->populateSheet();

        // Populate C2 sheet (index 1)
        Log::info('Populating C2 sheet');
        $c2Sheet = new PersonnelDataC2Sheet($this->personnel, $spreadsheet);
        $c2Sheet->populateSheet();

        // Populate C3 sheet (index 2)
        Log::info('Populating C3 sheet');
        $c3Sheet = new PersonnelDataC3Sheet($this->personnel, $spreadsheet);
        $c3Sheet->populateSheet();

        // Populate C4 sheet (index 3)
        Log::info('Populating C4 sheet');
        $c4Sheet = new PersonnelDataC4Sheet($this->personnel, $spreadsheet);
        $c4Sheet->populateSheet();

        Log::info('All PDS sheets populated successfully');

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
