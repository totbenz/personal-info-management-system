<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing CTO data from approved CTO requests to the new CTO entries system
        $approvedCTORequests = DB::table('cto_requests')
            ->where('status', 'approved')
            ->whereNotNull('approved_at')
            ->get();

        foreach ($approvedCTORequests as $ctoRequest) {
            $earnedDate = Carbon::parse($ctoRequest->approved_at)->toDateString();
            $expiryDate = Carbon::parse($ctoRequest->approved_at)->addYear()->toDateString();
            $daysEarned = $ctoRequest->requested_hours / 8; // Convert hours to days

            // Check if entry already exists to avoid duplicates
            $existingEntry = DB::table('cto_entries')
                ->where('cto_request_id', $ctoRequest->id)
                ->first();

            if (!$existingEntry) {
                DB::table('cto_entries')->insert([
                    'school_head_id' => $ctoRequest->school_head_id,
                    'cto_request_id' => $ctoRequest->id,
                    'days_earned' => $daysEarned,
                    'days_remaining' => $daysEarned,
                    'earned_date' => $earnedDate,
                    'expiry_date' => $expiryDate,
                    'is_expired' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Update school head leave balances to reflect the new CTO entries system
        $schoolHeads = DB::table('school_head_leaves')
            ->where('leave_type', 'Compensatory Time Off')
            ->get();

        foreach ($schoolHeads as $leaveRecord) {
            // Calculate actual available days from CTO entries
            $totalAvailable = DB::table('cto_entries')
                ->where('school_head_id', $leaveRecord->school_head_id)
                ->where('days_remaining', '>', 0)
                ->where('is_expired', false)
                ->where('expiry_date', '>=', now()->toDateString())
                ->sum('days_remaining');

            $totalEarned = DB::table('cto_entries')
                ->where('school_head_id', $leaveRecord->school_head_id)
                ->sum('days_earned');

            // Update the leave record
            DB::table('school_head_leaves')
                ->where('id', $leaveRecord->id)
                ->update([
                    'available' => $totalAvailable,
                    'ctos_earned' => $totalEarned,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove migrated CTO entries (keep only manually created ones if any)
        DB::table('cto_entries')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('cto_requests')
                      ->whereColumn('cto_requests.id', 'cto_entries.cto_request_id')
                      ->where('cto_requests.status', 'approved');
            })
            ->delete();
    }
};
