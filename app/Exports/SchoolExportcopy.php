<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Personnel;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchoolExportcopy
{
    protected $personnel;
    protected $school;
    protected $filename;
    protected $spreadsheet;
    protected $excelOutputPath;
    protected $worksheet;

    public function __construct($id)
    {
        $this->personnel = Personnel::findOrFail($id);
        $this->school = $this->personnel->school;

        $this->filename = public_path('report/school_form_7.xlsx');
        $this->excelOutputPath = public_path('report/sf7.xlsx');
        $this->spreadsheet = IOFactory::load($this->filename);

        // Assuming the first worksheet is to be used
        $this->worksheet = $this->spreadsheet->getActiveSheet();

        // Save the Excel file after data population
        $this->populateHeader();
        $this->populateSchoolHead();


    }

    public function getOutputPath()
    {
        return $this->excelOutputPath;
    }

    public function populateHeader()
    {
        $worksheet = $this->worksheet;

        // Populate school head data
        $worksheet->setCellValue('D5', $this->school->school_id);
        $worksheet->setCellValue('H5', 'Region 8');
        $worksheet->setCellValue('D5', $this->school->division);
        $worksheet->setCellValue('D7', $this->school->school_name);
        $worksheet->setCellValue('D5', $this->school->district->name);
        $worksheet->setCellValue('D5', $this->school->getSchoolYear());
    }


    public function populateSchoolHead()
    {
        $worksheet = $this->worksheet;
        $schoolHead = $this->school->schoolHead;

        // Populate school head data
        $worksheet->setCellValue('A20', $schoolHead->personnel_id);
        $worksheet->setCellValue('B20', $schoolHead->fullName());
        $worksheet->setCellValue('C20', $schoolHead->sex);
        $worksheet->setCellValue('D20', $schoolHead->fund_source);
        $worksheet->setCellValue('F20', $this->abbreviateTitle($this->personnel->position->title));
        $worksheet->setCellValue('G20', $this->personnel->appointment . '/' . $this->personnel->job_status);
        $worksheet->setCellValue('H20', $this->personnel->graduateEducation->degree);
        // $worksheet->setCellValue('H20', $this->personnel->graduateEducation ? $this->personnel->graduateEducation->abbreviateDegree($this->personnel->graduateEducation->degree) : '');
        // $worksheet->setCellValue('I20', $this->personnel->graduateStudiesEducation ? $this->personnel->graduateStudiesEducation->major : '');
        $worksheet->setCellValue('K20', 'Gen. Elem.');
        $this->populateAssignmentDetails($schoolHead);
    }

    protected function populateAssignmentDetails($personnel)
    {
        $worksheet = $this->worksheet;

        $startRow = 37; // Starting row for children info
        $endRow = 49; // Ending row for children info
        $currentRow = $startRow;
        $currentSchoolYear = $this->school->getSchoolYear();
        $filteredAssignments = $personnel->assignmentDetails()->where('school_year', $currentSchoolYear)->get();


        foreach ($filteredAssignments as $assignment_detail) {
            // if ($currentRow > $endRow) {
            //     // Create a new sheet or use the next existing sheet
            //     $currentSheetIndex = $this->worksheet->getParent()->getIndex($worksheet) + 1;
            //     if ($currentSheetIndex >= $this->worksheet->getParent()->getSheetCount()) {
            //         $worksheet = $this->worksheet->getParent()->createSheet();
            //         $worksheet->setTitle('Additional Children ' . ($currentSheetIndex + 1));
            //     } else {
            //         $worksheet = $this->worksheet->getParent()->getSheet($currentSheetIndex);
            //     }
            //     $currentRow = $startRow; // Reset the current row to the start row
            // }

            // Populate the cell values
            $worksheet->setCellValue('M' . $currentRow, $assignment_detail->assignment);
            $worksheet->setCellValue('N' . $currentRow, $assignment_detail->dtr_day);
            $worksheet->setCellValue('O' . $currentRow, $assignment_detail->dtr_from);
            $worksheet->setCellValue('P' . $currentRow, $assignment_detail->dtr_to);
            $worksheet->setCellValue('Q' . $currentRow, $assignment_detail->teaching_minutes_per_week);
            $currentRow++;
        }
    }

    // public function populateTeachers()
    // {
    //     $worksheet = $this->worksheet;

    //     // Get teachers sorted by their ranks
    //     $teachers = $this->school->teachers()
    //         ->with('position')
    //         ->get()
    //         ->sortBy(function ($teacher) {
    //             return $this->getPositionRank($teacher->position->title);
    //         });

    //     // Populate teachers' data starting from a specific row, e.g., row 21
    //     $row = 21;
    //     foreach ($teachers as $teacher) {
    //         $worksheet->setCellValue("A{$row}", $teacher->last_name);
    //         $worksheet->setCellValue("B{$row}", $teacher->first_name);
    //         $worksheet->setCellValue("C{$row}", $this->abbreviateTitle($teacher->position->title));
    //         // Populate other relevant cells...
    //         $row++;
    //     }

    //     // Save the updated file
    //     $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
    //     $writer->save($this->excelOutputPath);
    // }

    // public function populateOtherPersonnel()
    // {
    //     $worksheet = $this->worksheet;

    //     // Get other personnel sorted by their ranks
    //     $otherPersonnel = $this->school->personnels()
    //         ->with('position')
    //         ->whereDoesntHave('position', function ($query) {
    //             $query->whereIn('title', [
    //                 'School Principal I',
    //                 'School Principal II',
    //                 'School Principal III',
    //                 'School Principal IV',
    //                 'Teacher I',
    //                 'Teacher II',
    //                 'Teacher III',
    //                 'Master Teacher I',
    //                 'Master Teacher II',
    //                 'Master Teacher III',
    //                 'Master Teacher IV',
    //                 'Head Teacher I',
    //                 'Head Teacher II',
    //                 'Head Teacher III',
    //                 'Head Teacher IV',
    //                 'Head Teacher V',
    //                 'Head Teacher VI',
    //                 'Special Education Teacher I',
    //                 'Special Education Teacher II',
    //                 'Special Education Teacher III',
    //                 'Special Education Teacher IV',
    //                 'Special Education Teacher V'
    //             ]);
    //         })
    //         ->get()
    //         ->sortBy(function ($personnel) {
    //             return $this->getPositionRank($personnel->position->title);
    //         });

    //     // Populate other personnel data starting after the last teacher row
    //     $row = $this->getLastRow() + 1;
    //     foreach ($otherPersonnel as $personnel) {
    //         $worksheet->setCellValue("A{$row}", $personnel->last_name);
    //         $worksheet->setCellValue("B{$row}", $personnel->first_name);
    //         $worksheet->setCellValue("C{$row}", $this->abbreviateTitle($personnel->position->title));
    //         // Populate other relevant cells...
    //         $row++;
    //     }

    //     // Save the updated file
    //     $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
    //     $writer->save($this->excelOutputPath);
    // }

    protected function getPositionRank($title)
    {
        $ranks = [
            'Teacher I' => 1,
            'Teacher II' => 2,
            'Teacher III' => 3,
            'Master Teacher I' => 4,
            'Master Teacher II' => 5,
            'Master Teacher III' => 6,
            'Master Teacher IV' => 7,
            'Head Teacher I' => 8,
            'Head Teacher II' => 9,
            'Head Teacher III' => 10,
            'Head Teacher IV' => 11,
            'Head Teacher V' => 12,
            'Head Teacher VI' => 13,
            'Special Education Teacher I' => 14,
            'Special Education Teacher II' => 15,
            'Special Education Teacher III' => 16,
            'Special Education Teacher IV' => 17,
            'Special Education Teacher V' => 18,
            'School Principal I' => 19,
            'School Principal II' => 20,
            'School Principal III' => 21,
            'School Principal IV' => 22,
        ];

        return $ranks[$title] ?? 99;
    }

    protected function getLastRow()
    {
        $highestRow = $this->worksheet->getHighestRow();
        // Check for the actual last populated row if needed
        // For example:
        // $highestRow = $this->worksheet->getHighestDataRow();
        return $highestRow;
    }

    protected function abbreviateTitle($title)
    {
        $abbreviations = [
            'Teacher I' => 'T-I',
            'Teacher II' => 'T-II',
            'Teacher III' => 'T-III',
            'Master Teacher I' => 'MT-I',
            'Master Teacher II' => 'MT-II',
            'Master Teacher III' => 'MT-III',
            'Master Teacher IV' => 'MT-IV',
            'Head Teacher I' => 'HT-I',
            'Head Teacher II' => 'HT-II',
            'Head Teacher III' => 'HT-III',
            'Head Teacher IV' => 'HT-IV',
            'Head Teacher V' => 'HT-V',
            'Head Teacher VI' => 'HT-VI',
            'Special Education Teacher I' => 'SET-I',
            'Special Education Teacher II' => 'SET-II',
            'Special Education Teacher III' => 'SET-III',
            'Special Education Teacher IV' => 'SET-IV',
            'Special Education Teacher V' => 'SET-V',
            'School Principal I' => 'P-I',
            'School Principal II' => 'P-II',
            'School Principal III' => 'P-III',
            'School Principal IV' => 'P-IV',
        ];

        return $abbreviations[$title] ?? $title;
    }
}
