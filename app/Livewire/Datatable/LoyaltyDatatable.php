<?php

namespace App\Livewire\Datatable;

use App\Models\Personnel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LoyaltyDatatable extends Component
{
    use WithPagination;

    public $selectedSchool = null, $selectedCategory = null, $selectedClassification = null, $selectedPosition = null, $selectedJobStatus = null;
    public $search = '';
    public $sortDirection = 'ASC';
    public $sortColumn = 'id';

    public function doSort($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection ? 'DESC' : 'ASC';
            return;
        }
        $this->sortColumn = $column;
    }


    private function getEligiblePersonnels($paginated = true)
    {
        $query = Personnel::with(['school', 'position'])
            ->whereNotNull('employment_start')
            ->when($this->selectedSchool, function ($query) {
                $query->where('school_id', $this->selectedSchool);
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category', $this->selectedCategory);
            })
            ->when($this->selectedPosition, function ($query) {
                $query->where('position_id', $this->selectedPosition);
            })
            ->when($this->selectedJobStatus, function ($query) {
                $query->where('job_status', $this->selectedJobStatus);
            })
            ->search($this->search)
            ->orderBy($this->sortColumn, $this->sortDirection);

        $personnels = $paginated ? $query->paginate(10) : $query->get();

        // Calculate loyalty award eligibility for each personnel
        $collection = $paginated ? $personnels->getCollection() : $personnels;

        $eligiblePersonnels = $collection->map(function ($personnel) {
            $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);
            $personnel->years_of_service = $yearsOfService;
            $personnel->can_claim = $this->canClaimLoyaltyAward($yearsOfService);
            $personnel->next_award_year = $this->getNextAwardYear($yearsOfService);

            // Sanitize fields to prevent malformed UTF-8
            $personnel->first_name = $this->sanitizeUtf8($personnel->first_name);
            $personnel->middle_name = $this->sanitizeUtf8($personnel->middle_name);
            $personnel->last_name = $this->sanitizeUtf8($personnel->last_name);
            $personnel->name_ext = $this->sanitizeUtf8($personnel->name_ext);

            if (optional($personnel->position)->title) {
                $personnel->position->title = $this->sanitizeUtf8($personnel->position->title);
            }

            if (optional($personnel->school)->school_name) {
                $personnel->school->school_name = $this->sanitizeUtf8($personnel->school->school_name);
            }

            return $personnel;
        })->filter(function ($personnel) {
            return $personnel->can_claim;
        });

        if ($paginated) {
            $personnels->setCollection($eligiblePersonnels);
            return $personnels;
        }

        return $eligiblePersonnels;
    }

    private function sanitizeUtf8($string)
    {
        return mb_convert_encoding($string ?? '', 'UTF-8', 'UTF-8');
    }

    public function calculateYearsOfService($employmentStart)
    {
        if (!$employmentStart) {
            return 0;
        }

        $startDate = Carbon::parse($employmentStart);
        $currentDate = Carbon::now();

        return $startDate->diffInYears($currentDate);
    }

    public function canClaimLoyaltyAward($yearsOfService)
    {
        if ($yearsOfService < 10) {
            return false;
        }

        // First award at 10 years
        if ($yearsOfService == 10) {
            return true;
        }

        // After 10 years, awards every 5 years
        if ($yearsOfService > 10) {
            return ($yearsOfService - 10) % 5 == 0;
        }

        return false;
    }

    public function getNextAwardYear($yearsOfService)
    {
        if ($yearsOfService < 10) {
            return 10;
        }

        // Calculate next 5-year milestone after 10 years
        $yearsSinceFirstAward = $yearsOfService - 10;
        $nextMilestone = ceil(($yearsSinceFirstAward + 1) / 5) * 5;
        return 10 + $nextMilestone;
    }

    public function exportPdf()
    {
        $personnels = $this->getEligiblePersonnels(false); // not paginated, all eligible
        $date = now()->format('F d, Y');
        $pdf = Pdf::loadView('pdf.loyalty-awards', [
            'personnels' => $personnels,
            'date' => $date,
        ])->setPaper('a4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'loyalty_awardees.pdf');
    }

    public function render()
    {
        $personnels = $this->getEligiblePersonnels(true);

        return view('livewire.datatable.loyalty-datatable', [
            'personnels' => $personnels
        ]);
    }
}
