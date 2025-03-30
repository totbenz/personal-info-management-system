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
        return $pdf->download('service_record_' . $personnel->id . '.pdf');
    }
}
