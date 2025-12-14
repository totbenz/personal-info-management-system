<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SignatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('signatures')->insert([
            [
                'position' => 'Schools Division Superintendent',
                'position_name' => 'Schools Division Superintendent',
                'full_name' => 'MANUEL T. ALBAÑO, PH.D., CESO V',
            ],
            [
                'position' => 'OIC Assistant Schools Division Superintendent',
                'position_name' => 'OIC Assistant Schools Division Superintendent',
                'full_name' => 'JOSEMILO P. RUIZ, EDD., CESE',
            ],
            [
                'position' => 'Administrative Officer VI (HRMO II)',
                'position_name' => 'Administrative Officer VI (HRMO II)',
                'full_name' => 'JULIUS CAESAR C. DE LA CERNA'
            ],
            [
                'position' => 'Assistant School Division Superintendent',
                'position_name' => 'Assistant School Division Superintendent',
                'full_name' => 'JOSEMILO P. RUIZ EdD, CESE'
            ]
        ]);
    }
}
