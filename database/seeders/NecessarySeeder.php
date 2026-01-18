<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NecessarySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SalaryGradeSeeder::class, // necessary
            SalaryStepsSeeder::class, // necessary
            DistrictSeeder::class, // necessary
            PositionSeeder::class, // necessary
            SchoolSeeder::class, // necessary
            PersonnelSeeder::class, // necessary
            UserSeeder::class, // necessary
            // SalaryChangesSeeder::class,
            SignatureSeeder::class, // necessary
            // LeaveRequestSeeder::class,
            // ServiceCreditRequestSeeder::class,
            // ServiceRecordSeeder::class,
            // EventSeeder::class,
            NonTeachingLeaveSeeder::class,
            TeacherLeaveSeeder::class,
            SchoolHeadLeaveSeeder::class,
            PersonnelWithRelatedDataSeeder::class,
        ]);
    }
}
