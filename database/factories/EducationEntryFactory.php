<?php

namespace Database\Factories;

use App\Models\EducationEntry;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class EducationEntryFactory extends Factory
{
    protected $model = EducationEntry::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['elementary', 'secondary', 'vocational/trade', 'graduate', 'graduate studies']);
        $periodFrom = $this->faker->numberBetween(1990, 2010);
        $periodTo = $this->faker->numberBetween($periodFrom, $periodFrom + 6);

        return [
            'personnel_id' => Personnel::factory(),
            'type' => $type,
            'sort_order' => 0,
            'school_name' => $this->getSchoolName($type),
            'degree_course' => $this->getDegreeCourse($type),
            'major' => $this->faker->randomElement(['Mathematics', 'Science', 'English', 'Filipino', 'Social Studies', 'History', 'Physical Education', 'Arts', 'Computer Science', 'Physics', 'Chemistry', 'Biology', 'Accounting', 'Business Administration', 'Nursing', 'Engineering', 'Architecture', 'Medicine', 'Law', 'Mass Communication', 'Psychology']),
            'minor' => $this->faker->randomElement(['Psychology', 'Philosophy', 'History', 'Economics', 'Political Science', 'Sociology', 'Literature', 'Statistics', 'Research Methods', 'Mathematics', 'Foreign Language', 'Environmental Science', 'Gender Studies', 'Creative Writing', 'Digital Arts', 'Data Science', 'Public Administration', 'International Relations', 'Anthropology', 'Linguistics']),
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'highest_level_units' => $this->faker->randomElement(['120 units', '150 units', '180 units', '200 units', '240 units', '300 units', '360 units', '420 units']),
            'year_graduated' => $this->faker->numberBetween($periodFrom, $periodTo),
            'scholarship_honors' => $this->faker->randomElement(['Cum Laude', 'Magna Cum Laude', 'Summa Cum Laude', 'With Honors', 'Dean\'s List', 'Academic Excellence', 'Leadership Award', 'Service Award', 'Best in Thesis', 'Outstanding Student', 'Research Excellence', 'Community Service Award', 'Perfect Attendance', 'Athletic Achievement', 'Cultural Award', 'Technical Excellence']),
        ];
    }

    private function getSchoolName(string $type): string
    {
        return match($type) {
            'elementary' => $this->faker->city() . ' Elementary School',
            'secondary' => $this->faker->city() . ' High School',
            'vocational/trade' => $this->faker->city() . ' Technical College',
            'graduate' => $this->faker->randomElement(['University of the Philippines', 'Ateneo de Manila University', 'De La Salle University', 'University of Santo Tomas']),
            'graduate studies' => $this->faker->randomElement(['University of the Philippines', 'Ateneo de Manila University', 'De La Salle University']) . ' - Graduate School',
            default => $this->faker->company() . ' School',
        };
    }

    private function getDegreeCourse(?string $type): ?string
    {
        if (in_array($type, ['elementary', 'secondary'])) {
            return null;
        }

        return match($type) {
            'vocational/trade' => $this->faker->randomElement(['Computer Technology', 'Automotive Technology', 'Culinary Arts', 'Electronics']),
            'graduate' => $this->faker->randomElement(['Bachelor of Science in Education', 'Bachelor of Elementary Education', 'Bachelor of Secondary Education', 'Bachelor of Arts in English']),
            'graduate studies' => $this->faker->randomElement(['Master of Arts in Education', 'Master of Arts in Teaching', 'Doctor of Philosophy in Education']),
            default => null,
        };
    }

    public function elementary(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'elementary',
            'degree_course' => null,
        ]);
    }

    public function secondary(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'secondary',
            'degree_course' => null,
        ]);
    }

    public function graduate(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'graduate',
        ]);
    }
}
