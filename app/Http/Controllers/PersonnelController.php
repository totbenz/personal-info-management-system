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

    /**
     * Store a newly created personnel by school head - Matches PersonnelCreate.php structure
     */
    public function schoolHeadPersonnelStore(Request $request)
    {
        try {
            // Validate the request - Matching PersonnelCreate.php validation rules
            $validated = $request->validate([
                // Personal Information
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'name_ext' => 'nullable|string|max:10',
                'sex' => 'required|in:male,female',
                'civil_status' => 'required|in:single,married,widowed,divorced,seperated,others',
                'citizenship' => 'required|string|max:255',
                'blood_type' => 'nullable|string|max:5',
                'height' => 'nullable|string|max:10',
                'weight' => 'nullable|string|max:10',
                'date_of_birth' => 'required|date|before:today',
                'place_of_birth' => 'required|string|max:255',
                'email' => 'nullable|email|max:255|unique:personnels,email',
                'tel_no' => 'nullable|string|max:20',
                'mobile_no' => 'nullable|string|max:20',

                // Work Information
                'personnel_id' => 'required|string|max:50|unique:personnels,personnel_id',
                'school_id' => 'nullable|exists:schools,id',
                'position_id' => 'required|exists:position,id',
                'appointment' => 'required|in:regular,part-time,temporary,contract',
                'fund_source' => 'required|string|max:255',
                'salary_grade_id' => 'required|exists:salary_grades,id',
                'step_increment' => 'required|in:1,2,3,4,5,6,7,8',
                'category' => 'required|in:SDO Personnel,School Head,Elementary School Teacher,Junior High School Teacher,Senior High School Teacher,School Non-teaching Personnel',
                'job_status' => 'required|string|max:255',
                'employment_start' => 'required|date',
                'employment_end' => 'nullable|date|after:employment_start',
                'pantilla_of_personnel' => 'nullable|string|max:255',
                'is_solo_parent' => 'boolean',

                // Government Information
                'tin' => 'required|string|size:12',
                'sss_num' => 'nullable|string|size:10',
                'gsis_num' => 'nullable|string|size:11',
                'philhealth_num' => 'nullable|string|size:12',
                'pagibig_num' => 'nullable|string|size:12',
            ], [
                'personnel_id.unique' => 'This Employee ID already exists.',
                'email.unique' => 'This email is already registered.',
                'date_of_birth.before' => 'Date of birth must be before today.',
                'employment_end.after' => 'Employment end date must be after start date.',
                'tin.size' => 'TIN must be exactly 12 characters.',
                'sss_num.size' => 'SSS number must be exactly 10 characters.',
                'gsis_num.size' => 'GSIS number must be exactly 11 characters.',
                'philhealth_num.size' => 'PhilHealth number must be exactly 12 characters.',
                'pagibig_num.size' => 'Pag-IBIG number must be exactly 12 characters.',
            ]);

            // Ensure school head can only create personnel for their own school
            $schoolHead = Auth::user()->personnel;
            $validated['school_id'] = $schoolHead->school_id; // Force school_id to be the school head's school

            DB::beginTransaction();

            // Create personnel record (matching PersonnelCreate.php field names)
            $personnel = Personnel::create([
                // Personal Information
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'name_ext' => $validated['name_ext'],
                'sex' => $validated['sex'],
                'civil_status' => $validated['civil_status'],
                'citizenship' => $validated['citizenship'],
                'blood_type' => $validated['blood_type'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'date_of_birth' => $validated['date_of_birth'],
                'place_of_birth' => $validated['place_of_birth'],
                'email' => $validated['email'],
                'tel_no' => $validated['tel_no'],
                'mobile_no' => $validated['mobile_no'],

                // Work Information
                'personnel_id' => $validated['personnel_id'],
                'school_id' => $validated['school_id'],
                'position_id' => $validated['position_id'],
                'appointment' => $validated['appointment'],
                'fund_source' => $validated['fund_source'],
                'salary_grade_id' => $validated['salary_grade_id'],
                'step_increment' => $validated['step_increment'],
                'category' => $validated['category'],
                'job_status' => $validated['job_status'],
                'employment_start' => $validated['employment_start'],
                'employment_end' => $validated['employment_end'],
                'pantilla_of_personnel' => $validated['pantilla_of_personnel'],
                'is_solo_parent' => $validated['is_solo_parent'] ?? false,

                // Government Information
                'tin' => $validated['tin'],
                'sss_num' => $validated['sss_num'],
                'gsis_num' => $validated['gsis_num'],
                'philhealth_num' => $validated['philhealth_num'],
                'pagibig_num' => $validated['pagibig_num'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Personnel created successfully!',
                'personnel_id' => $personnel->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating personnel: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating personnel: ' . $e->getMessage()
            ], 500);
        }
    }
}
