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

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    background: '#10b981',
                    color: '#ffffff'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    background: '#ef4444',
                    color: '#ffffff'
                });
            });
        </script>
    @endif

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-yellow-50/20 to-orange-50/30">
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Statistics Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200/50">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Requests</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200/50">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Requests</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</p>
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
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</p>
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
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Type Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Teachers</p>
                            <p class="text-3xl font-bold">{{ $stats['teachers'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Non-Teaching</p>
                            <p class="text-3xl font-bold">{{ $stats['non_teaching'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">School Heads</p>
                            <p class="text-3xl font-bold">{{ $stats['school_heads'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200/50">
                <form method="GET" action="{{ route('admin.monetization-requests') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search Personnel</label>
                            <div class="relative">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by name..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">User Type</label>
                            <select name="user_type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <option value="all" {{ request('user_type') == 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="teacher" {{ request('user_type') == 'teacher' ? 'selected' : '' }}>Teachers</option>
                                <option value="non_teaching" {{ request('user_type') == 'non_teaching' ? 'selected' : '' }}>Non-Teaching</option>
                                <option value="school_head" {{ request('user_type') == 'school_head' ? 'selected' : '' }}>School Heads</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.monetization-requests') }}"
                           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Clear Filters
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Monetization Requests Table -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">All Monetization Requests</h3>
                        @if(request()->anyFilled(['search', 'user_type', 'status']))
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">{{ $paginatedMonetizations->total() }}</span>
                                result{{ $paginatedMonetizations->total() != 1 ? 's' : '' }} found
                            </div>
                        @endif
                    </div>
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
                            @forelse($paginatedMonetizations as $monetization)
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
                                    <div>VL: {{ number_format($monetization->vl_days_used, 1) }} day(s)</div>
                                    <div>SL: {{ number_format($monetization->sl_days_used, 1) }} day(s)</div>
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
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $paginatedMonetizations->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $paginatedMonetizations->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $paginatedMonetizations->total() }}</span>
                            results
                        </div>
                        {{ $paginatedMonetizations->appends(request()->query())->links() }}
                    </div>
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
                    <textarea name="rejection_reason" rows="3" required class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Please provide a reason..."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Remarks (Optional)</label>
                    <textarea name="admin_remarks" rows="2" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Additional notes..."></textarea>
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

        // Handle form submissions with SweetAlert
        document.getElementById('approvalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');

            Swal.fire({
                title: 'Approve Request?',
                text: 'Are you sure you want to approve this monetization request?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Approving...';
                    form.submit();
                }
            });
        });

        document.getElementById('rejectionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const rejectionReason = form.querySelector('textarea[name="rejection_reason"]').value;

            if (!rejectionReason.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Required Field',
                    text: 'Please provide a reason for rejection',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            Swal.fire({
                title: 'Reject Request?',
                text: 'Are you sure you want to reject this monetization request?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Rejecting...';
                    form.submit();
                }
            });
        });

        // Auto-submit search form on Enter key in search input
        document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });

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
