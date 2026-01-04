<div>
    <div class="mt-5">
        <div class="mt-3">
            <div class="w-full flex h-10 border border-gray-100 bg-gray-lightest items-center">
                <div class="w-3/12 px-4">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Fullname</span>
                </div>
                <div class="w-4/12 px-4">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Address</span>
                </div>
                <div class="w-2/12 px-4">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Phone</span>
                </div>
                <div class="w-1/12 px-4">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Action</span>
                </div>
            </div>
            <div class="mt-2">
                <!-- Old Data Children -->
                @foreach ($old_references as $index => $old_reference)
                <div class="mb-2 w-full flex h-12 border border-gray-200 rounded focus:outline-none">
                    <div class="w-3/12 px-4 flex items-center">
                        <x-input id="full_name_{{ $index }}" type="text" wire:model="old_references.{{ $index }}.full_name" name="old_references[{{ $index }}][full_name]" class="w-full" required/>
                    </div>
                    <div class="w-4/12 px-4 flex items-center">
                        <x-input id="address_{{ $index }}" type="text" wire:model="old_references.{{ $index }}.address" name="old_references[{{ $index }}][address]" class="w-full" required/>
                    </div>
                    <div class="w-2/12 px-4 flex items-center">
                        <x-input id="tel_no_{{ $index }}" type="text" wire:model="old_references.{{ $index }}.tel_no" name="old_references[{{ $index }}][tel_no]" class="w-full" required/>
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
                    <div class="mb-2 w-full flex h-12 border border-gray-200 rounded focus:outline-none"
                        x-cloak
                        x-transition:enter="transition ease-in-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in-out duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95">
                        <div class="w-3/12 px-4 flex items-center">
                            <x-input id="full_name_{{ $index }}" type="text" wire:model="new_references.{{ $index }}.full_name" name="new_references[{{ $index }}][full_name]" class="w-full" required/>
                        </div>
                        <div class="w-4/12 px-4 flex items-center">
                            <x-input id="address_{{ $index }}" type="text" wire:model="new_references.{{ $index }}.address" name="new_references[{{ $index }}][address]" class="w-full" required/>
                        </div>
                        <div class="w-2/12 px-4 flex items-center">
                            <x-input id="tel_no_{{ $index }}" type="text" wire:model="new_references.{{ $index }}.tel_no" name="new_references[{{ $index }}][tel_no]" class="w-full" required/>
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
                        <x-button wire:click.prevent="addField"
                            label="Add New"
                            class="w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:bg-main_hover"
                            :disabled="count($old_references) + count($new_references) >= 3"
                            :class="count($old_references) + count($new_references) >= 3 ? 'opacity-50 cursor-not-allowed' : ''" />
                        <p class="text-xs text-gray-500 mt-1">References: {{ count($old_references) + count($new_references) }}/3 (Maximum 3 references allowed)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="my-5 p-0 flex space-x-3 justify-end">
        <div class="w-2/12">
            <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:bg-danger/90 hover:scale-105 duration-150"/>
        </div>
        <div class="w-2/12">
            <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:bg-main/90"/>
        </div>
    </div>
</div>
