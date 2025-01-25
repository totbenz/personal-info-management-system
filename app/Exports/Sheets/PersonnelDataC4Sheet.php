<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;

class PersonnelDataC4Sheet
{
    protected $personnel;
    protected $worksheet;

    public function __construct($personnel, Spreadsheet $spreadsheet)
    {
        $this->personnel = $personnel;
        // $this->personnel = $this->personnel[0];
        $this->worksheet = $spreadsheet->getSheet(3);
    }

    public function populateSheet()
    {
        if ($this->personnel->references) {
            $this->populateQuestionnaire();
            $this->populateReferences();
        }
    }

    protected function populateReferences()
    {
        $worksheet = $this->worksheet;

        $startRow = 52;
        $endRow = 54;
        $currentRow = $startRow;

        if ($this->personnel->references) {
            foreach ($this->personnel->references as $reference) {
                $worksheet->setCellValue('A' . $currentRow, $reference->full_name);
                $worksheet->setCellValue('F' . $currentRow, $reference->address);
                $worksheet->setCellValue('G' . $currentRow, $reference->tel_no);
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('A52', 'N/A');
            $worksheet->setCellValue('F52', 'N/A');
            $worksheet->setCellValue('G52', 'N/A');
        }
    }

    protected function populateQuestionnaire()
    {
        $worksheet = $this->worksheet;

        if ($this->personnel->personnelDetail) {
            // Populate specific cells with data
            $worksheet->setCellValue('G6', $this->personnel->personnelDetail->consanguinity_third_degree);
            $worksheet->setCellValue('G8', $this->personnel->personnelDetail->consanguinity_fourth_degree);
            $worksheet->setCellValue('H11', $this->personnel->personnelDetail->consanguinity_third_degree_details);
            $worksheet->setCellValue('G13', $this->personnel->personnelDetail->found_guilty_administrative_offense);
            $worksheet->setCellValue('H15', $this->personnel->personnelDetail->administrative_offense_details);
            $worksheet->setCellValue('G18', $this->personnel->personnelDetail->criminally_charged);
            // $worksheet->setCellValue('D12', $this->personnel->personnelDetail->criminally_charged_details);
            $worksheet->setCellValue('K20', $this->personnel->personnelDetail->criminally_charged_date_filed);
            $worksheet->setCellValue('K21', $this->personnel->personnelDetail->criminally_charged_status);
            $worksheet->setCellValue('G23', $this->personnel->personnelDetail->convicted_crime);
            $worksheet->setCellValue('H25', $this->personnel->personnelDetail->convicted_crime_details);
            $worksheet->setCellValue('G27', $this->personnel->personnelDetail->separated_from_service);
            $worksheet->setCellValue('H29', $this->personnel->personnelDetail->separation_details);
            $worksheet->setCellValue('G31', $this->personnel->personnelDetail->candidate_last_year);
            $worksheet->setCellValue('K32', $this->personnel->personnelDetail->candidate_details);
            $worksheet->setCellValue('G34', $this->personnel->personnelDetail->resigned_to_campaign);
            $worksheet->setCellValue('K35', $this->personnel->personnelDetail->resigned_campaign_details);
            $worksheet->setCellValue('G37', $this->personnel->personnelDetail->immigrant_status);
            $worksheet->setCellValue('H39', $this->personnel->personnelDetail->immigrant_country_details);
            $worksheet->setCellValue('G43', $this->personnel->personnelDetail->member_indigenous_group);
            $worksheet->setCellValue('L44', $this->personnel->personnelDetail->indigenous_group_details);
            $worksheet->setCellValue('G45', $this->personnel->personnelDetail->person_with_disability);
            $worksheet->setCellValue('L46', $this->personnel->personnelDetail->disability_id_no);
            $worksheet->setCellValue('G47', $this->personnel->personnelDetail->solo_parent);
        };

    }
}
