<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\School;
use App\Models\User;
use App\Models\LeaveRequest;
use App\Models\ServiceCreditRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\SalaryGrade;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Utils\BladeOptimization;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function adminHome()
    {
        // Set timeout and memory limits for dashboard operations
        set_time_limit(90); // 1.5 minutes
        ini_set('memory_limit', '256M');


        // $config = config()->nonExistentMethod();

        $personnelCount = Personnel::count();
        $schoolCount = School::count();
        $userCount = User::count();

        // Limit data to prevent timeout - only load what's needed for dashboard
        $activePersonnels = Personnel::where('job_status', 'Active')
            ->limit(100) // Limit to prevent timeout
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'name_ext', 'job_status']);

        $schools = School::limit(100) // Limit to prevent timeout
            ->get(['id', 'school_id', 'school_name', 'district_id', 'division']);

        $allPersonnels = Personnel::limit(100) // Limit to prevent timeout
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'name_ext', 'job_status']);

        $users = User::with(['personnel' => function ($q) {
            $q->select('id', 'first_name', 'middle_name', 'last_name', 'name_ext');
        }])
            ->limit(100) // Limit to prevent timeout
            ->get(['id', 'email', 'created_at', 'personnel_id']);

        // Job status counts
        $jobStatusCounts = Personnel::selectRaw('job_status, COUNT(*) as count')
            ->groupBy('job_status')
            ->pluck('count', 'job_status');

        // Schools per district
        $schoolsPerDistrict = School::selectRaw('district_id, COUNT(*) as count')
            ->groupBy('district_id')
            ->pluck('count', 'district_id');

        // Schools per division
        $schoolsPerDivision = School::selectRaw('division, COUNT(*) as count')
            ->groupBy('division')
            ->pluck('count', 'division');

        // Pending leave requests from school heads, teachers, and non-teaching staff
        // This feature allows admin to view and approve/deny leave requests directly from the dashboard
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')
            ->with(['user.personnel'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'pending_leave_page');

        // Pending CTO requests from school heads
        $pendingCTORequests = \App\Models\CTORequest::where('status', 'pending')
            ->with(['personnel', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'pending_cto_page');

        // Pending Service Credit requests (teachers only)
        $pendingServiceCreditRequests = ServiceCreditRequest::where('status', 'pending')
            ->with(['teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'pending_service_credit_page');

        // Pending Monetization requests from all roles
        $pendingMonetizationRequests = \App\Models\LeaveMonetization::where('status', 'pending')
            ->with(['personnel', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'pending_monetization_page');

        // Debug logging
        \Illuminate\Support\Facades\Log::info('Admin Dashboard - Service Credit Requests', [
            'total_service_credit_requests' => ServiceCreditRequest::count(),
            'pending_service_credit_requests' => $pendingServiceCreditRequests->count(),
            'pending_requests_data' => $pendingServiceCreditRequests->toArray()
        ]);

        // Approved leave requests from all roles
        $approvedLeaveRequests = LeaveRequest::where('status', 'approved')
            ->with(['user.personnel.school', 'user.personnel.position'])
            ->orderBy('updated_at', 'desc')
            ->paginate(5, ['*'], 'approved_leave_page');

        // Approved CTO requests from all roles
        $approvedCTORequests = \App\Models\CTORequest::where('status', 'approved')
            ->with(['personnel.school', 'personnel.position', 'user'])
            ->orderBy('updated_at', 'desc')
            ->paginate(5, ['*'], 'approved_cto_page');

        // Approved Service Credit requests (for history / optional display) - limit to prevent timeout
        $approvedServiceCreditRequests = ServiceCreditRequest::where('status', 'approved')
            ->with(['teacher'])
            ->orderBy('updated_at', 'desc')
            ->limit(50) // Limit to prevent timeout
            ->get();

        return view('dashboard', BladeOptimization::optimizeViewData(compact(
            'personnelCount',
            'schoolCount',
            'userCount',
            'activePersonnels',
            'schools',
            'allPersonnels',
            'users',
            'jobStatusCounts',
            'schoolsPerDistrict',
            'schoolsPerDivision',
            'pendingLeaveRequests',
            'pendingCTORequests',
            'pendingServiceCreditRequests',
            'pendingMonetizationRequests',
            'approvedLeaveRequests',
            'approvedCTORequests',
            'approvedServiceCreditRequests',
        ), 100));
    }

    public function schoolHeadDashboard()
    {
        $user = Auth::user();
        $schoolHead = $user->personnel;
        $school = $schoolHead->school;

        // Initialize CTO service for enhanced CTO management
        $ctoService = app(\App\Services\CTOService::class);

        // Initialize Leave Accrual service for automatic leave calculation
        $accrualService = app(\App\Services\SchoolHeadLeaveAccrualService::class);

        // School Head Leaves
        $year = now()->year;
        $soloParent = $schoolHead->is_solo_parent ?? false;
        $userSex = $schoolHead->sex ?? null;
        $defaultLeaves = \App\Models\SchoolHeadLeave::defaultLeaves($soloParent, $userSex);

        // Automatically update leave records with calculated accruals
        $accrualService->updateLeaveRecords($schoolHead->id, $year);

        // Update CTO balance using the new service
        $ctoService->updateSchoolHeadLeaveBalance($schoolHead->id);

        // Get existing leave records for this year (now they should all exist and be updated)
        $leaves = \App\Models\SchoolHeadLeave::where('school_head_id', $schoolHead->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        $leaveData = [];
        foreach ($defaultLeaves as $type => $defaultMax) {
            $leave = $leaves->get($type);
            $available = $leave ? $leave->available : $defaultMax;
            $used = $leave ? $leave->used : 0;

            // Calculate dynamic max: if available exceeds default, use available + used as the new max
            $calculatedMax = max($defaultMax, $available + $used);

            $leaveData[] = [
                'type' => $type,
                'max' => $calculatedMax,
                'available' => $available,
                'used' => $used,
                'ctos_earned' => $leave ? $leave->ctos_earned : 0,
                'remarks' => $leave ? $leave->remarks : '',
            ];
        }

        // Get accrual summary for display
        $accrualSummary = $accrualService->getAccrualSummary($schoolHead->id, $year);

        // Get detailed CTO balance information
        $ctoBalance = $ctoService->getCTOBalance($schoolHead->id);

        // School head's leave requests history
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // School head's CTO requests history
        $ctoRequests = \App\Models\CTORequest::where('school_head_id', $schoolHead->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // School statistics
        $totalPersonnel = Personnel::where('school_id', $school->id)->count();
        $activePersonnel = Personnel::where('school_id', $school->id)
            ->where('job_status', 'Active')
            ->count();
        $teachingPersonnel = Personnel::where('school_id', $school->id)
            ->whereHas('position', function ($query) {
                $query->where('classification', 'teaching');
            })
            ->count();
        $nonTeachingPersonnel = Personnel::where('school_id', $school->id)
            ->whereHas('position', function ($query) {
                $query->where('classification', 'non-teaching');
            })
            ->count();

        // Personnel by category
        $personnelByCategory = Personnel::where('school_id', $school->id)
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        // Personnel by appointment status
        $personnelByAppointment = Personnel::where('school_id', $school->id)
            ->selectRaw('appointment, COUNT(*) as count')
            ->groupBy('appointment')
            ->pluck('count', 'appointment');

        // Recent personnel additions (last 30 days)
        $recentPersonnel = Personnel::where('school_id', $school->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Personnel by salary grade
        $personnelBySalaryGrade = Personnel::where('school_id', $school->id)
            ->with('salaryGrade')
            ->selectRaw('salary_grade_id, COUNT(*) as count')
            ->groupBy('salary_grade_id')
            ->get();

        // Recent events
        $recentEvents = \App\Models\Event::where('status', 'active')
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // Personnel with expiring contracts (next 3 months)
        $expiringContracts = Personnel::where('school_id', $school->id)
            ->where('employment_end', '>=', now()->toDateString())
            ->where('employment_end', '<=', now()->addMonths(3)->toDateString())
            ->orderBy('employment_end', 'asc')
            ->take(10)
            ->get();

        // Personnel by position
        $personnelByPosition = Personnel::where('school_id', $school->id)
            ->with('position')
            ->selectRaw('position_id, COUNT(*) as count')
            ->groupBy('position_id')
            ->get();

        // Recent salary changes
        $recentSalaryChanges = \App\Models\SalaryChange::whereHas('personnel', function ($query) use ($school) {
            $query->where('school_id', $school->id);
        })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Loyalty Award Information for School Head
        $schoolHeadPersonnel = $user->personnel;
        $schoolHeadYearsOfService = $this->calculateYearsOfService($schoolHeadPersonnel->employment_start);
        $schoolHeadCanClaim = $this->canClaimLoyaltyAward($schoolHeadYearsOfService);
        $schoolHeadMaxClaims = $this->calculateMaxClaims($schoolHeadYearsOfService);
        $schoolHeadNextAwardYear = $this->getNextAwardYear($schoolHeadYearsOfService);

        // Loyalty Award Information for School Personnel
        $schoolPersonnelLoyalty = Personnel::where('school_id', $school->id)
            ->whereNotNull('employment_start')
            ->get()
            ->map(function ($personnel) {
                $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);
                $canClaim = $this->canClaimLoyaltyAward($yearsOfService);
                $maxClaims = $this->calculateMaxClaims($yearsOfService);

                return [
                    'personnel' => $personnel,
                    'years_of_service' => $yearsOfService,
                    'can_claim' => $canClaim,
                    'max_claims' => $maxClaims,
                    'is_eligible' => $canClaim
                ];
            });

        $eligiblePersonnelCount = $schoolPersonnelLoyalty->where('is_eligible', true)->count();

        // School information
        $schoolInfo = [
            'name' => $school->school_name,
            'id' => $school->school_id,
            'address' => $school->address,
            'division' => $school->division,
            'email' => $school->email,
            'phone' => $school->phone,
        ];

        return view('school_head.dashboard', compact(
            'schoolInfo',
            'totalPersonnel',
            'activePersonnel',
            'teachingPersonnel',
            'nonTeachingPersonnel',
            'personnelByCategory',
            'personnelByAppointment',
            'recentPersonnel',
            'personnelBySalaryGrade',
            'recentEvents',
            'expiringContracts',
            'personnelByPosition',
            'recentSalaryChanges',
            'schoolHeadYearsOfService',
            'schoolHeadCanClaim',
            'schoolHeadMaxClaims',
            'schoolHeadNextAwardYear',
            'schoolPersonnelLoyalty',
            'eligiblePersonnelCount',
            'leaveData',
            'leaveRequests',
            'ctoRequests',
            'ctoBalance',
            'accrualSummary',
            'year'
        ));
    }

    public function teacherDashboard()
    {
        $user = Auth::user();
        $personnel = $user->personnel;

        // Personal Information
        $personalInfo = [
            'full_name' => $personnel->first_name . ' ' . $personnel->middle_name . ' ' . $personnel->last_name . ' ' . $personnel->name_ext,
            'personnel_id' => $personnel->personnel_id,
            'date_of_birth' => $personnel->date_of_birth,
            'place_of_birth' => $personnel->place_of_birth,
            'citizenship' => $personnel->citizenship,
            'civil_status' => $personnel->civil_status,
            'sex' => $personnel->sex,
            'blood_type' => $personnel->blood_type,
            'height' => $personnel->height,
            'weight' => $personnel->weight,
            'email' => $personnel->email,
            'tel_no' => $personnel->tel_no,
            'mobile_no' => $personnel->mobile_no,
        ];

        // Work Information
        $workInfo = [
            'position' => $personnel->position->title ?? 'N/A',
            'classification' => $personnel->position->classification ?? 'N/A',
            'school' => $personnel->school->school_name ?? 'N/A',
            'school_id' => $personnel->school->school_id ?? 'N/A',
            'category' => $personnel->category,
            'job_status' => $personnel->job_status,
            'appointment' => $personnel->appointment,
            'employment_start' => $personnel->employment_start,
            'employment_end' => $personnel->employment_end,
            'fund_source' => $personnel->fund_source,
            'salary_grade' => $personnel->salary_grade_id,
            'step_increment' => $personnel->step_increment,
            'leave_of_absence_without_pay_count' => $personnel->leave_of_absence_without_pay_count,
        ];

        // Government Information
        $governmentInfo = [
            'tin' => $personnel->tin,
            'sss_num' => $personnel->sss_num,
            'gsis_num' => $personnel->gsis_num,
            'philhealth_num' => $personnel->philhealth_num,
            'pagibig_num' => $personnel->pagibig_num,
            'pantilla_of_personnel' => $personnel->pantilla_of_personnel,
        ];

        // Address Information
        $addresses = $personnel->addresses()->get();

        // Contact Person Information
        $contactPersons = $personnel->contactPerson()->get();

        // Family Information
        $familyMembers = $personnel->families()->get();

        // Education Information
        $education = $personnel->educations()->orderBy('type')->get();

        // Civil Service Eligibility
        $civilServiceEligibility = $personnel->civilServiceEligibilities()->get();

        // Work Experience
        $workExperience = $personnel->workExperiences()->orderBy('inclusive_from', 'desc')->get();

        // Voluntary Work
        $voluntaryWork = $personnel->voluntaryWorks()->orderBy('inclusive_from', 'desc')->get();

        // Training and Certifications
        $trainingCertifications = $personnel->trainingCertifications()->orderBy('inclusive_from', 'desc')->get();

        // References
        $references = $personnel->references()->get();

        // Assignment Details
        $assignmentDetails = $personnel->assignmentDetails()->orderBy('school_year', 'desc')->get();

        // Awards Received
        // Fix: Specify the correct table name for the AwardReceived model
        $awardsReceived = \App\Models\AwardReceived::query()
            ->where('personnel_id', $personnel->id)
            ->orderBy('award_date', 'desc')
            ->get();

        // Service Records
        $serviceRecords = $personnel->serviceRecords()->orderBy('from_date', 'desc')->get();

        // Other Information
        $otherInformation = $personnel->otherInformations()->get();

        // Personnel Details (Special Cases)
        $personnelDetails = $personnel->personnelDetail()->first();

        // Calculate years of service
        $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);

        // Loyalty Award Information
        $canClaimLoyaltyAward = $this->canClaimLoyaltyAward($yearsOfService);
        $maxClaims = $this->calculateMaxClaims($yearsOfService);
        $nextAwardYear = $this->getNextAwardYear($yearsOfService);

        // Recent Events (if any)
        $recentEvents = \App\Models\Event::where('status', 'active')
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // Salary Information
        $salaryInfo = [
            'current_salary_grade' => $personnel->salary_grade_id,
            'step_increment' => $personnel->step_increment,
            'years_of_service' => $yearsOfService,
        ];

        // Recent Salary Changes
        $recentSalaryChanges = \App\Models\SalaryChange::where('personnel_id', $personnel->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Teacher's leave requests history
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get teacher leave data from database
        $year = now()->year;
        $soloParent = $personnel->is_solo_parent ?? false;
        $userSex = $personnel->sex ?? null;
        $defaultLeaves = \App\Models\TeacherLeave::defaultLeaves($yearsOfService, $soloParent, $userSex);

        // Get existing leave records for this year
        $leaves = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        // Only create records that don't exist
        foreach ($defaultLeaves as $leaveType => $maxDays) {
            if (!$leaves->has($leaveType)) {
                \App\Models\TeacherLeave::create([
                    'teacher_id' => $personnel->id,
                    'leave_type' => $leaveType,
                    'year' => $year,
                    'available' => $maxDays,
                    'used' => 0,
                    'remarks' => 'Auto-initialized'
                ]);
            }
        }

        // Re-fetch after creating any missing records
        $leaves = \App\Models\TeacherLeave::where('teacher_id', $personnel->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        $teacherLeaveData = [];
        foreach ($defaultLeaves as $type => $defaultMax) {
            $leave = $leaves->get($type);
            $available = $leave ? $leave->available : $defaultMax;
            $used = $leave ? $leave->used : 0;

            // Calculate dynamic max: if available exceeds default, use available + used as the new max
            $calculatedMax = max($defaultMax, $available + $used);

            $teacherLeaveData[] = [
                'type' => $type,
                'max' => $calculatedMax,
                'available' => $available,
                'used' => $used,
                'remarks' => $leave ? $leave->remarks : '',
            ];
        }

        $year = now()->year;

        // Teacher Service Credit Requests (recent)
        $serviceCreditRequests = \App\Models\ServiceCreditRequest::where('teacher_id', $personnel->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'personalInfo',
            'workInfo',
            'governmentInfo',
            'addresses',
            'contactPersons',
            'familyMembers',
            'education',
            'civilServiceEligibility',
            'workExperience',
            'voluntaryWork',
            'trainingCertifications',
            'references',
            'assignmentDetails',
            'awardsReceived',
            'serviceRecords',
            'otherInformation',
            'personnelDetails',
            'yearsOfService',
            'canClaimLoyaltyAward',
            'maxClaims',
            'nextAwardYear',
            'recentEvents',
            'salaryInfo',
            'recentSalaryChanges',
            'leaveRequests',
            'teacherLeaveData',
            'serviceCreditRequests',
            'year'
        ));
    }
    public function nonTeachingDashboard()
    {
        $user = Auth::user();
        $personnel = $user->personnel;

        // Personal Information
        $personalInfo = [
            'full_name' => $personnel->first_name . ' ' . $personnel->middle_name . ' ' . $personnel->last_name . ' ' . $personnel->name_ext,
            'personnel_id' => $personnel->personnel_id,
            'date_of_birth' => $personnel->date_of_birth,
            'place_of_birth' => $personnel->place_of_birth,
            'citizenship' => $personnel->citizenship,
            'civil_status' => $personnel->civil_status,
            'sex' => $personnel->sex,
            'blood_type' => $personnel->blood_type,
            'height' => $personnel->height,
            'weight' => $personnel->weight,
            'email' => $personnel->email,
            'tel_no' => $personnel->tel_no,
            'mobile_no' => $personnel->mobile_no,
        ];

        // Work Information
        $workInfo = [
            'position' => $personnel->position->title ?? 'N/A',
            'classification' => $personnel->position->classification ?? 'N/A',
            'school' => $personnel->school->school_name ?? 'N/A',
            'school_id' => $personnel->school->school_id ?? 'N/A',
            'category' => $personnel->category,
            'job_status' => $personnel->job_status,
            'appointment' => $personnel->appointment,
            'employment_start' => $personnel->employment_start,
            'employment_end' => $personnel->employment_end,
            'fund_source' => $personnel->fund_source,
            'salary_grade' => $personnel->salary_grade_id,
            'step_increment' => $personnel->step_increment,
            'leave_of_absence_without_pay_count' => $personnel->leave_of_absence_without_pay_count,
        ];

        // Government Information
        $governmentInfo = [
            'tin' => $personnel->tin,
            'sss_num' => $personnel->sss_num,
            'gsis_num' => $personnel->gsis_num,
            'philhealth_num' => $personnel->philhealth_num,
            'pagibig_num' => $personnel->pagibig_num,
            'pantilla_of_personnel' => $personnel->pantilla_of_personnel,
        ];

        // Address Information
        $addresses = $personnel->addresses()->get();

        // Contact Person Information
        $contactPersons = $personnel->contactPerson()->get();

        // Family Information
        $familyMembers = $personnel->families()->get();

        // Education Information
        $education = $personnel->educations()->orderBy('type')->get();

        // Civil Service Eligibility
        $civilServiceEligibility = $personnel->civilServiceEligibilities()->get();

        // Work Experience
        $workExperience = $personnel->workExperiences()->orderBy('inclusive_from', 'desc')->get();

        // Voluntary Work
        $voluntaryWork = $personnel->voluntaryWorks()->orderBy('inclusive_from', 'desc')->get();

        // Training and Certifications
        $trainingCertifications = $personnel->trainingCertifications()->orderBy('inclusive_from', 'desc')->get();

        // References
        $references = $personnel->references()->get();

        // Assignment Details
        $assignmentDetails = $personnel->assignmentDetails()->orderBy('school_year', 'desc')->get();

        // Awards Received
        // Fix: Specify the correct table name for the AwardReceived model
        $awardsReceived = \App\Models\AwardReceived::query()
            ->where('personnel_id', $personnel->id)
            ->orderBy('award_date', 'desc')
            ->get();

        // Service Records
        $serviceRecords = $personnel->serviceRecords()->orderBy('from_date', 'desc')->get();

        // Other Information
        $otherInformation = $personnel->otherInformations()->get();

        // Personnel Details (Special Cases)
        $personnelDetails = $personnel->personnelDetail()->first();

        // Calculate years of service
        $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);

        // Loyalty Award Information
        $canClaimLoyaltyAward = $this->canClaimLoyaltyAward($yearsOfService);
        $maxClaims = $this->calculateMaxClaims($yearsOfService);
        $nextAwardYear = $this->getNextAwardYear($yearsOfService);

        // Recent Events (if any)
        $recentEvents = \App\Models\Event::where('status', 'active')
            ->where('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get();

        // Salary Information
        $salaryInfo = [
            'current_salary_grade' => $personnel->salary_grade_id,
            'step_increment' => $personnel->step_increment,
            'years_of_service' => $yearsOfService,
        ];

        // Recent Salary Changes
        $recentSalaryChanges = \App\Models\SalaryChange::where('personnel_id', $personnel->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Leave requests history
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // CTO requests history (for this non-teaching personnel)
        $ctoRequests = \App\Models\CTORequest::where('school_head_id', $personnel->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $year = now()->year;

        // Build full leave data similar to school head (excluding Personal Leave; including Special Privilege & CTO)
        $soloParent = $personnel->is_solo_parent ?? false;
        $userSex = $personnel->sex ?? null;
        $civilStatus = $personnel->civil_status ?? null;
        $yearsOfService = $personnel->employment_start ? \Carbon\Carbon::parse($personnel->employment_start)->diffInYears(now()) : 0;
        $defaultLeaves = \App\Models\NonTeachingLeave::defaultLeaves($yearsOfService, $soloParent, $userSex, $civilStatus);

        // Fetch any existing NonTeachingLeave records if model/table exists (legacy compatibility)
        $existingLeaves = \App\Models\NonTeachingLeave::where('non_teaching_id', $personnel->id)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type');

        $leaveData = [];
        foreach ($defaultLeaves as $type => $defaultMax) {
            $record = $existingLeaves->get($type);
            $available = $record ? $record->available : $defaultMax;
            $used = $record ? $record->used : 0;
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

        // --- CTO Integration (reuse SchoolHeadLeave + CTOService for non-teaching) ---
        // We store CTO credits in SchoolHeadLeave for backward compatibility even for non-teaching staff
        $ctoService = app(\App\Services\CTOService::class);
        // Ensure legacy record reflects current CTOEntry data (if any approval already happened)
        $ctoService->updateSchoolHeadLeaveBalance($personnel->id);

        // Fetch updated CTO legacy record (will be created on first approval)
        $ctoLeaveRecord = \App\Models\SchoolHeadLeave::where('school_head_id', $personnel->id)
            ->where('leave_type', 'Compensatory Time Off')
            ->where('year', $year)
            ->first();

        // Override leaveData CTO slot with accurate values, including earned CTOs
        if ($ctoLeaveRecord) {
            foreach ($leaveData as &$ld) {
                if ($ld['type'] === 'Compensatory Time Off') {
                    $ld['available'] = $ctoLeaveRecord->available;
                    $ld['used'] = $ctoLeaveRecord->used;
                    $ld['max'] = max($ld['max'], $ctoLeaveRecord->available + $ctoLeaveRecord->used);
                    $ld['ctos_earned'] = $ctoLeaveRecord->ctos_earned;
                    $ld['remarks'] = $ctoLeaveRecord->remarks;
                    break;
                }
            }
            unset($ld); // break reference
        }

        // Detailed CTO balance (entries, totals, expiry info)
        $ctoBalance = $ctoService->getCTOBalance($personnel->id);
        $accrualSummary = null; // Not yet implemented for non-teaching

        return view('non_teaching.dashboard', compact(
            'personalInfo',
            'workInfo',
            'governmentInfo',
            'yearsOfService',
            'canClaimLoyaltyAward',
            'maxClaims',
            'recentEvents',
            'recentSalaryChanges',
            'leaveRequests',
            'ctoRequests',
            'leaveData',
            'ctoBalance',
            'accrualSummary',
            'year'
        ));
    }
    // public function nonTeachingDashboard()
    // {
    //     $user = Auth::user();
    //     $personnel = $user->personnel;

    //     // Personal Information
    //     $personalInfo = [
    //         'full_name' => $personnel->first_name . ' ' . $personnel->middle_name . ' ' . $personnel->last_name . ' ' . $personnel->name_ext,
    //         'personnel_id' => $personnel->personnel_id,
    //         'date_of_birth' => $personnel->date_of_birth,
    //         'place_of_birth' => $personnel->place_of_birth,
    //         'citizenship' => $personnel->citizenship,
    //         'civil_status' => $personnel->civil_status,
    //         'sex' => $personnel->sex,
    //         'blood_type' => $personnel->blood_type,
    //         'height' => $personnel->height,
    //         'weight' => $personnel->weight,
    //         'email' => $personnel->email,
    //         'tel_no' => $personnel->tel_no,
    //         'mobile_no' => $personnel->mobile_no,
    //     ];

    //     // Limited Work Information (basic placement context)
    //     $workInfo = [
    //         'position' => $personnel->position->title ?? 'N/A',
    //         'classification' => $personnel->position->classification ?? 'N/A',
    //         'school' => $personnel->school->school_name ?? 'N/A',
    //         'school_id' => $personnel->school->school_id ?? 'N/A',
    //         'job_status' => $personnel->job_status,
    //         'appointment' => $personnel->appointment,
    //         'employment_start' => $personnel->employment_start,
    //         'employment_end' => $personnel->employment_end,
    //     ];

    //     // Recent Events (optional common info)
    //     $recentEvents = \App\Models\Event::where('status', 'active')
    //         ->where('start_date', '>=', now()->toDateString())
    //         ->orderBy('start_date', 'asc')
    //         ->take(5)
    //         ->get();

    //     return view('non_teaching.dashboard', compact(
    //         'personalInfo',
    //         'workInfo',
    //         'recentEvents'
    //     ));
    // }

    // Helper methods for loyalty award calculations
    private function calculateYearsOfService($employmentStart)
    {
        if (!$employmentStart) {
            return 0;
        }

        $startDate = \Carbon\Carbon::parse($employmentStart);
        $currentDate = \Carbon\Carbon::now();

        return $startDate->diffInYears($currentDate);
    }

    private function canClaimLoyaltyAward($yearsOfService)
    {
        if ($yearsOfService < 10) {
            return false;
        }

        // First award at 10 years
        if ($yearsOfService == 10) {
            return true;
        }

        // After 10 years, awards every 5 years
        if ($yearsOfService > 10) {
            return ($yearsOfService - 10) % 5 == 0;
        }

        return false;
    }

    private function calculateMaxClaims($yearsOfService)
    {
        if ($yearsOfService < 10) return 0;
        return 1 + floor(max(0, $yearsOfService - 10) / 5);
    }

    private function getNextAwardYear($yearsOfService)
    {
        if ($yearsOfService < 10) {
            return 10;
        }

        // Calculate next 5-year milestone after 10 years
        $yearsSinceFirstAward = $yearsOfService - 10;
        $nextMilestone = ceil(($yearsSinceFirstAward + 1) / 5) * 5;
        return 10 + $nextMilestone;
    }

    public function schoolHeadHome()
    {
        $school = Auth::user()->personnel->school->id;
        return view('school.show', ['school' => $school->id]);
    }

    public function teacherHome()
    {
        $personnel = Auth::user()->personnel->id;
        return view('personnel.show', ['personnel' => $personnel->id]);
    }

    /**
     * Filter approved leave requests by month and year
     */
    public function filterApprovedLeaveRequests(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year', now()->year);

        $query = LeaveRequest::where('status', 'approved')
            ->with(['user.personnel.school', 'user.personnel.position']);

        if ($month) {
            $query->whereMonth('updated_at', $month);
        }
        if ($year) {
            $query->whereYear('updated_at', $year);
        }

        $approvedLeaveRequests = $query->orderBy('updated_at', 'desc')->paginate(5, ['*'], 'approved_leave_page');

        return response()->json([
            'requests' => $approvedLeaveRequests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'personnel_name' => $request->user->personnel
                        ? $request->user->personnel->first_name . ' ' . $request->user->personnel->last_name
                        : $request->user->name,
                    'personnel_initials' => $request->user->personnel
                        ? substr($request->user->personnel->first_name, 0, 1) . substr($request->user->personnel->last_name, 0, 1)
                        : substr($request->user->name, 0, 2),
                    'position_title' => $request->user->personnel && $request->user->personnel->position
                        ? $request->user->personnel->position->title
                        : 'N/A',
                    'role' => $request->user->role,
                    'school_name' => $request->user->personnel && $request->user->personnel->school
                        ? $request->user->personnel->school->school_name
                        : 'N/A',
                    'school_id' => $request->user->personnel && $request->user->personnel->school
                        ? $request->user->personnel->school->school_id
                        : 'N/A',
                    'leave_type' => $request->leave_type,
                    'custom_leave_name' => $request->custom_leave_name,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'days_count' => Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1,
                    'updated_at' => $request->updated_at->format('M d, Y'),
                    'updated_time' => $request->updated_at->format('g:i A'),
                ];
            }),
            'count' => $approvedLeaveRequests->total(),
            'pagination' => $approvedLeaveRequests->links()->render()
        ]);
    }

    /**
     * Filter approved CTO requests by month and year
     */
    public function filterApprovedCTORequests(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year', now()->year);

        $query = \App\Models\CTORequest::where('status', 'approved')
            ->with(['personnel.school', 'personnel.position', 'user']);

        if ($month) {
            $query->whereMonth('updated_at', $month);
        }
        if ($year) {
            $query->whereYear('updated_at', $year);
        }

        $approvedCTORequests = $query->orderBy('updated_at', 'desc')->paginate(5, ['*'], 'approved_cto_page');

        return response()->json([
            'requests' => $approvedCTORequests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'personnel_name' => $request->personnel
                        ? $request->personnel->first_name . ' ' . $request->personnel->last_name
                        : ($request->user ? $request->user->name : 'N/A'),
                    'personnel_initials' => $request->personnel
                        ? substr($request->personnel->first_name, 0, 1) . substr($request->personnel->last_name, 0, 1)
                        : ($request->user ? substr($request->user->name, 0, 2) : 'N/A'),
                    'position_title' => $request->personnel && $request->personnel->position
                        ? $request->personnel->position->title
                        : 'N/A',
                    'school_name' => $request->personnel && $request->personnel->school
                        ? $request->personnel->school->school_name
                        : 'N/A',
                    'school_id' => $request->personnel && $request->personnel->school
                        ? $request->personnel->school->school_id
                        : 'N/A',
                    'work_date' => $request->work_date,
                    'start_time' => Carbon::parse($request->start_time)->format('g:i A'),
                    'end_time' => Carbon::parse($request->end_time)->format('g:i A'),
                    'requested_hours' => $request->requested_hours,
                    'cto_days_earned' => number_format($request->cto_days_earned, 2),
                    'reason' => $request->reason,
                    'admin_notes' => $request->admin_notes,
                    'updated_at' => $request->updated_at->format('M d, Y'),
                    'updated_time' => $request->updated_at->format('g:i A'),
                ];
            }),
            'count' => $approvedCTORequests->total(),
            'pagination' => $approvedCTORequests->links()->render()
        ]);
    }

    /**
     * Download approved leave requests as PDF
     */
    public function downloadApprovedLeaveRequestsPDF(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year', now()->year);

        $query = LeaveRequest::where('status', 'approved')
            ->with(['user.personnel.school', 'user.personnel.position']);

        if ($month) {
            $query->whereMonth('updated_at', $month);
        }
        if ($year) {
            $query->whereYear('updated_at', $year);
        }

        $approvedLeaveRequests = $query->orderBy('updated_at', 'desc')->get();

        $monthName = $month ? Carbon::create()->month($month)->format('F') : 'All Months';
        $fileName = 'Approved_Leave_Requests_' . $monthName . '_' . $year . '.pdf';

        $pdf = Pdf::loadView('pdf.approved-leave-requests', [
            'requests' => $approvedLeaveRequests,
            'month' => $monthName,
            'year' => $year,
            'generatedAt' => now()->format('F d, Y g:i A'),
            'totalRequests' => $approvedLeaveRequests->count()
        ]);

        return $pdf->download($fileName);
    }

    /**
     * Download approved CTO requests as PDF
     */
    public function downloadApprovedCTORequestsPDF(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year', now()->year);

        $query = \App\Models\CTORequest::where('status', 'approved')
            ->with(['personnel.school', 'personnel.position', 'user']);

        if ($month) {
            $query->whereMonth('updated_at', $month);
        }
        if ($year) {
            $query->whereYear('updated_at', $year);
        }

        $approvedCTORequests = $query->orderBy('updated_at', 'desc')->get();

        $monthName = $month ? Carbon::create()->month($month)->format('F') : 'All Months';
        $fileName = 'Approved_CTO_Requests_' . $monthName . '_' . $year . '.pdf';

        $pdf = Pdf::loadView('pdf.approved-cto-requests', [
            'requests' => $approvedCTORequests,
            'month' => $monthName,
            'year' => $year,
            'generatedAt' => now()->format('F d, Y g:i A'),
            'totalRequests' => $approvedCTORequests->count()
        ]);

        return $pdf->download($fileName);
    }

    /**
     * Display loyalty awards management page
     */
    public function loyaltyAwards()
    {
        return view('admin.loyalty-awards');
    }
}
