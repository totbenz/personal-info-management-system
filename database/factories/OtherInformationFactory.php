<?php

namespace Database\Factories;

use App\Models\OtherInformation;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;

class OtherInformationFactory extends Factory
{
    protected $model = OtherInformation::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['special_skill', 'nonacademic_distinction', 'association']);

        return [
            'personnel_id' => Personnel::factory(),
            'name' => $this->getName($type),
            'type' => $type,
        ];
    }

    private function getName(string $type): string
    {
        return match($type) {
            'special_skill' => $this->faker->randomElement([
                'Computer Programming', 'Graphic Design', 'Video Editing', 'Public Speaking', 'Event Management',
                'Foreign Language (English)', 'Foreign Language (Spanish)', 'Foreign Language (Mandarin)', 'Foreign Language (French)',
                'Musical Instrument (Piano)', 'Musical Instrument (Guitar)', 'Musical Instrument (Violin)', 'Musical Instrument (Drums)',
                'Sports Coaching', 'First Aid and CPR', 'Photography', 'Creative Writing', 'Sign Language',
                'Web Development', 'Database Management', 'Network Administration', 'Cybersecurity', 'Cloud Computing',
                'Digital Marketing', 'Social Media Management', 'Content Creation', 'SEO Optimization', 'Data Analysis',
                'Project Management', 'Quality Assurance', 'Technical Writing', 'Research Methodology', 'Statistical Analysis',
                'Culinary Arts', 'Baking and Pastry', 'Barista Skills', 'Mixology', 'Food Safety Management',
                'Fashion Design', 'Interior Design', 'Architecture Design', '3D Modeling', 'Animation',
                'Leadership Skills', 'Team Building', 'Conflict Resolution', 'Negotiation Skills', 'Time Management',
                'Financial Management', 'Budget Planning', 'Investment Analysis', 'Risk Assessment', 'Accounting',
                'Teaching Methodology', 'Curriculum Development', 'Educational Technology', 'Online Teaching', 'Assessment Design',
                'Counseling Skills', 'Mentorship', 'Career Guidance', 'Student Advising', 'Academic Coaching',
                'Agricultural Skills', 'Farming Techniques', 'Organic Farming', 'Livestock Management', 'Crop Production',
                'Automotive Repair', 'Electrical Work', 'Plumbing', 'Carpentry', 'Home Repair',
                'Dance', 'Theater Arts', 'Singing', 'Acting', 'Directing',
                'Martial Arts', 'Yoga Instruction', 'Fitness Training', 'Sports Medicine', 'Athletic Training',
                'Gardening', 'Landscaping', 'Horticulture', 'Botany', 'Environmental Conservation',
                'Volunteer Management', 'Community Organizing', 'Advocacy Work', 'Public Relations', 'Media Relations',
            ]),
            'nonacademic_distinction' => $this->faker->randomElement([
                'Outstanding Teacher Award', 'Best in Community Service', 'Excellence in Innovation', 'Leadership Award',
                'Service Award', 'Community Recognition', 'Best Implementer Award', 'Model Employee', 'Perfect Attendance Award',
                'Employee of the Year', 'Most Outstanding Performance', 'Excellence in Teaching', 'Research Excellence Award',
                'Best Paper Presentation', 'Outstanding Researcher', 'Innovation Award', 'Quality Service Award', 'Customer Service Excellence',
                'Safety Award', 'Environmental Leadership Award', 'Digital Transformation Award', 'Technology Innovation Award',
                'Academic Excellence Award', 'Scholarship Achievement', 'Dean\'s List', 'President\'s List', 'Chancellor\'s Award',
                'Athletic Achievement', 'Sportsmanship Award', 'Team Player Award', 'Leadership Excellence', 'Mentorship Award',
                'Community Service Excellence', 'Volunteer Service Award', 'Humanitarian Award', 'Social Responsibility Award',
                'Cultural Achievement', 'Arts Excellence', 'Literary Award', 'Artistic Achievement', 'Creative Excellence',
                'Technical Excellence', 'Engineering Achievement', 'Scientific Achievement', 'Medical Excellence', 'Legal Excellence',
                'Business Excellence', 'Entrepreneurial Achievement', 'Management Excellence', 'Administrative Excellence',
                'Professional Excellence', 'Industry Recognition', 'Peer Recognition', 'International Recognition', 'National Recognition',
                'Regional Award', 'Provincial Award', 'City Award', 'Barangay Award', 'School Award',
                'Alumni Achievement', 'Distinguished Alumni', 'Outstanding Alumni', 'Alumni Service Award', 'Alumni Leadership Award',
            ]),
            'association' => $this->faker->randomElement([
                'Teachers Association of the Philippines', 'Philippine Association of Teachers and Educators',
                'National Educators Academy of the Philippines', 'Philippine Normal University Alumni Association',
                'Professional Regulation Commission - Teachers', 'Integrated Bar of the Philippines',
                'Philippine Institute of Certified Public Accountants', 'Local Teachers Organization',
                'School Parent-Teacher Association', 'Philippine Mathematical Society', 'Science Teachers Association',
                'English Teachers Association', 'Filipino Teachers Association', 'History Teachers Association',
                'Physical Education Teachers Association', 'Arts and Music Teachers Association', 'Guidance Counselors Association',
                'School Administrators Association', 'Principals Association', 'Superintendents Association',
                'DepEd Employees Association', 'Public School Teachers Association', 'Private School Teachers Association',
                'University of the Philippines Alumni Association', 'Ateneo Alumni Association', 'La Salle Alumni Association',
                'UST Alumni Association', 'Professional Engineers Association', 'Medical Association of the Philippines',
                'Philippine Nurses Association', 'Lawyers Association', 'Accountants Association', 'Architects Association',
                'IT Professionals Association', 'Marketing Professionals Association', 'HR Professionals Association',
                'Financial Executives Association', 'Bankers Association', 'Insurance Professionals Association',
                'Real Estate Professionals Association', 'Construction Industry Association', 'Manufacturing Association',
                'Retail Trade Association', 'Tourism Industry Association', 'Hospitality Industry Association',
                'Transport Industry Association', 'Logistics Association', 'Supply Chain Association',
                'Agriculture Association', 'Fisheries Association', 'Forestry Association', 'Mining Association',
                'Energy Industry Association', 'Telecommunications Association', 'Media Association',
                'Non-Government Organizations Council', 'Civil Society Organizations Forum', 'Volunteer Organizations Network',
                'Environmental Organizations Coalition', 'Women\'s Organizations Alliance', 'Youth Organizations Federation',
                'Senior Citizens Organizations', 'Persons with Disabilities Organizations', 'Indigenous Peoples Organizations',
                'Religious Organizations Council', 'Faith-Based Organizations Alliance', 'Interfaith Organizations Forum',
                'Sports Organizations Federation', 'Cultural Organizations Association', 'Arts Council',
                'Science and Technology Organizations', 'Research Institutions Association', 'Academic Institutions Alliance',
                'International Organizations Philippines', 'UN Agencies Philippines', 'Red Cross Philippines',
                'Lions Club Philippines', 'Rotary Club Philippines', 'Jaycees Philippines', 'Kiwanis Philippines',
                'Masonic Lodges Philippines', 'Knights of Columbus Philippines', 'Knights of Rizal Philippines',
            ]),
            default => 'Other',
        };
    }

    public function specialSkill(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'special_skill',
        ]);
    }

    public function nonacademicDistinction(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'nonacademic_distinction',
        ]);
    }

    public function association(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'association',
        ]);
    }
}
