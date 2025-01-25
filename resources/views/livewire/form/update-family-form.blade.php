<section>
    <div>
        <div class="mt-5">
            <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                <span class="w-1/4">
                    <x-input id="fathers_first_name" wire:model="fathers_first_name" label="Father's First Name" type="text" name="fathers_first_name" required/>
                </span>
                <span class="w-1/4">
                    <x-input id="fathers_middle_name" wire:model="fathers_middle_name" label="Father's Middle Name" type="text" name="fathers_middle_name" required/>
                </span>
                <span class="w-1/4">
                    <x-input id="fathers_last_name" wire:model="fathers_last_name" label="Father's Last Name" type="text" name="fathers_last_name" required/>
                </span>
                <span class="w-1/4">
                    <x-input id="fathers_name_ext" wire:model="fathers_name_ext" label="Father's Name Extension" type="text" name="fathers_name_ext"/>
                </span>
            </div>
            <div class="m-0 mb-4 p-0 flex space-x-3">
                <span class="w-1/4">
                    <x-input id="mothers_first_name" wire:model="mothers_first_name" label="Mother's First Name" type="text" name="mothers_first_name" required/>
                </span>
                <span class="w-1/4">
                    <x-input id="mothers_middle_name" wire:model="mothers_middle_name" label="Mother's Middle Name" type="text" name="mothers_middle_name" required/>
                </span>
                <span class="w-1/4">
                    <x-input id="mothers_last_name" wire:model="mothers_last_name" label="Mother's Maiden Name" type="text" name="mothers_last_name" required/>
                </span>
            </div>
        </div>

        <div class="mt-10">
            <h5 class="font-bold text-xl text-gray-darkest">Spouse</h5>
            <section>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/4">
                        <x-input id="spouse_first_name" wire:model="spouse_first_name" label="First Name" type="text" name="spouse_first_name" required/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="spouse_middle_name" wire:model="spouse_middle_name" label="Middle Name" type="text" name="spouse_middle_name" required/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="spouse_last_name" wire:model="spouse_last_name" label="Last Name" type="text" name="spouse_last_name" required/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="spouse_name_ext" wire:model="spouse_name_ext" label="Name Extension" type="text" name="spouse_name_ext"/>
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/2">
                        <x-input id="spouse_occupation" wire:model="spouse_occupation" label="Occupation" type="text" name="spouse_occupation" required/>
                    </span>
                    <span class="w-1/2">
                        <x-input id="spouse_business_name" wire:model="spouse_business_name" label="Employer/Business Name" type="text" name="spouse_business_name" required/>
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/2">
                        <x-input id="spouse_business_address" wire:model="spouse_business_address" label="Business Address" type="text" name="spouse_business_address" required/>
                    </span>
                    <span class="w-1/2">
                        <x-input id="spouse_tel_no" wire:model="spouse_tel_no" label="Telephone No." type="text" name="spouse_tel_no" required/>
                    </span>
                </div>
            </section>
        </div>

        <div class="mt-10">
            <h5 class="font-bold text-xl text-gray-darkest">Children</h5>
            <section>
                <div class="mt-3">
                    <div class="ps-5 w-full flex space-x-3 h-10 border border-gray-100 bg-gray-lightest items-center">
                        <h6 class="w-9/12">
                            <span class="text-xs text-gray-dark font-semibold uppercase">Full Name</span>
                        </h6>
                        <h6 class="w-2/12">
                            <span class="text-xs text-gray-dark font-semibold uppercase">Date of Birth</span>
                        </h6>
                    </div>

                    <div class="mt-2">
                        <!-- Old Data Children -->
                        @foreach ($old_children as $index => $child)
                            <div class="mb-2 px-3 w-full flex items-center space-x-3 h-12 border border-gray-200 rounded focus:outline-none">
                                <div class="w-8/12 ps-3 text-xs">
                                    {{-- {{ $old_children[{{ $index }}]['first_name'] }} --}}
                                    <div class="sm:flex space-x-1 rounded-md border border-gray-300">
                                        <input id="first_name_{{ $index }}" type="text" wire:model="old_children.{{ $index }}.first_name" name="old_children[{{ $index }}][first_name]" placeholder="First Name" class="w-[14rem] rounded-s-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                        <input id="middle_name_{{ $index }}" type="text" wire:model="old_children.{{ $index }}.middle_name" name="old_children[{{ $index }}][middle_name]" placeholder="M.I." class="w-[4rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                        <input id="last_name_{{ $index }}" type="text" wire:model="old_children.{{ $index }}.last_name" name="old_children[{{ $index }}][last_name]" placeholder="Last Name" class="w-[14rem] rounded-none border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                        <input id="name_ext_{{ $index }}" type="text" wire:model="old_children.{{ $index }}.name_ext" name="old_children[{{ $index }}][name_ext]" placeholder="Ext." class="w-[4rem] rounded-e-md border-none appearance-none placeholder-gray-400 focus:ring-0" required/>
                                    </div>
                                </div>
                                <div class="w-3/12 ps-3 text-xs">
                                    <x-input type="date" wire:model="old_children.{{ $index }}.date_of_birth" name="old_children[{{ $index }}][date_of_birth]" class="text-xs" placeholder="0" required/>
                                </div>
                                <div class="w-1/12 pe-3 text-xs text-center">
                                    <button wire:click="confirmRemoveOldField({{ $index }})" wire:confirm="Are you sure you want to delete this child?" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <!-- New Data Children -->
                        @foreach ($new_children as $index => $new_child)
                            <div class="mb-2 w-full flex items-center space-x-2 h-12 border border-gray-200 rounded focus:outline-none"
                                x-cloak
                                x-transition:enter="transition ease-in-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in-out duration-200"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95">
                                <div class="w-8/12 ps-3 text-xs">
                                    <div class="sm:flex space-x-1 rounded-md border border-gray-300">
                                        <x-input id="new_children.{{ $index }}.first_name"
                                                 wire:model="new_children.{{ $index }}.first_name"
                                                 name="new_children.{{ $index }}.first_name"
                                                 type="text"
                                                 placeholder="First Name"
                                                 class="form-control" required/>
                                        <x-input id="new_children.{{ $index }}.middle_name" wire:model="new_children.{{ $index }}.middle_name" type="text" name="new_children[{{ $index }}][middle_name]" placeholder="M.I." class="form-control" required/>
                                        <x-input id="last_name_{{ $index }}" wire:model="new_children.{{ $index }}.last_name" type="text" name="new_children[{{ $index }}][last_name]" placeholder="Last Name" class="form-control" required/>
                                        <x-input id="name_ext_{{ $index }}" wire:model="new_children.{{ $index }}.name_ext" type="text" name="new_children[{{ $index }}][name_ext]" placeholder="Ext." cclass="form-control" required/>
                                    </div>
                                </div>
                                <div class="w-3/12 ps-3 text-xs">
                                    <x-input wire:model="new_children.{{ $index }}.date_of_birth" type="date" name="new_children[{{ $index }}][date_of_birth]" class="text-xs" placeholder="0" required/>
                                </div>
                                <div class="w-1/12 pe-3 text-xs text-center">
                                    <button wire:click.prevent="removeNewField({{ $index }})" class="m-0 p-0 text-gray-400 hover:text-red-600 hover:scale-105 duration-300">
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
            </section>
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
</section>
