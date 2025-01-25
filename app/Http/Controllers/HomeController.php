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

        return view('dashboard', compact('personnelCount', 'schoolCount', 'userCount'));
        return view('dashboard');
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
