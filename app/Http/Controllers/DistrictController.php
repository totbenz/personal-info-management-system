<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::all();
        return view('district.index', compact('districts'));
    }

    public function create()
    {
        return view('district.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        District::create($request->all());
        return redirect()->route('district.index')->with('success', 'District created successfully.');
    }

    public function show(District $district)
    {
        return view('district.show', compact('district'));
    }

    public function edit(District $district)
    {
        return view('district.edit', compact('district'));
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $district->update($request->all());
        return redirect()->route('district.index')->with('success', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        $district->delete();
        return redirect()->route('district.index')->with('success', 'District deleted successfully.');
    }
}
