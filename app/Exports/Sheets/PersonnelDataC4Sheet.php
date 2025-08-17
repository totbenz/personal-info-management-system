<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PersonnelDataC4Sheet
{
    protected $personnel;
    protected $worksheet;

    public function __construct($personnel, Spreadsheet $spreadsheet)
    {
        $this->personnel = $personnel;
        $this->worksheet = $spreadsheet->getSheet(3);
    }

    public function populateSheet()
    {
        try {
            if ($this->personnel->references && $this->personnel->references->count() > 0) {
                $this->populateQuestionnaire();
                $this->populateReferences();
            } else {
                $this->populateQuestionnaire();
                $this->setDefaultReferenceValues($this->worksheet);
            }
            $this->populateCurrentDate();
        } catch (\Exception $e) {
            Log::error('Error populating C4 sheet: ' . $e->getMessage());
        }
    }

    protected function populateReferences()
    {
        $worksheet = $this->worksheet;

        $startRow = 52;
        $endRow = 54;
        $currentRow = $startRow;

        if ($this->personnel->references && $this->personnel->references->count() > 0) {
            foreach ($this->personnel->references as $reference) {
                $worksheet->setCellValue('A' . $currentRow, $reference->full_name ?? 'N/A');
                $worksheet->setCellValue('F' . $currentRow, $reference->address ?? 'N/A');
                $worksheet->setCellValue('G' . $currentRow, $reference->tel_no ?? 'N/A');
                $currentRow++;
            }
        } else {
            $this->setDefaultReferenceValues($worksheet);
        }
    }

    private function setDefaultReferenceValues($worksheet)
    {
        $worksheet->setCellValue('A52', 'N/A');
        $worksheet->setCellValue('F52', 'N/A');
        $worksheet->setCellValue('G52', 'N/A');
    }

    protected function populateQuestionnaire()
    {
        $worksheet = $this->worksheet;

        // Check if personnelDetail relationship exists
        if ($this->personnel->personnelDetail) {
            // Populate specific cells with data
            $worksheet->setCellValue('G6', $this->personnel->personnelDetail->consanguinity_third_degree ?? 'N/A');
            $worksheet->setCellValue('G8', $this->personnel->personnelDetail->consanguinity_fourth_degree ?? 'N/A');
            $worksheet->setCellValue('H11', $this->personnel->personnelDetail->consanguinity_third_degree_details ?? 'N/A');
            $worksheet->setCellValue('G13', $this->personnel->personnelDetail->found_guilty_administrative_offense ?? 'N/A');
            $worksheet->setCellValue('H15', $this->personnel->personnelDetail->administrative_offense_details ?? 'N/A');
            $worksheet->setCellValue('G18', $this->personnel->personnelDetail->criminally_charged ?? 'N/A');
            $worksheet->setCellValue('K20', $this->personnel->personnelDetail->criminally_charged_date_filed ? Carbon::parse($this->personnel->personnelDetail->criminally_charged_date_filed)->format('m/d/Y') : 'N/A');
            $worksheet->setCellValue('K21', $this->personnel->personnelDetail->criminally_charged_status ?? 'N/A');
            $worksheet->setCellValue('G23', $this->personnel->personnelDetail->convicted_crime ?? 'N/A');
            $worksheet->setCellValue('H25', $this->personnel->personnelDetail->convicted_crime_details ?? 'N/A');
            $worksheet->setCellValue('G27', $this->personnel->personnelDetail->separated_from_service ?? 'N/A');
            $worksheet->setCellValue('H29', $this->personnel->personnelDetail->separation_details ?? 'N/A');
            $worksheet->setCellValue('G31', $this->personnel->personnelDetail->candidate_last_year ?? 'N/A');
            $worksheet->setCellValue('K32', $this->personnel->personnelDetail->candidate_details ?? 'N/A');
            $worksheet->setCellValue('G34', $this->personnel->personnelDetail->resigned_to_campaign ?? 'N/A');
            $worksheet->setCellValue('K35', $this->personnel->personnelDetail->resigned_campaign_details ?? 'N/A');
            $worksheet->setCellValue('G37', $this->personnel->personnelDetail->immigrant_status ?? 'N/A');
            $worksheet->setCellValue('H39', $this->personnel->personnelDetail->immigrant_country_details ?? 'N/A');
            $worksheet->setCellValue('G43', $this->personnel->personnelDetail->member_indigenous_group ?? 'N/A');
            $worksheet->setCellValue('L44', $this->personnel->personnelDetail->indigenous_group_details ?? 'N/A');
            $worksheet->setCellValue('G45', $this->personnel->personnelDetail->person_with_disability ?? 'N/A');
            $worksheet->setCellValue('L46', $this->personnel->personnelDetail->disability_id_no ?? 'N/A');
            $worksheet->setCellValue('G47', $this->personnel->personnelDetail->solo_parent ?? 'N/A');
        } else {
            $this->setDefaultQuestionnaireValues($worksheet);
        }
    }

    private function setDefaultQuestionnaireValues($worksheet)
    {
        $worksheet->setCellValue('G6', 'N/A');
        $worksheet->setCellValue('G8', 'N/A');
        $worksheet->setCellValue('H11', 'N/A');
        $worksheet->setCellValue('G13', 'N/A');
        $worksheet->setCellValue('H15', 'N/A');
        $worksheet->setCellValue('G18', 'N/A');
        $worksheet->setCellValue('K20', 'N/A');
        $worksheet->setCellValue('K21', 'N/A');
        $worksheet->setCellValue('G23', 'N/A');
        $worksheet->setCellValue('H25', 'N/A');
        $worksheet->setCellValue('G27', 'N/A');
        $worksheet->setCellValue('H29', 'N/A');
        $worksheet->setCellValue('G31', 'N/A');
        $worksheet->setCellValue('K32', 'N/A');
        $worksheet->setCellValue('G34', 'N/A');
        $worksheet->setCellValue('K35', 'N/A');
        $worksheet->setCellValue('G37', 'N/A');
        $worksheet->setCellValue('H39', 'N/A');
        $worksheet->setCellValue('G43', 'N/A');
        $worksheet->setCellValue('L44', 'N/A');
        $worksheet->setCellValue('G45', 'N/A');
        $worksheet->setCellValue('L46', 'N/A');
        $worksheet->setCellValue('G47', 'N/A');
    }

    protected function populateCurrentDate()
    {
        $worksheet = $this->worksheet;
        $worksheet->setCellValue('F64', Carbon::now()->format('m/d/Y'));
    }
}
