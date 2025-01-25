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

                <button wire:click.prevent="back" type="button" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                        </svg>

                        <p>Back</p>
                    </span>
                </button>
            </div>
            @livewire('form.update-address-form', ['id' => $personnel->id])
        @endisset
    @endif
</div>
