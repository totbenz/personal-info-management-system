<?php

namespace Database\Factories;

use App\Models\TrainingCertification;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrainingCertificationFactory extends Factory
{
    protected $model = TrainingCertification::class;

    public function definition(): array
    {
        $from = $this->faker->dateTimeBetween('-10 years', '-1 year');
        $to = $this->faker->dateTimeBetween($from, $from->format('Y-m-d') . ' +7 days');

        return [
            'personnel_id' => Personnel::factory(),
            'training_seminar_title' => $this->faker->randomElement([
                'Classroom Management and Discipline',
                'Differentiated Instruction Strategies',
                'Assessment and Evaluation Techniques',
                'Technology Integration in Education',
                'Inclusive Education and Special Needs',
                'Curriculum Development and Design',
                'School-Based Management Training',
                'Child Protection and Safeguarding',
                'Mental Health and Wellness for Educators',
                'Research and Action Research Methodology',
                'K-12 Curriculum Implementation',
                'Mother Tongue-Based Multilingual Education',
                'Values Education and Character Formation',
                'Disaster Risk Reduction and Management',
                'Educational Leadership and Management',
            ]),
            'type' => $this->faker->randomElement([
                'Seminar',
                'Workshop',
                'Training',
                'Conference',
                'Webinar',
                'Symposium',
            ]),
            'sponsored' => $this->faker->randomElement([
                'DepEd',
                'DepEd Regional Office',
                'DepEd Division Office',
                'School',
                'Private Organization',
                'NGO',
                'University',
                'Professional Organization',
            ]),
            'inclusive_from' => $from->format('Y-m-d'),
            'inclusive_to' => $to->format('Y-m-d'),
            'hours' => $this->faker->randomElement([8, 16, 24, 32, 40, 48]),
        ];
    }
}
