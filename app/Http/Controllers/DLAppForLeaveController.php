<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Personnel;
use App\Models\SalaryStep;
use App\Models\Signature;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DLAppForLeaveController extends Controller
{
    /**
     * Download Leave Application Excel
     * Note: Monetization downloads are handled by MonetizationDownloadController
     */
    public function downloadExcel($leaveRequestId, $signatureChoice = null)
    {
        try {
            // Get the leave request
            $leaveRequest = LeaveRequest::findOrFail($leaveRequestId);

            // Authorization check - ensure user can only download their own leave requests
            abort_if($leaveRequest->user_id !== auth()->id(), 403, 'Unauthorized access to this leave request.');

            // Get the logged-in user's personnel data
            $user = Auth::user();
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

            // Fill date filing (created_at of leave request) - Assuming it goes in cell that makes sense for date filing
            // You may need to adjust this cell reference based on the actual template layout
            $sheet->setCellValue('F14', $leaveRequest->created_at->format('m/d/Y'));

            // Calculate working days
            $workingDays = $this->calculateWorkingDays($leaveRequest->start_date, $leaveRequest->end_date);
            $sheet->setCellValue('E36', $workingDays);

            // Fill full name
            $fullName = trim($personnel->first_name . ' ' . ($personnel->middle_name ? $personnel->middle_name . ' ' : '') . $personnel->last_name);
            $sheet->setCellValue('I38', $fullName);

            // Fill inclusive dates in E38
            $inclusiveDates = $leaveRequest->start_date . ' - ' . $leaveRequest->end_date;
            $sheet->setCellValue('E38', $inclusiveDates);

            // Add check marks based on leave type
            $leaveType = strtolower($leaveRequest->leave_type);
            $customLeaveName = strtolower($leaveRequest->custom_leave_name ?? '');

            // Handle Terminal Leave (custom leave with "Terminal Leave" name)
            if ($leaveType === 'custom' && $customLeaveName === 'terminal leave') {
                // Add check mark in J33 for Terminal Leave
                $sheet->setCellValue('J33', '✓');

                // Also add custom leave name in F33
                $sheet->setCellValue('F33', $leaveRequest->custom_leave_name);
            } elseif ($leaveType === 'custom') {
                // Add check mark in C33 for other custom leaves
                $sheet->setCellValue('C33', '✓');

                // Add custom leave name in F33
                $sheet->setCellValue('F33', $leaveRequest->custom_leave_name);
            } else {
                switch ($leaveType) {
                    case 'force leave':
                        $sheet->setCellValue('C21', '✓');
                        break;
                    case 'sick leave':
                        $sheet->setCellValue('C22', '✓');
                        break;
                    case 'maternity leave':
                        $sheet->setCellValue('C23', '✓');
                        break;
                    case 'paternity leave':
                        $sheet->setCellValue('C24', '✓');
                        break;
                    case 'solo parent leave':
                        $sheet->setCellValue('C26', '✓');
                        break;
                    case 'study leave':
                        $sheet->setCellValue('C27', '✓');
                        break;
                    case 'adoption leave':
                        $sheet->setCellValue('C32', '✓');
                        break;
                    case 'rehabilitation leave':
                        $sheet->setCellValue('C29', '✓');
                        break;
                    case 'vacation leave':
                        $sheet->setCellValue('C20', '✓');
                        break;
                    case 'special leave benefits for women':
                        $sheet->setCellValue('C30', '✓');
                        break;
                    case 'calamity leave':
                        $sheet->setCellValue('C31', '✓');
                        break;
                    case 'vawc leave':
                        $sheet->setCellValue('C28', '✓');
                        break;
                    case 'service credit':
                        $sheet->setCellValue('C22', '✓');
                        break;
                }
            }

            // Add leave deficit (day_debt) to B58 if it exists
            if ($leaveRequest->day_debt && $leaveRequest->day_debt > 0) {
                $sheet->setCellValue('B58', $leaveRequest->day_debt);
            } else {
                $sheet->setCellValue('B58', '0'); // Set to 0 if no day debt
            }

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
            $filename = 'Leave_Application_' . $personnel->personnel_id . '_' . now()->format('Y-m-d') . '.xlsx';

            // Save to temporary file
            $tempPath = storage_path('app/temp/' . $filename);
            if (!is_dir(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($tempPath);

            // Return download response
            return response()->download($tempPath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating Excel file: ' . $e->getMessage());
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

    private function calculateWorkingDays($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Ensure end date is after start date
        if ($end->lt($start)) {
            return 0;
        }

        // Calculate working days (excluding weekends)
        $workingDays = 0;
        $currentDate = $start->copy();

        while ($currentDate->lte($end)) {
            // Exclude Saturday (6) and Sunday (7)
            if ($currentDate->dayOfWeek !== Carbon::SATURDAY && $currentDate->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }
}
