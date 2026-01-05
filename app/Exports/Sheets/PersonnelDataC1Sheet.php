<?php

namespace App\Exports\Sheets;

use App\Models\Personnel;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PersonnelDataC1Sheet
{
    protected $personnel;
    protected $worksheet;

    public function __construct($personnel, Spreadsheet $spreadsheet)
    {
        $this->personnel = $personnel;
        $this->worksheet = $spreadsheet->getSheet(0); // Assuming the first sheet is being used
    }

    public function populateSheet()
    {
        Log::info('PersonnelDataC1Sheet::populateSheet called', [
            'personnelId' => $this->personnel->id,
            'fullName' => $this->personnel->full_name
        ]);

        $worksheet = $this->worksheet;

        try {
            $this->populatePersonalInfo($worksheet);
            Log::info('Personal info populated');

            $this->populateAddress($worksheet);
            Log::info('Address info populated');

            $this->populateFamilyInfo($worksheet);
            Log::info('Family info populated');

            $this->populateEducation($worksheet);
            Log::info('Education info populated');

            $this->populateChildren($worksheet);
            Log::info('Children info populated');

            $this->populateCurrentDate($worksheet);
            Log::info('Current date populated');

            Log::info('PersonnelDataC1Sheet::populateSheet completed successfully');
        } catch (\Exception $e) {
            Log::error('Error in PersonnelDataC1Sheet::populateSheet', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function populatePersonalInfo($worksheet)
    {
        // Populate specific cells with data
        $worksheet->setCellValue('D10', $this->personnel->last_name ?? 'N/A');
        $worksheet->setCellValue('D11', $this->personnel->first_name ?? 'N/A');
        $worksheet->setCellValue('D12', $this->personnel->middle_name ?? 'N/A');
        $worksheet->setCellValue('N11', $this->personnel->name_ext ?? 'N/A');
        $worksheet->setCellValue('D13', $this->personnel->date_of_birth ?? 'N/A');
        $worksheet->setCellValue('D15', $this->personnel->place_of_birth ?? 'N/A');


        // Mark civil status checkbox - Use existing macros in the template
        if ($this->personnel->civil_status === 'single') {
            // For single, we'll set the cell value that the macro expects
            $worksheet->setCellValue('D17', '☑Single ☐Married ☐Widowed');
            $worksheet->setCellValue('D20', '☐Separated ☐Others:');
        } elseif ($this->personnel->civil_status === 'married') {
            // For married, we'll set the cell value that the macro expects
            $worksheet->setCellValue('D17', '☐Single ☑Married ☐Widowed');
            $worksheet->setCellValue('D20', '☐Separated ☐Others:');
        } elseif ($this->personnel->civil_status === 'widowed') {
            // For widowed, we'll set the cell value that the macro expects
            $worksheet->setCellValue('D17', '☐Single ☐Married ☑Widowed');
        } elseif ($this->personnel->civil_status === 'separated') {
            // For separated, we'll set the cell value that the macro expects
            $worksheet->setCellValue('D17', '☐Single ☐Married ☐Widowed');
            $worksheet->setCellValue('D20', '☑Separated ☐Others:');
        } elseif ($this->personnel->civil_status === 'others') {
            // For others, we'll set the cell value that the macro expects
            $worksheet->setCellValue('D17', '☐Single ☐Married ☐Widowed');
            $worksheet->setCellValue('D20', '☐Separated ☑Others:');
        } else {
            // For any other value, we'll leave the cell empty
            $worksheet->setCellValue('D17', '');
        }
        $worksheet->setCellValue('J13', $this->personnel->citizenship ?? 'N/A');

        $worksheet->setCellValue('D22', $this->personnel->height ?? 'N/A');
        $worksheet->setCellValue('D24', $this->personnel->weight ?? 'N/A');
        $worksheet->setCellValue('D25', $this->personnel->blood_type ?? 'N/A');
        $worksheet->setCellValue('D27', $this->personnel->gsis_num ?? 'N/A');
        $worksheet->setCellValue('D29', $this->personnel->pagibig_num ?? 'N/A');
        $worksheet->setCellValue('D31', $this->personnel->philhealth_num ?? 'N/A');
        $worksheet->setCellValue('D32', $this->personnel->sss_num ?? 'N/A');
        $worksheet->setCellValue('D33', $this->personnel->tin ?? 'N/A');
        $worksheet->setCellValue('D34', $this->personnel->personnel_id ?? 'N/A');
        $worksheet->setCellValue('I32', $this->personnel->tel_no ?? 'N/A');
        $worksheet->setCellValue('I33', $this->personnel->mobile_no ?? 'N/A');
        $worksheet->setCellValue('I34', $this->personnel->email ?? 'N/A');

        // Mark sex checkbox - Use existing macros in the template
        if ($this->personnel->sex === 'male') {
            // For male, we'll set the cell value that the macro expects
            $worksheet->setCellValue('D16', 'Male ☑');
            $worksheet->setCellValue('E16', 'Female ☐');
        } else {
            // For female, we'll set the cell value that the macro expects
            $worksheet->setCellValue('E16', 'Female ☑');
            $worksheet->setCellValue('D16', 'Make ☐');
        }
    }

    protected function populateAddress()
    {
        $worksheet = $this->worksheet;

        // Use dedicated relations defined on Personnel model
        $residentialAddress = $this->personnel->residentialAddress;
        $permanentAddress   = $this->personnel->permanentAddress;

        if ($residentialAddress) {
            // Residential Address
            $worksheet->setCellValue('I17', $residentialAddress->house_no ?? 'N/A');
            $worksheet->setCellValue('L17', $residentialAddress->street ?? 'N/A');
            $worksheet->setCellValue('I19', $residentialAddress->subdivision ?? 'N/A');
            $worksheet->setCellValue('L19', $residentialAddress->barangay ?? 'N/A');
            $worksheet->setCellValue('I22', $residentialAddress->city ?? 'N/A');
            $worksheet->setCellValue('L22', $residentialAddress->province ?? 'N/A');
            $worksheet->setCellValue('I24', $residentialAddress->zip_code ?? 'N/A');
        } else {
            $this->setDefaultAddressValues($worksheet, 'I17', 'L17', 'I19', 'L19', 'I22', 'L22', 'I24');
        }

        if ($permanentAddress) {
            // Permanent Address
            $worksheet->setCellValue('I25', $permanentAddress->house_no ?? 'N/A');
            $worksheet->setCellValue('L25', $permanentAddress->street ?? 'N/A');
            $worksheet->setCellValue('I27', $permanentAddress->subdivision ?? 'N/A');
            $worksheet->setCellValue('L27', $permanentAddress->barangay ?? 'N/A');
            $worksheet->setCellValue('I29', $permanentAddress->city ?? 'N/A');
            $worksheet->setCellValue('L29', $permanentAddress->province ?? 'N/A');
            $worksheet->setCellValue('I31', $permanentAddress->zip_code ?? 'N/A');
        } else {
            $this->setDefaultAddressValues($worksheet, 'I25', 'L25', 'I27', 'L27', 'I29', 'L29', 'I31');
        }
    }

    private function setDefaultAddressValues($worksheet, ...$cells)
    {
        foreach ($cells as $cell) {
            $worksheet->setCellValue($cell, 'N/A');
        }
    }

    protected function populateFamilyInfo()
    {
        $worksheet = $this->worksheet;

        // Check if family relationships exist
        if ($this->personnel->families && $this->personnel->families->count() > 0) {
            $spouse = $this->personnel->families->where('relationship', 'spouse')->first();
            $father = $this->personnel->families->where('relationship', 'father')->first();
            $mother = $this->personnel->families->where('relationship', 'mother')->first();

            if ($spouse) {
                // Spouse Information
                $worksheet->setCellValue('D36', $spouse->last_name ?? 'N/A');
                $worksheet->setCellValue('D37', $spouse->first_name ?? 'N/A');
                $worksheet->setCellValue('D38', $spouse->middle_name ?? 'N/A');
                $worksheet->setCellValue('H37', $spouse->name_ext ?? 'N/A');
                $worksheet->setCellValue('D39', $spouse->occupation ?? 'N/A');
                $worksheet->setCellValue('D40', $spouse->employer_business_name ?? 'N/A');
                $worksheet->setCellValue('D41', $spouse->telephone_number ?? 'N/A');
                $worksheet->setCellValue('D42', $spouse->business_address ?? 'N/A');
            } else {
                $this->setDefaultFamilyValues($worksheet, ['D36', 'D37', 'D38', 'H37', 'D39', 'D40', 'D41', 'D42']);
            }

            if ($father) {
                // Father's Information
                $worksheet->setCellValue('D43', $father->last_name ?? 'N/A');
                $worksheet->setCellValue('D44', $father->first_name ?? 'N/A');
                $worksheet->setCellValue('D45', $father->middle_name ?? 'N/A');
                $worksheet->setCellValue('H44', $father->name_ext ?? 'N/A');
            } else {
                $this->setDefaultFamilyValues($worksheet, ['D43', 'D44', 'D45', 'H44']);
            }

            if ($mother) {
                // Mother's Information
                $worksheet->setCellValue('D47', $mother->last_name ?? 'N/A');
                $worksheet->setCellValue('D48', $mother->first_name ?? 'N/A');
                $worksheet->setCellValue('D49', $mother->middle_name ?? 'N/A');
            } else {
                $this->setDefaultFamilyValues($worksheet, ['D47', 'D48', 'D49']);
            }
        } else {
            // Set default values if no family data
            $this->setDefaultFamilyValues($worksheet, ['D36', 'D37', 'D38', 'H37', 'D39', 'D40', 'D41', 'D42']);
            $this->setDefaultFamilyValues($worksheet, ['D43', 'D44', 'D45', 'H44']);
            $this->setDefaultFamilyValues($worksheet, ['D47', 'D48', 'D49']);
        }
    }

    private function setDefaultFamilyValues($worksheet, $cells)
    {
        foreach ($cells as $cell) {
            $worksheet->setCellValue($cell, 'N/A');
        }
    }

    protected function populateCurrentDate()
    {
        $worksheet = $this->worksheet;
        $worksheet->setCellValue('L60', Carbon::now()->format('m/d/Y'));
    }

    protected function populateEducation()
    {
        $worksheet = $this->worksheet;

        // Check if education_entries relationship exists
        if ($this->personnel->educationEntries && $this->personnel->educationEntries->count() > 0) {
            $elementary = $this->personnel->educationEntries->where('type', 'elementary')->first();
            $secondary = $this->personnel->educationEntries->where('type', 'secondary')->first();
            $vocational = $this->personnel->educationEntries->where('type', 'vocational/trade')->first();
            $college = $this->personnel->educationEntries->where('type', 'graduate')->first();
            $graduateStudies = $this->personnel->educationEntries->where('type', 'graduate studies')->first();

            // Elementary Education
            if ($elementary) {
                $worksheet->setCellValue('D54', $elementary->school_name ?? 'N/A');
                $worksheet->setCellValue('G54', $elementary->degree_course ?? 'N/A');
                $worksheet->setCellValue('J54', $elementary->period_from ?? 'N/A');
                $worksheet->setCellValue('K54', $elementary->period_to ?? 'N/A');
                $worksheet->setCellValue('L54', $elementary->highest_level_units ?? 'N/A');
                $worksheet->setCellValue('M54', $elementary->year_graduated ?? 'N/A');
                $worksheet->setCellValue('N54', $elementary->scholarship_honors ?? 'N/A');
            } else {
                $this->setDefaultEducationValues($worksheet, 'D54', 'G54', 'J54', 'K54', 'L54', 'M54', 'N54');
            }

            // Secondary Education
            if ($secondary) {
                $worksheet->setCellValue('D55', $secondary->school_name ?? 'N/A');
                $worksheet->setCellValue('G55', $secondary->degree_course ?? 'N/A');
                $worksheet->setCellValue('J55', $secondary->period_from ?? 'N/A');
                $worksheet->setCellValue('K55', $secondary->period_to ?? 'N/A');
                $worksheet->setCellValue('L55', $secondary->highest_level_units ?? 'N/A');
                $worksheet->setCellValue('M55', $secondary->year_graduated ?? 'N/A');
                $worksheet->setCellValue('N55', $secondary->scholarship_honors ?? 'N/A');
            } else {
                $this->setDefaultEducationValues($worksheet, 'D55', 'G55', 'J55', 'K55', 'L55', 'M55', 'N55');
            }

            // Vocational Education
            if ($vocational) {
                $worksheet->setCellValue('D56', $vocational->school_name ?? 'N/A');
                $worksheet->setCellValue('G56', $vocational->degree_course ?? 'N/A');
                $worksheet->setCellValue('J56', $vocational->period_from ?? 'N/A');
                $worksheet->setCellValue('K56', $vocational->period_to ?? 'N/A');
                $worksheet->setCellValue('L56', $vocational->highest_level_units ?? 'N/A');
                $worksheet->setCellValue('M56', $vocational->year_graduated ?? 'N/A');
                $worksheet->setCellValue('N56', $vocational->scholarship_honors ?? 'N/A');
            } else {
                $this->setDefaultEducationValues($worksheet, 'D56', 'G56', 'J56', 'K56', 'L56', 'M56', 'N56');
            }

            // College Education (was Graduate Education)
            if ($college) {
                $worksheet->setCellValue('D57', $college->school_name ?? 'N/A');
                $worksheet->setCellValue('G57', $college->degree_course ?? 'N/A');
                $worksheet->setCellValue('J57', $college->period_from ?? 'N/A');
                $worksheet->setCellValue('K57', $college->period_to ?? 'N/A');
                $worksheet->setCellValue('L57', $college->highest_level_units ?? 'N/A');
                $worksheet->setCellValue('M57', $college->year_graduated ?? 'N/A');
                $worksheet->setCellValue('N57', $college->scholarship_honors ?? 'N/A');
            } else {
                $this->setDefaultEducationValues($worksheet, 'D57', 'G57', 'J57', 'K57', 'L57', 'M57', 'N57');
            }

            // Graduate Studies Education
            if ($graduateStudies) {
                $worksheet->setCellValue('D58', $graduateStudies->school_name ?? 'N/A');
                $worksheet->setCellValue('G58', $graduateStudies->degree_course ?? 'N/A');
                $worksheet->setCellValue('J58', $graduateStudies->period_from ?? 'N/A');
                $worksheet->setCellValue('K58', $graduateStudies->period_to ?? 'N/A');
                $worksheet->setCellValue('L58', $graduateStudies->highest_level_units ?? 'N/A');
                $worksheet->setCellValue('M58', $graduateStudies->year_graduated ?? 'N/A');
                $worksheet->setCellValue('N58', $graduateStudies->scholarship_honors ?? 'N/A');
            } else {
                $this->setDefaultEducationValues($worksheet, 'D58', 'G58', 'J58', 'K58', 'L58', 'M58', 'N58');
            }
        } else {
            // Set default values if no education data
            $this->setDefaultEducationValues($worksheet, 'D54', 'G54', 'J54', 'K54', 'L54', 'M54', 'N54');
            $this->setDefaultEducationValues($worksheet, 'D55', 'G55', 'J55', 'K55', 'L55', 'M55', 'N55');
            $this->setDefaultEducationValues($worksheet, 'D56', 'G56', 'J56', 'K56', 'L56', 'M56', 'N56');
            $this->setDefaultEducationValues($worksheet, 'D57', 'G57', 'J57', 'K57', 'L57', 'M57', 'N57');
            $this->setDefaultEducationValues($worksheet, 'D58', 'G58', 'J58', 'K58', 'L58', 'M58', 'N58');
        }
    }

    private function setDefaultEducationValues($worksheet, ...$cells)
    {
        foreach ($cells as $cell) {
            $worksheet->setCellValue($cell, 'N/A');
        }
    }

    protected function populateChildren()
    {
        $worksheet = $this->worksheet;

        $startRow = 37; // Starting row for children info
        $endRow = 48; // Ending row for children info (12 children max)
        $currentRow = $startRow;

        // Clear children cells first
        for ($row = $startRow; $row <= $endRow; $row++) {
            $worksheet->setCellValue('I' . $row, '');
            $worksheet->setCellValue('M' . $row, '');
        }

        // Check if children relationship exists
        if ($this->personnel->children && $this->personnel->children->count() > 0) {
            Log::info('C1 Sheet - Found ' . $this->personnel->children->count() . ' children');

            foreach ($this->personnel->children as $index => $child) {
                if ($currentRow > $endRow) break; // Maximum 12 children

                // Format full name as: Last Name, First Name Middle Name
                $fullName = trim($child->last_name . ', ' . $child->first_name . ' ' . ($child->middle_name ?? ''));

                // Format date of birth if exists
                $dateOfBirth = $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('m/d/Y') : '';

                $worksheet->setCellValue('I' . $currentRow, $fullName);
                $worksheet->setCellValue('M' . $currentRow, $dateOfBirth);

                Log::info("C1 Sheet - Set child {$index} at row {$currentRow}: {$fullName}");
                $currentRow++;
            }
        } elseif ($this->personnel->families && $this->personnel->families->where('relationship', 'child')->count() > 0) {
            // Fallback to families relationship if children doesn't exist
            Log::info('C1 Sheet - Using families relationship for children');
            $children = $this->personnel->families->where('relationship', 'child');

            foreach ($children as $index => $child) {
                if ($currentRow > $endRow) break;

                // Format full name as: Last Name, First Name Middle Name
                $fullName = trim($child->last_name . ', ' . $child->first_name . ' ' . ($child->middle_name ?? ''));

                // Format date of birth if exists
                $dateOfBirth = $child->date_of_birth ? \Carbon\Carbon::parse($child->date_of_birth)->format('m/d/Y') : '';

                $worksheet->setCellValue('I' . $currentRow, $fullName);
                $worksheet->setCellValue('M' . $currentRow, $dateOfBirth);

                Log::info("C1 Sheet - Set child {$index} at row {$currentRow}: {$fullName}");
                $currentRow++;
            }
        } else {
            Log::info('C1 Sheet - No children found');
            $worksheet->setCellValue('I37', 'N/A');
            $worksheet->setCellValue('M37', 'N/A');
        }
    }
}
