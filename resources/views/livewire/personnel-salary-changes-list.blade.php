<div>
    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if(count($salaryChanges) > 0)
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Salary Changes</h3>
            <p class="text-sm text-gray-500 mt-1">{{ count($salaryChanges) }} {{ Str::plural('record', count($salaryChanges)) }} found</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade Change</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Step Change</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary Change</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Effective Dates</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Download</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($salaryChanges as $change)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($change->type === 'NOSA') bg-green-100 text-green-800
                                    @elseif($change->type === 'NOSI') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                {{ $change->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @if($change->previous_salary_grade)
                                <span class="text-sm text-gray-500">{{ $change->previous_salary_grade }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                @endif
                                <span class="text-sm font-medium text-gray-900">{{ $change->current_salary_grade }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                @if($change->previous_salary_step)
                                <span class="text-sm text-gray-500">{{ $change->previous_salary_step }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                @endif
                                <span class="text-sm font-medium text-gray-900">{{ $change->current_salary_step }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                @if($change->previous_salary)
                                <div class="text-sm text-gray-500 line-through">
                                    ₱{{ number_format($change->previous_salary, 2) }}
                                </div>
                                @endif
                                <div class="text-sm font-semibold text-gray-900">
                                    ₱{{ number_format($change->current_salary, 2) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                @if($change->actual_monthly_salary_as_of_date)
                                <div class="text-sm text-gray-900">
                                    <span class="text-xs text-gray-500">Actual:</span>
                                    {{ \Carbon\Carbon::parse($change->actual_monthly_salary_as_of_date)->format('M j, Y') }}
                                </div>
                                @endif
                                @if($change->adjusted_monthly_salary_date)
                                <div class="text-sm text-gray-900">
                                    <span class="text-xs text-gray-500">Adjusted:</span>
                                    {{ \Carbon\Carbon::parse($change->adjusted_monthly_salary_date)->format('M j, Y') }}
                                </div>
                                @endif
                                @if(!$change->actual_monthly_salary_as_of_date && !$change->adjusted_monthly_salary_date)
                                <span class="text-sm text-gray-400">No dates specified</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($change->created_at)
                            <div class="space-y-1">
                                <div>{{ \Carbon\Carbon::parse($change->created_at)->format('M j, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($change->created_at)->format('g:i A') }}</div>
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('personnel-salary-changes.download', ['personnel' => $change->personnel_id, 'change' => $change->id]) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700 transition-colors duration-150">
                                Download PDF
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <button onclick="confirmDelete({{ $change->id }})" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No salary changes found</h3>
            <p class="mt-1 text-sm text-gray-500">This personnel member has no recorded salary changes.</p>
        </div>
        @endif
    </div>
</div>

<script>
function confirmDelete(changeId) {
    Swal.fire({
        title: 'Are you sure you want to delete this Salary Change Entry?',
        text: "You won't be able to revert this! This salary change record will be permanently deleted.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return @this.call('delete', changeId)
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleted!',
                text: 'The salary change record has been deleted.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}
</script>
