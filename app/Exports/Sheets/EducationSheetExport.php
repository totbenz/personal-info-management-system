<?php

namespace App\Exports\Sheets;

use App\Models\EducationEntry;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class EducationSheetExport implements WithMultipleSheets, WithEvents
{
    private $personnel;
    private $templatePath;

    public function __construct($personnel)
    {
        $this->personnel = $personnel;
        $this->templatePath = public_path('report/Education_Sheet.xlsx');
    }

    public function sheets(): array
    {
        return [
            new ElementarySheet($this->personnel),
            new SecondarySheet($this->personnel),
            new VocationalSheet($this->personnel),
            new CollegeSheet($this->personnel),
            new GraduateStudiesSheet($this->personnel),
        ];
    }

    public function registerEvents(): array
    {
        return [];
    }
}

class ElementarySheet implements WithEvents
{
    private $personnel;

    public function __construct($personnel)
    {
        $this->personnel = $personnel;
    }

    public function registerEvents(): array
    {
        return [];
    }

    public function fillWorksheet($worksheet)
    {
        Log::info('ElementarySheet::fillWorksheet called', [
            'personnelId' => $this->personnel->id
        ]);

        $educationEntries = $this->personnel->educationEntries()
            ->where('type', 'elementary')
            ->orderBy('sort_order')
            ->get();

        Log::info('Found elementary entries', ['count' => $educationEntries->count()]);

        // Skip the first entry, start from the second one
        $startIndex = $educationEntries->count() > 0 ? 1 : 0;

        // Fill data for each entry (starting from second entry)
        foreach ($educationEntries->slice($startIndex) as $index => $entry) {
            $row = 4 + $index; // Starting from row 4

            Log::info('Processing elementary entry', [
                'index' => $index,
                'row' => $row,
                'school' => $entry->school_name
            ]);

            // School Name
            $worksheet->setCellValue('C' . $row, $entry->school_name ?? 'N/A');
            // Degree/Course
            $worksheet->setCellValue('F' . $row, $entry->degree_course ?? 'N/A');
            // Period From
            $worksheet->setCellValue('I' . $row, $entry->period_from ?? 'N/A');
            // Period To
            $worksheet->setCellValue('J' . $row, $entry->period_to ?? 'N/A');
            // Highest Level/Units
            $worksheet->setCellValue('K' . $row, $entry->highest_level_units ?? 'N/A');
            // Year Graduated
            $worksheet->setCellValue('L' . $row, $entry->year_graduated ?? 'N/A');
            // Scholarship/Honors
            $worksheet->setCellValue('M' . $row, $entry->scholarship_honors ?? 'N/A');
        }

        Log::info('ElementarySheet::fillWorksheet completed');
    }

    public function title(): string
    {
        return 'Elementary';
    }
}

class SecondarySheet implements WithEvents
{
    private $personnel;

    public function __construct($personnel)
    {
        $this->personnel = $personnel;
    }

    public function registerEvents(): array
    {
        return [];
    }

    public function fillWorksheet($worksheet)
    {
        $educationEntries = $this->personnel->educationEntries()
            ->where('type', 'secondary')
            ->orderBy('sort_order')
            ->get();

        // Skip the first entry, start from the second one
        $startIndex = $educationEntries->count() > 0 ? 1 : 0;

        foreach ($educationEntries->slice($startIndex) as $index => $entry) {
            $row = 4 + $index; // Starting from row 4

            $worksheet->setCellValue('C' . $row, $entry->school_name ?? 'N/A');
            $worksheet->setCellValue('F' . $row, $entry->degree_course ?? 'N/A');
            $worksheet->setCellValue('I' . $row, $entry->period_from ?? 'N/A');
            $worksheet->setCellValue('J' . $row, $entry->period_to ?? 'N/A');
            $worksheet->setCellValue('K' . $row, $entry->highest_level_units ?? 'N/A');
            $worksheet->setCellValue('L' . $row, $entry->year_graduated ?? 'N/A');
            $worksheet->setCellValue('M' . $row, $entry->scholarship_honors ?? 'N/A');
        }
    }

    public function title(): string
    {
        return 'Secondary';
    }
}

class VocationalSheet implements WithEvents
{
    private $personnel;

    public function __construct($personnel)
    {
        $this->personnel = $personnel;
    }

    public function registerEvents(): array
    {
        return [];
    }

    public function fillWorksheet($worksheet)
    {
        $educationEntries = $this->personnel->educationEntries()
            ->where('type', 'vocational/trade')
            ->orderBy('sort_order')
            ->get();

        // Skip the first entry, start from the second one
        $startIndex = $educationEntries->count() > 0 ? 1 : 0;

        foreach ($educationEntries->slice($startIndex) as $index => $entry) {
            $row = 4 + $index; // Starting from row 4

            $worksheet->setCellValue('C' . $row, $entry->school_name ?? 'N/A');
            $worksheet->setCellValue('F' . $row, $entry->degree_course ?? 'N/A');
            $worksheet->setCellValue('I' . $row, $entry->period_from ?? 'N/A');
            $worksheet->setCellValue('J' . $row, $entry->period_to ?? 'N/A');
            $worksheet->setCellValue('K' . $row, $entry->highest_level_units ?? 'N/A');
            $worksheet->setCellValue('L' . $row, $entry->year_graduated ?? 'N/A');
            $worksheet->setCellValue('M' . $row, $entry->scholarship_honors ?? 'N/A');
        }
    }

    public function title(): string
    {
        return 'Vocational';
    }
}

class CollegeSheet implements WithEvents
{
    private $personnel;

    public function __construct($personnel)
    {
        $this->personnel = $personnel;
    }

    public function registerEvents(): array
    {
        return [];
    }

    public function fillWorksheet($worksheet)
    {
        $educationEntries = $this->personnel->educationEntries()
            ->where('type', 'graduate')
            ->orderBy('sort_order')
            ->get();

        // Skip the first entry, start from the second one
        $startIndex = $educationEntries->count() > 0 ? 1 : 0;

        foreach ($educationEntries->slice($startIndex) as $index => $entry) {
            $row = 4 + $index; // Starting from row 4

            $worksheet->setCellValue('C' . $row, $entry->school_name ?? 'N/A');
            $worksheet->setCellValue('F' . $row, $entry->degree_course ?? 'N/A');
            $worksheet->setCellValue('I' . $row, $entry->period_from ?? 'N/A');
            $worksheet->setCellValue('J' . $row, $entry->period_to ?? 'N/A');
            $worksheet->setCellValue('K' . $row, $entry->highest_level_units ?? 'N/A');
            $worksheet->setCellValue('L' . $row, $entry->year_graduated ?? 'N/A');
            $worksheet->setCellValue('M' . $row, $entry->scholarship_honors ?? 'N/A');
        }
    }

    public function title(): string
    {
        return 'College';
    }
}

class GraduateStudiesSheet implements WithEvents
{
    private $personnel;

    public function __construct($personnel)
    {
        $this->personnel = $personnel;
    }

    public function registerEvents(): array
    {
        return [];
    }

    public function fillWorksheet($worksheet)
    {
        $educationEntries = $this->personnel->educationEntries()
            ->where('type', 'graduate studies')
            ->orderBy('sort_order')
            ->get();

        // Skip the first entry, start from the second one
        $startIndex = $educationEntries->count() > 0 ? 1 : 0;

        foreach ($educationEntries->slice($startIndex) as $index => $entry) {
            $row = 4 + $index; // Starting from row 4

            $worksheet->setCellValue('C' . $row, $entry->school_name ?? 'N/A');
            $worksheet->setCellValue('F' . $row, $entry->degree_course ?? 'N/A');
            $worksheet->setCellValue('I' . $row, $entry->period_from ?? 'N/A');
            $worksheet->setCellValue('J' . $row, $entry->period_to ?? 'N/A');
            $worksheet->setCellValue('K' . $row, $entry->highest_level_units ?? 'N/A');
            $worksheet->setCellValue('L' . $row, $entry->year_graduated ?? 'N/A');
            $worksheet->setCellValue('M' . $row, $entry->scholarship_honors ?? 'N/A');
        }
    }

    public function title(): string
    {
        return 'Graduate Studies';
    }
}
