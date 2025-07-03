<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $divisions = ['Ormoc City', 'Maasin City', 'Leyte', 'Baybay City'];

        DB::table('schools')->insert([
            [
                'school_id' => 313316,
                'school_name' => 'Baybay City National Night High School',
                'address' => '30 de Deciembre,F. MascariÃ±as Zone 12',
                'division' => $divisions[array_rand($divisions)],
                'district_id' => '2',
                'email' => 'bnhs@mail.edu.ph',
                'phone' => '09101123456',
                'curricular_classification' => json_encode(['Grade 7-10'])
            ],
            [
                'school_id' => 313323,
                'school_name' => 'Ciabu National High School',
                'address' => 'Brgy. Ciabu, Baybay City, Leyte',
                'division' => $divisions[array_rand($divisions)],
                'district_id' => '1',
                'email' => 'ciabu_high_school@mail.edu.ph',
                'phone' => '09028123456',
                'curricular_classification' => json_encode(['Grade 7-10', 'Grade 11-12'])
            ],
            [
                'school_id' => 500673,
                'school_name' => 'Kabunga-an Integrated School',
                'address' => 'Brgy. Kambonggan, Baybay City, Leyte',
                'division' => $divisions[array_rand($divisions)],
                'district_id' => '2',
                'email' => 'kambonggan@mail.edu.ph',
                'phone' => '0828123456',
                'curricular_classification' => json_encode(['Kinder','Grade 1-6', 'Grade 7-10', 'Grade 11-12'])
            ],
            [
                'school_id' => 121087,
                'school_name' => 'Kambonggan Elementary School',
                'address' => 'Maybog, Baybay City, Leyte',
                'division' => $divisions[array_rand($divisions)],
                'district_id' => '1',
                'email' => 'maybog_elem_school@mail.edu.ph',
                'phone' => '09481234562',
                'curricular_classification' => json_encode(['Kinder','Grade 1-6'])
            ],
            [
                'school_id' => 121129,
                'school_name' => 'Zacarito Elementary School',
                'address' => '	Brgy. Zacarito, Baybay City, Leyte',
                'division' => $divisions[array_rand($divisions)],
                'district_id' => '3',
                'email' => 'zacarito_elem_school@mail.edu.ph',
                'phone' => '09927424011',
                'curricular_classification' => json_encode(['Grade 1-6'])
            ]
        ]);
    }
}
