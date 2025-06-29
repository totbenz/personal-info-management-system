<?php

namespace App\Exports;

use App\Models\Personnel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class PersonnelsIndexExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Personnel::select(
            'personnels.id',
            'personnels.personnel_id',
            DB::raw("CONCAT(personnels.first_name, ' ', COALESCE(personnels.middle_name, ''), ' ', personnels.last_name) as name"),
            'personnels.sex',
            'personnels.job_status',
            DB::raw("(SELECT title FROM position WHERE position.id = personnels.position_id) as position"),
            DB::raw("(SELECT classification FROM position WHERE position.id = personnels.position_id) as classification"),
            'personnels.category',
            DB::raw("(SELECT school_id FROM schools WHERE schools.id = personnels.school_id) as school"),
            DB::raw("(SELECT salary FROM salary_steps WHERE salary_steps.salary_grade_id = personnels.salary_grade_id AND salary_steps.step = personnels.step_increment ORDER BY salary_steps.year DESC LIMIT 1) as salary"),
        );

        // Apply school filter
        if (!empty($this->filters['schoolId'])) {
            $query->where('personnels.school_id', $this->filters['schoolId']);
        } elseif (!empty($this->filters['selectedSchool'])) {
            $query->where('personnels.school_id', $this->filters['selectedSchool']);
        }

        // Apply category filter
        if (!empty($this->filters['selectedCategory'])) {
            $query->where('personnels.category', $this->filters['selectedCategory']);
        }

        // Apply position filter
        if (!empty($this->filters['selectedPosition'])) {
            $query->where('personnels.position_id', $this->filters['selectedPosition']);
        }

        // Apply job status filter
        if (!empty($this->filters['selectedJobStatus'])) {
            $query->where('personnels.job_status', $this->filters['selectedJobStatus']);
        }

        // Apply search filter
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('personnels.personnel_id', "like", "%{$search}%")
                    ->orWhere(function ($subQuery) use ($search) {
                        $subQuery->whereRaw("CONCAT(personnels.first_name, ' ', SUBSTRING(personnels.middle_name, 1, 1), '. ', personnels.last_name) LIKE ?", ["%{$search}%"]);
                    })
                    ->orWhere('personnels.school_id', "like", "%{$search}%")
                    ->orWhere('personnels.category', "like", "%{$search}%");
            });
        }

        // Apply sorting
        $sortColumn = $this->filters['sortColumn'] ?? 'id';
        $sortDirection = $this->filters['sortDirection'] ?? 'ASC';
        $query->orderBy($sortColumn, $sortDirection);

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Personnel ID',
            'Name',
            'Sex',
            'Job Status',
            'Position',
            'Classification',
            'Category',
            'School ID',
            'Salary',
        ];
    }
}
