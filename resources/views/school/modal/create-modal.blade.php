<x-modal name="create-school-modal" blur="2xl">
    <x-card>
        <form action="{{ route('schools.store') }}" method="post">
            @csrf
            <div class="px-5 py-5">
                <div class="m-0 mb-4 p-0 flex space-x-5">
                    <span class="w-2/6">
                        <x-input id="school_id" wire:model="school_id" type="number" label="School ID" name="school_id" required/>
                    </span>
                    <span class="w-2/6">
                        <x-input id="division" wire:model="division" type="text" label="Division"  name="division" required/>
                    </span>
                    <span class="w-2/6">
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
                    <div class="w-2/4">
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
            <div class="my-5 p-0 flex space-x-3 justify-end">
                <div class="w-2/12">
                    <x-button wire:click.prevent="cancel" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105 duration-150"/>
                </div>
                <div class="w-2/12">
                    <x-button type="submit" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                </div>
            </div>
        </form>
    </x-card>
</x-modal>
