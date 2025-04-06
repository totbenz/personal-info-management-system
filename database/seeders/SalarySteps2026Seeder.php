<?php

// 2026

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalarySteps2026Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data extracted from the "Salary Increase Table 2026 - Third Tranche" image
        $salarySteps = [
            1 => [14634, 14730, 14849, 14968, 15089, 15211, 15333, 15456],
            2 => [15522, 15636, 15752, 15869, 15986, 16103, 16223, 16342],
            3 => [16486, 16610, 16732, 16856, 16982, 17106, 17234, 17360],
            4 => [17506, 17636, 17767, 17898, 18031, 18163, 18298, 18433],
            5 => [18581, 18720, 18858, 18998, 19137, 19280, 19423, 19565],
            6 => [19716, 19862, 20009, 20158, 20307, 20457, 20609, 20761], // Corrected Step 6 for Grade 6 based on pattern
            7 => [20914, 21069, 21224, 21382, 21539, 21699, 21859, 22022],
            8 => [22423, 22627, 22832, 23038, 23246, 23456, 23668, 23883],
            9 => [24329, 24523, 24720, 24917, 25117, 25318, 25521, 25725],
            10 => [26917, 27131, 27347, 27565, 27788, 28007, 28230, 28456],
            11 => [31705, 31820, 32109, 32401, 32697, 32998, 33302, 33611], // Corrected Step 2 for Grade 11 based on transcription
            12 => [33947, 34069, 34357, 34648, 34943, 35242, 35544, 35850], // Corrected Step 2 for Grade 12 based on transcription
            13 => [36125, 36283, 36599, 36919, 37244, 37572, 37904, 38241], // Corrected Step 2 for Grade 13 based on transcription
            14 => [38764, 39141, 39523, 39910, 40300, 40696, 41097, 41503], // Corrected Step 2 for Grade 14 based on transcription
            15 => [42178, 42594, 43015, 43442, 43874, 44310, 44753, 45202],
            16 => [45694, 46152, 46615, 47084, 47559, 48040, 48528, 49020],
            17 => [49562, 50066, 50576, 51092, 51614, 52144, 52678, 53218], // Corrected Step 8 for Grade 17 based on transcription
            18 => [53818, 54371, 54933, 55499, 56075, 56667, 57246, 57842], // Note: Space in image data, assumed no missing step
            19 => [59153, 59966, 60793, 61632, 62486, 63353, 64236, 65132], // Corrected Step 6 for Grade 19 based on transcription
            20 => [66052, 66970, 67904, 68853, 69818, 70772, 71727, 72671], // Note: Space in image data, assumed no missing step
            21 => [73303, 74337, 75388, 76456, 77542, 78645, 79692, 80831], // Corrected Step 7 based on transcription
            22 => [81796, 82963, 84151, 85366, 86582, 87746, 89011, 90295], // Corrected Step 7 based on transcription
            23 => [91306, 92622, 93962, 95330, 96823, 98341, 99883, 101318],
            24 => [102603, 104209, 105841, 107500, 109185, 110898, 112533, 114301], // Corrected Step 7 based on transcription
            25 => [116643, 118469, 120326, 122212, 124131, 126079, 128061, 130073],
            26 => [131807, 133870, 135968, 138100, 140268, 142469, 144707, 146983],
            27 => [148940, 151273, 153644, 155906, 158353, 160236, 162752, 165310], // Corrected Step 6 based on transcription
            28 => [167129, 169752, 172418, 174797, 177545, 180339, 182660, 185537], // Corrected Step 7 based on transcription
            29 => [187531, 190482, 193480, 196528, 199624, 202006, 205191, 208430], // Corrected Step 6 based on transcription
            30 => [210718, 214038, 217207, 220425, 223691, 227224, 230595, 234240], // Corrected Step 3 and 7 based on transcription
            31 => [300961, 306691, 312532, 318182, 323938, 329989, 336092, 342310],
            32 => [356237, 363257, 370418, 377359, 384805, 392400, 400150, 408055],
        ];

        $data = [];

        foreach ($salarySteps as $grade => $steps) {
            foreach ($steps as $step => $amount) {
                $data[] = [
                    'salary_grade_id' => $grade,
                    'step' => $step + 1, // Add 1 because array index starts at 0
                    'salary' => $amount,
                    'year' => 2026, // Updated year based on the image title
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Consider deleting old data for the year 2026 if necessary before inserting
        // DB::table('salary_steps')->where('year', 2026)->delete();

        DB::table('salary_steps')->insert($data);
    }
}



