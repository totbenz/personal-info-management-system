<?php

namespace App\Exports;

use App\Models\Personnel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PersonnelsIndexExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Personnel::select(
            'id',
            'sex',
            DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as name"),
            'job_status',
            DB::raw("(SELECT title FROM position WHERE position.id = personnels.position_id) as position"),
            DB::raw("(SELECT classification FROM position WHERE position.id = personnels.position_id) as classification"),
            'category',
            DB::raw("(SELECT school_id FROM schools WHERE schools.id = personnels.school_id) as school"),
        )->get();
    }

    public function headings(): array
    {
        return [
            'Personnel ID',
            'Name',
            'Sex',
            'Job Status',
            'Position',
            'Classification',
            'Category',
            'School',
        ];
    }
}
