<?php

namespace App\Livewire\Form;

use App\Models\EducationEntry;
use App\Models\Personnel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EducationEntriesForm extends Component
{
    public $personnel;

    public $showMode = false;
    public $updateMode = false;

    public array $entries = [];
    public array $entriesToDelete = [];

    private const TYPES = [
        'elementary',
        'secondary',
        'vocational_trade',
        'college',
        'graduate_studies',
    ];

    private const TYPE_DB_VALUES = [
        'elementary' => 'elementary',
        'secondary' => 'secondary',
        'vocational_trade' => 'vocational/trade',
        'college' => 'graduate',
        'graduate_studies' => 'graduate studies',
    ];

    private const TYPE_LABELS = [
        'elementary' => 'Elementary',
        'secondary' => 'Secondary',
        'vocational_trade' => 'Vocational/Trade Course',
        'college' => 'College',
        'graduate_studies' => 'Graduate Studies',
    ];

    public function mount($id = null, $showMode = true): void
    {
        if ($id) {
            $this->personnel = Personnel::findOrFail($id);
        }

        $this->showMode = $showMode;
        $this->updateMode = !$showMode;

        $this->loadEntries();
    }

    private function blankEntry(): array
    {
        return [
            'id' => null,
            'school_name' => null,
            'degree_course' => null,
            'major' => null,
            'minor' => null,
            'period_from' => null,
            'period_to' => null,
            'highest_level_units' => null,
            'year_graduated' => null,
            'scholarship_honors' => null,
            // School Location Information
            'school_address' => null,
            'school_city' => null,
            'school_province' => null,
            'school_country' => null,
            // Academic Performance
            'gpa' => null,
            'gpa_scale' => null,
            'class_rank' => null,
            'academic_status' => null,
            // Thesis/Dissertation
            'thesis_title' => null,
            'thesis_advisor' => null,
            // Licenses and Certifications
            'license_number' => null,
            'license_date' => null,
            'license_expiry' => null,
            'board_exam_rating' => null,
            // Recognition and Achievements
            'achievements' => null,
            'extracurricular_activities' => null,
            'leadership_roles' => null,
            'awards' => null,
            'remarks' => null,
            'enrollment_date' => null,
            'completion_date' => null,
        ];
    }

    private function normalizeValue($value)
    {
        if (is_string($value)) {
            $value = trim($value);
            return $value === '' ? null : $value;
        }

        return $value;
    }

    private function isEntryBlank(array $entry): bool
    {
        foreach (['school_name', 'degree_course', 'major', 'minor', 'period_from', 'period_to', 'highest_level_units', 'year_graduated', 'scholarship_honors',
            'school_address', 'school_city', 'school_province', 'school_country', 'gpa', 'gpa_scale', 'class_rank', 'academic_status',
            'thesis_title', 'thesis_advisor', 'license_number', 'license_date', 'license_expiry', 'board_exam_rating',
            'achievements', 'extracurricular_activities', 'leadership_roles', 'awards',
            'remarks', 'enrollment_date', 'completion_date'] as $key) {
            $value = $this->normalizeValue(Arr::get($entry, $key));
            if ($value !== null) {
                return false;
            }
        }

        return true;
    }

    private function dbTypeFor(string $type): string
    {
        return self::TYPE_DB_VALUES[$type] ?? $type;
    }

    private function loadEntries(): void
    {
        $this->entries = [];
        $this->entriesToDelete = [];

        foreach (self::TYPES as $type) {
            $this->entries[$type] = [];
        }

        if (!$this->personnel) {
            foreach (self::TYPES as $type) {
                $this->entries[$type][] = $this->blankEntry();
            }
            return;
        }

        $rows = EducationEntry::query()
            ->where('personnel_id', $this->personnel->id)
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $reverseTypeMap = array_flip(self::TYPE_DB_VALUES);

        foreach ($rows as $row) {
            $typeKey = $reverseTypeMap[$row->type] ?? null;
            if (!$typeKey || !isset($this->entries[$typeKey])) {
                continue;
            }

            $this->entries[$typeKey][] = [
                'id' => $row->id,
                'school_name' => $row->school_name,
                'degree_course' => $row->degree_course,
                'major' => $row->major,
                'minor' => $row->minor,
                'period_from' => $row->period_from,
                'period_to' => $row->period_to,
                'highest_level_units' => $row->highest_level_units,
                'year_graduated' => $row->year_graduated,
                'scholarship_honors' => $row->scholarship_honors,
                // School Location Information
                'school_address' => $row->school_address,
                'school_city' => $row->school_city,
                'school_province' => $row->school_province,
                'school_country' => $row->school_country,
                // Academic Performance
                'gpa' => $row->gpa,
                'gpa_scale' => $row->gpa_scale,
                'class_rank' => $row->class_rank,
                'academic_status' => $row->academic_status,
                // Thesis/Dissertation
                'thesis_title' => $row->thesis_title,
                'thesis_advisor' => $row->thesis_advisor,
                // Licenses and Certifications
                'license_number' => $row->license_number,
                'license_date' => $row->license_date?->format('Y-m-d'),
                'license_expiry' => $row->license_expiry?->format('Y-m-d'),
                'board_exam_rating' => $row->board_exam_rating,
                // Recognition and Achievements
                'achievements' => $row->achievements,
                'extracurricular_activities' => $row->extracurricular_activities,
                'leadership_roles' => $row->leadership_roles,
                'awards' => $row->awards,
                'remarks' => $row->remarks,
                'enrollment_date' => $row->enrollment_date?->format('Y-m-d'),
                'completion_date' => $row->completion_date?->format('Y-m-d'),
            ];
        }

        // Don't automatically add blank entries - let users add them manually if needed
    }

    public function edit(): void
    {
        $this->loadEntries();

        // Ensure at least one blank entry for each type when editing
        foreach (self::TYPES as $type) {
            if (empty($this->entries[$type])) {
                $this->entries[$type][] = $this->blankEntry();
            }
        }

        $this->updateMode = true;
        $this->showMode = false;
    }

    public function cancel(): void
    {
        $this->loadEntries();
        $this->updateMode = false;
        $this->showMode = true;
    }

    public function addEntry(string $type): void
    {
        if (!in_array($type, self::TYPES, true)) {
            return;
        }

        $this->entries[$type][] = $this->blankEntry();
    }

    public function removeEntry(string $type, int $index): void
    {
        if (!in_array($type, self::TYPES, true)) {
            return;
        }

        if (!isset($this->entries[$type][$index])) {
            return;
        }

        // Store the ID if it's an existing entry for deletion
        $entry = $this->entries[$type][$index];
        if (isset($entry['id'])) {
            if (!isset($this->entriesToDelete[$type])) {
                $this->entriesToDelete[$type] = [];
            }
            $this->entriesToDelete[$type][] = $entry['id'];
        }

        array_splice($this->entries[$type], $index, 1);

        // Don't automatically add a blank entry - let users add them manually
    }

    protected function rules(): array
    {
        $rules = [];

        foreach (self::TYPES as $type) {
            $rules["entries.$type"] = ['array'];

            $requiredSchool = "entries.$type.*.school_name";
            $rules[$requiredSchool] = ['nullable', 'string', 'max:255'];

            $rules["entries.$type.*.degree_course"] = ['nullable', 'string', 'max:255'];
            $rules["entries.$type.*.major"] = ['nullable', 'string', 'max:255'];
            $rules["entries.$type.*.minor"] = ['nullable', 'string', 'max:255'];

            $rules["entries.$type.*.period_from"] = ['nullable', 'integer', 'min:1900', 'max:2100'];
            $rules["entries.$type.*.period_to"] = ['nullable', 'integer', 'min:1900', 'max:2100'];
            $rules["entries.$type.*.highest_level_units"] = ['nullable', 'string', 'max:255'];
            $rules["entries.$type.*.year_graduated"] = ['nullable', 'integer', 'min:1900', 'max:2100'];
            $rules["entries.$type.*.scholarship_honors"] = ['nullable', 'string', 'max:255'];

            // All education types are now optional
        }

        return $rules;
    }

    private function validateEntriesLogic(): void
    {
        $errors = [];

        foreach (self::TYPES as $type) {
            foreach ($this->entries[$type] as $index => $entry) {
                $entry = array_map(fn ($v) => $this->normalizeValue($v), $entry);

                if ($this->isEntryBlank($entry)) {
                    continue;
                }

                if (empty($entry['school_name'])) {
                    $errors["entries.$type.$index.school_name"][] = 'School Name is required.';
                }

                $from = Arr::get($entry, 'period_from');
                $to = Arr::get($entry, 'period_to');
                $grad = Arr::get($entry, 'year_graduated');

                if ($from !== null && $to !== null && (int) $to < (int) $from) {
                    $errors["entries.$type.$index.period_to"][] = 'End year must be greater than or equal to start year.';
                }

                if ($grad !== null && $from !== null && (int) $grad < (int) $from) {
                    $errors["entries.$type.$index.year_graduated"][] = 'Graduation year cannot be before the start year.';
                }

                if ($grad !== null && $to !== null && (int) $grad > (int) $to) {
                    $errors["entries.$type.$index.year_graduated"][] = 'Graduation year cannot be after the end year.';
                }
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    public function save(): void
    {
        if (!$this->personnel) {
            return;
        }

        foreach (self::TYPES as $type) {
            $normalized = [];
            foreach (($this->entries[$type] ?? []) as $entry) {
                $clean = [];
                foreach ($this->blankEntry() as $key => $_) {
                    if ($key === 'id') {
                        $clean[$key] = Arr::get($entry, 'id');
                        continue;
                    }
                    $clean[$key] = $this->normalizeValue(Arr::get($entry, $key));
                }

                if ($this->isEntryBlank($clean)) {
                    continue;
                }

                $normalized[] = $clean;
            }

            $this->entries[$type] = $normalized;

            if (empty($this->entries[$type])) {
                // Don't force any education type to have entries
            }
        }

        $this->validate();
        $this->validateEntriesLogic();

        DB::transaction(function () {
            foreach (self::TYPES as $type) {
                $dbType = $this->dbTypeFor($type);
                $existingIds = EducationEntry::query()
                    ->where('personnel_id', $this->personnel->id)
                    ->where('type', $dbType)
                    ->pluck('id')
                    ->all();

                $keptIds = [];

                foreach (($this->entries[$type] ?? []) as $sortOrder => $entry) {
                    $payload = [
                        'personnel_id' => $this->personnel->id,
                        'type' => $dbType,
                        'sort_order' => $sortOrder,
                        'school_name' => Arr::get($entry, 'school_name'),
                        'degree_course' => Arr::get($entry, 'degree_course'),
                        'major' => Arr::get($entry, 'major'),
                        'minor' => Arr::get($entry, 'minor'),
                        'period_from' => Arr::get($entry, 'period_from'),
                        'period_to' => Arr::get($entry, 'period_to'),
                        'highest_level_units' => Arr::get($entry, 'highest_level_units'),
                        'year_graduated' => Arr::get($entry, 'year_graduated'),
                        'scholarship_honors' => Arr::get($entry, 'scholarship_honors'),
                        // School Location Information
                        'school_address' => Arr::get($entry, 'school_address'),
                        'school_city' => Arr::get($entry, 'school_city'),
                        'school_province' => Arr::get($entry, 'school_province'),
                        'school_country' => Arr::get($entry, 'school_country'),
                        // Academic Performance
                        'gpa' => Arr::get($entry, 'gpa'),
                        'gpa_scale' => Arr::get($entry, 'gpa_scale'),
                        'class_rank' => Arr::get($entry, 'class_rank'),
                        'academic_status' => Arr::get($entry, 'academic_status'),
                        // Thesis/Dissertation
                        'thesis_title' => Arr::get($entry, 'thesis_title'),
                        'thesis_advisor' => Arr::get($entry, 'thesis_advisor'),
                        // Licenses and Certifications
                        'license_number' => Arr::get($entry, 'license_number'),
                        'license_date' => Arr::get($entry, 'license_date') ?: null,
                        'license_expiry' => Arr::get($entry, 'license_expiry') ?: null,
                        'board_exam_rating' => Arr::get($entry, 'board_exam_rating'),
                        // Recognition and Achievements
                        'achievements' => Arr::get($entry, 'achievements'),
                        'extracurricular_activities' => Arr::get($entry, 'extracurricular_activities'),
                        'leadership_roles' => Arr::get($entry, 'leadership_roles'),
                        'awards' => Arr::get($entry, 'awards'),
                        'remarks' => Arr::get($entry, 'remarks'),
                        'enrollment_date' => Arr::get($entry, 'enrollment_date') ?: null,
                        'completion_date' => Arr::get($entry, 'completion_date') ?: null,
                    ];

                    $id = Arr::get($entry, 'id');

                    if ($id) {
                        EducationEntry::query()
                            ->where('id', $id)
                            ->where('personnel_id', $this->personnel->id)
                            ->where('type', $dbType)
                            ->update($payload);
                        $keptIds[] = (int) $id;
                    } else {
                        $created = EducationEntry::create($payload);
                        $keptIds[] = $created->id;
                        $this->entries[$type][$sortOrder]['id'] = $created->id;
                    }
                }

                $toDelete = array_diff($existingIds, $keptIds);

                if (!empty($toDelete)) {
                    EducationEntry::query()
                        ->where('personnel_id', $this->personnel->id)
                        ->where('type', $dbType)
                        ->whereIn('id', $toDelete)
                        ->delete();
                }
            }
        });

        $this->loadEntries();
        $this->updateMode = false;
        $this->showMode = true;

        session()->flash('flash.banner', 'Education information saved successfully!');
        session()->flash('flash.bannerStyle', 'success');
        session(['active_personnel_tab' => 'education']);
    }

    public function getTypeLabelsProperty(): array
    {
        return self::TYPE_LABELS;
    }

    public function render()
    {
        return view('livewire.form.education-entries-form');
    }
}
