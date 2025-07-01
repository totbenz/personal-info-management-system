<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalaryChangesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        // Fetch all salary changes for the given personnel ID
        $salaryChanges = \App\Models\SalaryChange::where('personnel_id', $id)->get();

        // Fetch personnel details
        $personnel = \App\Models\Personnel::find($id);

        // Combine full name
        $fullName = trim("{$personnel->first_name} {$personnel->middle_name} {$personnel->last_name}");

        // Pass the data to a view (correct path: livewire.salary-changes.index)
        return view('livewire.salary-changes.index', [
            'salaryChanges' => $salaryChanges,
            'personnelId' => $id,
            'fullName' => $fullName,
        ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
