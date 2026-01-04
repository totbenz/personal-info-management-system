<?php

namespace App\Http\Controllers;

use App\Exports\Sheets\EducationSheetExport;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class EducationSheetController extends Controller
{
    /**
     * Export the Education Sheet for a personnel
     */
    public function export($personnelId)
    {
        $personnel = Personnel::findOrFail($personnelId);

        // Check authorization
        $user = Auth::user();
        if (!$this->canExport($user, $personnel)) {
            abort(403, 'Unauthorized action.');
        }

        $filename = 'Education_Sheet_' . str_replace(' ', '_', $personnel->full_name) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new EducationSheetExport($personnel), $filename);
    }

    /**
     * Export the Education Sheet for current teacher (teacher profile)
     */
    public function exportTeacherProfile()
    {
        $user = Auth::user();

        if ($user->role !== 'teacher') {
            abort(403, 'Unauthorized action.');
        }

        $personnel = $user->personnel;

        if (!$personnel) {
            abort(404, 'Personnel record not found.');
        }

        $filename = 'Education_Sheet_' . str_replace(' ', '_', $personnel->full_name) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new EducationSheetExport($personnel), $filename);
    }

    /**
     * Check if user can export the personnel's education sheet
     */
    private function canExport($user, $personnel)
    {
        // Admin can export all
        if ($user->role === 'admin') {
            return true;
        }

        // School head can export personnel from their school
        if ($user->role === 'school_head') {
            return $user->school_id === $personnel->school_id;
        }

        // Teacher can only export their own
        if ($user->role === 'teacher') {
            return $user->personnel_id === $personnel->id;
        }

        return false;
    }
}
