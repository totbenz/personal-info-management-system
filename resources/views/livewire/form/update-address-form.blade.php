<section>
    <div>
        <div class="mt-5">
            <h5 class="font-bold text-xl text-gray-darkest">Permanent Address</h5>
            <div class="mt-5 mb-4 p-0 flex space-x-3 justify-between">
                <span class="w-1/2">
                    <x-input  class="form-control"  id="permanent_house_no" wire:model="permanent_house_no" type="text" name="permanent_house_no" label="House/Block/Lot No."/>
                </span>
                <span class="w-1/2">
                    <x-input  class="form-control"  id="permanent_st_address" wire:model="permanent_st_address" type="text" name="permanent_st_address" label="Street Address"/>
                </span>
            </div>
            <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                <span class="w-1/3">
                    <x-input  class="form-control"  id="permanent_subdivision" wire:model="permanent_subdivision" type="text" name="permanent_subdivision" label="Subdivision/Village"/>
                </span>
                <span class="w-1/3">
                    <x-input  class="form-control"  id="permanent_brgy" wire:model="permanent_brgy" type="text" name="permanent_brgy" label="Barangay"/>
                </span>
                <span class="w-1/3">
                    <x-input  class="form-control"  id="permanent_city" wire:model="permanent_city" type="text" name="permanent_city" label="City/Municipality"/>
                </span>
            </div>
            <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                <span class="w-1/3">
                    <x-input  class="form-control"  id="permanent_province" wire:model="permanent_province" type="text" name="permanent_province" label="Province"/>
                </span>
                <span class="w-1/3">
                    <x-input  class="form-control"  id="permanent_region" wire:model="permanent_region" type="text" name="permanent_region" label="Region"/>
                </span>
                <span class="w-1/3">
                    <x-input  class="form-control"  id="permanent_zip_code" wire:model="permanent_zip_code" type="text" name="permanent_zip_code" label="Zip Code"/>
                </span>
            </div>
        </div>

        <div class="mt-10">
            <h5 class="font-bold text-xl text-gray-darkest">Residential Address</h5>
            <section>
                <div class="mt-5 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/2">
                        <x-input  class="form-control"  id="residential_house_no" wire:model="residential_house_no" type="text" name="residential_house_no" label="House/Block/Lot No."/>
                    </span>
                    <span class="w-1/2">
                        <x-input  class="form-control"  id="residential_st_address" wire:model="residential_st_address" type="text" name="residential_st_address" label="Street Address"/>
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/3">
                        <x-input  class="form-control"  id="residential_subdivision" wire:model="residential_subdivision" type="text" name="residential_subdivision" label="Subdivision/Village"/>
                    </span>
                    <span class="w-1/3">
                        <x-input  class="form-control"  id="residential_brgy" wire:model="residential_brgy" type="text" name="residential_brgy" label="Barangay"/>
                    </span>
                    <span class="w-1/3">
                        <x-input  class="form-control"  id="residential_city" wire:model="residential_city" type="text" name="residential_city" label="City/Municipality"/>
                    </span>
                </div>
                <div class="m-0 mb-4 p-0 flex space-x-3 justify-between">
                    <span class="w-1/3">
                        <x-input  class="form-control"  id="residential_province" wire:model="residential_province" type="text" name="residential_province" label="Province"/>
                    </span>
                    <span class="w-1/3">
                        <x-input  class="form-control"  id="residential_region" wire:model="residential_region" type="text" name="residential_region" label="Region"/>
                    </span>
                    <span class="w-1/3">
                        <x-input class="form-control" id="residential_zip_code" wire:model="residential_zip_code" type="text" name="residential_zip_code" label="Zip Code"/>
                    </span>
                </div>
            </section>
        </div>

        <div class="mt-10">
            <h5 class="font-bold text-xl text-gray-darkest">Contact Person In Case Of Emergency</h5>
            <section>
                <div class="m-0 mb-4 p-0 flex space-x-3">
                    <div class="w-5/12">
                        <x-input class="form-control" id="contact_person_name" wire:model="contact_person_name" type="text" name="contact_person_name" label="Name"/>
                    </div>
                    <span class="w-4/12">
                        <x-input class="form-control" id="contact_person_email" wire:model="contact_person_email" type="email" name="contact_person_email" label="Email"/>
                    </span>
                    <span class="w-3/12">
                        <x-input class="form-control" id="contact_person_mobile_no" wire:model="contact_person_mobile_no" type="number" name="contact_person_mobile_no" label="Mobile No."/>
                    </span>
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
