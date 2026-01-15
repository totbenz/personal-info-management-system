<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Barryvdh\DomPDF\Facade\Pdf;

class NosaController extends Controller
{
    public function download($personnelId)
    {
        // Set timeout and memory limits
        set_time_limit(60);
        ini_set('memory_limit', '512M');

        // Fetch the personnel with their relationships
        $personnel = Personnel::with(['position', 'salaryChanges', 'school'])
            ->findOrFail($personnelId);

        // Get the latest salary change or create a default object
        $salaryChange = $personnel->salaryChanges()
            ->orderBy('adjusted_monthly_salary_date', 'desc')
            ->first() ?? (object)[
                'adjusted_monthly_salary_date' => null,
                'previous_salary_grade' => 0,
                'previous_salary_step' => 0,
                'actual_monthly_salary_as_of_date' => null,
                'previous_salary' => 0,
                'current_salary' => 0,
                'current_salary_grade' => 0,
                'current_salary_step' => 0
            ];

        // Generate the PDF using the Blade view
        $pdf = Pdf::loadView('pdf.nosa', [
            'personnel' => $personnel,
            'salaryChange' => $salaryChange
        ]);

        // Return the PDF as a download response
        return $pdf->download($personnel->last_name . ' ' . $personnel->first_name . ' - NOSA' . '.pdf');
    }

    public function preview($personnelId)
    {
        // Set timeout and memory limits
        set_time_limit(60);
        ini_set('memory_limit', '512M');

        // Fetch the personnel with their relationships
        $personnel = Personnel::with(['position', 'salaryChanges', 'school'])
            ->findOrFail($personnelId);

        // Get the latest salary change or create a default object
        $salaryChange = $personnel->salaryChanges()
            ->orderBy('adjusted_monthly_salary_date', 'desc')
            ->first() ?? (object)[
                'adjusted_monthly_salary_date' => null,
                'previous_salary_grade' => 0,
                'previous_salary_step' => 0,
                'actual_monthly_salary_as_of_date' => null,
                'previous_salary' => 0,
                'current_salary' => 0,
                'current_salary_grade' => 0,
                'current_salary_step' => 0
            ];

        // Generate the PDF using the Blade view
        $pdf = Pdf::loadView('pdf.nosa', [
            'personnel' => $personnel,
            'salaryChange' => $salaryChange
        ]);

        // Return the PDF as an inline response for preview
        return $pdf->stream($personnel->last_name . ' ' . $personnel->first_name . ' - NOSA' . '.pdf');
    }
}
