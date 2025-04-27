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
                    option-value="id"
                />
            </div>

            <!-- Category Dropdown -->
            <div class="w-[16rem] px-0.5 text-xs">
                <x-native-select wire:model.live.debounce.300ms="selectedCategory">
                    <option value="">Select category</option>
                    <option value="SDO Personnel">SDO Personnel</option>
                    <option value="School Head">School Head</option>
                    <option value="Elementary School Teacher">Elementary School Teacher</option>
                    <option value="Junior High School Teacher">Junior High School Teacher</option>
                    <option value="Senior High School Teacher">Senior High School Teacher</option>
                    <option value="School Non-teaching Personnel">School Non-teaching Personnel</option>
                </x-native-select>
            </div>

            <!-- School Dropdown -->
            <div class="w-[11rem] px-0.5 text-xs">
                <x-select
                    wire:model.live.debounce.300ms="selectedSchool"
                    placeholder="Select a school"
                    :async-data="route('api.schools.index')"
                    option-label="school_id"
                    option-value="id"
                    option-description="school_name"
                />
            </div>
        </div>
        <!-- Search Input -->
        <div class="flex space-x-2 relative"> 
            <input 
                type="text" 
                wire:model.live.debounce.150ms="search" 
                placeholder="Search ..." 
                class="w-[16rem] px-2 py-1 border rounded text-sm pl-10"
            />
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-1 top-1/2 transform -translate-y-1/2 h-4 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                </path>
            </svg>
            
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
                                <span class="font-semibold text-left">ID</span>
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
                    <th class="p-2 whitespace-nowrap w-1/12" wire:click="doSort('job_status')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="job_status">
                                <span class="font-semibold text-left">Job Status</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
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
                    <th class="p-2 whitespace-nowrap w-2/12" wire:click="doSort('classification')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="school_id">
                                <span class="font-semibold text-left">Classification</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-2/12" wire:click="doSort('category')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="email">
                                <span class="font-semibold text-left">Category</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12" wire:click="doSort('school_id')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="phone">
                                <span class="font-semibold text-left">School ID</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
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
                @foreach ( $personnels as $index => $personnel)
                <tr wire:loading.class="opacity-75" class="text-sm">
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-left">{{ $personnel->personnel_id }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        <div class="text-left capitalize">{{ $personnel->fullName() }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-left capitalize">{{ $personnel->job_status }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        <div class="text-left capitalize">{{ $personnel->position->title }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        <div class="text-left capitalize">{{ $personnel->position->classification }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        <div class="text-left capitalize">{{ $personnel->category }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-left">{{ $personnel->school->school_id }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="flex justify-between space-x-3">
                            <a href="{{ route('personnels.show', ['personnel' => $personnel->id]) }}">
                                <button class="py-1 px-2 bg-white font-medium text-sm tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300">
                                    View
                                </button>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if ($personnels->isEmpty())
                    <tr wire:loading.class="opacity-75">
                        <td colspan="8" class="p-2 w-full text-center">No Loyalty Awardees for {{ now()->year }} found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $personnels->links() }}
    </div>
</div>
