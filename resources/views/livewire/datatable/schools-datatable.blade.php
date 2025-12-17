<div class="mx-5 my-8 p-3 bg-white rounded-xl shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <!-- Buttons -->
        <div class="flex space-x-4">
            @include('school.modal.create-modal')
            <button x-on:click="$openModal('create-school')" class="bg-main text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-500 transition font-semibold">
                {{ __('New School') }}
            </button>
            <button wire:click='export' class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600 transition font-semibold">
                Export Excel
            </button>
        </div>
        <!-- Search Input -->
        <div class="relative">
            <input
                type="text"
                wire:model.live.debounce.150ms="search"
                placeholder="Search ..."
                class="w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm pl-10 focus:ring-2 focus:ring-main focus:border-main text-sm" />
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
            </svg>
        </div>
    </div>
    <div class="overflow-x-auto rounded-lg">
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead class="sticky top-0 z-10 bg-gray-100 text-gray-700 text-xs uppercase tracking-wider shadow">
                <tr>
                    <th wire:click="doSort('id')" class="p-3 cursor-pointer hover:bg-gray-200 transition">#</th>
                    <th wire:click="doSort('school_id')" class="p-3 cursor-pointer hover:bg-gray-200 transition">ID</th>
                    <th wire:click="doSort('school_name')" class="p-3 cursor-pointer hover:bg-gray-200 transition">Name</th>
                    <th wire:click="doSort('district_id')" class="p-3 cursor-pointer hover:bg-gray-200 transition">District</th>
                    <th wire:click="doSort('email')" class="p-3 cursor-pointer hover:bg-gray-200 transition">Email</th>
                    <th wire:click="doSort('phone')" class="p-3 cursor-pointer hover:bg-gray-200 transition">Phone</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schools as $school)
                <tr wire:loading.class="opacity-75" class="hover:bg-indigo-50 transition">
                    <td class="p-3 text-left border-b border-gray-200">{{ $school->id }}</td>
                    <td class="p-3 text-left border-b border-gray-200">{{ $school->school_id }}</td>
                    <td class="p-3 text-left font-medium text-gray-900 border-b border-gray-200">{{ $school->school_name }}</td>
                    <td class="p-3 text-left border-b border-gray-200">{{ ucwords($school->district->name) }}</td>
                    <td class="p-3 text-left border-b border-gray-200">{{ $school->email }}</td>
                    <td class="p-3 text-left border-b border-gray-200">{{ $school->phone }}</td>
                    <td class="p-3 border-b border-gray-200">
                        <a wire:navigate href="{{ route('schools.show', ['school' => $school->id]) }}">
                            <button class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                View
                            </button>
                        </a>
                    </td>
                </tr>
                @endforeach
                @if ($schools->isEmpty())
                <tr wire:loading.class="opacity-75">
                    <td colspan="7" class="p-4 text-center text-gray-500">No School Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $schools->links() }}
    </div>
</div>
