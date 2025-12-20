<div class="mx-auto py-8 px-10" >
    @if (!$updateMode)
        <section>
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Address</h4>
                <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-main border border-main rounded-lg hover:bg-main_hover hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                        <p>Edit</p>
                    </span>
                </button>
            </div>

            <div>
                <div class="mt-5">
                    <h5 class="font-bold text-xl text-gray-darkest">Permanent Address</h5>
                    <div class="mt-5 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-1/2">
                            <x-input  class="bg-gray-50 border-gray-300 "  id="permanent_house_no" wire:model="permanent_house_no" type="text" name="permanent_house_no" label="House/Block/Lot No." readonly/>
                        </span>
                        <span class="w-1/2">
                            <x-input  class="bg-gray-50 border-gray-300 "  id="permanent_st_address" wire:model="permanent_st_address" type="text" name="permanent_st_address" label="Street Address" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-1/3">
                            <x-input  class="bg-gray-50 border-gray-300 "  id="permanent_subdivision" wire:model="permanent_subdivision" type="text" name="permanent_subdivision" label="Subdivision/Village" readonly/>
                        </span>
                        <span class="w-1/3">
                            <x-input  class="bg-gray-50 border-gray-300 "  id="permanent_brgy" wire:model="permanent_brgy" type="text" name="permanent_brgy" label="Barangay" readonly/>
                        </span>
                        <span class="w-1/3">
                            <x-input  class="bg-gray-50 border-gray-300 "  id="permanent_city" wire:model="permanent_city" type="text" name="permanent_city" label="City/Municipality" readonly/>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-1/3">
                            <x-input  class="bg-gray-50 border-gray-300 "  id="permanent_province" wire:model="permanent_province" type="text" name="permanent_province" label="Province" readonly/>
                        </span>
                        <span class="w-1/3">
                            <x-input  class="bg-gray-50 border-gray-300 "  id="permanent_region" wire:model="permanent_region" type="text" name="permanent_region" label="Region" readonly/>
                        </span>
                        <span class="w-1/3">
                            <x-input  class="bg-gray-50 border-gray-300 "  id="permanent_zip_code" wire:model="permanent_zip_code" type="text" name="permanent_zip_code" label="Zip Code" readonly/>
                        </span>
                    </div>
                </div>

                <div class="mt-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Residential Address</h5>
                    <section>
                        <div class="mt-5 mb-4 p-0 flex space-x-3 justify-between">
                            <span class="w-1/2">
                                <x-input  class="bg-gray-50 border-gray-300 "  id="residential_house_no" wire:model="residential_house_no" type="text" name="residential_house_no" label="House/Block/Lot No." readonly/>
                            </span>
                            <span class="w-1/2">
                                <x-input  class="bg-gray-50 border-gray-300 "  id="residential_st_address" wire:model="residential_st_address" type="text" name="residential_st_address" label="Street Address" readonly/>
                            </span>
                        </div>
                        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                            <span class="w-1/3">
                                <x-input  class="bg-gray-50 border-gray-300 "  id="residential_subdivision" wire:model="residential_subdivision" type="text" name="residential_subdivision" label="Subdivision/Village" readonly/>
                            </span>
                            <span class="w-1/3">
                                <x-input  class="bg-gray-50 border-gray-300 "  id="residential_brgy" wire:model="residential_brgy" type="text" name="residential_brgy" label="Barangay" readonly/>
                            </span>
                            <span class="w-1/3">
                                <x-input  class="bg-gray-50 border-gray-300 "  id="residential_city" wire:model="residential_city" type="text" name="residential_city" label="City/Municipality" readonly/>
                            </span>
                        </div>
                        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                            <span class="w-1/3">
                                <x-input  class="bg-gray-50 border-gray-300 "  id="residential_province" wire:model="residential_province" type="text" name="residential_province" label="Province" readonly/>
                            </span>
                            <span class="w-1/3">
                                <x-input  class="bg-gray-50 border-gray-300 "  id="residential_region" wire:model="residential_region" type="text" name="residential_region" label="Region" readonly/>
                            </span>
                            <span class="w-1/3">
                                <x-input class="bg-gray-50 border-gray-300 " id="residential_zip_code" wire:model="residential_zip_code" type="text" name="residential_zip_code" label="Zip Code" readonly/>
                            </span>
                        </div>
                    </section>
                </div>

                <div class="mt-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Contact Person In Case Of Emergency</h5>
                    <section>
                        <div class="m-0 mb-4 p-0 flex space-x-3">
                            <div class="w-5/12">
                                <x-input class="bg-gray-50 border-gray-300 " id="contact_person_name" wire:model="contact_person_name" type="text" name="contact_person_name" label="Name" readonly/>
                            </div>
                            <span class="w-4/12">
                                <x-input class="bg-gray-50 border-gray-300 " id="contact_person_email" wire:model="contact_person_email" type="email" name="contact_person_email" label="Email" readonly/>
                            </span>
                            <span class="w-3/12">
                                <x-input class="bg-gray-50 border-gray-300 " id="contact_person_mobile_no" wire:model="contact_person_mobile_no" type="number" name="contact_person_mobile_no" label="Mobile No."  readonly/>
                            </span>
                        </div>
                    </section>
                </div>
            </div>

        </section>
    @else
        @isset($personnel)
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">Edit Address</h4>
            </div>

            <div>
                <div class="mt-5">
                    <h5 class="font-bold text-xl text-gray-darkest">Permanent Address</h5>
                    <div class="mt-5 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-1/2">
                            <x-input class="form-control" id="permanent_house_no" wire:model.live="permanent_house_no" type="text" name="permanent_house_no" label="House/Block/Lot No." required />
                            <p class="text-xs text-gray-500 mt-1">Enter house number, block, or lot number</p>
                            @error('permanent_house_no')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="permanent_house_no" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-1/2">
                            <x-input class="form-control" id="permanent_st_address" wire:model.live="permanent_st_address" type="text" name="permanent_st_address" label="Street Address" required />
                            <p class="text-xs text-gray-500 mt-1">Street name and address details</p>
                            @error('permanent_st_address')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="permanent_st_address" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-1/3">
                            <x-input class="form-control" id="permanent_subdivision" wire:model.live="permanent_subdivision" type="text" name="permanent_subdivision" label="Subdivision/Village" />
                            <p class="text-xs text-gray-500 mt-1">Subdivision or village name (if applicable)</p>
                            @error('permanent_subdivision')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="permanent_subdivision" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-1/3">
                            <x-input class="form-control" id="permanent_brgy" wire:model.live="permanent_brgy" type="text" name="permanent_brgy" label="Barangay" required />
                            <p class="text-xs text-gray-500 mt-1">Barangay name</p>
                            @error('permanent_brgy')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="permanent_brgy" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-1/3">
                            <x-input class="form-control" id="permanent_city" wire:model.live="permanent_city" type="text" name="permanent_city" label="City/Municipality" required />
                            <p class="text-xs text-gray-500 mt-1">City or municipality name</p>
                            @error('permanent_city')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="permanent_city" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                    <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                        <span class="w-1/3">
                            <x-input class="form-control" id="permanent_province" wire:model.live="permanent_province" type="text" name="permanent_province" label="Province" />
                            <p class="text-xs text-gray-500 mt-1">Province name (optional)</p>
                            @error('permanent_province')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="permanent_province" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-1/3">
                            <x-input class="form-control" id="permanent_region" wire:model.live="permanent_region" type="text" name="permanent_region" label="Region" required />
                            <p class="text-xs text-gray-500 mt-1">Region name (e.g., NCR, Region I)</p>
                            @error('permanent_region')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="permanent_region" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                        <span class="w-1/3">
                            <x-input class="form-control" id="permanent_zip_code" wire:model.live="permanent_zip_code" type="text" name="permanent_zip_code" label="Zip Code" required />
                            <p class="text-xs text-gray-500 mt-1">Postal/ZIP code (4-5 digits)</p>
                            @error('permanent_zip_code')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div wire:loading wire:target="permanent_zip_code" class="text-xs text-blue-600 mt-1">Validating...</div>
                        </span>
                    </div>
                </div>

                <div class="mt-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Residential Address</h5>
                    <section>
                        <div class="mt-5 mb-4 p-0 flex space-x-3 justify-between">
                            <span class="w-1/2">
                                <x-input class="form-control" id="residential_house_no" wire:model.live="residential_house_no" type="text" name="residential_house_no" label="House/Block/Lot No." required />
                                <p class="text-xs text-gray-500 mt-1">Enter house number, block, or lot number</p>
                                @error('residential_house_no')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="residential_house_no" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/2">
                                <x-input class="form-control" id="residential_st_address" wire:model.live="residential_st_address" type="text" name="residential_st_address" label="Street Address" required />
                                <p class="text-xs text-gray-500 mt-1">Street name and address details</p>
                                @error('residential_st_address')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="residential_st_address" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                        </div>
                        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                            <span class="w-1/3">
                                <x-input class="form-control" id="residential_subdivision" wire:model.live="residential_subdivision" type="text" name="residential_subdivision" label="Subdivision/Village" />
                                <p class="text-xs text-gray-500 mt-1">Subdivision or village name (if applicable)</p>
                                @error('residential_subdivision')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="residential_subdivision" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/3">
                                <x-input class="form-control" id="residential_brgy" wire:model.live="residential_brgy" type="text" name="residential_brgy" label="Barangay" required />
                                <p class="text-xs text-gray-500 mt-1">Barangay name</p>
                                @error('residential_brgy')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="residential_brgy" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/3">
                                <x-input class="form-control" id="residential_city" wire:model.live="residential_city" type="text" name="residential_city" label="City/Municipality" required />
                                <p class="text-xs text-gray-500 mt-1">City or municipality name</p>
                                @error('residential_city')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="residential_city" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                        </div>
                        <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                            <span class="w-1/3">
                                <x-input class="form-control" id="residential_province" wire:model.live="residential_province" type="text" name="residential_province" label="Province" required />
                                <p class="text-xs text-gray-500 mt-1">Province name</p>
                                @error('residential_province')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="residential_province" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/3">
                                <x-input class="form-control" id="residential_region" wire:model.live="residential_region" type="text" name="residential_region" label="Region" required />
                                <p class="text-xs text-gray-500 mt-1">Region name (e.g., NCR, Region I)</p>
                                @error('residential_region')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="residential_region" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-1/3">
                                <x-input class="form-control" id="residential_zip_code" wire:model.live="residential_zip_code" type="text" name="residential_zip_code" label="Zip Code" required />
                                <p class="text-xs text-gray-500 mt-1">Postal/ZIP code (4-5 digits)</p>
                                @error('residential_zip_code')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="residential_zip_code" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                        </div>
                    </section>
                </div>

                <div class="mt-10">
                    <h5 class="font-bold text-xl text-gray-darkest">Contact Person In Case Of Emergency</h5>
                    <section>
                        <div class="m-0 mb-4 p-0 flex space-x-3">
                            <div class="w-5/12">
                                <x-input class="form-control" id="contact_person_name" wire:model.live="contact_person_name" type="text" name="contact_person_name" label="Name" required />
                                <p class="text-xs text-gray-500 mt-1">Full name of emergency contact person</p>
                                @error('contact_person_name')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="contact_person_name" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </div>
                            <span class="w-4/12">
                                <x-input class="form-control" id="contact_person_email" wire:model.live="contact_person_email" type="email" name="contact_person_email" label="Email" />
                                <p class="text-xs text-gray-500 mt-1">Email address (optional)</p>
                                @error('contact_person_email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="contact_person_email" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                            <span class="w-3/12">
                                <x-input class="form-control" id="contact_person_mobile_no" wire:model.live="contact_person_mobile_no" type="number" name="contact_person_mobile_no" label="Mobile No." required />
                                <p class="text-xs text-gray-500 mt-1">Mobile phone number</p>
                                @error('contact_person_mobile_no')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <div wire:loading wire:target="contact_person_mobile_no" class="text-xs text-blue-600 mt-1">Validating...</div>
                            </span>
                        </div>
                    </section>
                </div>
            </div>

            <div class="my-5 p-0 flex space-x-3 justify-end">
                <div class="w-2/12">
                    <x-button wire:click.prevent="cancel" wire:loading.attr="disabled" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
                </div>
                <div class="w-2/12">
                    <x-button wire:click.prevent="save" wire:loading.attr="disabled" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover">
                        <span wire:loading wire:target="save" class="ml-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </x-button>
                </div>
            </div>
        @endisset
    @endif
</div>
