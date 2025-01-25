<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('district')->insert([
            ['name' => 'District 1'],
            ['name' => 'District 2'],
            ['name' => 'District 3'],
            ['name' => 'District 4'],
        ]);
    }
}
