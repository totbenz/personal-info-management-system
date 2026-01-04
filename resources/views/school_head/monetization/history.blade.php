<x-app-layout title="Leave Monetization History">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Leave Monetization History</h1>
                        <p class="text-orange-100 mt-1">Track your leave monetization requests</p>
                    </div>
                    <a href="{{ route('school_head.monetization.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-white text-orange-600 rounded-md hover:bg-orange-50 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Request
                    </a>
                </div>
            </div>

            <!-- Current Balances -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Leave Balances</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($leaveBalances as $type => $balance)
                    <div class="text-center">
                        <p class="text-sm text-gray-600">{{ $type }}</p>
                        <p class="text-2xl font-bold {{ $type == 'Vacation Leave' ? 'text-blue-600' : 'text-green-600' }}">
                            {{ number_format($balance, 1) }}
                        </p>
                        <p class="text-xs text-gray-500">days</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Monetization Requests Table -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @if($monetizationRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Days</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VL Used</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SL Used</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($monetizationRequests as $request)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($request->request_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $request->days_requested }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $request->vl_deducted ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $request->sl_deducted ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($request->status == 'pending')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($request->status == 'approved')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="truncate block max-w-xs" title="{{ $request->reason }}">
                                        {{ $request->reason }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($request->status == 'approved')
                                        <button onclick="openDownloadModal({{ $request->id }})" type="button" class="inline-flex items-center px-3 py-1 border border-orange-600 text-orange-700 text-xs font-semibold rounded-full hover:bg-orange-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M8 12l4 4m0 0l4-4m-4 4V4" />
                                            </svg>
                                            <span class="ml-1">Download</span>
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-400">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No monetization requests</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new monetization request.</p>
                    <div class="mt-6">
                        <a href="{{ route('school_head.monetization.create') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            New Request
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Download Modal -->
<div id="downloadModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
        <button onclick="closeDownloadModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <h3 class="text-xl font-bold text-gray-900 mb-4">Download Monetization Application</h3>
        <p class="text-gray-600 mb-6">Choose the signature type for your monetization application:</p>
        <div class="space-y-3">
            <a id="downloadAssistant" href="#" class="block w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center font-medium">
                Assistant SDS
                <p class="text-sm opacity-90">For Assistant School Division Superintendent</p>
            </a>
            <a id="downloadSchools" href="#" class="block w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center font-medium">
                Schools SDS
                <p class="text-sm opacity-90">For Schools Division Superintendent</p>
            </a>
        </div>
    </div>
</div>

<script>
// Download modal functions
function openDownloadModal(monetizationId) {
    const modal = document.getElementById('downloadModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Set download URLs
    document.getElementById('downloadAssistant').href = `/school-head/monetization-application/download/${monetizationId}/assistant`;
    document.getElementById('downloadSchools').href = `/school-head/monetization-application/download/${monetizationId}/schools`;
}

function closeDownloadModal() {
    const modal = document.getElementById('downloadModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
</x-app-layout>
