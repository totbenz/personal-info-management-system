<div class="mx-5 my-8 p-3">
    <div class="flex justify-between">
        <div class="w-1/4 inline-flex space-x-4">
            @include('personnel.modal.create-modal')
            <div class="flex justify-between space-x-3">
                <a href="#" x-on:click="$openModal('create-personnel-modal')">
                    <button class="py-2 px-4 bg-white font-medium text-xs tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300 h-10">
                        New Personnel
                    </button>
                </a>
            </div>
            <div class="flex justify-between space-x-3">
                <button wire:click='export' class="py-2 px-4 bg-white font-medium text-xs tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300 h-10">
                    Export Excel
                </button>
            </div>
        </div>
        <!-- Search Input -->
        <div class="flex space-x-2 relative">
            <input
                type="text"
                wire:model.live.debounce.150ms="search"
                placeholder="Search Personnels..."
                class="w-[16rem] px-2 py-1 border rounded text-sm pl-10" />
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-1 top-1/2 transform -translate-y-1/2 h-4 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                </path>
            </svg>

        </div>

        <!-- <div class="flex w-2/4 items-center rounded-md border border-gray-400 bg-white focus:bg-white focus:border-gray-500">
            <div class="pl-2">
                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                    <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                    </path>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search"
                class="appearance-none rounded-md border-none block pl-2 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700">
        </div> -->
    </div>
    <div class="mt-5 overflow-x-auto">
        <div class="my-2 flex space-x-2">
            <div class="w-[9rem] px-0.5 text-xs">
                <x-native-select wire:model.live.debounce.300ms="selectedJobStatus">
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
            <div class="w-[12rem] px-0.5 text-xs">
                <x-select
                    wire:model.live.debounce.300ms="selectedPosition"
                    placeholder="Select a position"
                    :async-data="route('api.positions.index')"
                    option-label="title"
                    option-value="id" />
            </div>
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
            @if(auth()->user()->role !== 'school_head')
            <div class="w-[11rem] px-0.5 text-xs">
                <x-select
                    wire:model.live.debounce.300ms="selectedSchool"
                    placeholder="Select a school"
                    :async-data="route('api.schools.index')"
                    option-label="school_id"
                    option-value="id"
                    option-description="school_name" />
            </div>
            @endif
        </div>
        <div class="overflow-hidden rounded-lg shadow border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th wire:click="doSort('personnel_id')" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer group">
                            <div class="flex items-center gap-x-1">
                                ID
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400 group-hover:text-main transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th wire:click="doSort('personnel_id')" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer group">
                            <div class="flex items-center gap-x-1">
                                Name
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400 group-hover:text-main transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th wire:click="doSort('job_status')" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer group">
                            <div class="flex items-center gap-x-1">
                                Job Status
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400 group-hover:text-main transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th wire:click="doSort('position_id')" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer group">
                            <div class="flex items-center gap-x-1">
                                Position
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400 group-hover:text-main transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th wire:click="doSort('category')" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer group">
                            <div class="flex items-center gap-x-1">
                                Category
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400 group-hover:text-main transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th wire:click="doSort('school_id')" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 cursor-pointer group">
                            <div class="flex items-center gap-x-1">
                                School Name
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400 group-hover:text-main transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>

                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($personnels as $index => $personnel)
                    <tr wire:click="viewPersonnel({{ $personnel->id }})" wire:loading.class="opacity-60" class="text-sm hover:bg-gray-50 cursor-pointer transition">
                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $personnel->personnel_id }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-900 font-medium capitalize">{{ $personnel->fullName() }}</td>
                        <td class="px-4 py-3 whitespace-nowrap capitalize text-gray-700">
                            <span class="inline-block rounded px-2 py-0.5 text-xs bg-gray-100 text-gray-600">{{ $personnel->job_status }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap capitalize text-gray-700">{{ $personnel->position->title }}</td>
                        <td class="px-4 py-3 whitespace-nowrap capitalize text-gray-700">{{ $personnel->category }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-gray-700">{{ $personnel->school->school_name }}</td>

                    </tr>
                    @endforeach
                    @if ($personnels->isEmpty())
                    <tr wire:loading.class="opacity-60">
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400 text-sm">No Personnel Found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-5">
        {{ $personnels->links() }}
    </div>
</div>

