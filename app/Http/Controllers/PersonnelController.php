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

            // Use the same logic as getSalaryStepAmountAttribute for consistency
            $salaryStep = DB::table('salary_steps')
                ->where('salary_grade_id', $personnel->salary_grade_id)
                ->where('step', $personnel->step_increment)
                ->orderByDesc('year') // Get the latest year instead of current year
                ->first();

            // Update the personnel's salary if a match is found
            $salary = $salaryStep ? $salaryStep->salary : null;
            $personnel->update(['salary' => $salary]);
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

    public function store(Request $request)
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

            // Check if file was created successfully
            if (!file_exists($outputPath)) {
                throw new \Exception('Export file was not created');
            }

            // Clean up the export object
            $export->cleanup();

            return response()->download($outputPath, $personnel->personnel_id . '_' . $personnel->first_name . '_' . $personnel->last_name . '_pds.xlsx');
        } catch (\Exception $e) {
            Log::error('Error during export: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('dashboard')->with('error', 'Failed to export personnel data: ' . $e->getMessage());
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

            // Check if file was created successfully
            if (!file_exists($outputPath)) {
                throw new \Exception('Export file was not created');
            }

            // Clean up the export object
            $export->cleanup();

            // Return the Excel file as a download with correct extension
            return response()->download($outputPath, $personnel->personnel_id . '_' . $personnel->first_name . '_' . $personnel->last_name . '_pds.xlsx');
        } catch (\Exception $e) {
            Log::error('Error during export: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('dashboard')->with('error', 'Failed to export personnel data: ' . $e->getMessage());
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
