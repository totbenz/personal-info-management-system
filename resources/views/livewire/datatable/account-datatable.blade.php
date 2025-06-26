<div class="mx-5 my-8 p-3">
    <div class="flex justify-between">
        <div class="w-1/4 inline-flex space-x-4">
            @include('user.forms.create')
            <a href="#" x-on:click="$openModal('create-account-modal')">
                <button class="w-[9rem] py-2 px-4 bg-main font-medium text-sm tracking-wider rounded-md border-2 hover:bg-blue-900 text-white duration-300">
                    Add Account
                </button>
            </a>
        </div>
        <!-- Search Input -->
        <div class="flex space-x-2 relative">
            <input
                type="text"
                wire:model.live.debounce.150ms="search"
                placeholder="Search ..."
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
            <div class="w-[11.5rem] px-0.5 text-xs">
                <x-native-select wire:model.live.debounce.300ms="selectedRole" id="selectedRole" name="selectedRole">
                    <option value="">Select classification</option>
                    <option value="admin">Admin</option>
                    <option value="school_head">School Head</option>
                    <option value="teacher">Teacher</option>
                </x-native-select>
            </div>
        </div>
        <table class="table-auto w-full">
            <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                <tr>
                    <th wire:click="doSort('id')" class="w-1/12 p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="school_is">
                                <span class="font-semibold text-left">ID</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th wire:click="doSort('id')" class="w-1/12 p-2 whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2" sortColumn="$sortColumn" sortDirection="$sortDirection" columnName="school_is">
                                <span class="font-semibold text-left">Personnel ID</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-3/12" wire:click="doSort('title')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Email</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-3/12" wire:click="doSort('classification')">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Role</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </button>
                        </div>
                    </th>
                    <th class="p-2 whitespace-nowrap w-1/12">
                        <div class="flex items-center gap-x-3">
                            <button class="flex items-center gap-x-2">
                                <span class="font-semibold text-left">Actions</span>
                            </button>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $accounts as $account)
                <tr wire:key="account-{{ $account->id }}" wire:loading.class="opacity-75">
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-left">{{ $account->id }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        <div class="text-left">{{ $account->personnel->personnel_id }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        <div class="text-left">{{ $account->email }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-1/12">
                        <div class="text-xs tracking-wider text-left uppercase">{{ $account->role }}</div>
                    </td>
                    <td class="p-2 whitespace-nowrap w-2/12">
                        @include('user.forms.edit')
                        <div class="flex space-x-2">
                            <button wire:click="editAccount({{ $account->id }})" class="py-1 px-4 bg-white font-medium text-sm tracking-wider rounded-md border-2 border-main hover:bg-main hover:text-white text-main duration-300">
                                View
                            </button>
                            <button wire:click="setDeleteId({{ $account->id }})" x-on:click="$openModal('delete-account-modal')"
                                class="py-1 px-4 bg-red-600 font-medium text-sm tracking-wider rounded-md border-2 border-red-600 hover:bg-red-700 hover:text-white text-white duration-300">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if ($accounts->isEmpty())
                <tr wire:loading.class="opacity-75">
                    <td colspan="5" class="p-2 w-full text-center">No Accounts Found</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="mt-5">
        {{ $accounts->links() }}
    </div>
    @include('position.forms.create')
    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-account-modal" wire:model.live="showDeleteModal">
        <x-card title="Delete Account">
            <div class="px-8 py-5">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="rounded-full bg-red-100 p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Delete Confirmation</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Are you sure you want to delete this account? This action cannot be undone.
                            </p>
                            @if ($deleteError)
                            <div class="mt-3 p-2 bg-red-100 text-red-700 rounded text-sm border border-red-300">
                                {{ $deleteError }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end space-x-3">
                        <x-button wire:click="cancelDelete()" type="button" class="bg-white text-gray-700 border-gray-300 hover:bg-gray-50">
                            Cancel
                        </x-button>
                        <x-button wire:click="deleteAccount()" type="button" class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                            Delete
                        </x-button>
                    </div>
                </div>
            </div>
        </x-card>
    </x-modal>
</div>
