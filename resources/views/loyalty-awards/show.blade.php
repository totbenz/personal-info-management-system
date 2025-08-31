<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Loyalty Award Claims
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="mt-2 text-gray-600">Manage and claim loyalty service awards</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('personnels.show', ['personnel' => $personnel->id]) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Back to Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center space-x-3 animate-pulse">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-green-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-green-800">Successfully Claimed Award!</h4>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Personnel Info Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Employee ID</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $personnel->personnel_id }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Years of Service</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $personnel->years_of_service }} years</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-purple-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.625a7.458 7.458 0 00.981-3.172M9.497 14.625v.375a3.375 3.375 0 002.25 2.25" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Claims Status</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $personnel->loyalty_award_claim_count ?? 0 }} / {{ $personnel->max_claims }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-indigo-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Position</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $personnel->position->title ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Full Name</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $personnel->fullName() }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">School</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $personnel->school->school_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Claims Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Loyalty Service Awards</h2>
                            <p class="mt-1 text-sm text-gray-600">Available awards based on your years of service</p>
                        </div>
                        @if(!empty($paginatedClaims) && $pagination['last_page'] > 1)
                        <div class="text-sm text-gray-500">
                            Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}
                        </div>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    @if(empty($paginatedClaims))
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.625a7.458 7.458 0 00.981-3.172M9.497 14.625v.375a3.375 3.375 0 002.25 2.25" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Awards Available</h3>
                        <p class="text-gray-500">You need at least 10 years of service to be eligible for loyalty awards.</p>
                    </div>
                    @else
                    <div class="space-y-4">
                        @foreach($paginatedClaims as $index => $claim)
                        <div class="border border-gray-200 rounded-lg p-6 {{ $claim['is_claimed'] ? 'bg-gray-50' : 'hover:bg-gray-50' }} transition-colors" data-claim-index="{{ $index }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($claim['is_claimed'])
                                            <div class="w-12 h-12 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            @else
                                            <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-green-500 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.625a7.458 7.458 0 00.981-3.172M9.497 14.625v.375a3.375 3.375 0 002.25 2.25" />
                                                </svg>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <h3 class="text-xl font-semibold {{ $claim['is_claimed'] ? 'text-gray-600' : 'text-gray-900' }}">{{ $claim['label'] }}</h3>
                                                @if($claim['is_claimed'])
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Claimed
                                                </span>
                                                @endif
                                            </div>
                                            <p class="text-gray-600 mt-1">Service milestone achievement award</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-6">
                                    <div class="text-right">
                                        <div class="text-3xl font-bold {{ $claim['is_claimed'] ? 'text-gray-500' : 'text-green-600' }}">₱{{ number_format($claim['amount']) }}</div>
                                        <div class="text-sm text-gray-500">Award Amount</div>
                                    </div>
                                    @if($claim['is_claimed'])
                                    <div class="px-6 py-3 bg-gray-100 text-gray-600 rounded-lg font-semibold flex items-center space-x-2 cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Award Claimed</span>
                                    </div>
                                    @else
                                    <button
                                        data-personnel-id="{{ $personnel->id }}"
                                        data-claim-index="{{ $index }}"
                                        data-award-name="{{ $claim['label'] }}"
                                        data-award-amount="{{ $claim['amount'] }}"
                                        class="claim-award-btn px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-semibold shadow-md transition-all duration-200 flex items-center space-x-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
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
                    @endif

                    <!-- Pagination Controls -->
                    @if(!empty($paginatedClaims) && $pagination['last_page'] > 1)
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing <span class="font-medium">{{ $pagination['from'] }}</span> to <span class="font-medium">{{ $pagination['to'] }}</span> of <span class="font-medium">{{ $pagination['total'] }}</span> results
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Previous Page Button -->
                            @if($pagination['has_previous_pages'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                                Previous
                            </a>
                            @else
                            <button disabled class="inline-flex items-center px-3 py-2 border border-gray-200 rounded-md text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                                Previous
                            </button>
                            @endif

                            <!-- Page Numbers -->
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= $pagination['last_page']; $i++)
                                    @if($i==$pagination['current_page'])
                                    <span class="inline-flex items-center px-3 py-2 border border-indigo-500 rounded-md text-sm font-medium text-indigo-600 bg-indigo-50">
                                    {{ $i }}
                                    </span>
                                    @elseif($i == 1 || $i == $pagination['last_page'] || ($i >= $pagination['current_page'] - 1 && $i <= $pagination['current_page'] + 1))
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ $i }}
                                        </a>
                                        @elseif($i == $pagination['current_page'] - 2 || $i == $pagination['current_page'] + 2)
                                        <span class="inline-flex items-center px-3 py-2 text-sm text-gray-500">...</span>
                                        @endif
                                        @endfor
                            </div>

                            <!-- Next Page Button -->
                            @if($pagination['has_more_pages'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            @else
                            <button disabled class="inline-flex items-center px-3 py-2 border border-gray-200 rounded-md text-sm font-medium text-gray-400 bg-gray-50 cursor-not-allowed">
                                Next
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Summary Section -->
            @if(!empty($personnel->available_claims))
            <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Award Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @php
                    $totalClaimed = collect($personnel->available_claims)->where('is_claimed', true)->sum('amount');
                    $totalAvailable = collect($personnel->available_claims)->where('is_claimed', false)->sum('amount');
                    @endphp
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gray-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Claimed</p>
                                <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalClaimed) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-green-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Available to Claim</p>
                                <p class="text-2xl font-bold text-green-900">₱{{ number_format($totalAvailable) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="claim-confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" style="display: none;">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
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
                    Are you sure you want to claim the <strong id="claim-award-name"></strong> for <strong>{{ $personnel->fullName() }}</strong>?
                </p>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-lg font-bold text-green-700" id="claim-award-amount"></span>
                    </div>
                    <p class="text-xs text-green-600 mt-1">Award Amount</p>
                </div>

                <p class="text-xs text-gray-500">
                    <strong>Note:</strong> This action cannot be undone. The award will be marked as claimed.
                </p>
            </div>

            <div class="flex items-center justify-center space-x-3">
                <button onclick="closeClaimConfirmModal()"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button id="confirm-claim-btn" onclick="processClaim()"
                    class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg font-semibold shadow-md transition-all duration-200 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Confirm Claim</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        // Global variables to store claim data
        let currentClaimData = {};

        // Event delegation for claim award buttons
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                if (e.target.closest('.claim-award-btn')) {
                    const btn = e.target.closest('.claim-award-btn');
                    const personnelId = btn.getAttribute('data-personnel-id');
                    const claimIndex = btn.getAttribute('data-claim-index');
                    const awardName = btn.getAttribute('data-award-name');
                    const awardAmount = btn.getAttribute('data-award-amount');

                    openClaimConfirmModal(personnelId, claimIndex, awardName, awardAmount);
                }
            });
        });

        // Function to open claim confirmation modal
        function openClaimConfirmModal(personnelId, claimIndex, awardName, amount) {
            currentClaimData = {
                personnelId: personnelId,
                claimIndex: claimIndex,
                awardName: awardName,
                amount: amount
            };

            const awardNameEl = document.getElementById('claim-award-name');
            const awardAmountEl = document.getElementById('claim-award-amount');
            const confirmModal = document.getElementById('claim-confirm-modal');

            if (awardNameEl) awardNameEl.textContent = awardName;
            if (awardAmountEl) awardAmountEl.textContent = '₱' + new Intl.NumberFormat().format(amount);
            if (confirmModal) confirmModal.style.display = 'block';
        }

        // Function to close claim confirmation modal
        function closeClaimConfirmModal() {
            const confirmModal = document.getElementById('claim-confirm-modal');
            if (confirmModal) confirmModal.style.display = 'none';
            currentClaimData = {};
        }

        // Function to process the claim
        function processClaim() {
            if (!currentClaimData.personnelId || currentClaimData.claimIndex === undefined) {
                alert('Error: Missing claim data');
                return;
            }

            // Disable the confirm button to prevent double clicks
            const confirmBtn = document.getElementById('confirm-claim-btn');
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<span>Processing...</span>';

            // Create form data
            const formData = new FormData();
            formData.append('personnel_id', currentClaimData.personnelId);
            formData.append('claim_index', currentClaimData.claimIndex);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Send AJAX request
            fetch('/loyalty-awards/claim', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showSuccessMessage(data.message);

                        // Close modal and refresh page
                        setTimeout(() => {
                            closeClaimConfirmModal();
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Failed to claim award');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                })
                .finally(() => {
                    // Re-enable the confirm button
                    confirmBtn.disabled = false;
                    confirmBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><span>Confirm Claim</span>';
                });
        }

        // Function to show success message
        function showSuccessMessage(message) {
            // Create a temporary success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.textContent = message;
            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 3000);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
