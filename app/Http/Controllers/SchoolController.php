<?php

namespace App\Http\Controllers;

use App\Exports\SchoolExport;
use App\Exports\SchoolsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Models\AppointmentsFunding;
use App\Models\FundedItem;
use App\Models\School;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all();
        return view('school.index', compact('schools'));
    }

    public function create()
    {
        return view('school.create');
    }

    public function show($id)
    {
        $school = School::findOrFail($id);
        return view('school.show', compact('school'));
    }

    public function edit($id)
    {
        $school = School::findOrFail($id);
        return view('school.edit', ['school' => $school]);
    }

    public function profile($school)
    {
        $school = Auth::user()->personnel->school->id;
        return view('school.show', compact('school'));
    }

    public function export($id)
    {
        $school = School::findOrFail($id);
        $export = new SchoolExport($school->id);

        return response()->download($export->getOutputPath(), $school->school_id . '_sf7.xlsx');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'school_id' => 'required',
                'school_name' => 'required',
                'address' => 'required',
                'division' => 'required',
                'district_id' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'curricular_classification' => 'required',
            ]);

            School::create($request->all());
            session()->flash('flash.banner', 'School Created Successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (ValidationException $e) {
            session()->flash('flash.banner', 'Failed to create School');
            session()->flash('flash.bannerStyle', 'danger');
        }
        return redirect()->back();
    }
    

}
