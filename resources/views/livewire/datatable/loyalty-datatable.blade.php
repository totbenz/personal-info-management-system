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
                    <th class="p-2 whitespace-nowrap w-2/12">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Eligibility & Amount</span>
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
                                <span class="font-semibold text-left">Claims Status</span>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Actions</span>
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
                    <td class="p-2 whitespace-nowrap w-2/12">
                        @php
                        $yearsOfService = $personnel->years_of_service;
                        $isEligible = false;
                        $eligibilityText = '';
                        $amountText = '';

                        if ($yearsOfService >= 10) {
                            // Eligible at 10 years, then every 5 years after (15, 20, 25, etc.)
                            $isEligible = ($yearsOfService == 10) || (($yearsOfService - 10) % 5 == 0);
                            
                            if ($isEligible) {
                                if ($yearsOfService == 10) {
                                    $eligibilityText = '10 Years Award';
                                    $amountText = '₱10,000';
                                } else {
                                    $eligibilityText = $yearsOfService . ' Years Award';
                                    $amountText = '₱5,000';
                                }
                            } else {
                                $nextEligible = $yearsOfService + (5 - (($yearsOfService - 10) % 5));
                                $eligibilityText = 'Next: ' . $nextEligible . ' years';
                                $amountText = '₱5,000';
                            }
                        } else {
                            $eligibilityText = 'Next: 10 years';
                            $amountText = '₱10,000';
                        }
                        @endphp

                        @if($isEligible)
                        <div class="space-y-1">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-green-700 font-medium text-xs">ELIGIBLE</span>
                            </div>
                            <div class="text-xs text-gray-600">{{ $eligibilityText }}</div>
                            <div class="text-xs font-semibold text-green-600">{{ $amountText }}</div>
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
                    <td class="p-2 whitespace-nowrap w-1/12">
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
                            <div class="font-semibold text-sm">{{ $claimedCount }} / {{ $maxClaims }}</div>
                            @if($claimedAmount > 0)
                            <div class="text-xs text-green-600 font-medium">₱{{ number_format($claimedAmount) }}</div>
                            @else
                            <div class="text-xs text-gray-400">₱0</div>
                            @endif
                        </div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        @php
                        $canClaim = ($personnel->loyalty_award_claim_count ?? 0) < ($personnel->max_claims ?? 0);
                        $availableClaims = $personnel->available_claims ?? [];
                        @endphp
                        
                        <div class="flex items-center space-x-2">
                            @if($canClaim && !empty($availableClaims))
                                <button x-data="{}" x-on:click="$openModal('loyalty-claims-modal-{{ $personnel->id }}')" 
                                        class="px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-xs font-semibold shadow-md transition-all duration-200 flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.625a7.458 7.458 0 00.981-3.172M9.497 14.625v.375a3.375 3.375 0 002.25 2.25" />
                                    </svg>
                                    <span>Awards</span>
                                </button>
                            @else
                                <span class="px-3 py-1.5 bg-gray-100 text-gray-500 rounded-lg text-xs font-medium">
                                    {{ $canClaim ? 'No Claims' : 'Completed' }}
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
    
    <!-- Loyalty Claims Modals -->
    @foreach ($personnels as $personnel)
        @php
        $canClaim = ($personnel->loyalty_award_claim_count ?? 0) < ($personnel->max_claims ?? 0);
        $availableClaims = $personnel->available_claims ?? [];
        $claimedCount = $personnel->loyalty_award_claim_count ?? 0;
        $maxClaims = $personnel->max_claims ?? 0;
        @endphp
        
        @if(!empty($availableClaims))
        <x-modal name="loyalty-claims-modal-{{ $personnel->id }}" :show="false" max-width="2xl">
            <div class="bg-white rounded-lg shadow-xl p-6 max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Loyalty Award Claims</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $personnel->fullName() }} - {{ $personnel->personnel_id }}</p>
                    </div>
                    <button @click="show = false" 
                            class="p-2 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <!-- Personnel Info Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Years of Service:</span>
                            <span class="font-semibold text-gray-900 ml-2">{{ $personnel->years_of_service }} years</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Claims Status:</span>
                            <span class="font-semibold text-gray-900 ml-2">{{ $claimedCount }} / {{ $maxClaims }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Position:</span>
                            <span class="font-semibold text-gray-900 ml-2">{{ $personnel->position->title ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">School:</span>
                            <span class="font-semibold text-gray-900 ml-2">{{ $personnel->school->school_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Available Claims - Scrollable Content -->
                <div class="flex-1 overflow-y-auto pr-2">
                    <div class="space-y-4">
                        <h4 class="font-medium text-gray-900 mb-3 sticky top-0 bg-white py-2 border-b border-gray-100">Loyalty Service Awards:</h4>
                        
                        @foreach($availableClaims as $index => $claim)
                        <div class="border border-gray-200 rounded-lg p-4 {{ $claim['is_claimed'] ? 'bg-gray-50' : 'hover:bg-gray-50' }} transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if($claim['is_claimed'])
                                            <div class="w-10 h-10 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            @else
                                            <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-500 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.625a7.458 7.458 0 00.981-3.172M9.497 14.625v.375a3.375 3.375 0 002.25 2.25" />
                                                </svg>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2">
                                                <h5 class="font-semibold {{ $claim['is_claimed'] ? 'text-gray-600' : 'text-gray-900' }}">{{ $claim['label'] }}</h5>
                                                @if($claim['is_claimed'])
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Claimed
                                                </span>
                                                @endif
                                            </div>
                                            <p class="text-sm {{ $claim['is_claimed'] ? 'text-gray-500' : 'text-gray-600' }}">Service milestone achievement award</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <div class="text-2xl font-bold {{ $claim['is_claimed'] ? 'text-gray-500' : 'text-green-600' }}">₱{{ number_format($claim['amount']) }}</div>
                                        <div class="text-xs text-gray-500">Award Amount</div>
                                    </div>
                                    @if($claim['is_claimed'])
                                    <div class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-semibold flex items-center space-x-2 cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Award Claimed</span>
                                    </div>
                                    @else
                                    <button x-data="{}" 
                                            x-on:click="$openModal('confirm-claim-modal-{{ $personnel->id }}-{{ $claim['claim_index'] }}')" 
                                            class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-semibold shadow-md transition-all duration-200 flex items-center space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Claim Award</span>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Footer - Fixed at bottom -->
                <div class="flex-shrink-0 mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            @php
                                $totalClaimed = collect($availableClaims)->where('is_claimed', true)->sum('amount');
                                $totalAvailable = collect($availableClaims)->where('is_claimed', false)->sum('amount');
                            @endphp
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium">Total Claimed:</span>
                                    <span class="text-lg font-bold text-gray-600 ml-2">₱{{ number_format($totalClaimed) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Available to Claim:</span>
                                    <span class="text-lg font-bold text-green-600 ml-2">₱{{ number_format($totalAvailable) }}</span>
                                </div>
                            </div>
                        </div>
                        <button @click="show = false" 
                                class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-colors shadow-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </x-modal>
        
        <!-- Confirmation Modals for Each Claim -->
        @foreach($availableClaims as $index => $claim)
        <x-modal name="confirm-claim-modal-{{ $personnel->id }}-{{ $index }}" :show="false" max-width="md">
            <div class="bg-white rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-yellow-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                </div>
                
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Award Claim</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Are you sure you want to claim the <strong>{{ $claim['label'] }}</strong> for <strong>{{ $personnel->fullName() }}</strong>?
                    </p>
                    
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-lg font-bold text-green-700">₱{{ number_format($claim['amount']) }}</span>
                        </div>
                        <p class="text-xs text-green-600 mt-1">Award Amount</p>
                    </div>
                    
                    <p class="text-xs text-gray-500">
                        <strong>Note:</strong> This action cannot be undone. The award will be marked as claimed.
                    </p>
                </div>
                
                <div class="flex items-center justify-center space-x-3">
                    <button @click="show = false" 
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                        Cancel
                    </button>
                    <button wire:click="claimLoyaltyAward({{ $personnel->id }}, {{ $index }})" 
                            @click="show = false; $closeModal('loyalty-claims-modal-{{ $personnel->id }}')" 
                            class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-semibold shadow-md transition-all duration-200 flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Confirm Claim</span>
                    </button>
                </div>
            </div>
        </x-modal>
        @endforeach
        
        @endif
    @endforeach
</div>
