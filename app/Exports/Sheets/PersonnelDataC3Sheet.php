<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;

class PersonnelDataC3Sheet
{
    protected $personnel;
    protected $worksheet;

    public function __construct($personnel, Spreadsheet $spreadsheet)
    {
        $this->personnel = $personnel;
        $this->worksheet = $spreadsheet->getSheet(2);
    }

    public function populateSheet()
    {
        $this->populateVoluntaryWorks();
        $this->populateTrainingCertifications();
        $this->populateOtherInformation();
        $this->populateCurrentDate();
    }

    protected function populateCurrentDate()
    {
        $worksheet = $this->worksheet;
        $worksheet->setCellValue('I50', Carbon::now()->format('m/d/Y'));
    }

    protected function populateVoluntaryWorks()
    {
        $worksheet = $this->worksheet;

        $startRow = 6;
        $endRow = 12;
        $currentRow = $startRow;

        if ($this->personnel->voluntaryWorks) {
            foreach ($this->personnel->voluntaryWorks as $voluntary_work) {
                if ($currentRow > $endRow) {
                    // Create a new sheet or use the next existing sheet
                    $currentSheetIndex = $this->worksheet->getParent()->getIndex($worksheet) + 1;
                    if ($currentSheetIndex >= $this->worksheet->getParent()->getSheetCount()) {
                        $worksheet = $this->worksheet->getParent()->createSheet();
                        $worksheet->setTitle('Additional Voluntary Work ' . ($currentSheetIndex + 1));
                    } else {
                        $worksheet = $this->worksheet->getParent()->getSheet($currentSheetIndex);
                    }
                    $currentRow = $startRow; // Reset the current row to the start row
                }

                // Populate the cell values
                $worksheet->setCellValue('A' . $currentRow, $voluntary_work->organization ?? 'N/A');
                $worksheet->setCellValue('E' . $currentRow, $voluntary_work->inclusive_from ? Carbon::parse($voluntary_work->inclusive_from)->format('m/d/Y') : 'N/A');
                $worksheet->setCellValue('F' . $currentRow, $voluntary_work->inclusive_to ? Carbon::parse($voluntary_work->inclusive_to)->format('m/d/Y') : 'N/A');
                $worksheet->setCellValue('G' . $currentRow, $voluntary_work->hours ?? 'N/A');
                $worksheet->setCellValue('H' . $currentRow, $voluntary_work->position ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('A' . $startRow, 'N/A');
            $worksheet->setCellValue('E' . $startRow, 'N/A');
            $worksheet->setCellValue('F' . $startRow, 'N/A');
            $worksheet->setCellValue('G' . $startRow, 'N/A');
            $worksheet->setCellValue('H' . $startRow, 'N/A');
        }
    }

    protected function populateTrainingCertifications()
    {
        $worksheet = $this->worksheet;

        $startRow = 18;
        $endRow = 38;
        $currentRow = $startRow;

        if ($this->personnel->trainingCertifications) {
            foreach ($this->personnel->trainingCertifications as $training_certification) {
                if ($currentRow > $endRow) {
                    // Create a new sheet or use the next existing sheet
                    $currentSheetIndex = $this->worksheet->getParent()->getIndex($worksheet) + 1;
                    if ($currentSheetIndex >= $this->worksheet->getParent()->getSheetCount()) {
                        $worksheet = $this->worksheet->getParent()->createSheet();
                        $worksheet->setTitle('Additional Training Certification ' . ($currentSheetIndex + 1));
                    } else {
                        $worksheet = $this->worksheet->getParent()->getSheet($currentSheetIndex);
                    }
                    $currentRow = $startRow; // Reset the current row to the start row
                }

                // Populate the cell values
                $worksheet->setCellValue('A' . $currentRow, $training_certification->training_seminar_title ?? 'N/A');
                $worksheet->setCellValue('E' . $currentRow, $training_certification->inclusive_from ? Carbon::parse($training_certification->inclusive_from)->format('m/d/Y') : 'N/A');
                $worksheet->setCellValue('F' . $currentRow, $training_certification->inclusive_to ? Carbon::parse($training_certification->inclusive_to)->format('m/d/Y') : 'N/A');
                $worksheet->setCellValue('G' . $currentRow, $training_certification->hours ?? 'N/A');
                $worksheet->setCellValue('H' . $currentRow, $training_certification->type ?? 'N/A');
                $worksheet->setCellValue('I' . $currentRow, $training_certification->sponsored ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('A' . $startRow, 'N/A');
            $worksheet->setCellValue('E' . $startRow, 'N/A');
            $worksheet->setCellValue('F' . $startRow, 'N/A');
            $worksheet->setCellValue('G' . $startRow, 'N/A');
            $worksheet->setCellValue('H' . $startRow, 'N/A');
            $worksheet->setCellValue('I' . $startRow, 'N/A');
        }
    }

    protected function populateOtherInformation()
    {
        $worksheet = $this->worksheet;

        $startRow = 42;
        $endRow = 48;
        $currentRow = $startRow;

        // Skills Information
        if ($this->personnel->skillsInformation) {
            foreach ($this->personnel->skillsInformation as $skills_information) {
                if ($currentRow > $endRow) {
                    // Create a new sheet or use the next existing sheet
                    $currentSheetIndex = $this->worksheet->getParent()->getIndex($worksheet) + 1;
                    if ($currentSheetIndex >= $this->worksheet->getParent()->getSheetCount()) {
                        $worksheet = $this->worksheet->getParent()->createSheet();
                        $worksheet->setTitle('Additional Skills Information ' . ($currentSheetIndex + 1));
                    } else {
                        $worksheet = $this->worksheet->getParent()->getSheet($currentSheetIndex);
                    }
                    $currentRow = $startRow; // Reset the current row to the start row
                }

                // Populate the cell values
                $worksheet->setCellValue('A' . $currentRow, $skills_information->name ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('A' . $startRow, 'N/A');
        }

        // Nonacademic Distinction Information
        if ($this->personnel->nonacademicDistinctionInformation) {
            foreach ($this->personnel->nonacademicDistinctionInformation as $nonacademic_distinction_information) {
                if ($currentRow > $endRow) {
                    // Create a new sheet or use the next existing sheet
                    $currentSheetIndex = $this->worksheet->getParent()->getIndex($worksheet) + 1;
                    if ($currentSheetIndex >= $this->worksheet->getParent()->getSheetCount()) {
                        $worksheet = $this->worksheet->getParent()->createSheet();
                        $worksheet->setTitle('Additional Nonacademic Distinction Information ' . ($currentSheetIndex + 1));
                    } else {
                        $worksheet = $this->worksheet->getParent()->getSheet($currentSheetIndex);
                    }
                    $currentRow = $startRow; // Reset the current row to the start row
                }

                // Populate the cell values
                $worksheet->setCellValue('C' . $currentRow, $nonacademic_distinction_information->name ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('C' . $startRow, 'N/A');
        }

        // Association Information
        if ($this->personnel->associationInformation) {
            foreach ($this->personnel->associationInformation as $association_information) {
                if ($currentRow > $endRow) {
                    // Create a new sheet or use the next existing sheet
                    $currentSheetIndex = $this->worksheet->getParent()->getIndex($worksheet) + 1;
                    if ($currentSheetIndex >= $this->worksheet->getParent()->getSheetCount()) {
                        $worksheet = $this->worksheet->getParent()->createSheet();
                        $worksheet->setTitle('Additional Association Information ' . ($currentSheetIndex + 1));
                    } else {
                        $worksheet = $this->worksheet->getParent()->getSheet($currentSheetIndex);
                    }
                    $currentRow = $startRow; // Reset the current row to the start row
                }

                // Populate the cell values
                $worksheet->setCellValue('I' . $currentRow, $association_information->name ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('I' . $startRow, 'N/A');
        }
    }
}
