<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Personnel;
use App\Exports\Sheets\PersonnelDataC1Sheet;
use App\Exports\Sheets\PersonnelDataC2Sheet;
use App\Exports\Sheets\PersonnelDataC3Sheet;
use App\Exports\Sheets\PersonnelDataC4Sheet;
use Illuminate\Support\Facades\Log;

class PersonnelDataExport
{
    protected $personnel;
    protected $filename;
    protected $spreadsheet;
    protected $excelOutputPath;
    protected $pdfOutputPath;

    public function __construct($id)
    {
        try {
            Log::info('PersonnelDataExport constructor called with id: ' . $id);

            $this->personnel = Personnel::with([
                'school',
                'position',
                'salaryGrade',
                'user',
                // Eager-load specific address relations used by sheets
                'residentialAddress',
                'permanentAddress',
                'contactPerson',
                'families',
                'educations',
                'civilServiceEligibilities',
                'workExperiences',
                'voluntaryWorks',
                'trainingCertifications',
                'otherInformations',
                'references',
                'assignmentDetails',
                'awardsReceived',
                'salaryChanges',
                'personnelDetail'
            ])->findOrFail($id);
            Log::info('Personnel found: ', ['personnel' => $this->personnel]);

            $this->filename = public_path('report/macro_enabled_cs_form_no_2122.xlsx');
            Log::info('Loading spreadsheet from file: ' . $this->filename);

            // Check if template file exists
            if (!file_exists($this->filename)) {
                throw new \Exception('Template file not found: ' . $this->filename);
            }

            $this->spreadsheet = IOFactory::load($this->filename);
            Log::info('Template spreadsheet loaded successfully');

            // Populate all sheets with error handling
            $this->populateAllSheets();

            $this->excelOutputPath = public_path('report/pds_generated.xlsx');
            $this->pdfOutputPath = public_path('report/pds_generated.pdf');

            // Ensure output directory exists
            $outputDir = dirname($this->excelOutputPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            Log::info('Saving Excel file to: ' . $this->excelOutputPath);
            $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');

            // Set additional options to prevent corruption
            $writer->setPreCalculateFormulas(false);

            $writer->save($this->excelOutputPath);

            Log::info('Excel file saved successfully');
        } catch (\Exception $e) {
            Log::error('Error in PersonnelDataExport constructor: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    protected function populateAllSheets()
    {
        try {
            Log::info('Starting to populate all sheets');

            // Populate C1 Sheet (Personal Information)
            Log::info('Populating PersonnelDataC1Sheet');
            $personnelC1Sheet = new PersonnelDataC1Sheet($this->personnel, $this->spreadsheet);
            $personnelC1Sheet->populateSheet();

            // Populate C2 Sheet (Civil Service & Work Experience)
            Log::info('Populating PersonnelDataC2Sheet');
            $personnelC2Sheet = new PersonnelDataC2Sheet($this->personnel, $this->spreadsheet);
            $personnelC2Sheet->populateSheet();

            // Populate C3 Sheet (Voluntary Work & Training)
            Log::info('Populating PersonnelDataC3Sheet');
            $personnelC3Sheet = new PersonnelDataC3Sheet($this->personnel, $this->spreadsheet);
            $personnelC3Sheet->populateSheet();

            // Populate C4 Sheet (References & Questionnaire)
            Log::info('Populating PersonnelDataC4Sheet');
            $personnelC4Sheet = new PersonnelDataC4Sheet($this->personnel, $this->spreadsheet);
            $personnelC4Sheet->populateSheet();

            Log::info('All sheets populated successfully');
        } catch (\Exception $e) {
            Log::error('Error populating sheets: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getOutputPath()
    {
        Log::info('getOutputPath called, returning: ' . $this->excelOutputPath);
        return $this->excelOutputPath;
    }

    public function getPersonnel()
    {
        return $this->personnel;
    }

    public function cleanup()
    {
        try {
            if ($this->spreadsheet) {
                $this->spreadsheet->disconnectWorksheets();
                unset($this->spreadsheet);
            }
        } catch (\Exception $e) {
            Log::error('Error during cleanup: ' . $e->getMessage());
        }
    }
}


    // public function convertToPdf()
    // {
    //     // Load the Excel file
    //     $spreadsheet = IOFactory::load($this->excelOutputPath);

    //     // Create options for dompdf
    //     $options = new Options();
    //     $options->set('isHtml5ParserEnabled', true);

    //     // Create dompdf instance
    //     $dompdf = new Dompdf($options);

    //     // Initialize HTML content
    //     $html = '<html>';
    //     $html .= '<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head>';
    //     $html .= '<body>';

    //     // Loop through each sheet
    //     foreach ($spreadsheet->getAllSheets() as $sheet) {
    //         $html .= '<table>';

    //         // Loop through each row in the sheet
    //         foreach ($sheet->getRowIterator() as $row) {
    //             $html .= '<tr>';

    //             // Loop through each cell in the row
    //             foreach ($row->getCellIterator() as $cell) {
    //                 $html .= '<td>' . $cell->getValue() . '</td>';
    //             }

    //             $html .= '</tr>';
    //         }

    //         $html .= '</table>';
    //     }

    //     $html .= '</body>';
    //     $html .= '</html>';

    //     // Load HTML into dompdf
    //     $dompdf->loadHtml($html);

    //     // Set paper size and orientation
    //     $dompdf->setPaper('A4', 'portrait');

    //     // Render PDF
    //     $dompdf->render();

    //     // Save PDF
    //     file_put_contents($this->pdfOutputPath, $dompdf->output());
    // }
