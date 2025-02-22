<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;

class PersonnelDataC2Sheet
{
    protected $personnel;
    protected $worksheet;

    public function __construct($personnel, Spreadsheet $spreadsheet)
    {
        $this->personnel = $personnel;
        $this->worksheet = $spreadsheet->getSheet(1);
    }

    public function populateSheet()
    {
        $this->populateCivilServiceEligibilities();
        $this->populateWorkExperiences();
        $this->populateCurrentDate();
    }

    protected function populateCivilServiceEligibilities()
    {
        $worksheet = $this->worksheet;

        $startRow = 5;
        $endRow = 11;
        $currentRow = $startRow;

        if ($this->personnel->civilServiceEligibilities) {
            foreach ($this->personnel->civilServiceEligibilities as $civil_service_eligibility) {
                if ($currentRow > $endRow) {
                    // Copy the current sheet and use the new copy
                    $newSheet = $worksheet->copy();
                    $newSheet->setTitle('Additional CSE ' . ($this->worksheet->getParent()->getSheetCount() + 1));
                    $this->worksheet->getParent()->addSheet($newSheet);
                    $worksheet = $newSheet;
                    $currentRow = $startRow; // Reset the current row to the start row
                }

                // Populate the cell values
                $worksheet->setCellValue('A' . $currentRow, $civil_service_eligibility->title ?? 'N/A');
                $worksheet->setCellValue('F' . $currentRow, $civil_service_eligibility->rating ?? 'N/A');
                $worksheet->setCellValue('G' . $currentRow, $civil_service_eligibility->date_of_exam ? Carbon::parse($civil_service_eligibility->date_of_exam)->format('m/d/Y') : 'N/A');
                $worksheet->setCellValue('I' . $currentRow, $civil_service_eligibility->place_of_exam ?? 'N/A');
                $worksheet->setCellValue('L' . $currentRow, $civil_service_eligibility->license_num ?? 'N/A');
                $worksheet->setCellValue('M' . $currentRow, $civil_service_eligibility->license_date_of_validity ? Carbon::parse($civil_service_eligibility->license_date_of_validity)->format('m/d/Y') : 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('A' . $startRow, 'N/A');
            $worksheet->setCellValue('F' . $startRow, 'N/A');
            $worksheet->setCellValue('G' . $startRow, 'N/A');
            $worksheet->setCellValue('I' . $startRow, 'N/A');
            $worksheet->setCellValue('L' . $startRow, 'N/A');
            $worksheet->setCellValue('M' . $startRow, 'N/A');
        }
    }

    protected function populateWorkExperiences()
    {
        $worksheet = $this->worksheet;

        $startRow = 18;
        $endRow = 45;
        $currentRow = $startRow;

        if ($this->personnel->workExperiences) {
            foreach ($this->personnel->workExperiences as $work_experience) {
                if ($currentRow > $endRow) {
                    // Create a new sheet or use the next existing sheet
                    $currentSheetIndex = $this->worksheet->getParent()->getIndex($worksheet) + 1;
                    if ($currentSheetIndex >= $this->worksheet->getParent()->getSheetCount()) {
                        $worksheet = $this->worksheet->getParent()->createSheet();
                        $worksheet->setTitle('Additional WORK EXPERIENCE ' . ($currentSheetIndex + 1));
                    } else {
                        $worksheet = $this->worksheet->getParent()->getSheet($currentSheetIndex);
                    }
                    $currentRow = $startRow; // Reset the current row to the start row
                }

                // Populate the cell values
                $worksheet->setCellValue('A' . $currentRow, $work_experience->inclusive_from ? Carbon::parse($work_experience->inclusive_from)->format('m/d/Y') : 'N/A');
                $worksheet->setCellValue('C' . $currentRow, $work_experience->inclusive_to ? Carbon::parse($work_experience->inclusive_to)->format('m/d/Y') : 'N/A');
                $worksheet->setCellValue('D' . $currentRow, $work_experience->title ?? 'N/A');
                $worksheet->setCellValue('G' . $currentRow, $work_experience->company ?? 'N/A');
                $worksheet->setCellValue('J' . $currentRow, $work_experience->monthly_salary ?? 'N/A');
                $worksheet->setCellValue('K' . $currentRow, $work_experience->paygrade_step_increment ?? 'N/A');
                $worksheet->setCellValue('L' . $currentRow, $work_experience->appointment ?? 'N/A');
                $worksheet->setCellValue('M' . $currentRow, $work_experience->is_gov_service ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('A' . $currentRow, 'N/A');
            $worksheet->setCellValue('C' . $currentRow, 'N/A');
            $worksheet->setCellValue('D' . $currentRow, 'N/A');
            $worksheet->setCellValue('G' . $currentRow, 'N/A');
            $worksheet->setCellValue('J' . $currentRow, 'N/A');
            $worksheet->setCellValue('K' . $currentRow, 'N/A');
            $worksheet->setCellValue('L' . $currentRow, 'N/A');
            $worksheet->setCellValue('M' . $currentRow, 'N/A');
        }
    }

    protected function populateCurrentDate()
    {
        $worksheet = $this->worksheet;
        $worksheet->setCellValue('J47', Carbon::now()->format('m/d/Y'));
    }
}
