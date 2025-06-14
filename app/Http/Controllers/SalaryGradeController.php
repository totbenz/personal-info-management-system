<?php

namespace App\Http\Controllers;

use App\Models\SalaryGrade;
use Illuminate\Http\Request;

class SalaryGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salaryGrades = SalaryGrade::all();
        return view('salary_grade.index', compact('salaryGrades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SalaryGrade $salaryGrade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryGrade $salaryGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryGrade $salaryGrade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryGrade $salaryGrade)
    {
        //
    }
}
