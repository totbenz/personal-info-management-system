<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SalaryStepsMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Call the individual seeders
        $this->call([
            SalarySteps2025Seeder::class,
            SalarySteps2026Seeder::class,
            SalarySteps2027Seeder::class,
        ]);
    }
}
