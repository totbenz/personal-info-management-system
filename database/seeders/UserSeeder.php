<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'email' => 'admin@mailinator.com',
                'role' => 'admin',
                'password' => Hash::make('Password'),
                'personnel_id' => 1
            ],
            [
                'email' => 'schoolhead@mailinator.com',
                'role' => 'school_head',
                'password' => Hash::make('Password'),
                'personnel_id' => 2
            ],
            [
                'email' => 'teacher@mailinator.com',
                'role' => 'teacher',
                'password' => Hash::make('Password'),
                'personnel_id' => 3
            ],

        ]);
    }
}
