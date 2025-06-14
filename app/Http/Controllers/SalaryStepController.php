<?php

namespace App\Http\Controllers;

use App\Models\SalaryStep;
use Illuminate\Http\Request;

class SalaryStepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salarySteps = SalaryStep::all();
        return view('salary_steps.index', compact('salarySteps'));
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
    public function show(SalaryStep $salaryStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalaryStep $salaryStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SalaryStep $salaryStep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryStep $salaryStep)
    {
        //
    }
}
