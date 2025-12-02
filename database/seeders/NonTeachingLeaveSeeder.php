<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NonTeachingLeave;
use App\Models\Personnel;
use Carbon\Carbon;

class NonTeachingLeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personnels = Personnel::all();
        $year = Carbon::now()->year;
        foreach ($personnels as $personnel) {
            $leaves = NonTeachingLeave::defaultLeaves(
                $personnel->employment_start ? Carbon::parse($personnel->employment_start)->diffInYears(Carbon::now()) : 0,
                $personnel->is_solo_parent ?? false,
                $personnel->sex ?? null,
                $personnel->civil_status ?? null
            );
            foreach ($leaves as $type => $available) {
                NonTeachingLeave::firstOrCreate([
                    'non_teaching_id' => $personnel->id,
                    'leave_type' => $type,
                    'year' => $year,
                ], [
                    'available' => $available,
                    'used' => 0,
                    'remarks' => 'Seeded',
                ]);
            }
        }
    }
}
