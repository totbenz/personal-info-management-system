<div class="mx-auto py-8 px-10" >
    @if ($storeMode || $updateMode)
        <section>
            <div class="flex justify-between">
                <h4 class="font-bold text-2xl text-gray-darkest">
                    {{ $updateMode ? 'Edit ' . $school->school_name . "'s Information" : "School Information" }}
                </h4>
            </div>
            <div class="my-5">
                <div class="m-0 mb-4 p-0 flex space-x-5">
                    <span class="w-1/4">
                        <x-input id="school_id" wire:model="school_id" type="number" label="School ID" name="school_id" required/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="division" wire:model="division" type="text" label="Division"  name="division" required/>
                    </span>
                    <span class="w-1/4">
                        {{-- <x-select
                        wire:model="district_id"
                        id="district_id"
                        name="district_id"
                        placeholder="Select a District"
                        :async-data="route('api.disctricts.index')"
                        option-label="name"
                        option-value="id"
                        label="District"
                        class="form-control"
                    /> --}}
                        <x-input id="district_id" wire:model="district_id" type="text" label="District"  name="district_id" required/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-3 justify-between">
                    <span class="w-full">
                        <x-input id="school_name" wire:model="school_name" type="text" label="School Name"  name="school_name" required/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-4 justify-between">
                    <span class="w-full">
                        <x-input id="address" wire:model="address" type="text" label="Address"  name="address"  required/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-3 justify-between">
                    <span class="w-2/4">
                        <x-input id="email" wire:model="email" type="email" label="Email"  name="email" required/>
                    </span>
                    <span class="w-2/4">
                        <x-input id="phone" wire:model="phone" type="tel" label="Phone"  name="phone" required/>
                    </span>
                </div>
                <div class="flex space-x-4">
                    <div class="w-full">
                        {{-- <label for="curricular_classification" class="mb-2 block font-medium text-sm text-gray-700">Curricular Classifications</label>
                        <x-grade-level-multi-select :curricular_classification="$curricular_classification"/> --}}
                        <x-native-select wire:model="curricular_classification" class="form-control" label="Curricular Classifications">
                            <option value="grade 1-6">Grade 1-6</option>
                            <option value="grade 7-10">Grade 7-10</option>
                            <option value="grade 11-12">Grade 11-12</option>
                        </x-native-select>
                    </div>
                </div>
            </div>
            <div class="m-0 mb-4 p-0 flex space-x-3 justify-end">
                <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
                @if ($storeMode == true)
                    <x-button wire:click.prevent="store" label="Save" class="px-5 bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                @else
                    <x-button wire:click.prevent="update" label="Save" class="px-5 bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                @endif
            </div>
        </section>
    @elseif ($showMode)
        <section>
            <div class="flex justify-between">
                <div class="w-3/4">
                    <h4 class="font-bold text-2xl text-gray-darkest">School's Information</h4>
                </div>
                <div class="w-1/4 flex space-x-2 justify-end">
                    <div class="w-1/2">
                        <x-button wire:click.prevent="edit" label="Edit" class="w-full px-5 bg-main text-white tracking-wider hover:hover:bg-main_hover hover:scale-105 duration-100"/>
                    </div>
                </div>
            </div>
            <div class="mt-7">
                <div class="m-0 mb-4 p-0 flex space-x-3">
                    <span class="w-1/4">
                        <x-input id="school_id" wire:model="school_id" type="number" label="School ID" class="bg-gray-50 border-gray-300" name="school_id"  readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="division" wire:model="division" type="text" label="Division" class="bg-gray-50 border-gray-300" name="division" readonly/>
                    </span>
                    <span class="w-1/4">
                        <x-input id="district_id" wire:model="district_id" type="text" label="District" class="bg-gray-50 border-gray-300" name="district"  readonly/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-3 justify-between">
                    <span class="w-full">
                        <x-input id="school_name" wire:model="school_name" type="text" label="School Name" class="bg-gray-50 border-gray-300" name="school_name"  readonly/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-4 justify-between">
                    <span class="w-full">
                        <x-input id="address" wire:model="address" type="text" label="Address" class="bg-gray-50 border-gray-300" name="address"   readonly/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-3 justify-between">
                    <span class="w-2/4">
                        <x-input id="email" wire:model="email" type="email" label="Email" class="bg-gray-50 border-gray-300" name="email"  readonly/>
                    </span>
                    <span class="w-2/4">
                        <x-input id="phone" wire:model="phone" type="tel" label="Phone" class="bg-gray-50 border-gray-300" name="phone"  readonly/>
                    </span>
                </div>
                <div class="flex space-x-4">
                    <div class="w-full">
                        {{-- <label for="curricular_classification" class="mb-2 block font-medium text-sm text-gray-700">Curricular Classifications</label> --}}
                        {{-- <x-grade-level-multi-select :curricular_classification="$curricular_classification"/> --}}
                        <x-native-select wire:model="curricular_classification" class="form-control" label="Curricular Classifications">
                            <option value="grade 1-6">Grade 1-6</option>
                            <option value="grade 7-10">Grade 7-10</option>
                            <option value="grade 11-12">Grade 11-12</option>
                        </x-native-select>
                    </div>
                </div>
            </div>
            <div class="m-0 mb-4 p-0 flex space-x-3 justify-end">
                @if (!$showMode)
                    <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
                    @if ($storeMode == true)
                        <x-button wire:click.prevent="store" label="Save" class="px-5 bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                    @endif

                @endif
            </div>
        </section>
    @endif
</div>
