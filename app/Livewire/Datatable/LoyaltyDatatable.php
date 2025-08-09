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

            // Calculate max possible claims and available claims
            $personnel->max_claims = $this->calculateMaxClaims($yearsOfService);
            $personnel->available_claims = $this->getAvailableClaims($personnel);
            $personnel->total_claimable_amount = $this->calculateTotalClaimableAmount($yearsOfService);

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

    // Calculate max possible claims based on years of service
    private function calculateMaxClaims($yearsOfService)
    {
        if ($yearsOfService < 10) return 0;
        return 1 + floor(max(0, $yearsOfService - 10) / 5);
    }

    // Get all claims (both claimed and available) with status for a personnel
    private function getAvailableClaims($personnel)
    {
        $yearsOfService = $personnel->years_of_service;
        $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
        $maxClaims = $this->calculateMaxClaims($yearsOfService);
        
        $allClaims = [];
        
        for ($i = 0; $i < $maxClaims; $i++) {
            $isClaimed = $i < $claimedCount;
            
            if ($i == 0) {
                // First claim (10 years)
                $allClaims[] = [
                    'label' => '10 Years Service Award',
                    'amount' => 10000,
                    'years' => 10,
                    'is_claimed' => $isClaimed,
                    'claim_index' => $i
                ];
            } else {
                // Subsequent claims (every 5 years)
                $years = 10 + ($i * 5);
                $allClaims[] = [
                    'label' => $years . ' Years Service Award',
                    'amount' => 5000,
                    'years' => $years,
                    'is_claimed' => $isClaimed,
                    'claim_index' => $i
                ];
            }
        }
        
        return $allClaims;
    }

    // Calculate total claimable amount
    private function calculateTotalClaimableAmount($yearsOfService)
    {
        $maxClaims = $this->calculateMaxClaims($yearsOfService);
        if ($maxClaims == 0) return 0;
        
        $totalAmount = 10000; // First 10 years
        if ($maxClaims > 1) {
            $totalAmount += ($maxClaims - 1) * 5000; // Each subsequent 5 years
        }
        
        return $totalAmount;
    }

    public function claimLoyaltyAward($personnelId, $claimIndex = null)
    {
        $personnel = Personnel::find($personnelId);
        if (!$personnel) return;
        
        $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);
        $maxClaims = $this->calculateMaxClaims($yearsOfService);
        $currentClaims = $personnel->loyalty_award_claim_count ?? 0;
        
        if ($currentClaims < $maxClaims) {
            $personnel->loyalty_award_claim_count = $currentClaims + 1;
            $personnel->save();
            
            // Determine the amount claimed
            $amount = ($currentClaims == 0) ? 10000 : 5000;
            $years = ($currentClaims == 0) ? 10 : (10 + ($currentClaims * 5));
            
            session()->flash('success', "Loyalty award claimed! ₱" . number_format($amount) . " for {$years} years of service.");
        }
    }

    public function exportPdf()
    {
        $personnels = $this->getEligiblePersonnels(false); // not paginated, all eligible
        $date = now()->format('F d, Y');
        
        // Fetch signatures
        $schools_division_superintendent_signature = \App\Models\Signature::where('position', 'Schools Division Superintendent')->first();
        $oic_assistant_schools_division_superintendent_signature = \App\Models\Signature::where('position', 'OIC Assistant Schools Division Superintendent')->first();
        $administrative_officer_vi_signature = \App\Models\Signature::where('position', 'Administrative Officer VI (HRMO II)')->first();
        
        $pdf = Pdf::loadView('pdf.loyalty-awards', [
            'personnels' => $personnels,
            'date' => $date,
            'schools_division_superintendent_signature' => $schools_division_superintendent_signature,
            'oic_assistant_schools_division_superintendent_signature' => $oic_assistant_schools_division_superintendent_signature,
            'administrative_officer_vi_signature' => $administrative_officer_vi_signature,
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
