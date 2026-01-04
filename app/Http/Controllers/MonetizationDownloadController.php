<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveMonetization;
use App\Models\SchoolHeadMonetization;
use App\Models\Personnel;
use App\Models\SalaryStep;
use App\Models\Signature;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonetizationDownloadController extends Controller
{
    public function downloadExcel($monetizationId, $signatureChoice = null)
    {
        try {
            // Get the logged-in user
            $user = Auth::user();

            // Determine which model to use based on user role
            if ($user->role === 'school_head') {
                $monetizationRequest = SchoolHeadMonetization::findOrFail($monetizationId);
                // Authorization check for school head
                abort_if($monetizationRequest->school_head_id !== $user->personnel->id, 403, 'Unauthorized access to this monetization request.');
            } else {
                $monetizationRequest = LeaveMonetization::findOrFail($monetizationId);
                // Authorization check for teacher/non-teaching
                abort_if($monetizationRequest->user_id !== auth()->id(), 403, 'Unauthorized access to this monetization request.');
            }

            // Get the logged-in user's personnel data
            $personnel = $user->personnel;

            if (!$personnel) {
                return back()->with('error', 'Personnel record not found.');
            }

            // Verify template file exists
            $templatePath = resource_path('views/forms/LEAVE PLARIDEL CS-REVISED-2025 BLANK.xlsx');
            if (!file_exists($templatePath)) {
                return back()->with('error', 'Excel template not found.');
            }

            // Load the Excel template
            $spreadsheet = IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();

            // Fill in personnel data
            $sheet->setCellValue('G12', $personnel->last_name);
            $sheet->setCellValue('I12', $personnel->first_name);
            $sheet->setCellValue('N12', $personnel->middle_name ?? '');

            // Calculate and fill salary
            $salary = $this->calculateSalary($personnel->salary_grade_id, $personnel->step_increment);
            $sheet->setCellValue('O14', 'PHP ' . number_format($salary, 2));

            // Fill position
            $sheet->setCellValue('H14', $personnel->position->title ?? '');

            // Fill date filing (created_at of monetization request)
            $sheet->setCellValue('F14', $monetizationRequest->created_at->format('m/d/Y'));

            // Calculate working days (use monetization days)
            $monetizationDays = $user->role === 'school_head' ? $monetizationRequest->days_requested : $monetizationRequest->total_days;
            $sheet->setCellValue('E36', $monetizationDays);

            // Fill full name
            $fullName = trim($personnel->first_name . ' ' . ($personnel->middle_name ? $personnel->middle_name . ' ' : '') . $personnel->last_name);
            $sheet->setCellValue('I38', $fullName);

            // Fill inclusive dates (use monetization request date)
            if ($user->role === 'school_head') {
                $requestDate = \Carbon\Carbon::parse($monetizationRequest->request_date)->format('m/d/Y');
                $inclusiveDates = $requestDate . ' - ' . $requestDate;
            } else {
                $requestDate = $monetizationRequest->created_at->format('m/d/Y');
                $inclusiveDates = $requestDate . ' - ' . $requestDate;
            }
            $sheet->setCellValue('E38', $inclusiveDates);

            // Add check mark for Monetization in J32
            $sheet->setCellValue('J32', '✓');

            // Get Administrative Officer VI signature from signatures table
            $adminOfficerSignature = Signature::where('position', 'Administrative Officer VI (HRMO II)')->first();
            if ($adminOfficerSignature) {
                $sheet->setCellValue('E53', $adminOfficerSignature->full_name);
            }

            // Get School Head based on logged-in user's school
            if ($personnel->school_id) {
                $schoolHead = Personnel::where('school_id', $personnel->school_id)
                    ->where('category', 'School Head')
                    ->first();

                if ($schoolHead) {
                    $schoolHeadFullName = trim($schoolHead->first_name . ' ' .
                        ($schoolHead->middle_name ? $schoolHead->middle_name . ' ' : '') .
                        $schoolHead->last_name);
                    $sheet->setCellValue('L53', $schoolHeadFullName);
                }
            }

            // Get Division Superintendent signature based on user choice
            if ($signatureChoice === 'assistant') {
                $divisionSuperintendent = Signature::where('position', 'Assistant School Division Superintendent')->first();
                if ($divisionSuperintendent) {
                    $sheet->setCellValue('G61', $divisionSuperintendent->full_name);
                    $sheet->setCellValue('G62', $divisionSuperintendent->position_name);
                }
            } elseif ($signatureChoice === 'schools') {
                $divisionSuperintendent = Signature::where('position', 'Schools Division Superintendent')->first();
                if ($divisionSuperintendent) {
                    $sheet->setCellValue('G61', $divisionSuperintendent->full_name);
                    $sheet->setCellValue('G62', $divisionSuperintendent->position_name);
                }
            } else {
                // Default to Assistant School Division Superintendent if no choice specified
                $divisionSuperintendent = Signature::where('position', 'Assistant School Division Superintendent')->first();
                if ($divisionSuperintendent) {
                    $sheet->setCellValue('G61', $divisionSuperintendent->full_name);
                    $sheet->setCellValue('G62', $divisionSuperintendent->position_name);
                }
            }

            // Create the Excel file
            $filename = 'Monetization_Application_' . $personnel->personnel_id . '_' . now()->format('Y-m-d') . '.xlsx';

            // Save the file to temporary location
            $tempPath = storage_path('app/temp/' . $filename);
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempPath);

            // Download the file
            return response()->download($tempPath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('Error downloading monetization application: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while downloading the application: ' . $e->getMessage());
        }
    }

    private function calculateSalary($salaryGradeId, $stepIncrement)
    {
        // Get the current year
        $currentYear = date('Y');

        // Find the salary step record
        $salaryStep = SalaryStep::where('salary_grade_id', $salaryGradeId)
            ->where('step', $stepIncrement)
            ->where('year', $currentYear)
            ->first();

        // If not found for current year, try to get the latest year
        if (!$salaryStep) {
            $salaryStep = SalaryStep::where('salary_grade_id', $salaryGradeId)
                ->where('step', $stepIncrement)
                ->orderBy('year', 'desc')
                ->first();
        }

        return $salaryStep ? $salaryStep->salary : 0;
    }
}
