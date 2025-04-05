<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salaryGrades = [];

        for ($i = 1; $i <= 32; $i++) {
            $salaryGrades[] = [
                'grade' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('salary_grades')->insert($salaryGrades);
    }
}
