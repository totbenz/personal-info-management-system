<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\TeacherLeave;
use App\Models\SchoolHeadLeave;
use App\Models\NonTeachingLeave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PersonnelLeaveController extends Controller
{
    /**
     * Get leave types for a personnel
     */
    public function getLeaveTypes(Request $request, $personnelId)
    {
        $year = $request->get('year', Carbon::now()->year);

        // Find personnel
        $personnel = Personnel::find($personnelId);
        if (!$personnel) {
            return response()->json(['error' => 'Personnel not found'], 404);
        }

        // Find the user to get the role
        $user = User::where('personnel_id', $personnelId)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found for this personnel'], 404);
        }

        $leaveTypes = [];
        $leaves = [];
        $role = '';

        // Based on the role, query ONLY the specific table
        if ($user->role === 'teacher') {
            // Query only teacher_leaves table
            $teacherLeaves = TeacherLeave::where('teacher_id', $personnelId)
                ->where('year', $year)
                ->get();

            $role = 'Teacher';
            foreach ($teacherLeaves as $leave) {
                $leaveTypes[] = $leave->leave_type;
                $leaves[$leave->leave_type] = [
                    'available' => $leave->available,
                    'used' => $leave->used
                ];
            }
        }
        elseif ($user->role === 'school_head') {
            // Query only school_head_leaves table
            $schoolHeadLeaves = SchoolHeadLeave::where('school_head_id', $personnelId)
                ->where('year', $year)
                ->get();

            $role = 'School Head';
            foreach ($schoolHeadLeaves as $leave) {
                $leaveTypes[] = $leave->leave_type;
                $leaves[$leave->leave_type] = [
                    'available' => $leave->available,
                    'used' => $leave->used
                ];
            }
        }
        elseif ($user->role === 'non_teaching') {
            // Query only non_teaching_leaves table
            $nonTeachingLeaves = NonTeachingLeave::where('non_teaching_id', $personnelId)
                ->where('year', $year)
                ->get();

            $role = 'Non Teaching';
            foreach ($nonTeachingLeaves as $leave) {
                $leaveTypes[] = $leave->leave_type;
                $leaves[$leave->leave_type] = [
                    'available' => $leave->available,
                    'used' => $leave->used
                ];
            }
        }

        return response()->json([
            'personnel' => [
                'id' => $personnel->id,
                'name' => $personnel->first_name . ' ' . $personnel->last_name,
                'role' => $role
            ],
            'leaveTypes' => $leaveTypes,
            'leaves' => $leaves
        ]);
    }
}
