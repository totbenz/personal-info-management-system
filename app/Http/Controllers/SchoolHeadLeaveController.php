<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolHeadLeave;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SchoolHeadLeaveController extends Controller
{
    public function index(Request $request)
    {
        $schoolHead = Auth::user()->personnel;
        $year = $request->input('year', Carbon::now()->year);
        $soloParent = $schoolHead->is_solo_parent ?? false;
        $defaultLeaves = SchoolHeadLeave::defaultLeaves($soloParent);

        $leaves = SchoolHeadLeave::where('school_head_id', $schoolHead->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        $leaveData = [];
        foreach ($defaultLeaves as $type => $max) {
            $leave = $leaves->get($type);
            $leaveData[] = [
                'type' => $type,
                'max' => $max,
                'available' => $leave ? $leave->available : $max,
                'used' => $leave ? $leave->used : 0,
                'ctos_earned' => $leave ? $leave->ctos_earned : 0,
                'remarks' => $leave ? $leave->remarks : '',
            ];
        }

        return view('school_head.partials.leaves', compact('leaveData', 'year'));
    }
}
