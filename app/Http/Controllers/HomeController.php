<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $activePersonnels = Personnel::where('job_status', 'Active')->get(['first_name', 'middle_name', 'last_name', 'name_ext', 'job_status']);

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
            'jobStatusCounts',
            'schoolsPerDistrict',
            'schoolsPerDivision',
        ));
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
