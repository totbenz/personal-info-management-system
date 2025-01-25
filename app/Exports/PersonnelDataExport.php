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

class PersonnelDataExport
{
    protected $personnel;
    protected $filename;
    protected $spreadsheet;
    protected $excelOutputPath;
    protected $pdfOutputPath;

    public function __construct($id)
    {
        $this->personnel = Personnel::findOrFail($id);

        $this->filename = public_path('report/macro_enabled_cs_form_no_212.xlsm');
        $this->spreadsheet = IOFactory::load($this->filename);

        $personnelC1Sheet = new PersonnelDataC1Sheet($this->personnel, $this->spreadsheet);
        $personnelC1Sheet->populateSheet();

        $personnelC2Sheet = new PersonnelDataC2Sheet($this->personnel, $this->spreadsheet);
        $personnelC2Sheet->populateSheet();

        $personnelC3Sheet = new PersonnelDataC3Sheet($this->personnel, $this->spreadsheet);
        $personnelC3Sheet->populateSheet();

        $personnelC4Sheet = new PersonnelDataC4Sheet($this->personnel, $this->spreadsheet);
        $personnelC4Sheet->populateSheet();

        $this->excelOutputPath = public_path('report/pds_generated.xlsm');
        $this->pdfOutputPath = public_path('report/pds_generated.pdf');

        // Save the Excel file
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($this->excelOutputPath);

        // $writer = IOFactory::createWriter($this->spreadsheet, 'Pdf');
        // $writer->save($this->pdfOutputPath);
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

    public function getOutputPath()
    {
        return $this->excelOutputPath;
    }
}
