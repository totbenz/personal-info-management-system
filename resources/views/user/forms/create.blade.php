<x-modal name="create-account-modal">
    <x-card title="Create New Account">
        <form action="{{ route('accounts.store') }}" method="POST">
            @csrf
            <div>
                <label for="personnel_id" class="block font-medium text-sm text-gray-700 mb-1">Employee ID</label>
                <x-select
                    name="personnel_id"
                    wire:model.live.debounce.300ms="selectedPersonnelId"
                    placeholder="Select a Employee ID"
                    :async-data="route('api.personnel_list.index')"
                    option-label="personnel_id"
                    option-value="personnel_id"
                    option-description="full_name" 
                />
            </div>

            <div class="mt-4">
                <x-input id="email" label="Email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-input id="password" label="Password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-input id="password_confirmation" label="Confirm Password" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-native-select name="role" id="role" class="form-control" label="Role">
                    <option value="teacher">Personnel</option>
                    <option value="school_head">School Head</option>
                    <option value="admin">Admin</option>
                </x-native-select>
            </div>

            <div class="mt-5 flex justify-end gap-x-4">
                <div class="w-1/6">
                    <x-button type="button" x-on:click="close" label="Cancel" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105"/>
                </div>
                <div class="w-1/6">
                    <x-button type="submit" label="Create" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                </div>
            </div>
        </form>
    </x-card>
</x-modal>
