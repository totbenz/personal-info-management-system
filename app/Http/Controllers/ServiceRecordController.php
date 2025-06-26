<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Barryvdh\DomPDF\Facade\Pdf;

class ServiceRecordController extends Controller
{
    public function download($personnelId)
    {
        // Fetch the personnel and their service records
        $personnel = Personnel::findOrFail($personnelId);
        $serviceRecords = $personnel->serviceRecords()->with('position')->get();

        // Generate the PDF using the Blade view
        $pdf = Pdf::loadView('pdf.service-record', [
            'personnel' => $personnel,
            'serviceRecords' => $serviceRecords
        ]);

        // Return the PDF as a download response
        return $pdf->download($personnel->last_name . ' ' . $personnel->first_name . ' - Service Record' . '.pdf');
    }

    public function preview($personnelId)
    {
        // Fetch the personnel and their service records
        $personnel = Personnel::findOrFail($personnelId);
        $serviceRecords = $personnel->serviceRecords()->with('position')->get();

        // Generate the PDF using the Blade view
        $pdf = Pdf::loadView('pdf.service-record', [
            'personnel' => $personnel,
            'serviceRecords' => $serviceRecords
        ]);

        // Return the PDF as an inline response for preview
        return $pdf->stream($personnel->last_name . ' ' . $personnel->first_name . ' - Service Record' . '.pdf');
    }
}
