<!--Add New School Modal -->
<x-modal name="create-school-modal" blur="2xl">
    <x-card class="rounded-lg shadow-lg">
        <form action="{{ route('schools.store') }}" method="post">
            @csrf
            <div class="px-5 py-5">

                <!-- Title -->
                <div class="flex space-x-3 justify-between">
                    <span class="w-full">
                        <h2 class="text-lg font-semibold text-gray-800">Add New School</h2>
                    </span>
                </div>
                <hr class="mb-7">

                <div class="m-0 mb-4 p-0 flex space-x-5">
                    <span class="w-2/6">
                        <label for="school_id" class="block font-medium text-sm text-gray-700">
                            School ID <span class="text-red-500">*</span>
                        </label>
                        <x-input id="school_id" wire:model="school_id" type="number" name="school_id" placeholder="School ID" required/>
                    </span>
                    <span class="w-2/6">
                        <label for="division" class="block font-medium text-sm text-gray-700">
                            Division <span class="text-red-500">*</span>
                        </label>
                        <x-native-select id="division" wire:model="division" name="division" required>
                            <option value="" disabled selected>Select Division</option>
                            @for ($i = 1; $i <= 13; $i++)
                                <option value="Division {{ $i }}">Division {{ $i }}</option>
                            @endfor
                        </x-native-select>
                    </span>
                    <span class="w-2/6">
                        <label for="district_id" class="block font-medium text-sm text-gray-700">
                            District <span class="text-red-500">*</span>
                        </label>
                        <x-input id="district_id" wire:model="district_id" type="number" name="district_id" placeholder="District" required/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-3 justify-between">
                    <span class="w-full">
                        <label for="school_name" class="block font-medium text-sm text-gray-700">
                            School Name <span class="text-red-500">*</span>
                        </label>
                        <x-input id="school_name" wire:model="school_name" type="text" name="school_name" placeholder="School Name" required/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-4 justify-between">
                    <span class="w-full">
                        <label for="address" class="block font-medium text-sm text-gray-700">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <x-input id="address" wire:model="address" type="text" name="address" placeholder="Address" required/>
                    </span>
                </div>
                <div class="mb-4 flex space-x-3 justify-between">
                    <span class="w-2/4">
                        <label for="email" class="block font-medium text-sm text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <x-input id="email" wire:model="email" type="email" name="email" placeholder="Email" required/>
                    </span>
                    <span class="w-2/4">
                        <label for="phone" class="block font-medium text-sm text-gray-700">
                            Phone <span class="text-red-500">*</span>
                        </label>
                        <x-input id="phone" wire:model="phone" type="tel" name="phone" placeholder="Phone" required/>
                    </span>
                </div>
                <div class="flex space-x-4">
                    <div class="w-2/4">
                        <label for="curricular_classification" class="block font-medium text-sm text-gray-700">
                            Curricular Classifications <span class="text-red-500">*</span>
                        </label>
                        <x-native-select wire:model="curricular_classification" class="form-control" required>
                            <option value="" disabled selected>Select Curricular Classification</option>
                            <option value="grade 1-6">Grade 1-6</option>
                            <option value="grade 7-10">Grade 7-10</option>
                            <option value="grade 11-12">Grade 11-12</option>
                        </x-native-select>
                    </div>
                </div>
            </div>
            <div class="my-5 p-0 flex space-x-3 justify-end">
                <div class="w-2/12">
                    <x-button wire:click.prevent="cancel" x-on:click="$dispatch('close-modal', { name: 'create-school-modal' })" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105"/>
                </div>
                <div class="w-2/12">
                    <x-button type="button" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105" x-on:click="$dispatch('close')"/>
                </div>
                <div class="w-2/12">
                    <x-button type="submit" label="Save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                </div>
            </div>
        </form>
    </x-card>
</x-modal>
