<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Dompdf\Dompdf;
use Dompdf\Options;
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
        Log::info('PersonnelDataExport constructor called with id: ' . $id);

        $this->personnel = Personnel::findOrFail($id);
        Log::info('Personnel found: ', ['personnel' => $this->personnel]);

        $this->filename = public_path('report/macro_enabled_cs_form_no_212.xlsm');
        Log::info('Loading spreadsheet from file: ' . $this->filename);
        $this->spreadsheet = IOFactory::load($this->filename);

        Log::info('Populating PersonnelDataC1Sheet');
        $personnelC1Sheet = new PersonnelDataC1Sheet($this->personnel, $this->spreadsheet);
        $personnelC1Sheet->populateSheet();

        Log::info('Populating PersonnelDataC2Sheet');
        $personnelC2Sheet = new PersonnelDataC2Sheet($this->personnel, $this->spreadsheet);
        $personnelC2Sheet->populateSheet();

        Log::info('Populating PersonnelDataC3Sheet');
        $personnelC3Sheet = new PersonnelDataC3Sheet($this->personnel, $this->spreadsheet);
        $personnelC3Sheet->populateSheet();

        Log::info('Populating PersonnelDataC4Sheet');
        $personnelC4Sheet = new PersonnelDataC4Sheet($this->personnel, $this->spreadsheet);
        $personnelC4Sheet->populateSheet();

        $this->excelOutputPath = public_path('report/pds_generated.xlsm');
        $this->pdfOutputPath = public_path('report/pds_generated.pdf');

        Log::info('Saving Excel file to: ' . $this->excelOutputPath);
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($this->excelOutputPath);

        // $writer = IOFactory::createWriter($this->spreadsheet, 'Pdf');
        // $writer->save($this->pdfOutputPath);
    }

    public function getOutputPath()
    {
        Log::info('getOutputPath called, returning: ' . $this->excelOutputPath);
        return $this->excelOutputPath;
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
