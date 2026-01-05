<?php

namespace App\Http\Controllers;

use App\Exports\PersonnelDataExport;
use App\Exports\Sheets\CombinedPDSExport;
use App\Models\Personnel;
use App\Jobs\UpdateStepIncrement;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exports\Sheets\PersonnelDataC1Sheet;
use App\Exports\Sheets\PersonnelDataC2Sheet;
use App\Exports\Sheets\PersonnelDataC3Sheet;
use App\Exports\Sheets\PersonnelDataC4Sheet;
use App\Exports\Sheets\EducationSheetExport;
use ZipArchive;

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
        $personnel = Personnel::with([
            'position',
            'school',
            'salaryGrade',
            'educations',
            'families',
            'trainingCertifications',
            'workExperiences',
            'voluntaryWorks',
            'associations',
            'skills',
            'nonacademicDistinctions',
            'voluntaryWorks',
            'references',
            'children',
            'father',
            'mother',
            'spouse'
        ])->findOrFail($id);
        return view('personnel.show', compact('personnel'));
    }

    public function profile()
    {
        $personnel = Personnel::with([
            'position',
            'school',
            'salaryGrade',
            'educations',
            'families',
            'trainingCertifications',
            'workExperiences',
            'voluntaryWorks',
            'associations',
            'skills',
            'nonacademicDistinctions',
            'voluntaryWorks',
            'references',
            'children',
            'father',
            'mother',
            'spouse'
        ])->findOrFail(Auth::user()->personnel->id);
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
            $personnel = Personnel::with([
                'residentialAddress',
                'permanentAddress',
                'families',
                'children',
                'educationEntries',
                'civilServiceEligibilities',
                'workExperiences',
                'voluntaryWorks',
                'trainingCertifications',
                'otherInformations',
                'references',
                'personnelDetail'
            ])->findOrFail($id);
            Log::info('Personnel found: ' . $personnel->personnel_id);

            // Create temporary directory
            $tempDir = storage_path('app/temp/exports/' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Generate PDS (C1-C4 all in one file)
            $templatePath = public_path('report/macro_enabled_cs_form_no_2122.xlsx');
            $pdsSpreadsheet = IOFactory::load($templatePath);

            // Populate all sheets
            $c1Sheet = new PersonnelDataC1Sheet($personnel, $pdsSpreadsheet);
            $c1Sheet->populateSheet();

            $c2Sheet = new PersonnelDataC2Sheet($personnel, $pdsSpreadsheet);
            $c2Sheet->populateSheet();

            $c3Sheet = new PersonnelDataC3Sheet($personnel, $pdsSpreadsheet);
            $c3Sheet->populateSheet();

            $c4Sheet = new PersonnelDataC4Sheet($personnel, $pdsSpreadsheet);
            $c4Sheet->populateSheet();

            $pdsPath = $tempDir . '/PDS_' . str_replace(' ', '_', $personnel->full_name) . '.xlsx';
            $pdsWriter = new Xlsx($pdsSpreadsheet);
            $pdsWriter->save($pdsPath);

            // Generate Education Sheet
            $educationTemplatePath = public_path('report/Education_Sheet.xlsx');
            $educationSpreadsheet = IOFactory::load($educationTemplatePath);
            $educationExport = new EducationSheetExport($personnel);

            $sheets = $educationExport->sheets();
            foreach ($sheets as $index => $sheet) {
                $worksheet = $educationSpreadsheet->getSheet($index);
                $sheet->fillWorksheet($worksheet);
            }

            $educationPath = $tempDir . '/Education_Sheet_' . str_replace(' ', '_', $personnel->full_name) . '.xlsx';
            $educationWriter = new Xlsx($educationSpreadsheet);
            $educationWriter->save($educationPath);

            // Create ZIP file
            $zipPath = $tempDir . '/PDS_Complete_' . str_replace(' ', '_', $personnel->full_name) . '.zip';
            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($pdsPath, basename($pdsPath));
                $zip->addFile($educationPath, basename($educationPath));
                $zip->close();
            }

            // Clean up temp files
            @unlink($pdsPath);
            @unlink($educationPath);
            @rmdir($tempDir);

            // Download the ZIP file
            $filename = 'PDS_Complete_' . str_replace(' ', '_', $personnel->full_name) . '_' . date('Y-m-d') . '.zip';
            $response = Response::download($zipPath, $filename);
            $response->deleteFileAfterSend(true);

            return $response;

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
            $personnel = Personnel::with([
                'residentialAddress',
                'permanentAddress',
                'families',
                'children',
                'educationEntries',
                'civilServiceEligibilities',
                'workExperiences',
                'voluntaryWorks',
                'trainingCertifications',
                'otherInformations',
                'references',
                'personnelDetail'
            ])->findOrFail(Auth::user()->personnel->id);
            Log::info('Personnel found: ' . $personnel->personnel_id);

            // Create temporary directory
            $tempDir = storage_path('app/temp/exports/' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Generate PDS (C1-C4 all in one file)
            $templatePath = public_path('report/macro_enabled_cs_form_no_2122.xlsx');
            $pdsSpreadsheet = IOFactory::load($templatePath);

            // Populate all sheets
            $c1Sheet = new PersonnelDataC1Sheet($personnel, $pdsSpreadsheet);
            $c1Sheet->populateSheet();

            $c2Sheet = new PersonnelDataC2Sheet($personnel, $pdsSpreadsheet);
            $c2Sheet->populateSheet();

            $c3Sheet = new PersonnelDataC3Sheet($personnel, $pdsSpreadsheet);
            $c3Sheet->populateSheet();

            $c4Sheet = new PersonnelDataC4Sheet($personnel, $pdsSpreadsheet);
            $c4Sheet->populateSheet();

            $pdsPath = $tempDir . '/PDS_' . str_replace(' ', '_', $personnel->full_name) . '.xlsx';
            $pdsWriter = new Xlsx($pdsSpreadsheet);
            $pdsWriter->save($pdsPath);

            // Generate Education Sheet
            $educationTemplatePath = public_path('report/Education_Sheet.xlsx');
            $educationSpreadsheet = IOFactory::load($educationTemplatePath);
            $educationExport = new EducationSheetExport($personnel);

            $sheets = $educationExport->sheets();
            foreach ($sheets as $index => $sheet) {
                $worksheet = $educationSpreadsheet->getSheet($index);
                $sheet->fillWorksheet($worksheet);
            }

            $educationPath = $tempDir . '/Education_Sheet_' . str_replace(' ', '_', $personnel->full_name) . '.xlsx';
            $educationWriter = new Xlsx($educationSpreadsheet);
            $educationWriter->save($educationPath);

            // Create ZIP file
            $zipPath = $tempDir . '/PDS_Complete_' . str_replace(' ', '_', $personnel->full_name) . '.zip';
            $zip = new ZipArchive();

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($pdsPath, basename($pdsPath));
                $zip->addFile($educationPath, basename($educationPath));
                $zip->close();
            }

            // Clean up temp files
            @unlink($pdsPath);
            @unlink($educationPath);
            @rmdir($tempDir);

            // Download the ZIP file
            $filename = 'PDS_Complete_' . str_replace(' ', '_', $personnel->full_name) . '_' . date('Y-m-d') . '.zip';
            $response = Response::download($zipPath, $filename);
            $response->deleteFileAfterSend(true);

            return $response;

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
