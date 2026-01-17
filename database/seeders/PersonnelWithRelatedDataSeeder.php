<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personnel;
use App\Models\Address;
use App\Models\ContactPerson;
use App\Models\Family;
use App\Models\Education;
use App\Models\EducationEntry;
use App\Models\CivilServiceEligibility;
use App\Models\WorkExperience;
use App\Models\VoluntaryWork;
use App\Models\TrainingCertification;
use App\Models\Reference;
use App\Models\OtherInformation;

class PersonnelWithRelatedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates ALL existing personnel with complete related data.
     */
    public function run(): void
    {
        // Get ALL existing personnel
        $allPersonnel = Personnel::all();

        if ($allPersonnel->isEmpty()) {
            $this->command->warn('No personnel found in database. Please seed personnel first!');
            return;
        }

        $this->command->info("Found {$allPersonnel->count()} personnel. Adding complete related data...");

        $processed = 0;
        $skipped = 0;

        foreach ($allPersonnel as $personnel) {
            $this->command->info("Processing: {$personnel->first_name} {$personnel->last_name} (ID: {$personnel->id})");

            // Check and create addresses if missing
            $permanentAddress = Address::where('personnel_id', $personnel->id)
                ->where('address_type', 'permanent')
                ->first();

            if (!$permanentAddress) {
                Address::factory()->permanent()->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line('  Created permanent address');
            }

            $residentialAddress = Address::where('personnel_id', $personnel->id)
                ->where('address_type', 'residential')
                ->first();

            if (!$residentialAddress) {
                Address::factory()->residential()->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line('  Created residential address');
            }

            // Check and create contact persons if missing
            $contactPersonCount = ContactPerson::where('personnel_id', $personnel->id)->count();
            if ($contactPersonCount === 0) {
                ContactPerson::factory(rand(1, 2))->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line('  Created contact persons');
            }

            // Check and create family members if missing
            $hasFather = Family::where('personnel_id', $personnel->id)
                ->where('relationship', 'father')
                ->exists();

            if (!$hasFather) {
                Family::factory()->father()->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line('  Created father record');
            }

            $hasMother = Family::where('personnel_id', $personnel->id)
                ->where('relationship', 'mother')
                ->exists();

            if (!$hasMother) {
                Family::factory()->mother()->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line('  Created mother record');
            }

            // Create spouse and children if married
            if (in_array($personnel->civil_status, ['married', 'widowed', 'seperated'])) {
                $hasSpouse = Family::where('personnel_id', $personnel->id)
                    ->where('relationship', 'spouse')
                    ->exists();

                if (!$hasSpouse) {
                    Family::factory()->spouse()->create([
                        'personnel_id' => $personnel->id,
                    ]);
                    $this->command->line('  Created spouse record');
                }

                $childrenCount = Family::where('personnel_id', $personnel->id)
                    ->where('relationship', 'children')
                    ->count();

                if ($childrenCount === 0) {
                    $numChildren = rand(1, 3);
                    Family::factory($numChildren)->children()->create([
                        'personnel_id' => $personnel->id,
                    ]);
                    $this->command->line("  Created {$numChildren} children records");
                }
            }

            // Check and create education records if missing
            $educationTypes = ['elementary', 'secondary', 'graduate'];

            foreach ($educationTypes as $type) {
                $hasEducation = Education::where('personnel_id', $personnel->id)
                    ->where('type', $type)
                    ->exists();

                if (!$hasEducation) {
                    try {
                        if ($type === 'elementary') {
                            Education::factory()->elementary()->create([
                                'personnel_id' => $personnel->id,
                            ]);
                        } elseif ($type === 'secondary') {
                            Education::factory()->secondary()->create([
                                'personnel_id' => $personnel->id,
                            ]);
                        } else {
                            Education::factory()->graduate()->create([
                                'personnel_id' => $personnel->id,
                            ]);
                        }
                        $this->command->line("  Created {$type} education record");
                    } catch (\Exception $e) {
                        $this->command->warn("  Could not create {$type} education: " . $e->getMessage());
                    }
                }
            }

            // Add graduate studies for 30% of personnel
            $hasGraduateStudies = Education::where('personnel_id', $personnel->id)
                ->where('type', 'graduate studies')
                ->exists();

            if (!$hasGraduateStudies && rand(1, 100) <= 30) {
                try {
                    Education::factory()->create([
                        'personnel_id' => $personnel->id,
                        'type' => 'graduate studies',
                    ]);
                    $this->command->line('  Created graduate studies record');
                } catch (\Exception $e) {
                    // Skip if duplicate
                }
            }

            // Check and create education entries if missing (newer table) - MULTIPLE ENTRIES PER LEVEL
            $educationEntryTypes = ['elementary', 'secondary', 'vocational/trade', 'graduate'];

            foreach ($educationEntryTypes as $type) {
                $existingCount = EducationEntry::where('personnel_id', $personnel->id)
                    ->where('type', $type)
                    ->count();

                // Create 2 entries per education level
                $entriesToCreate = max(0, 2 - $existingCount);

                for ($i = 0; $i < $entriesToCreate; $i++) {
                    try {
                        if ($type === 'elementary') {
                            EducationEntry::factory()->elementary()->create([
                                'personnel_id' => $personnel->id,
                            ]);
                        } elseif ($type === 'secondary') {
                            EducationEntry::factory()->secondary()->create([
                                'personnel_id' => $personnel->id,
                            ]);
                        } elseif ($type === 'vocational/trade') {
                            EducationEntry::factory()->create([
                                'personnel_id' => $personnel->id,
                                'type' => 'vocational/trade',
                            ]);
                        } else {
                            EducationEntry::factory()->graduate()->create([
                                'personnel_id' => $personnel->id,
                            ]);
                        }
                    } catch (\Exception $e) {
                        // Skip if error
                    }
                }
            }

            // Add graduate studies entries for 80% of personnel (increased from 60%)
            $existingGradStudies = EducationEntry::where('personnel_id', $personnel->id)
                ->where('type', 'graduate studies')
                ->count();

            if ($existingGradStudies < 2 && rand(1, 100) <= 80) {
                $entriesToCreate = max(0, 2 - $existingGradStudies);
                for ($i = 0; $i < $entriesToCreate; $i++) {
                    try {
                        EducationEntry::factory()->create([
                            'personnel_id' => $personnel->id,
                            'type' => 'graduate studies',
                        ]);
                    } catch (\Exception $e) {
                        // Skip if duplicate
                    }
                }
            }

            // Add additional vocational/trade courses for 70% of personnel (increased from 40%)
            if (rand(1, 100) <= 70) {
                $existingVocational = EducationEntry::where('personnel_id', $personnel->id)
                    ->where('type', 'vocational/trade')
                    ->count();

                if ($existingVocational < 3) {
                    $entriesToCreate = max(0, 3 - $existingVocational);
                    for ($i = 0; $i < $entriesToCreate; $i++) {
                        try {
                            EducationEntry::factory()->create([
                                'personnel_id' => $personnel->id,
                                'type' => 'vocational/trade',
                            ]);
                        } catch (\Exception $e) {
                            // Skip if duplicate
                        }
                    }
                }
            }

            // Check and create civil service eligibilities if missing
            $eligibilityCount = CivilServiceEligibility::where('personnel_id', $personnel->id)->count();
            if ($eligibilityCount === 0) {
                $numEligibilities = rand(1, 3);
                CivilServiceEligibility::factory($numEligibilities)->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line("  Created {$numEligibilities} civil service eligibilities");
            }

            // Check and create work experiences if missing
            $workExpCount = WorkExperience::where('personnel_id', $personnel->id)->count();
            if ($workExpCount === 0) {
                $numWorkExp = rand(2, 5);
                WorkExperience::factory($numWorkExp)->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line("  Created {$numWorkExp} work experiences");
            }

            // Check and create voluntary works if missing
            $voluntaryWorkCount = VoluntaryWork::where('personnel_id', $personnel->id)->count();
            if ($voluntaryWorkCount === 0) {
                $numVoluntaryWork = rand(1, 3);
                VoluntaryWork::factory($numVoluntaryWork)->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line("  Created {$numVoluntaryWork} voluntary works");
            }

            // Check and create training certifications if missing
            $trainingCount = TrainingCertification::where('personnel_id', $personnel->id)->count();
            if ($trainingCount === 0) {
                $numTraining = rand(3, 7);
                TrainingCertification::factory($numTraining)->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line("  Created {$numTraining} training certifications");
            }

            // Check and create references if missing
            $referenceCount = Reference::where('personnel_id', $personnel->id)->count();
            if ($referenceCount === 0) {
                Reference::factory(3)->create([
                    'personnel_id' => $personnel->id,
                ]);
                $this->command->line('  ✓ Created 3 references');
            }

            // Check and create other information if missing - CREATE MULTIPLE ENTRIES PER TYPE
            $existingSkills = OtherInformation::where('personnel_id', $personnel->id)
                ->where('type', 'special_skill')
                ->count();

            // Create 3-5 special skills
            if ($existingSkills < 3) {
                $skillsToCreate = rand(3, 5) - $existingSkills;
                OtherInformation::factory($skillsToCreate)->specialSkill()->create([
                    'personnel_id' => $personnel->id,
                ]);
            }

            // Create 2-4 nonacademic distinctions (always)
            $existingDistinctions = OtherInformation::where('personnel_id', $personnel->id)
                ->where('type', 'nonacademic_distinction')
                ->count();

            if ($existingDistinctions < 2) {
                $distinctionsToCreate = rand(2, 4) - $existingDistinctions;
                OtherInformation::factory($distinctionsToCreate)->nonacademicDistinction()->create([
                    'personnel_id' => $personnel->id,
                ]);
            }

            // Create 3-5 associations (always)
            $existingAssociations = OtherInformation::where('personnel_id', $personnel->id)
                ->where('type', 'association')
                ->count();

            if ($existingAssociations < 3) {
                $associationsToCreate = rand(3, 5) - $existingAssociations;
                OtherInformation::factory($associationsToCreate)->association()->create([
                    'personnel_id' => $personnel->id,
                ]);
            }

            $totalOtherInfo = OtherInformation::where('personnel_id', $personnel->id)->count();
            $this->command->line("  ✓ Total other information records: {$totalOtherInfo}");

            $processed++;
        }

        $this->command->newLine();
        $this->command->info('✅ All personnel data populated successfully!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("Processed: {$processed} personnel");
        $this->command->newLine();
        $this->command->info('📊 Final Database Statistics:');
        $this->command->table(
            ['Table', 'Total Records'],
            [
                ['Personnel', Personnel::count()],
                ['Addresses', Address::count()],
                ['Contact Persons', ContactPerson::count()],
                ['Family Members', Family::count()],
                ['Education Records', Education::count()],
                ['Education Entries', EducationEntry::count()],
                ['Civil Service Eligibilities', CivilServiceEligibility::count()],
                ['Work Experiences', WorkExperience::count()],
                ['Voluntary Works', VoluntaryWork::count()],
                ['Training Certifications', TrainingCertification::count()],
                ['References', Reference::count()],
                ['Other Information', OtherInformation::count()],
            ]
        );
    }
}
