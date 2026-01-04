<div wire:key="update-references-form-{{ $id }}" class="px-8 py-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="mt-5">
            <div class="mt-3">
                <div class="w-full flex h-10 border border-gray-100 bg-gray-50 items-center rounded-t-lg">
                    <div class="w-3/12 px-4">
                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Fullname</span>
                    </div>
                    <div class="w-4/12 px-4">
                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Address</span>
                    </div>
                    <div class="w-2/12 px-4">
                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Phone</span>
                    </div>
                    <div class="w-1/12 px-4">
                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</span>
                    </div>
                </div>
                <div class="mt-2">
                    <!-- Old Data Children -->
                    @foreach ($old_references as $index => $old_reference)
                    <div class="mb-2 w-full flex h-12 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors duration-200">
                        <div class="w-3/12 px-4 flex items-center">
                            <input type="text" id="full_name_{{ $index }}" wire:model="old_references.{{ $index }}.full_name" name="old_references[{{ $index }}][full_name]"
                                class="w-full px-3 py-2 border-0 focus:ring-2 focus:ring-blue-500 rounded-lg" placeholder="Enter full name" required/>
                        </div>
                        <div class="w-4/12 px-4 flex items-center">
                            <input type="text" id="address_{{ $index }}" wire:model="old_references.{{ $index }}.address" name="old_references[{{ $index }}][address]"
                                class="w-full px-3 py-2 border-0 focus:ring-2 focus:ring-blue-500 rounded-lg" placeholder="Enter address" required/>
                        </div>
                        <div class="w-2/12 px-4 flex items-center">
                            <input type="text" id="tel_no_{{ $index }}" wire:model="old_references.{{ $index }}.tel_no" name="old_references[{{ $index }}][tel_no]"
                                class="w-full px-3 py-2 border-0 focus:ring-2 focus:ring-blue-500 rounded-lg" placeholder="Phone number" required/>
                        </div>
                        <div class="w-1/12 px-4 flex items-center justify-center">
                            <button wire:click="removeOldField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach

                    <!-- New Data Children -->
                    @foreach ($new_references as $index => $new_reference)
                        <div class="mb-2 w-full flex h-12 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors duration-200"
                            x-cloak
                            x-transition:enter="transition ease-in-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in-out duration-200"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95">
                            <div class="w-3/12 px-4 flex items-center">
                                <input type="text" id="full_name_{{ $index }}" wire:model="new_references.{{ $index }}.full_name" name="new_references[{{ $index }}][full_name]"
                                    class="w-full px-3 py-2 border-0 focus:ring-2 focus:ring-blue-500 rounded-lg" placeholder="Enter full name" required/>
                            </div>
                            <div class="w-4/12 px-4 flex items-center">
                                <input type="text" id="address_{{ $index }}" wire:model="new_references.{{ $index }}.address" name="new_references[{{ $index }}][address]"
                                    class="w-full px-3 py-2 border-0 focus:ring-2 focus:ring-blue-500 rounded-lg" placeholder="Enter address" required/>
                            </div>
                            <div class="w-2/12 px-4 flex items-center">
                                <input type="text" id="tel_no_{{ $index }}" wire:model="new_references.{{ $index }}.tel_no" name="new_references[{{ $index }}][tel_no]"
                                    class="w-full px-3 py-2 border-0 focus:ring-2 focus:ring-blue-500 rounded-lg" placeholder="Phone number" required/>
                            </div>
                            <div class="w-1/12 px-4 flex items-center justify-center">
                                <button wire:click="removeNewField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-3 flex space-x-3 items-center">
                        <div class="w-full">
                            <button wire:click.prevent="addField"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-100 border-0 rounded-lg hover:bg-blue-200 transform hover:scale-105 transition-all duration-200 w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-1.5 h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add New Reference
                            </button>
                            <p class="text-xs text-gray-500 mt-1">References: {{ count($old_references) + count($new_references) }}/3 (Maximum 3 references allowed)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-5 p-0 flex space-x-3 justify-end">
            <button wire:click.prevent="cancel"
                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transform hover:scale-105 transition-all duration-200 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                Cancel
            </button>
            <button wire:click.prevent="save"
                class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 border-0 rounded-xl hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L8.863 9.83A3.75 3.75 0 0 0 7.5 6.75v-.75m0 0a3.75 3.75 0 0 1 7.5 0v.75m-7.5 0H18A2.25 2.25 0 0 1 20.25 9v.75m-8.5 6.75h.008v.008h-.008v-.008Z" />
                </svg>
                Save Changes
            </button>
        </div>
    </div>
</div>
