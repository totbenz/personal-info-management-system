<div class="mx-5 my-8 p-3">
    <div class="flex justify-between">
        <div class="w-1/4 inline-flex space-x-4">
            <a href="{{ route('districts.create') }}">
                <x-button class="m-0 hover:shadow-[0.5rem_0.5rem_#FA0302,-0.5rem_-0.5rem_#FCC008] transition">
                    {{ __('New District') }}
                </x-button>
            </a>
        </div>

        <div class="flex w-2/4 items-center rounded-md border border-gray-400 bg-white focus:bg-white focus:border-gray-500">
            <div class="pl-2">
                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current text-gray-500">
                    <path d="M10 4a6 6 0 100 12 6 6 0 000-12zm-8 6a8 8 0 1114.32 4.906l5.387 5.387a1 1 0 01-1.414 1.414l-5.387-5.387A8 8 0 012 10z">
                    </path>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search"
                   class="appearance-none rounded-md border-none block pl-2 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700">
        </div>
    </div>
    <div class="mt-8 overflow-x-auto">
        <table class="table-auto w-full">
            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                <tr>
                    <th wire:click="doSort('id')" class="w-1/12 p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="id">
                                <span class="font-semibold text-left">#</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-5/12" wire:click="doSort('school_name')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Name</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-2/12">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Action</span>
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($districts as $district)
                <tr wire:loading.class="opacity-75">
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-left">{{ $district->id }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-5/12">
                        <div class="text-left">{{ $district->name }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        {{-- @livewire('modal') --}}
                        <div class="flex justify-between space-x-3">
                            <a href="{{ route('districts.show', ['district' => $district->id]) }}">
                                <button class="py-1 px-4 bg-white font-medium text-sm tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300">
                                    View
                                </button>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if ($districts->isEmpty())
                    <tr wire:loading.class="opacity-75">
                        <td colspan="5" class="p-2 w-full text-center">No District Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $districts->links() }}
    </div>
</div>
