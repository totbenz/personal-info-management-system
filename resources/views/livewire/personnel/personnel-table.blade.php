<div>
    <!-- Personnel Table Component -->
<div>
    <!-- Header Section -->
    <div class="mx-5 my-8 p-3">
        <div class="flex justify-between items-center mb-5">
            <div class="flex space-x-3">
                <!-- Show regular create button for admins -->
                <a href="{{ route('personnels.create') }}">
                    <button class="py-2 px-4 bg-white font-medium text-xs tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300 h-10">
                        New Personnel
                    </button>
                </a>
                <button wire:click='export' class="py-2 px-4 bg-white font-medium text-xs tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300 h-10">
                    Export Excel
                </button>
            </div>

            <!-- Search Input -->
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search Personnels..."
                    class="w-[16rem] px-2 py-2 pl-10 border rounded text-sm" />
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-4 flex flex-wrap gap-2">
            <div class="w-[9rem]">
                <x-native-select wire:model.live.debounce.300ms="selectedJobStatus" class="w-full">
                    <option value="">Select status</option>
                    <option value="active">Active</option>
                    <option value="vacation">Vacation</option>
                    <option value="terminated">Terminated</option>
                    <option value="on leave">On Leave</option>
                    <option value="suspended">Suspended</option>
                    <option value="resigned">Resigned</option>
                    <option value="probation">Probation</option>
                    <option value="contract">Contract</option>
                    <option value="part-time">Part-time</option>
                </x-native-select>
            </div>

            <div class="w-[12rem]">
                <x-select
                    wire:model.live.debounce.300ms="selectedPosition"
                    placeholder="Select a position"
                    :async-data="route('api.positions.index')"
                    option-label="title"
                    option-value="id"
                    class="w-full" />
            </div>

            <div class="w-[16rem]">
                <x-native-select wire:model.live.debounce.300ms="selectedCategory" class="w-full">
                    <option value="">Select category</option>
                    <option value="SDO Personnel">SDO Personnel</option>
                    <option value="School Head">School Head</option>
                    <option value="Elementary School Teacher">Elementary School Teacher</option>
                    <option value="Junior High School Teacher">Junior High School Teacher</option>
                    <option value="Senior High School Teacher">Senior High School Teacher</option>
                    <option value="School Non-teaching Personnel">School Non-teaching Personnel</option>
                </x-native-select>
            </div>

            @if(auth()->user()->role !== 'school_head')
            <div class="w-[11rem]">
                <x-select
                    wire:model.live.debounce.300ms="selectedSchool"
                    placeholder="Select a school"
                    :async-data="route('api.schools.index')"
                    option-label="school_id"
                    option-value="id"
                    option-description="school_name"
                    class="w-full" />
            </div>
            @endif

            <div class="flex items-center">
                <button wire:click="resetFilters" class="text-xs text-gray-600 hover:text-gray-800 underline">
                    Reset Filters
                </button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading.delay.longer class="text-center py-4">
            <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-main"></div>
            <span class="ml-2 text-sm text-gray-600">Loading...</span>
        </div>

        <!-- Table Section -->
        <div class="overflow-hidden rounded-lg shadow border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th wire:click="doSort('personnel_id')" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-x-1">
                                ID
                                @if($sortColumn === 'personnel_id')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-main" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'ASC')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </div>
                        </th>

                        <th wire:click="doSort('last_name')" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-x-1">
                                Name
                                @if($sortColumn === 'last_name')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-main" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'ASC')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </div>
                        </th>

                        <th wire:click="doSort('job_status')" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-x-1">
                                Job Status
                                @if($sortColumn === 'job_status')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-main" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'ASC')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </div>
                        </th>

                        <th wire:click="doSort('position_title')" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-x-1">
                                Position
                                @if($sortColumn === 'position_title')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-main" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'ASC')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </div>
                        </th>

                        <th wire:click="doSort('category')" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-x-1">
                                Category
                                @if($sortColumn === 'category')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-main" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'ASC')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </div>
                        </th>

                        <th wire:click="doSort('school_name')" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer hover:bg-gray-50">
                            <div class="flex items-center gap-x-1">
                                School Name
                                @if($sortColumn === 'school_name')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-main" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($sortDirection === 'ASC')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        @endif
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                    </svg>
                                @endif
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($personnels as $personnel)
                    <tr wire:key="personnel-{{ $personnel->id }}"
                        wire:click="viewPersonnel({{ $personnel->id }})"
                        class="text-sm hover:bg-gray-50 cursor-pointer transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $personnel->personnel_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium capitalize">{{ $personnel->fullName() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($personnel->job_status === 'active') bg-green-100 text-green-800
                                @elseif($personnel->job_status === 'vacation') bg-blue-100 text-blue-800
                                @elseif($personnel->job_status === 'terminated') bg-red-100 text-red-800
                                @elseif($personnel->job_status === 'on leave') bg-yellow-100 text-yellow-800
                                @elseif($personnel->job_status === 'suspended') bg-purple-100 text-purple-800
                                @elseif($personnel->job_status === 'resigned') bg-gray-100 text-gray-800
                                @elseif($personnel->job_status === 'probation') bg-orange-100 text-orange-800
                                @elseif($personnel->job_status === 'contract') bg-indigo-100 text-indigo-800
                                @elseif($personnel->job_status === 'part-time') bg-pink-100 text-pink-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $personnel->job_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $personnel->position?->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $personnel->category }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $personnel->school?->school_name ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-2 text-sm">No personnel found</p>
                            <p class="text-xs text-gray-400 mt-1">Try adjusting your search or filters</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($personnels->hasPages())
        <div class="mt-6">
            {{ $personnels->links() }}
        </div>
        @endif
    </div>
</div>

</div>
