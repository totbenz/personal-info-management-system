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
            Log::info('PersonnelDataC4Sheet::populateSheet called', [
                'personnelId' => $this->personnel->id,
                'referenceCount' => $this->personnel->references ? $this->personnel->references->count() : 0,
                'hasPersonnelDetail' => $this->personnel->personnelDetail ? true : false
            ]);

            $this->populateQuestionnaire();
            Log::info('Questionnaire populated');

            $this->populateReferences();
            Log::info('References populated');

            $this->populateCurrentDate();
            Log::info('Current date populated');

            Log::info('PersonnelDataC4Sheet::populateSheet completed successfully');
        } catch (\Exception $e) {
            Log::error('Error in PersonnelDataC4Sheet::populateSheet', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function populateReferences()
    {
        $worksheet = $this->worksheet;

        // Get references data
        $references = $this->personnel->references ?? collect([]);

        // Define the exact cell positions based on template structure
        $positions = [
            'name' => 'A',
            'address' => 'F',
            'phone' => 'G'
        ];

        // Process up to 3 references
        for ($i = 0; $i < 3; $i++) {
            $row = 52 + $i;

            if ($i < $references->count()) {
                $reference = $references->get($i);

                // Set values directly without any complex operations
                $worksheet->setCellValue($positions['name'] . $row, $reference->full_name ?? '');
                $worksheet->setCellValue($positions['address'] . $row, $reference->address ?? '');
                $worksheet->setCellValue($positions['phone'] . $row, $reference->tel_no ?? '');
            } else {
                // Set N/A for unused rows instead of empty
                $worksheet->setCellValue($positions['name'] . $row, 'N/A');
                $worksheet->setCellValue($positions['address'] . $row, 'N/A');
                $worksheet->setCellValue($positions['phone'] . $row, 'N/A');
            }
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
            // Helper function to render checkboxes
            $checkbox = function($value) {
                return $value ? '☑ Yes   ☐ No' : '☐ Yes   ☑ No';
            };

            $worksheet->setCellValue('G6', $checkbox($this->personnel->personnelDetail->consanguinity_third_degree));
            $worksheet->setCellValue('G8', $checkbox($this->personnel->personnelDetail->consanguinity_fourth_degree));
            $worksheet->setCellValue('H11', $this->personnel->personnelDetail->consanguinity_third_degree_details ?? 'N/A');
            $worksheet->setCellValue('G13', $checkbox($this->personnel->personnelDetail->found_guilty_administrative_offense));
            $worksheet->setCellValue('H15', $this->personnel->personnelDetail->administrative_offense_details ?? 'N/A');
            $worksheet->setCellValue('G18', $checkbox($this->personnel->personnelDetail->criminally_charged));
            $worksheet->setCellValue('K20', $this->personnel->personnelDetail->criminally_charged_date_filed ? Carbon::parse($this->personnel->personnelDetail->criminally_charged_date_filed)->format('m/d/Y') : 'N/A');
            $worksheet->setCellValue('K21', $this->personnel->personnelDetail->criminally_charged_status ?? 'N/A');
            $worksheet->setCellValue('G23', $checkbox($this->personnel->personnelDetail->convicted_crime));
            $worksheet->setCellValue('H25', $this->personnel->personnelDetail->convicted_crime_details ?? 'N/A');
            $worksheet->setCellValue('G27', $checkbox($this->personnel->personnelDetail->separated_from_service));
            $worksheet->setCellValue('H29', $this->personnel->personnelDetail->separation_details ?? 'N/A');
            $worksheet->setCellValue('G31', $checkbox($this->personnel->personnelDetail->candidate_last_year));
            $worksheet->setCellValue('K32', $this->personnel->personnelDetail->candidate_details ?? 'N/A');
            $worksheet->setCellValue('G34', $checkbox($this->personnel->personnelDetail->resigned_to_campaign));
            $worksheet->setCellValue('K35', $this->personnel->personnelDetail->resigned_campaign_details ?? 'N/A');
            $worksheet->setCellValue('G37', $checkbox($this->personnel->personnelDetail->immigrant_status));
            $worksheet->setCellValue('H39', $this->personnel->personnelDetail->immigrant_country_details ?? 'N/A');
            $worksheet->setCellValue('G43', $checkbox($this->personnel->personnelDetail->member_indigenous_group));
            $worksheet->setCellValue('L44', $this->personnel->personnelDetail->indigenous_group_details ?? 'N/A');
            $worksheet->setCellValue('G45', $checkbox($this->personnel->personnelDetail->person_with_disability));
            $worksheet->setCellValue('L46', $this->personnel->personnelDetail->disability_id_no ?? 'N/A');
            $worksheet->setCellValue('G47', $checkbox($this->personnel->personnelDetail->solo_parent));
            $worksheet->setCellValue('L48', $this->personnel->personnelDetail->solo_parent_id_no ?? 'N/A');
        } else {
            // Set default values if no questionnaire data
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
