<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Personnel;
use App\Models\SalaryChange;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalaryChangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all personnel IDs
        $personnelIds = Personnel::pluck('id')->toArray();

        foreach ($personnelIds as $personnelId) {
            // Check if record already exists for this personnel
            if (!SalaryChange::where('personnel_id', $personnelId)->exists()) {

                // Get the personnel's current salary grade and step
                $personnel = Personnel::find($personnelId);

                // Insert NOSA record
                DB::table('salary_changes')->insert([
                    'personnel_id' => $personnelId,
                    'type' => 'NOSA',
                    'previous_salary_grade' => 0,
                    'current_salary_grade' => $personnel->salary_grade_id,
                    'previous_salary_step' => 0,
                    'current_salary_step' => $personnel->step_increment,
                    'previous_salary' => 0,
                    'current_salary' => $personnel->salary,
                    'actual_monthly_salary_as_of_date' => Carbon::now(),
                    'adjusted_monthly_salary_date' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);

                // Insert NOSi record
                DB::table('salary_changes')->insert([
                    'personnel_id' => $personnelId,
                    'type' => 'NOSi',
                    'previous_salary_grade' => 0,
                    'current_salary_grade' => $personnel->salary_grade_id,
                    'previous_salary_step' => 0,
                    'current_salary_step' => $personnel->step_increment,
                    'previous_salary' => 0,
                    'current_salary' => $personnel->salary,
                    'actual_monthly_salary_as_of_date' => Carbon::now(),
                    'adjusted_monthly_salary_date' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
