<?php

namespace App\Livewire\Datatable;

use App\Models\Personnel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

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

    public function render()
    {
        $currentYear = Carbon::now()->year;
        $personnels = Personnel::with(['school', 'position'])
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
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);

        // Calculate loyalty award eligibility for each personnel
        $personnels->getCollection()->transform(function ($personnel) {
            $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);
            $personnel->years_of_service = $yearsOfService;
            $personnel->can_claim = $this->canClaimLoyaltyAward($yearsOfService);
            $personnel->next_award_year = $this->getNextAwardYear($yearsOfService);
            return $personnel;
        });

        return view('livewire.datatable.loyalty-datatable', [
            'personnels' => $personnels
        ]);
    }
}
