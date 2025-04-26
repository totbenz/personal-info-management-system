<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SalaryGradeSeeder::class,
            SalaryStepsSeeder::class,
            DistrictSeeder::class,
            PositionSeeder::class,
            SchoolSeeder::class,
            PersonnelSeeder::class,
            UserSeeder::class,
        ]);
    }
}
