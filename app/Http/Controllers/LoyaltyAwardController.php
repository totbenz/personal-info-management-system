<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LoyaltyAwardController extends Controller
{
    private function getPersonnels()
    {
        $query = Personnel::with(['school', 'position'])
            ->whereNotNull('employment_start');

        $personnels = $query->get();

        // Calculate loyalty award eligibility for each personnel
        $processedPersonnels = $personnels->map(function ($personnel) {
            $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);
            $personnel->years_of_service = $yearsOfService;
            $personnel->can_claim = $this->canClaimLoyaltyAward($yearsOfService);
            $personnel->next_award_year = $this->getNextAwardYear($yearsOfService);
            $personnel->award_type = $this->getAwardType($yearsOfService);

            // Calculate max possible claims and available claims
            $personnel->max_claims = $this->calculateMaxClaims($yearsOfService);
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
        });

        return $processedPersonnels;
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

    public function getAwardType($yearsOfService)
    {
        if ($yearsOfService == 10) {
            return '10 Years Award';
        } elseif ($yearsOfService > 10 && ($yearsOfService - 10) % 5 == 0) {
            return $yearsOfService . ' Years Award';
        }
        return 'Not Eligible';
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

    public function export10YearPdf()
    {
        $personnels = $this->getPersonnels();

        // Filter for personnel who can claim 10-year awards (have 10+ years but haven't claimed yet)
        $personnels = $personnels->filter(function ($personnel) {
            $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
            return $personnel->years_of_service >= 10 && $claimedCount == 0;
        });

        $date = now()->format('F d, Y');
        $exportType = '10 Years Service Awardees';

        // Fetch signatures
        $schools_division_superintendent_signature = \App\Models\Signature::where('position', 'Schools Division Superintendent')->first();
        $oic_assistant_schools_division_superintendent_signature = \App\Models\Signature::where('position', 'OIC Assistant Schools Division Superintendent')->first();
        $administrative_officer_vi_signature = \App\Models\Signature::where('position', 'Administrative Officer VI (HRMO II)')->first();

        $pdf = Pdf::loadView('pdf.loyalty-awards-10year', [
            'personnels' => $personnels,
            'date' => $date,
            'exportType' => $exportType,
            'schools_division_superintendent_signature' => $schools_division_superintendent_signature,
            'oic_assistant_schools_division_superintendent_signature' => $oic_assistant_schools_division_superintendent_signature,
            'administrative_officer_vi_signature' => $administrative_officer_vi_signature,
        ])->setPaper('a4', 'portrait');

        $filename = '10year_loyalty_awardees_' . now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function export5YearPdf()
    {
        $personnels = $this->getPersonnels();

        // Filter for personnel who can claim 5-year milestone awards
        $personnels = $personnels->filter(function ($personnel) {
            $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
            $maxClaims = $this->calculateMaxClaims($personnel->years_of_service);
            $yearsOfService = $personnel->years_of_service;

            // Can claim 5-year milestone if they have claimed the 10-year award
            // and have reached the next milestone (15, 20, 25, etc.)
            return $yearsOfService > 10 &&
                $claimedCount > 0 &&
                $claimedCount < $maxClaims &&
                (($yearsOfService - 10) % 5 == 0);
        });

        $date = now()->format('F d, Y');
        $exportType = '5-Year Milestone Service Awardees';

        // Fetch signatures
        $schools_division_superintendent_signature = \App\Models\Signature::where('position', 'Schools Division Superintendent')->first();
        $oic_assistant_schools_division_superintendent_signature = \App\Models\Signature::where('position', 'OIC Assistant Schools Division Superintendent')->first();
        $administrative_officer_vi_signature = \App\Models\Signature::where('position', 'Administrative Officer VI (HRMO II)')->first();

        $pdf = Pdf::loadView('pdf.loyalty-awards-5year', [
            'personnels' => $personnels,
            'date' => $date,
            'exportType' => $exportType,
            'schools_division_superintendent_signature' => $schools_division_superintendent_signature,
            'oic_assistant_schools_division_superintendent_signature' => $oic_assistant_schools_division_superintendent_signature,
            'administrative_officer_vi_signature' => $administrative_officer_vi_signature,
        ])->setPaper('a4', 'portrait');

        $filename = '5year_milestone_loyalty_awardees_' . now()->format('Y-m-d') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function claim(Request $request)
    {
        try {
            $request->validate([
                'personnel_id' => 'required|integer',
                'claim_index' => 'required|integer'
            ]);

            $personnel = Personnel::find($request->personnel_id);
            if (!$personnel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Personnel not found'
                ], 404);
            }

            $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);
            $maxClaims = $this->calculateMaxClaims($yearsOfService);
            $currentClaims = $personnel->loyalty_award_claim_count ?? 0;

            // Check if personnel is eligible to claim this specific award
            if ($currentClaims < $maxClaims && $request->claim_index !== null) {
                // Get available claims to determine which award is being claimed
                $availableClaims = $this->getAvailableClaims($personnel, $yearsOfService);

                if (isset($availableClaims[$request->claim_index]) && !$availableClaims[$request->claim_index]['is_claimed']) {
                    $personnel->loyalty_award_claim_count = $currentClaims + 1;
                    $personnel->save();

                    $claimedAward = $availableClaims[$request->claim_index];
                    $amount = $claimedAward['amount'];
                    $years = $claimedAward['years'];

                    // Store the award record in the award_received table
                    \App\Models\AwardReceived::create([
                        'personnel_id' => $personnel->id,
                        'award_name' => $claimedAward['label'],
                        'description' => 'Loyalty Service Award for ' . $years . ' years of service',
                        'award_date' => now(),
                        'awarding_body' => 'Department of Education - Schools Division of Baybay City',
                        'reward_date' => now(),
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => "Loyalty award claimed! ₱" . number_format($amount) . " for {$years} years of service."
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Award not available or already claimed'
                    ], 400);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Personnel not eligible for this award'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the claim: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get all claims (both claimed and available) with status for a personnel
    private function getAvailableClaims($personnel, $yearsOfService)
    {
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

    public function show(Personnel $personnel)
    {
        // Calculate loyalty award eligibility for the personnel
        $yearsOfService = $this->calculateYearsOfService($personnel->employment_start);
        $personnel->years_of_service = $yearsOfService;
        $personnel->max_claims = $this->calculateMaxClaims($yearsOfService);
        $personnel->available_claims = $this->getAvailableClaims($personnel, $yearsOfService);

        // Paginate the available claims (show 5 per page)
        $perPage = 5;
        $currentPage = request()->get('page', 1);
        $totalClaims = count($personnel->available_claims);
        $offset = ($currentPage - 1) * $perPage;

        $paginatedClaims = array_slice($personnel->available_claims, $offset, $perPage);

        // Create pagination data
        $lastPage = ceil($totalClaims / $perPage);
        $pagination = [
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'per_page' => $perPage,
            'total' => $totalClaims,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $totalClaims),
            'has_more_pages' => $currentPage < $lastPage,
            'has_previous_pages' => $currentPage > 1,
        ];

        return view('loyalty-awards.show', compact('personnel', 'paginatedClaims', 'pagination'));
    }
}
