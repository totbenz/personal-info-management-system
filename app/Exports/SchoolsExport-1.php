<?php
namespace App\Exports;

use App\Models\School;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class SchoolsExport implements FromView, WithDefaultStyles, WithHeadings, WithStyles, WithEvents
{
    use Exportable;
    public $school_id;
    protected $data;

    public function __construct($data, $id)
    {
        $this->data = $data;
        $this->school_id = $id;
    }

    public function defaultStyles(Style $defaultStyle)
    {
        $defaultStyle->getFont()->setName('Arial');
        $defaultStyle->getFont()->setSize(11);

        return $defaultStyle;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $writer = $event->writer;
                $sheet = $writer->getActiveSheet();

                $sheet->getStyle('A1:S100')->getAlignment()->setWrapText(true);

                // Define an array of row heights where the key is the row number and the value is the row height
                $rowHeights = [
                    1 => 23.25,
                    3 => 14.25,
                    4 => 13.5,
                    5 => 21.75,
                    6 => 4.5,
                    7 => 19.5,
                    8 => 4.5,
                    9 => 30,
                    10 => 32.25,
                    11 => 30.75,
                    12 => 12,
                    13 => 12,
                    14 => 12,
                    15 => 12,
                    16 => 12,
                    17 => 12,
                    18 => 27.75,
                    19 => 82.5,
                    20 => 12.75,
                    21 => 12.75,
                    22 => 12.75,
                    23 => 12.75,
                    24 => 12.75,
                    25 => 12.75,
                ];

                // Apply the defined row heights
                foreach ($rowHeights as $row => $height) {
                    $sheet->getRowDimension($row)->setRowHeight($height);
                }

                // Set the row height for rows 20 to 72 to 12.75
                for ($row = 20; $row <= 72; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(12.75);
                }
                $sheet->getColumnDimension('A')->setWidth(8.57);
                $sheet->getColumnDimension('B')->setWidth(24.43);
                $sheet->getColumnDimension('C')->setWidth(4.86);
                $sheet->getColumnDimension('D')->setWidth(7.29);
                $sheet->getColumnDimension('E')->setWidth(0.58);
                $sheet->getColumnDimension('F')->setWidth(8.57);
                $sheet->getColumnDimension('G')->setWidth(8.57);
                $sheet->getColumnDimension('H')->setWidth(12.29);
                $sheet->getColumnDimension('I')->setWidth(11.57);
                $sheet->getColumnDimension('J')->setWidth(0.83);
                $sheet->getColumnDimension('K')->setWidth(5.86);
                $sheet->getColumnDimension('L')->setWidth(3);
                $sheet->getColumnDimension('M')->setWidth(17.43);
                $sheet->getColumnDimension('N')->setWidth(6.43);
                $sheet->getColumnDimension('O')->setWidth(6.43);
                $sheet->getColumnDimension('P')->setWidth(6.14);
                $sheet->getColumnDimension('Q')->setWidth(9.71);
                $sheet->getColumnDimension('R')->setWidth(7.29);
                $sheet->getColumnDimension('S')->setWidth(7.14);
            },
        ];
    }

    public function view(): View
    {
        return view('export.school-form-report', [
            'school' => School::findOrFail($this->school_id)
        ]);
    }

    public function headings(): array
    {
        return [
            'School ID',
            'School Name',
            'Region',
            'Division',
            'District',
            'Address',
            'Email',
            'Phone',
            'General Curricular Offering',
            'Curricular Classification'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:S100')->getAlignment()->setWrapText(true);
    }
}
