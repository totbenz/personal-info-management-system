<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\ServiceRecord;

class ServiceRecordController extends Controller
{
    public function download($personnelId)
    {
        // Fetch the personnel and their service records
        $personnel = Personnel::findOrFail($personnelId);
        $serviceRecords = $personnel->serviceRecords()->with('position')->get();

        // Map service records to include position title as designation
        $serviceRecords = $serviceRecords->map(function ($record) {
            $record->designation = $record->position->title ?? '';
            return $record;
        });

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

    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        // Fetch all service records for the given personnel ID
        $serviceRecords = ServiceRecord::where('personnel_id', $id)->get();

        // Fetch personnel details
        $personnel = Personnel::find($id);
        // Fetch all positions
        $positions = \App\Models\Position::all();

        // Combine full name
        $fullName = trim("{$personnel->first_name} {$personnel->middle_name} {$personnel->last_name}");

        // Pass the data to a view (correct path: livewire.service-records.index)
        return view('livewire.service-records.index', [
            'serviceRecords' => $serviceRecords,
            'personnelId' => $id,
            'fullName' => $fullName,
            'positions' => $positions,
        ]);
    }
}
