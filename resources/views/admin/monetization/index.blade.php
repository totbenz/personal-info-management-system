<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">Leave Monetization Requests</h2>
                <p class="text-sm text-gray-600">Review and manage leave monetization requests</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-yellow-50/20 to-orange-50/30">
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200/50">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $monetizations->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200/50">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Approved Requests</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $monetizations->where('status', 'approved')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200/50">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Rejected Requests</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $monetizations->where('status', 'rejected')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monetization Requests Table -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">All Monetization Requests</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Personnel</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User Type</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Leave Distribution</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Total Days</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date Requested</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($monetizations as $monetization)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $monetization->personnel->full_name ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        ID: {{ $monetization->personnel->personnel_id ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $monetization->user_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>VL: {{ $monetization->vl_days_used }} day(s)</div>
                                    <div>SL: {{ $monetization->sl_days_used }} day(s)</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $monetization->total_days }} days
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($monetization->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($monetization->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($monetization->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $monetization->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($monetization->status === 'pending')
                                        <button onclick="openApprovalModal({{ $monetization->id }})" class="text-green-600 hover:text-green-900 mr-3">
                                            Approve
                                        </button>
                                        <button onclick="openRejectionModal({{ $monetization->id }})" class="text-red-600 hover:text-red-900">
                                            Reject
                                        </button>
                                    @else
                                        <button onclick="viewDetails({{ $monetization->id }})" class="text-blue-600 hover:text-blue-900">
                                            View Details
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No monetization requests found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $monetizations->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button onclick="closeApprovalModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Approve Monetization Request</h3>
            <form id="approvalForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Remarks (Optional)</label>
                    <textarea name="admin_remarks" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Add any remarks..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Approve Request
                    </button>
                    <button type="button" onclick="closeApprovalModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectionModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-md relative">
            <button onclick="closeRejectionModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Reject Monetization Request</h3>
            <form id="rejectionForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection <span class="text-red-500">*</span></label>
                    <textarea name="admin_remarks" rows="3" required class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Please provide a reason..."></textarea>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Reject Request
                    </button>
                    <button type="button" onclick="closeRejectionModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200/50 p-8 w-full max-w-2xl relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeDetailsModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div id="detailsModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function openApprovalModal(id) {
            const form = document.getElementById('approvalForm');
            form.action = `/admin/monetization-requests/${id}/approve`;
            document.getElementById('approvalModal').classList.remove('hidden');
            document.getElementById('approvalModal').classList.add('flex');
        }

        function closeApprovalModal() {
            document.getElementById('approvalModal').classList.add('hidden');
            document.getElementById('approvalModal').classList.remove('flex');
            document.getElementById('approvalForm').reset();
        }

        function openRejectionModal(id) {
            const form = document.getElementById('rejectionForm');
            form.action = `/admin/monetization-requests/${id}/reject`;
            document.getElementById('rejectionModal').classList.remove('hidden');
            document.getElementById('rejectionModal').classList.add('flex');
        }

        function closeRejectionModal() {
            document.getElementById('rejectionModal').classList.add('hidden');
            document.getElementById('rejectionModal').classList.remove('flex');
            document.getElementById('rejectionForm').reset();
        }

        function viewDetails(id) {
            fetch(`/admin/monetization-requests/${id}/details`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const content = `
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monetization Request Details</h3>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Personnel Name</p>
                                    <p class="text-sm text-gray-900">${data.data.personnel.first_name} ${data.data.personnel.last_name}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">User Type</p>
                                    <p class="text-sm text-gray-900">${data.data.user_type}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Date Requested</p>
                                    <p class="text-sm text-gray-900">${new Date(data.data.created_at).toLocaleString()}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                        data.data.status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                        data.data.status == 'approved' ? 'bg-green-100 text-green-800' :
                                        'bg-red-100 text-red-800'
                                    }">
                                        ${data.data.status.charAt(0).toUpperCase() + data.data.status.slice(1)}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Leave Breakdown</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-blue-50 p-3 rounded">
                                        <p class="text-sm text-blue-600 font-medium">VL Days</p>
                                        <p class="text-xl font-bold text-blue-900">${data.data.vl_days_used}</p>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded">
                                        <p class="text-sm text-green-600 font-medium">SL Days</p>
                                        <p class="text-xl font-bold text-green-900">${data.data.sl_days_used}</p>
                                    </div>
                                    <div class="bg-purple-50 p-3 rounded">
                                        <p class="text-sm text-purple-600 font-medium">Total Days</p>
                                        <p class="text-xl font-bold text-purple-900">${data.data.total_days}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-4 mt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Reason</h4>
                                <p class="text-sm text-gray-700">${data.data.reason || 'No reason provided'}</p>
                            </div>

                            ${data.data.processed_at ? `
                            <div class="border-t pt-4 mt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Processing Information</h4>
                                <p class="text-sm text-gray-700">Processed by: ${data.data.processed_by || 'N/A'}</p>
                                <p class="text-sm text-gray-700">Processed at: ${new Date(data.data.processed_at).toLocaleString()}</p>
                                ${data.data.remarks ? `<p class="text-sm text-gray-700">Remarks: ${data.data.remarks}</p>` : ''}
                            </div>
                            ` : ''}
                        </div>
                    `;

                    document.getElementById('detailsModalContent').innerHTML = content;
                    document.getElementById('detailsModal').classList.remove('hidden');
                    document.getElementById('detailsModal').classList.add('flex');
                }
            })
            .catch(error => {
                console.error('Error fetching details:', error);
            });
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.getElementById('detailsModal').classList.remove('flex');
        }

        // Close modals when clicking outside
        document.getElementById('approvalModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApprovalModal();
            }
        });

        document.getElementById('rejectionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectionModal();
            }
        });
    </script>
</x-app-layout>
