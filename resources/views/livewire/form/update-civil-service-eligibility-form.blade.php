<div>
    <div class="mt-5">
        <div class="mt-3">
            <div class="w-full flex space-x-2 h-10 border border-gray-100 bg-gray-lightest items-center">
                <h6 class="ps-5 w-3/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Title</span>
                </h6>
                <h6 class="w-1/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Rating</span>
                </h6>
                <h6 class="w-2/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Date of Examination</span>
                </h6>
                <h6 class="w-2/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">Place of Examination</span>
                </h6>
                <h6 class="w-1/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">License Number</span>
                </h6>
                <h6 class="w-1/12">
                    <span class="text-xs text-gray-dark font-semibold uppercase">License Date of Validity</span>
                </h6>
            </div>
            <div class="mt-2">
                <!-- Old Data Children -->
                @foreach ($old_civil_services as $index => $old_civil_service)
                <div class="mb-2 w-full flex items-center space-x-2 h-12 border border-gray-200 rounded focus:outline-none">
                    <div class="w-3/12 ps-3 text-xs">
                        <input id="title_{{ $index }}" type="text" wire:model="old_civil_services.{{ $index }}.title" name="old_civil_services[{{ $index }}][title]" placeholder="Title" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                    </div>
                    <div class="w-1/12 ps-3 text-xs">
                        <input id="rating_{{ $index }}" type="text" wire:model="old_civil_services.{{ $index }}.rating" name="old_civil_services[{{ $index }}][rating]" placeholder="Rating" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0"/>
                    </div>
                    <div class="w-2/12 ps-3 text-xs">
                        <input id="date_of_exam_{{ $index }}" type="date" wire:model="old_civil_services.{{ $index }}.date_of_exam" name="old_civil_services[{{ $index }}][date_of_exam]" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                    </div>
                    <div class="w-2/12 ps-3 text-xs">
                        <input id="place_of_exam_{{ $index }}" type="text" wire:model="old_civil_services.{{ $index }}.place_of_exam" name="old_civil_services[{{ $index }}][place_of_exam]" placeholder="Place of Exam" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                    </div>
                    <div class="w-1/12 ps-3 text-xs">
                        <input id="license_num_{{ $index }}" type="text" wire:model="old_civil_services.{{ $index }}.license_num" name="old_civil_services[{{ $index }}][license_num]" placeholder="License Number" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                    </div>
                    <div class="w-2/12 ps-3 text-xs">
                        <input id="license_date_of_validity_{{ $index }}" type="date" wire:model="old_civil_services.{{ $index }}.license_date_of_validity" name="old_civil_services[{{ $index }}][license_date_of_validity]" placeholder="Date of Validity" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0"/>
                    </div>
                    <div class="w-1/12 ps-3 text-xs">
                        <button wire:click="removeOldField({{ $index }})" wire:confirm="Are you sure you want to delete this civil service eligibility?" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach

                <!-- New Data Children -->
                @foreach ($new_civil_services as $index => $new_civil_service)
                    <div class="mb-2 w-full flex items-center space-x-2 h-12 border border-gray-200 rounded focus:outline-none"
                        x-cloak
                        x-transition:enter="transition ease-in-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in-out duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95">
                        <div class="w-3/12 ps-3 text-xs">
                            <input id="title_{{ $index }}" type="text" wire:model="new_civil_services.{{ $index }}.title" name="new_civil_services[{{ $index }}][title]" placeholder="Title" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
                            <input id="rating_{{ $index }}" type="text" wire:model="new_civil_services.{{ $index }}.rating" name="new_civil_services[{{ $index }}][rating]" placeholder="Rating" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0"/>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <input id="date_of_exam_{{ $index }}" type="date" wire:model="new_civil_services.{{ $index }}.date_of_exam" name="new_civil_services[{{ $index }}][date_of_exam]" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <input id="place_of_exam_{{ $index }}" type="text" wire:model="new_civil_services.{{ $index }}.place_of_exam" name="new_civil_services[{{ $index }}][place_of_exam]" placeholder="Place of Exam" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
                            <input id="license_num_{{ $index }}" type="text" wire:model="new_civil_services.{{ $index }}.license_num" name="new_civil_services[{{ $index }}][license_num]" placeholder="License Number" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <input id="license_date_of_validity_{{ $index }}" type="date" wire:model="new_civil_services.{{ $index }}.license_date_of_validity" name="new_civil_services[{{ $index }}][license_date_of_validity]" placeholder="Date of Validity" class="w-full rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0"/>
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
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
                        <x-button wire:click.prevent="addField" label="Add New" class="w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="my-5 p-0 flex space-x-3 justify-end">
        <div class="w-2/12">
            <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
        </div>
        <div class="w-2/12">
            <x-button wire:click.prevent="save" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
        </div>
    </div>
</div>
