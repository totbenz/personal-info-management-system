<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Personnel;
use App\Models\School;

class SchoolExport
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
        $this->spreadsheet = IOFactory::load($this->filename);

        $this->worksheet = $this->spreadsheet->getActiveSheet();

        $this->populateHeader();
        $this->populateSchoolHead();

        $this->excelOutputPath = public_path('report/sf7.xlsx');
        // Save the Excel file
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($this->excelOutputPath);
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
        $worksheet->setCellValue('K5', $this->school->division);
        $worksheet->setCellValue('D7', $this->school->school_name);
        $worksheet->setCellValue('K7', $this->school->district->name);
        $worksheet->setCellValue('R7', $this->school->getSchoolYear());
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
        $worksheet->setCellValue('F20', $schoolHead->getAbbreviatedTitleAttribute());
        $worksheet->setCellValue('G20', $schoolHead->appointment . '/' . $schoolHead->job_status);

        // Populate Education Qualification
        $worksheet->setCellValue('H20', $schoolHead->getEQDegreePostGraduate());
        $worksheet->setCellValue('I20', $schoolHead->getEQMajor());
        $worksheet->setCellValue('K20', $schoolHead->getEQMinor());

        //Populate DTR
        $startRow = 20;
        $endRow = 27;
        $currentRow = $startRow;

        if ($schoolHead->assignmentDetails()->exists()) {
            foreach ($schoolHead->assignmentDetails as $assignment) {
                // Populate the cell values
                $worksheet->setCellValue('M' . $currentRow, $assignment->assignment);
                $worksheet->setCellValue('N' . $currentRow, $assignment->dtr_day);
                $worksheet->setCellValue('O' . $currentRow, $assignment->dtr_from);
                $worksheet->setCellValue('P' . $currentRow, $assignment->dtr_to);
                $worksheet->setCellValue('Q' . $currentRow, $assignment->teaching_minutes_per_week);
                $currentRow++;
            }
        } else {
            $worksheet->setCellValue('M' . $startRow, 'N/A');
            $worksheet->setCellValue('N' . $startRow, 'N/A');
            $worksheet->setCellValue('O' . $startRow, 'N/A');
            $worksheet->setCellValue('P' . $startRow, 'N/A');
            $worksheet->setCellValue('Q' . $startRow, 'N/A');
        }
    }

    public function populateTeacher()
    {
        $worksheet = $this->worksheet;
        $teachers = $this->school->teachers;

        $startRow = 29;
        $endRow = 27;
        foreach($teachers as $teacher)
        {

        }
    }
}
