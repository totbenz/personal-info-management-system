<!-- This code is intended to dashboard Loyalty Award Receipts -->
<div class="mx-5 my-8 p-3">
    <div class="flex justify-between">
        <div class="flex space-x-2">

            <!--Position Dropdown  -->
            <div class="w-[12rem] px-0.5 text-xs">
                <x-select
                    wire:model.live.debounce.300ms="selectedPosition"
                    placeholder="Select a position"
                    :async-data="route('api.positions.index')"
                    option-label="title"
                    option-value="id" />
            </div>
            <!-- School Dropdown -->
            <div class="w-[11rem] px-0.5 text-xs">
                <x-select
                    wire:model.live.debounce.300ms="selectedSchool"
                    placeholder="Select a school"
                    :async-data="route('api.schools.index')"
                    option-label="school_id"
                    option-value="id"
                    option-description="school_name" />
            </div>
        </div>
        <!-- Search Input -->
        <div class="flex space-x-2 relative">
            <input
                type="text"
                wire:model.live.debounce.150ms="search"
                placeholder="Search ID..."
                class="w-[16rem] px-2 py-1 border rounded text-sm pl-10" />
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-1 top-1/2 transform -translate-y-1/2 h-4 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                </path>
            </svg>
            @php
            // Count eligible personnels
            $eligibleCount = $personnels->filter(function($personnel) {
            $yearsOfService = $personnel->years_of_service;
            return $yearsOfService >= 10 && ($yearsOfService == 10 || (($yearsOfService - 10) % 5 == 0));
            })->count();
            @endphp

            @if($eligibleCount > 0)
            <a href="{{ route('loyalty-awards.export-pdf') }}" target="_blank">
                <button class="ml-2 px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white flex items-center gap-2 rounded shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span class="font-semibold tracking-wide">Export PDF</span>
                </button>
            </a>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="mt-5 overflow-x-auto">
        <table class="table-auto w-full">
            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                <tr>
                    <th wire:click="doSort('personnel_id')" class="w-1/12 p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="personnel_id">
                                <span class="font-semibold text-left">Employee ID</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th wire:click="doSort('personnel_id')" class="w-2/12 p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="personnel_id">
                                <span class="font-semibold text-left">Name</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12" wire:click="doSort('years_of_service')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="years_of_service">
                                <span class="font-semibold text-left">Years of Service</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Eligibility Status</span>
                            </button>
                        </div>
                    </th>

                    <th class="p-2 whitespace-nowrap w-2/12" wire:click="doSort('position_id')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="position_id">
                                <span class="font-semibold text-left">Position</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12" wire:click="doSort('school_id')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="school_id">
                                <span class="font-semibold text-left">School Name</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Claims</span>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Action</span>
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($personnels as $index => $personnel)
                <tr wire:loading.class="opacity-75" class="text-sm hover:bg-indigo-50 cursor-pointer">
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-left">{{ $personnel->personnel_id }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        <div class="text-left capitalize">{{ $personnel->fullName() }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-left font-medium">{{ $personnel->years_of_service }} years</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        @php
                        $yearsOfService = $personnel->years_of_service;
                        $isEligible = false;

                        if ($yearsOfService >= 10) {
                        // Eligible at 10 years, then every 5 years after (15, 20, 25, etc.)
                        $isEligible = ($yearsOfService == 10) || (($yearsOfService - 10) % 5 == 0);
                        }
                        @endphp

                        @if($isEligible)
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-green-700 font-medium text-xs">ELIGIBLE</span>
                        </div>
                        @else
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                            <span class="text-gray-600 font-medium text-xs">NOT ELIGIBLE</span>
                        </div>
                        @endif
                    </td>


                    <td class="p-2 whitespace-nowrap w-2/12">
                        <div class="text-left capitalize">{{ $personnel->position->title ?? 'N/A' }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-left">{{ $personnel->school->school_name ?? 'N/A' }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-center font-semibold">
                            {{ $personnel->loyalty_award_claim_count ?? 0 }} / {{ $personnel->max_claims ?? 0 }}
                        </div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        @php
                        $canClaim = ($personnel->loyalty_award_claim_count ?? 0) < ($personnel->max_claims ?? 0);
                            @endphp
                            <div class="flex justify-center gap-2">
                                @if($canClaim)
                                <button wire:click.stop="claimLoyaltyAward({{ $personnel->id }})" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold shadow disabled:opacity-50" @if(!$canClaim) disabled @endif>
                                    Claim
                                </button>
                                @else
                                <span class="px-2 py-1 bg-gray-300 text-gray-600 rounded text-xs font-semibold">Claimed</span>
                                @endif
                                <a href="{{ route('personnels.show', ['personnel' => $personnel->id]) }}" class="ml-2">
                                    <button class="py-1 px-2 bg-white font-medium text-xs tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300">
                                        View
                                    </button>
                                </a>
                            </div>
                    </td>
                </tr>
                @endforeach
                @if ($personnels->isEmpty())
                <tr wire:loading.class="opacity-75">
                    <td colspan="11" class="p-2 w-full text-center">No personnel found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $personnels->links() }}
    </div>
</div>
