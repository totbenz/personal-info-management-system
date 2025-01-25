<?php
namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class ExcelController extends Controller
{
    public function importView()
    {
       return view('import');
    }

    public function convert(Request $request)
    {
        // Load the Excel file
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($request->file('excel_file'));

        // Convert Excel to HTML
        $htmlWriter = new \PhpOffice\PhpSpreadsheet\Writer\HTML($spreadsheet);
        $html = $htmlWriter->generateHTML(true);

        // Load the HTML into DomPDF
        $pdf = PDF::loadHTML($html);

        // Optionally, you can set additional options for DomPDF
        // $pdf->set_option('isRemoteEnabled', true);

        // Return the PDF as a download or save it to a specific location
        return $pdf->download('workbook.pdf');
        // or
        // $pdf->save(storage_path('app/workbook.pdf'));
    }
}
