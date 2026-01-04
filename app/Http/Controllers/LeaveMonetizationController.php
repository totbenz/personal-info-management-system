<?php

namespace App\Http\Controllers;

use App\Services\LeaveMonetizationService;
use App\Services\SchoolHeadMonetizationService;
use App\Models\LeaveMonetization;
use App\Models\SchoolHeadMonetization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaveMonetizationController extends Controller
{
    protected $monetizationService;
    protected $schoolHeadMonetizationService;

    public function __construct(LeaveMonetizationService $monetizationService, SchoolHeadMonetizationService $schoolHeadMonetizationService)
    {
        $this->monetizationService = $monetizationService;
        $this->schoolHeadMonetizationService = $schoolHeadMonetizationService;
    }

    /**
     * Show monetization form
     */
    public function create()
    {
        $user = Auth::user();
        $personnel = $user->personnel;

        if (!$personnel) {
            return redirect()->back()->with('error', 'Personnel record not found.');
        }

        $maxDays = $this->monetizationService->calculateMaxMonetizableDays($personnel, $user->role);

        return response()->json([
            'vl_available' => $maxDays['vl_available'],
            'sl_available' => $maxDays['sl_available'],
            'vl_max_monetizable' => $maxDays['vl_max_monetizable'],
            'sl_max_monetizable' => $maxDays['sl_max_monetizable'],
            'total_max_monetizable' => $maxDays['total_max_monetizable'],
        ]);
    }

    /**
     * Store monetization request
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        $personnel = $user->personnel;

        // Get monetization requests for the current user
        $monetizationRequests = LeaveMonetization::where('personnel_id', $personnel->id)
            ->where('user_type', $user->role)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Prepare leave data for the modal (same as dashboard)
        $year = now()->year;
        $userSex = $personnel->sex ?? null;
        $civilStatus = $personnel->civil_status ?? null;
        $isSoloParent = $personnel->is_solo_parent ?? false;
        $yearsOfService = $personnel->employment_start ? \Carbon\Carbon::parse($personnel->employment_start)->diffInYears(now()) : 0;

        // Get default leaves based on user type
        if ($user->role == 'teacher') {
            $defaultLeaves = \App\Models\TeacherLeave::defaultLeaves($yearsOfService, $isSoloParent, $userSex, $civilStatus);
            $existingLeaves = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
                ->where('year', $year)
                ->get()
                ->keyBy('leave_type');

            // For teachers, get Service Credit balance for Sick Leave
            $serviceCredit = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
                ->where('leave_type', 'Service Credit')
                ->where('year', $year)
                ->first();
            $serviceCreditBalance = $serviceCredit ? $serviceCredit->available : 0;
        } else {
            $defaultLeaves = \App\Models\NonTeachingLeave::defaultLeaves($yearsOfService, $isSoloParent, $userSex, $civilStatus);
            $existingLeaves = \App\Models\NonTeachingLeave::where('non_teaching_id', $personnel->id)
                ->where('year', $year)
                ->get()
                ->keyBy('leave_type');
            $serviceCreditBalance = 0;
        }

        $leaveData = [];
        foreach ($defaultLeaves as $type => $defaultMax) {
            $record = $existingLeaves->get($type);

            // For teachers, use Service Credit balance for Sick Leave
            if ($user->role == 'teacher' && $type == 'Sick Leave') {
                $available = $serviceCreditBalance;
                $used = 0; // Service Credit usage is tracked separately
            } else {
                $available = $record ? $record->available : $defaultMax;
                $used = $record ? $record->used : 0;
            }

            $calculatedMax = max($defaultMax, $available + $used);
            $leaveData[] = [
                'type' => $type,
                'max' => $calculatedMax,
                'available' => $available,
                'used' => $used,
                'ctos_earned' => 0,
                'remarks' => $record ? $record->remarks : '',
            ];
        }

        // Filter leave data based on user profile
        $filteredLeaveData = array_filter($leaveData, function($leave) use ($userSex, $isSoloParent, $civilStatus) {
            if ($leave['type'] === 'Compensatory Time Off') return false;
            if (!$isSoloParent && $leave['type'] === 'Solo Parent Leave') return false;
            if ($userSex !== 'Female' && in_array($leave['type'], ['Maternity Leave', 'Special Leave Benefits for Women'])) return false;
            if ($userSex !== 'Male' && $leave['type'] === 'Paternity Leave') return false;
            return true;
        });

        // Create leave balances array
        $leaveBalances = [];
        foreach ($filteredLeaveData as $leave) {
            $leaveBalances[$leave['type']] = $leave['available'];
        }

        return view('monetization.history', compact('monetizationRequests', 'leaveBalances'));
    }

    public function store(Request $request)
    {
        // Debug: Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        try {
            $request->validate([
                'days_to_monetize' => 'required|integer|min:1',
                'reason' => 'required|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors())
            ], 422);
        }

        $user = Auth::user();
        $personnel = $user->personnel;

        if (!$personnel) {
            return response()->json([
                'success' => false,
                'message' => 'Personnel record not found.'
            ], 404);
        }

        // Debug: Check user role
        $userRole = $user->role;
        if (!in_array($userRole, ['teacher', 'non_teaching', 'school_head'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user role: ' . $userRole
            ], 403);
        }

        // Get leave balances from the request (sent from frontend)
        $vlAvailable = $request->input('vl_available', 0);
        $slAvailable = $request->input('sl_available', 0);

        // Calculate maximum monetizable days
        $maxMonetizableDays = max(0, $vlAvailable - 5) + max(0, $slAvailable - 5);

        // Debug: Log the validation request
        \Log::info('Monetization request:', [
            'user_id' => $user->id,
            'role' => $userRole,
            'days_requested' => $request->days_to_monetize,
            'personnel_id' => $personnel->id,
            'vl_available' => $vlAvailable,
            'sl_available' => $slAvailable,
            'max_monetizable' => $maxMonetizableDays
        ]);

        // Validate the request using frontend values
        if ($request->days_to_monetize > $maxMonetizableDays) {
            return response()->json([
                'success' => false,
                'message' => "Invalid amount. You can only monetize up to {$maxMonetizableDays} days. You must retain at least 5 days for each leave type."
            ], 422);
        }

        // Calculate distribution
        $remainingDays = $request->days_to_monetize;
        $vlToUse = min(max(0, $vlAvailable - 5), $remainingDays);
        $remainingDays -= $vlToUse;
        $slToUse = min(max(0, $slAvailable - 5), $remainingDays);

        try {
            // Create the monetization request
            $monetization = $this->monetizationService->createMonetizationRequest($personnel, $user->role, [
                'vl_days_used' => $vlToUse,
                'sl_days_used' => $slToUse,
                'total_days' => $request->days_to_monetize,
                'reason' => $request->reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Monetization request submitted successfully!',
                'data' => [
                    'id' => $monetization->id,
                    'total_days' => $monetization->total_days,
                    'status' => $monetization->status,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating monetization request', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your request. Please try again.'
            ], 500);
        }
    }

    /**
     * Show user's monetization history
     */
    public function index()
    {
        $user = Auth::user();

        $monetizations = LeaveMonetization::where('user_id', $user->id)
            ->with(['approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('monetization.index', compact('monetizations'));
    }

    /**
     * Admin: List all monetization requests
     */
    public function adminIndex(Request $request)
    {
        $search = $request->input('search');
        $userType = $request->input('user_type');
        $status = $request->input('status');

        // Get regular monetizations (teacher/non-teaching) with filters
        $regularQuery = LeaveMonetization::with(['user', 'personnel', 'approvedBy'])
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($search) {
            $regularQuery->whereHas('personnel', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status) {
            $regularQuery->where('status', $status);
        }

        $regularMonetizations = $regularQuery->get()
            ->map(function ($item) {
                $item->user_type = $item->user->role ?? 'unknown';
                return $item;
            });

        // Get school head monetizations with filters
        $schoolHeadQuery = SchoolHeadMonetization::with(['schoolHead'])
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($search) {
            $schoolHeadQuery->whereHas('schoolHead', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status) {
            $schoolHeadQuery->where('status', $status);
        }

        $schoolHeadMonetizations = $schoolHeadQuery->get()
            ->map(function ($item) {
                // Convert to similar format for display
                $item->user_id = $item->school_head_id;
                $item->personnel = $item->schoolHead;
                $item->total_days = $item->days_requested;
                $item->vl_days_used = $item->vl_deducted;
                $item->sl_days_used = $item->sl_deducted;
                $item->created_at = $item->request_date;
                $item->user_type = 'school_head';
                return $item;
            });

        // Merge and filter by user type if specified
        $allMonetizations = $regularMonetizations->concat($schoolHeadMonetizations);

        if ($userType && $userType !== 'all') {
            $allMonetizations = $allMonetizations->filter(function($item) use ($userType) {
                return $item->user_type === $userType;
            });
        }

        // Sort by date
        $allMonetizations = $allMonetizations->sortByDesc('created_at')->values();

        // Paginate
        $perPage = 15;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $itemsForCurrentPage = $allMonetizations->slice($offset, $perPage)->values();
        $paginatedMonetizations = new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsForCurrentPage,
            $allMonetizations->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        // Calculate statistics
        $stats = [
            'total' => $allMonetizations->count(),
            'pending' => $allMonetizations->where('status', 'pending')->count(),
            'approved' => $allMonetizations->where('status', 'approved')->count(),
            'rejected' => $allMonetizations->where('status', 'rejected')->count(),
            'teachers' => $regularMonetizations->filter(function($item) { return $item->user_type === 'teacher'; })->count(),
            'non_teaching' => $regularMonetizations->filter(function($item) { return $item->user_type === 'non_teaching'; })->count(),
            'school_heads' => $schoolHeadMonetizations->count(),
        ];

        return view('admin.monetization.index', compact('paginatedMonetizations', 'stats'));
    }

    /**
     * Admin: Get monetization request details
     */
    public function details($id)
    {
        // Check if this is a school head monetization
        $schoolHeadMonetization = SchoolHeadMonetization::with(['schoolHead'])->find($id);

        if ($schoolHeadMonetization) {
            // Transform the data to have consistent structure
            $data = $schoolHeadMonetization->toArray();
            $data['personnel'] = $schoolHeadMonetization->schoolHead;
            $data['user_type'] = 'school_head';

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        // Handle regular monetization
        $monetization = LeaveMonetization::with(['personnel', 'user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $monetization
        ]);
    }

    /**
     * Admin: Approve monetization request
     */
    public function approve(Request $request, $id)
    {
        // Check if this is a school head monetization
        $schoolHeadMonetization = SchoolHeadMonetization::find($id);

        if ($schoolHeadMonetization) {
            // Handle school head monetization
            if ($schoolHeadMonetization->status !== 'pending') {
                return redirect()->back()->with('error', 'This request has already been processed.');
            }

            $request->validate([
                'admin_remarks' => 'nullable|string|max:500',
            ]);

            try {
                $this->schoolHeadMonetizationService->processApprovedMonetization($schoolHeadMonetization);

                $schoolHeadMonetization->admin_remarks = $request->admin_remarks;
                $schoolHeadMonetization->save();

                Log::info('Admin approved school head monetization', [
                    'monetization_id' => $schoolHeadMonetization->id,
                    'admin_id' => Auth::id(),
                    'school_head_id' => $schoolHeadMonetization->school_head_id
                ]);

                return redirect()->back()->with('success', 'Monetization request approved successfully!');
            } catch (\Exception $e) {
                Log::error('Error approving school head monetization', [
                    'monetization_id' => $schoolHeadMonetization->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()->with('error', 'An error occurred while approving the request.');
            }
        }

        // Handle regular teacher/non-teaching monetization
        $monetization = LeaveMonetization::findOrFail($id);

        if ($monetization->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        try {
            $this->monetizationService->processApprovedMonetization($monetization);

            $monetization->update([
                'admin_remarks' => $request->admin_remarks,
            ]);

            return redirect()->back()->with('success', 'Monetization request approved successfully!');

        } catch (\Exception $e) {
            Log::error('Error approving monetization', [
                'monetization_id' => $monetization->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'An error occurred while approving the request.');
        }
    }

    /**
     * Admin: Reject monetization request
     */
    public function reject(Request $request, $id)
    {
        // Check if this is a school head monetization
        $schoolHeadMonetization = SchoolHeadMonetization::find($id);

        if ($schoolHeadMonetization) {
            // Handle school head monetization
            if ($schoolHeadMonetization->status !== 'pending') {
                return redirect()->back()->with('error', 'This request has already been processed.');
            }

            $request->validate([
                'rejection_reason' => 'required|string|max:500',
                'admin_remarks' => 'nullable|string|max:500',
            ]);

            try {
                $this->schoolHeadMonetizationService->processRejectedMonetization(
                    $schoolHeadMonetization,
                    $request->rejection_reason
                );

                $schoolHeadMonetization->admin_remarks = $request->admin_remarks;
                $schoolHeadMonetization->save();

                Log::info('Admin rejected school head monetization', [
                    'monetization_id' => $schoolHeadMonetization->id,
                    'admin_id' => Auth::id(),
                    'school_head_id' => $schoolHeadMonetization->school_head_id,
                    'reason' => $request->rejection_reason
                ]);

                return redirect()->back()->with('success', 'Monetization request rejected.');
            } catch (\Exception $e) {
                Log::error('Error rejecting school head monetization', [
                    'monetization_id' => $schoolHeadMonetization->id,
                    'error' => $e->getMessage()
                ]);
                return redirect()->back()->with('error', 'An error occurred while rejecting the request.');
            }
        }

        // Handle regular teacher/non-teaching monetization
        $monetization = LeaveMonetization::findOrFail($id);

        if ($monetization->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        try {
            $this->monetizationService->processRejectedMonetization($monetization, $request->rejection_reason, $request->admin_remarks);

            return redirect()->back()->with('success', 'Monetization request rejected.');
        } catch (\Exception $e) {
            Log::error('Error rejecting monetization', [
                'monetization_id' => $monetization->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'An error occurred while rejecting the request.');
        }
    }
}
