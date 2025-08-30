<!-- This code is intended to dashboard Loyalty Award Receipts -->
<div class="mx-5 my-8 p-3">
    <!-- Success Message -->
    @if (session()->has('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center space-x-3 animate-pulse">
        <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-green-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="text-sm font-semibold text-green-800">Successfully Claimed Award!</h4>
            <p class="text-sm text-green-700">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-green-400 hover:text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif
    <div class="flex justify-between items-center">
        <div class="flex space-x-2 items-center">
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
            <!-- Reset Filters Button -->
            <button wire:click="resetFilters"
                class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white rounded text-xs font-medium transition-colors">
                Reset Filters
            </button>
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
            // Count eligible personnels who can actually claim awards
            $eligible10Year = $personnels->getCollection()->filter(function($personnel) {
            $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
            return $personnel->years_of_service >= 10 && $claimedCount == 0;
            })->count();

            $eligible5Year = $personnels->getCollection()->filter(function($personnel) {
            $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
            $maxClaims = $personnel->max_claims ?? 0;
            $yearsOfService = $personnel->years_of_service;

            // Can claim 5-year milestone if they have claimed the 10-year award
            // and have reached the next milestone (15, 20, 25, etc.)
            return $yearsOfService > 10 &&
            $claimedCount > 0 &&
            $claimedCount < $maxClaims &&
                (($yearsOfService - 10) % 5==0);
                })->count();
                @endphp

                <div class="flex space-x-2">
                    @if($eligible10Year > 0)
                    <a href="{{ route('loyalty-awards.export-10year-pdf') }}" target="_blank">
                        <button class="px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white flex items-center gap-2 rounded shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span class="font-semibold tracking-wide text-xs">10-Year Awards ({{ $eligible10Year }})</span>
                        </button>
                    </a>
                    @endif

                    @if($eligible5Year > 0)
                    <a href="{{ route('loyalty-awards.export-5year-pdf') }}" target="_blank">
                        <button class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white flex items-center gap-2 rounded shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span class="font-semibold tracking-wide text-xs">5-Year Milestones ({{ $eligible5Year }})</span>
                        </button>
                    </a>
                    @endif
                </div>
        </div>
    </div>

    <!-- Results Counter -->
    <div class="mt-4 text-sm text-gray-600">
        <p>Showing {{ $personnels->count() }} of {{ $personnels->total() }} personnel records</p>
        @if($search || $selectedSchool || $selectedPosition)
        <p class="text-blue-600 mt-1">
            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
            </svg>
            Filters applied:
            @if($search) <span class="font-medium">Search: "{{ $search }}"</span> @endif
            @if($selectedSchool) <span class="font-medium">School: {{ \App\Models\School::find($selectedSchool)->school_name ?? 'Unknown' }}</span> @endif
            @if($selectedPosition) <span class="font-medium">Position: {{ \App\Models\Position::find($selectedPosition)->title ?? 'Unknown' }}</span> @endif
        </p>
        @endif
    </div>

    <!-- Summary Cards -->
    @php
    $totalPersonnel = $personnels->total();

    // Count personnel who can claim 10-year awards (have 10+ years but haven't claimed yet)
    $canClaim10Year = $personnels->getCollection()->filter(function($personnel) {
    $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
    return $personnel->years_of_service >= 10 && $claimedCount == 0;
    })->count();

    // Count personnel who can claim 5-year milestone awards
    $canClaim5Year = $personnels->getCollection()->filter(function($personnel) {
    $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
    $maxClaims = $personnel->max_claims ?? 0;
    $yearsOfService = $personnel->years_of_service;

    return $yearsOfService > 10 &&
    $claimedCount > 0 &&
    $claimedCount < $maxClaims &&
        (($yearsOfService - 10) % 5==0);
        })->count();

        // Count personnel who have completed all their possible claims
        $completedClaims = $personnels->getCollection()->filter(function($personnel) {
        $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
        $maxClaims = $personnel->max_claims ?? 0;
        return $personnel->years_of_service >= 10 && $claimedCount >= $maxClaims && $maxClaims > 0;
        })->count();

        // Count personnel not eligible yet (less than 10 years)
        $notEligibleYet = $personnels->getCollection()->filter(function($personnel) {
        return $personnel->years_of_service < 10;
            })->count();
            @endphp

            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-100 to-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-800">{{ $totalPersonnel }}</h3>
                            <p class="text-sm text-blue-600">Total Personnel</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-100 to-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.625a7.458 7.458 0 00.981-3.172M9.497 14.625v.375a3.375 3.375 0 002.25 2.25" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-green-800">{{ $canClaim10Year }}</h3>
                            <p class="text-sm text-green-600">Can Claim 10-Year</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-100 to-purple-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-purple-800">{{ $canClaim5Year }}</h3>
                            <p class="text-sm text-purple-600">Can Claim 5-Year</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-100 to-orange-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-orange-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-orange-800">{{ $completedClaims }}</h3>
                            <p class="text-sm text-orange-600">Claims Completed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="mt-5 overflow-x-auto relative">
                <!-- Table loading overlay -->
                <div wire:loading class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
                    <div class="text-center">
                        <svg class="animate-spin h-8 w-8 text-blue-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm text-gray-600">Updating results...</p>
                    </div>
                </div>
                <table class="table-auto w-full">
                    <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                        <tr>
                            <th class="w-1/12 p-2 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <button wire:click="doSort('personnel_id')" class="flex items-center gap-x-2">
                                        <span class="font-semibold text-left">Employee ID</span>
                                        @if ($sortColumn === 'personnel_id')
                                        @if ($sortDirection === 'ASC')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @endif
                                    </button>
                                </div>
                            </th>
                            <th class="w-2/12 p-2 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    <button wire:click="doSort('first_name')" class="flex items-center gap-x-2">
                                        <span class="font-semibold text-left">Name</span>
                                        @if ($sortColumn === 'first_name')
                                        @if ($sortDirection === 'ASC')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @endif
                                    </button>
                                </div>
                            </th>
                            <th class="p-2 whitespace-nowrap w-1/12">
                                <div class="flex items-center gap-x-3">
                                    <button wire:click="doSort('years_of_service')" class="flex items-center gap-x-2">
                                        <span class="font-semibold text-left">Years of Service</span>
                                        @if ($sortColumn === 'years_of_service')
                                        @if ($sortDirection === 'ASC')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @endif
                                    </button>
                                </div>
                            </th>
                            <th class="p-2 whitespace-nowrap w-2/12">
                                <div class="flex items-center gap-x-3">
                                    <button class="flex items-center gap-x-2">
                                        <span class="font-semibold text-left">Eligibility & Amount</span>
                                    </button>
                                </div>
                            </th>

                            <th class="p-2 whitespace-nowrap w-2/12">
                                <div class="flex items-center gap-x-3">
                                    <button wire:click="doSort('position_id')" class="flex items-center gap-x-2">
                                        <span class="font-semibold text-left">Position</span>
                                        @if ($sortColumn === 'position_id')
                                        @if ($sortDirection === 'ASC')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @endif
                                    </button>
                                </div>
                            </th>
                            <th class="p-2 whitespace-nowrap w-1/12">
                                <div class="flex items-center gap-x-3">
                                    <button wire:click="doSort('school_id')" class="flex items-center gap-x-2">
                                        <span class="font-semibold text-left">School Name</span>
                                        @if ($sortColumn === 'school_id')
                                        @if ($sortDirection === 'ASC')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                                        </svg>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        @endif
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                        @endif
                                    </button>
                                </div>
                            </th>
                            <th class="p-2 whitespace-nowrap w-1/12">
                                <div class="font-semibold text-left">Claims Status</div>
                            </th>
                            <th class="p-2 whitespace-nowrap w-1/12">
                                <div class="font-semibold text-left">Actions</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($personnels as $index => $personnel)
                        <tr wire:loading.class="opacity-75" class="text-sm hover:bg-indigo-50 cursor-pointer" data-personnel-id="{{ $personnel->id }}">
                            <td class="p-2 whitespace-nowrap w-1/12">
                                <div class="text-left">{{ $personnel->personnel_id }}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap w-2/12">
                                <div class="text-left capitalize">{{ $personnel->fullName() }}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap w-1/12">
                                <div class="text-left font-medium">{{ $personnel->years_of_service }} years</div>
                            </td>
                            <td class="p-2 whitespace-nowrap w-2/12">
                                @php
                                $yearsOfService = $personnel->years_of_service;
                                $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
                                $maxClaims = $personnel->max_claims ?? 0;
                                $hasUnclaimedAwards = $claimedCount < $maxClaims;

                                    // Determine current eligibility status
                                    $eligibilityText='' ;
                                    $amountText='' ;
                                    $isEligible=false;

                                    if ($yearsOfService>= 10 && $hasUnclaimedAwards) {
                                    $isEligible = true;

                                    // Determine what award they can claim next
                                    if ($claimedCount == 0) {
                                    $eligibilityText = '10 Years Award Available';
                                    $amountText = '₱10,000';
                                    } else {
                                    // They've claimed the 10-year award, check for next milestone
                                    $nextMilestone = 10 + ($claimedCount * 5);
                                    if ($yearsOfService >= $nextMilestone) {
                                    $eligibilityText = $nextMilestone . ' Years Award Available';
                                    $amountText = '₱5,000';
                                    } else {
                                    $isEligible = false;
                                    $eligibilityText = 'Next: ' . $nextMilestone . ' years';
                                    $amountText = '₱5,000';
                                    }
                                    }
                                    } else if ($yearsOfService >= 10 && !$hasUnclaimedAwards) {
                                    $eligibilityText = 'All Awards Claimed';
                                    $amountText = 'Complete';
                                    } else {
                                    $eligibilityText = 'Next: 10 years';
                                    $amountText = '₱10,000';
                                    }
                                    @endphp

                                    @if($isEligible)
                                    <div class="space-y-1">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-green-700 font-medium text-xs">CAN CLAIM</span>
                                        </div>
                                        <div class="text-xs text-gray-600">{{ $eligibilityText }}</div>
                                        <div class="text-xs font-semibold text-green-600">{{ $amountText }}</div>
                                    </div>
                                    @elseif($yearsOfService >= 10 && !$hasUnclaimedAwards)
                                    <div class="space-y-1">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                            <span class="text-blue-700 font-medium text-xs">COMPLETED</span>
                                        </div>
                                        <div class="text-xs text-gray-600">{{ $eligibilityText }}</div>
                                        <div class="text-xs text-blue-600">{{ $amountText }}</div>
                                    </div>
                                    @else
                                    <div class="space-y-1">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-gray-400 rounded-full mr-2"></div>
                                            <span class="text-gray-600 font-medium text-xs">NOT ELIGIBLE</span>
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $eligibilityText }}</div>
                                        <div class="text-xs text-gray-500">{{ $amountText }}</div>
                                    </div>
                                    @endif
                            </td>


                            <td class="p-2 whitespace-nowrap w-2/12">
                                <div class="text-left capitalize">{{ $personnel->position->title ?? 'N/A' }}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap w-1/12">
                                <div class="text-left">{{ $personnel->school->school_name ?? 'N/A' }}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap w-1/12 claims-status-cell">
                                @php
                                $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
                                $maxClaims = $personnel->max_claims ?? 0;
                                $claimedAmount = 0;

                                // Calculate claimed amount
                                if ($claimedCount > 0) {
                                $claimedAmount = 10000; // First claim
                                if ($claimedCount > 1) {
                                $claimedAmount += ($claimedCount - 1) * 5000; // Additional claims
                                }
                                }
                                @endphp

                                <div class="text-center space-y-1">
                                    <div class="font-semibold text-sm claims-count">{{ $claimedCount }} / {{ $maxClaims }}</div>
                                    @if($claimedAmount > 0)
                                    <div class="text-xs text-green-600 font-medium claims-amount">₱{{ number_format($claimedAmount) }}</div>
                                    @else
                                    <div class="text-xs text-gray-400 claims-amount">₱0</div>
                                    @endif
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap w-1/12">
                                @php
                                $canClaim = ($personnel->loyalty_award_claim_count ?? 0) < ($personnel->max_claims ?? 0);
                                    $availableClaims = $personnel->available_claims ?? [];
                                    @endphp

                                    <div class="flex items-center space-x-2">
                                        @php
                                        $showAwardsButton = $personnel->years_of_service >= 10;
                                        @endphp

                                        @if($showAwardsButton)
                                        <a href="{{ route('loyalty-awards.show', ['personnel' => $personnel->id]) }}">
                                            <button class="px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-xs font-semibold shadow-md transition-all duration-200 flex items-center space-x-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m-9 0a3 3 0 00-3 3h15a3 3 0 00-3-3m-9 0v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.625a7.458 7.458 0 00.981-3.172M9.497 14.625v.375a3.375 3.375 0 002.25 2.25" />
                                                </svg>
                                                <span>Awards</span>
                                            </button>
                                        </a>
                                        @else
                                        <span class="px-3 py-1.5 bg-gray-100 text-gray-500 rounded-lg text-xs font-medium">
                                            Not Eligible
                                        </span>
                                        @endif

                                        <a href="{{ route('personnels.show', ['personnel' => $personnel->id]) }}">
                                            <button class="px-2 py-1.5 bg-white font-medium text-xs tracking-wider rounded-md border border-gray-300 hover:bg-gray-50 text-gray-700 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 inline">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </button>
                                        </a>
                                    </div>
                            </td>
                        </tr>
                        @endforeach
                        @if ($personnels->isEmpty())
                        <tr wire:loading.class="opacity-75">
                            <td colspan="7" class="p-2 w-full text-center">No personnel found</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                {{ $personnels->links() }}
            </div>
</div>

<script>
    // Listen for refresh events
    document.addEventListener('livewire:refresh', function() {
        // Trigger a Livewire refresh
        if (typeof Livewire !== 'undefined') {
            Livewire.dispatch('$refresh');
        }
    });
</script>
