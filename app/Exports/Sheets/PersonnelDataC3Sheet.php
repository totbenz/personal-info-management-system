<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        try {
            Log::info('PersonnelDataC3Sheet::populateSheet called', [
                'personnelId' => $this->personnel->id,
                'voluntaryWorkCount' => $this->personnel->voluntaryWorks ? $this->personnel->voluntaryWorks->count() : 0,
                'trainingCount' => $this->personnel->trainingCertifications ? $this->personnel->trainingCertifications->count() : 0,
                'otherInfoCount' => $this->personnel->otherInformations ? $this->personnel->otherInformations->count() : 0
            ]);

            $this->populateVoluntaryWorks();
            Log::info('Voluntary works populated');

            $this->populateTrainingCertifications();
            Log::info('Training certifications populated');

            $this->populateOtherInformation();
            Log::info('Other information populated');

            $this->populateCurrentDate();
            Log::info('Current date populated');

            Log::info('PersonnelDataC3Sheet::populateSheet completed successfully');
        } catch (\Exception $e) {
            Log::error('Error in PersonnelDataC3Sheet::populateSheet', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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

        // Check if voluntary works relationship exists
        if ($this->personnel->voluntaryWorks && $this->personnel->voluntaryWorks->count() > 0) {
            foreach ($this->personnel->voluntaryWorks as $voluntary_work) {
                if ($currentRow > $endRow) {
                    // Stop if we exceed the available rows
                    break;
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
            $this->setDefaultVoluntaryWorkValues($worksheet, $startRow);
        }
    }

    private function setDefaultVoluntaryWorkValues($worksheet, $row)
    {
        $worksheet->setCellValue('A' . $row, 'N/A');
        $worksheet->setCellValue('E' . $row, 'N/A');
        $worksheet->setCellValue('F' . $row, 'N/A');
        $worksheet->setCellValue('G' . $row, 'N/A');
        $worksheet->setCellValue('H' . $row, 'N/A');
    }

    protected function populateTrainingCertifications()
    {
        $worksheet = $this->worksheet;

        $startRow = 18;
        $endRow = 38;
        $currentRow = $startRow;

        // Check if training certifications relationship exists
        if ($this->personnel->trainingCertifications && $this->personnel->trainingCertifications->count() > 0) {
            foreach ($this->personnel->trainingCertifications as $training_certification) {
                if ($currentRow > $endRow) {
                    // Stop if we exceed the available rows
                    break;
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
            $this->setDefaultTrainingCertificationValues($worksheet, $startRow);
        }
    }

    private function setDefaultTrainingCertificationValues($worksheet, $row)
    {
        $worksheet->setCellValue('A' . $row, 'N/A');
        $worksheet->setCellValue('E' . $row, 'N/A');
        $worksheet->setCellValue('F' . $row, 'N/A');
        $worksheet->setCellValue('G' . $row, 'N/A');
        $worksheet->setCellValue('H' . $row, 'N/A');
        $worksheet->setCellValue('I' . $row, 'N/A');
    }

    protected function populateOtherInformation()
    {
        $worksheet = $this->worksheet;

        $startRow = 42;
        $endRow = 48;
        $currentRow = $startRow;

        // Skills Information - Check if otherInformations relationship exists
        if ($this->personnel->otherInformations && $this->personnel->otherInformations->where('type', 'special_skill')->count() > 0) {
            $skillsInformation = $this->personnel->otherInformations->where('type', 'special_skill');
            foreach ($skillsInformation as $skills_information) {
                if ($currentRow > $endRow) {
                    // Stop if we exceed the available rows
                    break;
                }

                // Populate the cell values
                $worksheet->setCellValue('A' . $currentRow, $skills_information->name ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('A' . $startRow, 'N/A');
        }

        // Reset row for nonacademic distinctions
        $currentRow = $startRow;

        // Nonacademic Distinction Information
        if ($this->personnel->otherInformations && $this->personnel->otherInformations->where('type', 'nonacademic_distinction')->count() > 0) {
            $nonacademicDistinctionInformation = $this->personnel->otherInformations->where('type', 'nonacademic_distinction');
            foreach ($nonacademicDistinctionInformation as $nonacademic_distinction_information) {
                if ($currentRow > $endRow) {
                    // Stop if we exceed the available rows
                    break;
                }

                // Populate the cell values
                $worksheet->setCellValue('C' . $currentRow, $nonacademic_distinction_information->name ?? 'N/A');
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('C' . $startRow, 'N/A');
        }

        // Reset row for associations
        $currentRow = $startRow;

        // Association Information
        if ($this->personnel->otherInformations && $this->personnel->otherInformations->where('type', 'association')->count() > 0) {
            $associationInformation = $this->personnel->otherInformations->where('type', 'association');
            foreach ($associationInformation as $association_information) {
                if ($currentRow > $endRow) {
                    // Stop if we exceed the available rows
                    break;
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
