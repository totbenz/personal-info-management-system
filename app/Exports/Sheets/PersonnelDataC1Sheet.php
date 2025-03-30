<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
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
        $this->populatePersonalInfo();
        $this->populateAddress();
        $this->populateFamilyInfo();
        $this->populateChildren();
        $this->populateEducation();
        $this->populateCurrentDate();
    }

    protected function populatePersonalInfo()
    {
        $worksheet = $this->worksheet;

        // Populate specific cells with data
        $worksheet->setCellValue('D10', $this->personnel->last_name ?? 'N/A');
        $worksheet->setCellValue('D11', $this->personnel->first_name ?? 'N/A');
        $worksheet->setCellValue('D12', $this->personnel->middle_name ?? 'N/A');
        $worksheet->setCellValue('N11', $this->personnel->name_ext ?? 'N/A');
        $worksheet->setCellValue('D13', $this->personnel->date_of_birth ?? 'N/A');
        $worksheet->setCellValue('D15', $this->personnel->place_of_birth ?? 'N/A');

        // Handle sex checkboxes
        if ($this->personnel->sex === 'male') {
            $worksheet->setCellValue('D16', '✅'); // Assuming 'D16' is linked to the 'male' checkbox
            $worksheet->setCellValue('E16', '☐'); // Assuming 'E16' is linked to the 'female' checkbox
        } elseif ($this->personnel->sex === 'female') {
            $worksheet->setCellValue('D16', '☐'); // Assuming 'D16' is linked to the 'male' checkbox
            $worksheet->setCellValue('E16', '✅'); // Assuming 'E16' is linked to the 'female' checkbox
        } else {
            $worksheet->setCellValue('D16', '☐'); // Assuming 'D16' is linked to the 'male' checkbox
            $worksheet->setCellValue('E16', '☐'); // Assuming 'E16' is linked to the 'female' checkbox
        }

        $worksheet->setCellValue('D17', $this->personnel->civil_status ?? 'N/A');
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
    }

    protected function populateAddress()
    {
        $worksheet = $this->worksheet;

        if ($this->personnel->residentialAddress) {
            // Residential Address
            $worksheet->setCellValue('I17', $this->personnel->residentialAddress->house_no ?? 'N/A');
            $worksheet->setCellValue('L17', $this->personnel->residentialAddress->street ?? 'N/A');
            $worksheet->setCellValue('I19', $this->personnel->residentialAddress->subdivision ?? 'N/A');
            $worksheet->setCellValue('L19', $this->personnel->residentialAddress->barangay ?? 'N/A');
            $worksheet->setCellValue('I22', $this->personnel->residentialAddress->city ?? 'N/A');
            $worksheet->setCellValue('L22', $this->personnel->residentialAddress->province ?? 'N/A');
            $worksheet->setCellValue('I24', $this->personnel->residentialAddress->zip_code ?? 'N/A');
        } else {
            $worksheet->setCellValue('I17', 'N/A');
            $worksheet->setCellValue('L17', 'N/A');
            $worksheet->setCellValue('I19', 'N/A');
            $worksheet->setCellValue('L19', 'N/A');
            $worksheet->setCellValue('I22', 'N/A');
            $worksheet->setCellValue('L22', 'N/A');
            $worksheet->setCellValue('I24', 'N/A');
        }

        if ($this->personnel->permanentAddress) {
            // Permanent Address
            $worksheet->setCellValue('I25', $this->personnel->permanentAddress->house_no ?? 'N/A');
            $worksheet->setCellValue('L25', $this->personnel->permanentAddress->street ?? 'N/A');
            $worksheet->setCellValue('I27', $this->personnel->permanentAddress->subdivision ?? 'N/A');
            $worksheet->setCellValue('L27', $this->personnel->permanentAddress->barangay ?? 'N/A');
            $worksheet->setCellValue('I29', $this->personnel->permanentAddress->city ?? 'N/A');
            $worksheet->setCellValue('L29', $this->personnel->permanentAddress->province ?? 'N/A');
            $worksheet->setCellValue('I31', $this->personnel->permanentAddress->zip_code ?? 'N/A');
        } else {
            $worksheet->setCellValue('I25', 'N/A');
            $worksheet->setCellValue('L25', 'N/A');
            $worksheet->setCellValue('I27', 'N/A');
            $worksheet->setCellValue('L27', 'N/A');
            $worksheet->setCellValue('I29', 'N/A');
            $worksheet->setCellValue('L29', 'N/A');
            $worksheet->setCellValue('I31', 'N/A');
        }
    }

    protected function populateFamilyInfo()
    {
        $worksheet = $this->worksheet;

        if ($this->personnel->spouse) {
            // Spouse Information
            $worksheet->setCellValue('D36', $this->personnel->spouse->last_name ?? 'N/A');
            $worksheet->setCellValue('D37', $this->personnel->spouse->first_name ?? 'N/A');
            $worksheet->setCellValue('D38', $this->personnel->spouse->middle_name ?? 'N/A');
            $worksheet->setCellValue('H37', $this->personnel->spouse->name_ext ?? 'N/A');
            $worksheet->setCellValue('D39', $this->personnel->spouse->occupation ?? 'N/A');
            $worksheet->setCellValue('D40', $this->personnel->spouse->employer_business_name ?? 'N/A');
            $worksheet->setCellValue('D41', $this->personnel->spouse->telephone_number ?? 'N/A');
            $worksheet->setCellValue('D42', $this->personnel->spouse->business_address ?? 'N/A');
        } else {
            $worksheet->setCellValue('D36', 'N/A');
            $worksheet->setCellValue('D37', 'N/A');
            $worksheet->setCellValue('D38', 'N/A');
            $worksheet->setCellValue('H37', 'N/A');
            $worksheet->setCellValue('D39', 'N/A');
            $worksheet->setCellValue('D40', 'N/A');
            $worksheet->setCellValue('D41', 'N/A');
            $worksheet->setCellValue('D42', 'N/A');
        }

        if ($this->personnel->father) {
            // Father's Information
            $worksheet->setCellValue('D43', $this->personnel->father->last_name ?? 'N/A');
            $worksheet->setCellValue('D44', $this->personnel->father->first_name ?? 'N/A');
            $worksheet->setCellValue('D45', $this->personnel->father->middle_name ?? 'N/A');
            $worksheet->setCellValue('H44', $this->personnel->father->name_ext ?? 'N/A');
        } else {
            $worksheet->setCellValue('D43', 'N/A');
            $worksheet->setCellValue('D44', 'N/A');
            $worksheet->setCellValue('D45', 'N/A');
            $worksheet->setCellValue('H44', 'N/A');
        }

        if ($this->personnel->mother) {
            // Mother's Information
            $worksheet->setCellValue('D47', $this->personnel->mother->last_name ?? 'N/A');
            $worksheet->setCellValue('D48', $this->personnel->mother->first_name ?? 'N/A');
            $worksheet->setCellValue('D49', $this->personnel->mother->middle_name ?? 'N/A');
        } else {
            $worksheet->setCellValue('D47', 'N/A');
            $worksheet->setCellValue('D48', 'N/A');
            $worksheet->setCellValue('D49', 'N/A');
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

        // Elementary Education
        $worksheet->setCellValue('D54', $this->personnel->elementaryEducation->school_name ?? 'N/A');
        $worksheet->setCellValue('G54', $this->personnel->elementaryEducation->degree_course ?? 'N/A');
        $worksheet->setCellValue('J54', $this->personnel->elementaryEducation->period_from ?? 'N/A');
        $worksheet->setCellValue('K54', $this->personnel->elementaryEducation->period_to ?? 'N/A');
        $worksheet->setCellValue('L54', $this->personnel->elementaryEducation->highest_level_units ?? 'N/A');
        $worksheet->setCellValue('M54', $this->personnel->elementaryEducation->year_graduated ?? 'N/A');
        $worksheet->setCellValue('N54', $this->personnel->elementaryEducation->scholarship_honors ?? 'N/A');

        // Secondary Education
        $worksheet->setCellValue('D55', $this->personnel->secondaryEducation->school_name ?? 'N/A');
        $worksheet->setCellValue('G55', $this->personnel->secondaryEducation->degree_course ?? 'N/A');
        $worksheet->setCellValue('J55', $this->personnel->secondaryEducation->period_from ?? 'N/A');
        $worksheet->setCellValue('K55', $this->personnel->secondaryEducation->period_to ?? 'N/A');
        $worksheet->setCellValue('L55', $this->personnel->secondaryEducation->highest_level_units ?? 'N/A');
        $worksheet->setCellValue('M55', $this->personnel->secondaryEducation->year_graduated ?? 'N/A');
        $worksheet->setCellValue('N55', $this->personnel->secondaryEducation->scholarship_honors ?? 'N/A');

        // Vocational Education
        $worksheet->setCellValue('D56', $this->personnel->vocationalEducation->school_name ?? 'N/A');
        $worksheet->setCellValue('G56', $this->personnel->vocationalEducation->degree_course ?? 'N/A');
        $worksheet->setCellValue('J56', $this->personnel->vocationalEducation->period_from ?? 'N/A');
        $worksheet->setCellValue('K56', $this->personnel->vocationalEducation->period_to ?? 'N/A');
        $worksheet->setCellValue('L56', $this->personnel->vocationalEducation->highest_level_units ?? 'N/A');
        $worksheet->setCellValue('M56', $this->personnel->vocationalEducation->year_graduated ?? 'N/A');
        $worksheet->setCellValue('N56', $this->personnel->vocationalEducation->scholarship_honors ?? 'N/A');

        // Graduate Education
        $worksheet->setCellValue('D57', $this->personnel->graduateEducation->school_name ?? 'N/A');
        $worksheet->setCellValue('G57', $this->personnel->graduateEducation->degree_course ?? 'N/A');
        $worksheet->setCellValue('J57', $this->personnel->graduateEducation->period_from ?? 'N/A');
        $worksheet->setCellValue('K57', $this->personnel->graduateEducation->period_to ?? 'N/A');
        $worksheet->setCellValue('L57', $this->personnel->graduateEducation->highest_level_units ?? 'N/A');
        $worksheet->setCellValue('M57', $this->personnel->graduateEducation->year_graduated ?? 'N/A');
        $worksheet->setCellValue('N57', $this->personnel->graduateEducation->scholarship_honors ?? 'N/A');

        // Graduate Studies Education
        $worksheet->setCellValue('D58', $this->personnel->graduateStudiesEducation->school_name ?? 'N/A');
        $worksheet->setCellValue('G58', $this->personnel->graduateStudiesEducation->degree_course ?? 'N/A');
        $worksheet->setCellValue('J58', $this->personnel->graduateStudiesEducation->period_from ?? 'N/A');
        $worksheet->setCellValue('K58', $this->personnel->graduateStudiesEducation->period_to ?? 'N/A');
        $worksheet->setCellValue('L58', $this->personnel->graduateStudiesEducation->highest_level_units ?? 'N/A');
        $worksheet->setCellValue('M58', $this->personnel->graduateStudiesEducation->year_graduated ?? 'N/A');
        $worksheet->setCellValue('N58', $this->personnel->graduateStudiesEducation->scholarship_honors ?? 'N/A');
    }

    protected function populateChildren()
    {
        $worksheet = $this->worksheet;

        $startRow = 37; // Starting row for children info
        $endRow = 49; // Ending row for children info
        $currentRow = $startRow;

        if ($this->personnel->children) {
            foreach ($this->personnel->children as $child) {
                if ($currentRow > $endRow) {
                    // Create a new sheet or use the next existing sheet
                    $currentSheetIndex = $this->worksheet->getParent()->getIndex($worksheet) + 1;
                    if ($currentSheetIndex >= $this->worksheet->getParent()->getSheetCount()) {
                        $worksheet = $this->worksheet->getParent()->createSheet();
                        $worksheet->setTitle('Additional Children ' . ($currentSheetIndex + 1));
                    } else {
                        $worksheet = $this->worksheet->getParent()->getSheet($currentSheetIndex);
                    }
                    $currentRow = $startRow; // Reset the current row to the start row
                }

                // Populate the cell values
                $worksheet->setCellValue('I' . $currentRow, $child->fullName() ?? 'N/A');
                $worksheet->setCellValue('M' . $currentRow, $child->date_of_birth ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('I37', 'N/A');
            $worksheet->setCellValue('M37', 'N/A');
        }
    }
}
