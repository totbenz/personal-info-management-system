<?php

namespace App\Http\Controllers;

use App\Exports\PersonnelDataExport;
use App\Models\Personnel;
use App\Jobs\UpdateStepIncrement;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PersonnelController extends Controller
{
    public function index()
    {
        // Retrieve all personnels
        $personnels = Personnel::all();

        foreach ($personnels as $personnel) {
            // Check if salary_grade_id or step_increment is null
            if (is_null($personnel->salary_grade_id) || is_null($personnel->step_increment)) {
                // Set salary to null if either is null
                $personnel->update(['salary' => null]);
                continue;
            }

            // Compare salary_grade_id and step_increment in the salary_steps table
            $salaryStep = DB::table('salary_steps')
                ->where('salary_grade_id', $personnel->salary_grade_id)
                ->where('step', $personnel->step_increment)
                ->where('year', now()->year) // Match the current year
                ->value('salary'); // Get the salary value

            // Update the personnel's salary if a match is found
            $personnel->update(['salary' => $salaryStep]);
        }

        // Fetch updated personnels to display in the view
        $personnels = Personnel::all();

        return view('personnel.index', compact('personnels'));
    }

    public function show($id)
    {
        $personnel = Personnel::findOrFail($id);
        return view('personnel.show', compact('personnel'));
    }

    public function profile()
    {
        $personnel = Personnel::findOrFail(Auth::user()->personnel->id);
        return view('personnel.show', compact('personnel'));
    }

    public function save(Request $request)
    {
        try {
            $request->validate([
                'personnel_id' => 'required',
                'school_id' => 'required',
            ]);

            Personnel::create($request->all());
            session()->flash('flash.banner', 'Personnel Created Successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (ValidationException $e) {
            session()->flash('flash.banner', 'Failed to create Personnel');
            session()->flash('flash.bannerStyle', 'danger');
        }
        return redirect()->back();
    }

    public function create()
    {
        return view('personnel.create');
    }

    public function loyaltyAwards()
    {
        $recipients = Personnel::getLoyaltyAwardRecipients();
        return view('personnel.loyalty-awards', compact('recipients'));
    }

    public function export($id)
    {
        Log::info('Export method called with id: ' . $id);

        try {
            $personnel = Personnel::findOrFail($id);
            Log::info('Personnel found: ' . $personnel->personnel_id);

            // Pass the personnel data to the export class
            $export = new PersonnelDataExport($personnel->id);
            Log::info('PersonnelDataExport instance created');

            $outputPath = $export->getOutputPath();
            Log::info('Output path: ' . $outputPath);

            return response()->download($outputPath, $personnel->personnel_id . '_' . $personnel->first_name . $personnel->last_name . '_pds.xlsm');
        } catch (\Exception $e) {
            Log::error('Error during export: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to export personnel data.');
        }
    }

    public function exportTeacherProfile()
    {
        Log::info('Export method called for authenticated user.');

        try {
            // Get the authenticated user's personnel data
            $personnel = Personnel::findOrFail(Auth::user()->personnel->id);
            Log::info('Personnel found: ' . $personnel->personnel_id);

            // Pass the personnel data to the export class
            $export = new PersonnelDataExport($personnel->id);
            Log::info('PersonnelDataExport instance created');

            $outputPath = $export->getOutputPath();
            Log::info('Output path: ' . $outputPath);

            // Return the Excel file as a download
            return response()->download($outputPath, $personnel->personnel_id . '_' . $personnel->first_name . $personnel->last_name . '_pds.xlsm');
        } catch (\Exception $e) {
            Log::error('Error during export: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to export personnel data.');
        }
    }

    public function destroy($id)
    {
        try {
            $personnel = Personnel::findOrFail($id);
            $personnel->delete();

            session()->flash('flash.banner', 'Personnel Deleted Successfully');
            session()->flash('flash.bannerStyle', 'success');
        } catch (\Exception $e) {
            session()->flash('flash.banner', 'Failed To Delete Personnel.' . $e);
            session()->flash('flash.bannerStyle', 'danger');
        }
        return redirect()->route('personnels.index');
    }
}
