<div>
    <div class="mt-5">
        <div class="mt-3">
            <div class="mt-2">
                <!-- Old Data Children -->
                @foreach ($old_training_certifications as $index => $old_training_certification)
                    <div class="flex justify-between items-center">
                        <div class="w-11/12 mb-4 px-3 py-3 bg-gray-50 border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-6/12 text-xs">
                                    <x-input type="text" wire:model="old_training_certifications.{{ $index }}.training_seminar_title" name="old_training_certification[{{ $index }}][training_seminar_title]" label="Development Interventions/ Training Programs" class="form-control" required/>
                                </div>
                                <div class="w-6/12 text-xs">
                                    <x-input type="text" wire:model="old_training_certifications.{{ $index }}.sponsored" name="old_training_certification[{{ $index }}][sponsored]" class="text-xs" label="Sponsored" required/>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center space-x-3">
                                <div class="w-3/12 text-xs">
                                    <x-input type="date" wire:model="old_training_certifications.{{ $index }}.inclusive_from" name="old_training_certification[{{ $index }}][inclusive_from]" class="form-control" label="Start Date" required/>
                                </div>
                                <div class="w-3/12 text-xs">
                                    <x-input type="date" wire:model="old_training_certifications.{{ $index }}.inclusive_to" name="old_training_certification[{{ $index }}][inclusive_to]" class="form-control" label="End Date" required/>
                                </div>
                                <div class="w-3/12 text-xs">
                                    <x-input type="text" wire:model="old_training_certifications.{{ $index }}.type" name="old_training_certification[{{ $index }}][type]" class="text-xs" label="Type" required/>
                                </div>
                                <div class="w-3/12 text-xs">
                                    <x-input type="number" wire:model="old_training_certifications.{{ $index }}.hours" name="old_training_certification[{{ $index }}][hours]" class="text-xs" label="Hours" required/>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/12 px-5">
                            <button wire:click.prevent="removeOldField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- New Data Children -->
                @foreach ($new_training_certifications as $index => $new_training_certification)
                <div class="flex justify-between items-center"
                     x-cloak
                     x-transition:enter="transition ease-in-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in-out duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                    <div class="w-11/12 mb-4 px-3 py-3 bg-white border border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-6/12 text-xs">
                                <x-input type="text"
                                         wire:model="new_training_certifications.{{ $index }}.training_seminar_title"
                                         name="new_training_certification[{{ $index }}][training_seminar_title]"
                                         label="Development Interventions/ Training Programs" class="form-control" required/>
                            </div>
                            <div class="w-6/12 text-xs">
                                <x-input type="text"
                                         wire:model="new_training_certifications.{{ $index }}.sponsored"
                                         name="new_training_certification[{{ $index }}][sponsored]"
                                         class="text-xs" label="Sponsored" required/>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center space-x-3">
                            <div class="w-3/12 text-xs">
                                <x-input type="date"
                                         wire:model="new_training_certifications.{{ $index }}.inclusive_from"
                                         name="new_training_certification[{{ $index }}][inclusive_from]"
                                         class="form-control" label="Start Date" required/>
                            </div>
                            <div class="w-3/12 text-xs">
                                <x-input type="date"
                                         wire:model="new_training_certifications.{{ $index }}.inclusive_to"
                                         name="new_training_certification[{{ $index }}][inclusive_to]"
                                         class="form-control" label="End Date" required/>
                            </div>
                            <div class="w-3/12 text-xs">
                                <x-input type="text"
                                         wire:model="new_training_certifications.{{ $index }}.type"
                                         name="new_training_certification[{{ $index }}][type]"
                                         class="text-xs" label="Type" required/>
                            </div>
                            <div class="w-3/12 text-xs">
                                <x-input type="number"
                                         wire:model="new_training_certifications.{{ $index }}.hours"
                                         name="new_training_certification[{{ $index }}][hours]"
                                         class="text-xs" label="Hours" required/>
                            </div>
                        </div>
                    </div>
                    <div class="w-1/12 px-5">
                        <button wire:click.prevent="removeNewField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                    {{-- <div class="mb-2 px-3 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none"
                        x-cloak
                        x-transition:enter="transition ease-in-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in-out duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95">
                        <div class="w-2/12 ps-3 text-xs">
                            <x-input type="date" wire:model="new_training_certifications.{{ $index }}.inclusive_from" name="new_training_certifications[{{ $index }}][inclusive_from]" class="text-xs" required/>
                        </div>
                        <div class="w-2/12 ps-3 text-xs">
                            <x-input type="date" wire:model="new_training_certifications.{{ $index }}.inclusive_to" name="new_training_certifications[{{ $index }}][inclusive_to]" class="text-xs" required/>
                        </div>
                        <div class="w-3/12 ps-3 text-xs">
                            <x-input type="text" wire:model="new_training_certifications.{{ $index }}.training_seminar_title" name="new_training_certifications[{{ $index }}][training_seminar_title]" class="text-xs" required/>
                        </div>
                        <div class="w-3/12 ps-3 text-xs">
                            <x-input type="text" wire:model="new_training_certifications.{{ $index }}.sponsored" name="new_training_certifications[{{ $index }}][sponsored]" class="text-xs" required/>
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
                            <x-input type="text" wire:model="new_training_certifications.{{ $index }}.type" name="new_training_certifications[{{ $index }}][type]" class="text-xs" required/>
                        </div>
                        <div class="w-1/12 ps-3 text-xs">
                            <x-input type="number" wire:model="new_training_certifications.{{ $index }}.hours" name="new_training_certifications[{{ $index }}][hours]" class="text-xs" required/>
                        </div>
                    </div> --}}
                @endforeach

                <div class="mt-3 flex space-x-3 items-center">
                    <div class="w-full">
                        <x-button wire:click.prevent="addField" label="New" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
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
