<div class="mx-5 my-8 p-3">
    <div class="flex justify-between">


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

    <div class="mt-5 overflow-x-auto">
        <table class="table-auto w-full">
            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                <tr>

                    <th wire:click="doSort('grade')" class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Grade</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th wire:click="doSort('created_at')" class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Created At</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th wire:click="doSort('updated_at')" class="p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Updated At</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salaryGrades as $salaryGrade)
                <tr wire:loading.class="opacity-75" class="text-sm">
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-left">{{ $salaryGrade->grade }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-left">{{ $salaryGrade->created_at->format('M d, Y h:i A') }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap">
                        <div class="text-left">{{ $salaryGrade->updated_at->format('M d, Y h:i A') }}</div>
                    </td>
                </tr>
                @endforeach
                @if ($salaryGrades->isEmpty())
                    <tr wire:loading.class="opacity-75">
                        <td colspan="5" class="p-2 w-full text-center">No Salary Grades Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $salaryGrades->links() }}
    </div>
</div>
