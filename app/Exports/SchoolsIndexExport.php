<?php

namespace App\Exports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SchoolsIndexExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return School::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'School ID',
            'District ID',
            'School Name',
            'Address',
            'Division',
            'Email',
            'Curricular Classification',
        ];
    }
}
