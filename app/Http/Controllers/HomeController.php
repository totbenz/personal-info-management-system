<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SalaryGrade;

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
        $personnelCount = Personnel::count();
        $schoolCount = School::count();
        $userCount = User::count();
        $activePersonnels = Personnel::where('job_status', 'Active')->get(['id', 'first_name', 'middle_name', 'last_name', 'name_ext', 'job_status']);
        $schools = School::all(['id', 'school_id', 'school_name', 'district_id', 'division']);
        $allPersonnels = Personnel::all(['id', 'first_name', 'middle_name', 'last_name', 'name_ext', 'job_status']);
        $users = User::with(['personnel' => function ($q) {
            $q->select('id', 'first_name', 'middle_name', 'last_name', 'name_ext');
        }])->get(['id', 'email', 'created_at', 'personnel_id']);

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

        return view('dashboard', compact(
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
        ));
    }

    public function schoolHeadDashboard()
    {
        $user = Auth::user();
        $school = $user->personnel->school;

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
            'eligiblePersonnelCount'
        ));
    }

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
}
