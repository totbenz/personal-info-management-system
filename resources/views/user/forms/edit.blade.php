<x-modal name="edit-account-modal" wire:model="showEditModal">
    <x-card title="Edit Account">
        <div class="px-8 py-5">
            <form wire:submit.prevent="save" x-data>
                <div>
                    <x-input
                        wire:model="editingAccount.personnel.personnel_id"
                        id="personnel_id"
                        label="Personnel ID"
                        class="block mt-1 w-full"
                        type="number"
                        name="personnel_id"
                        required />
                    <x-input-error :messages="$errors->get('editingAccount.personnel.personnel_id')" />
                </div>

                <div class="mt-4">
                    <x-input
                        wire:model="editingAccount.email"
                        id="email"
                        label="Email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        required
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('editingAccount.email')" />
                </div>

                <div class="mt-4">
                    <x-native-select
                        wire:model="editingAccount.role"
                        class="form-control"
                        label="Role"
                        name="role"
                        id="role">
                        <option value="">Select Role</option>
                        <option value="teacher">Teacher</option>
                        <option value="school_head">School Head</option>
                        <option value="admin">Admin</option>
                    </x-native-select>
                    <x-input-error :messages="$errors->get('editingAccount.role')" />
                </div>

                <div class="mt-5 flex justify-end gap-x-4">
                    <div class="w-1/6">
                        <x-button
                            x-on:click="$wire.set('showEditModal', false); $dispatch('close-modal')"
                            type="button"
                            label="Cancel"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-danger hover:bg-danger-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-700" />
                    </div>
                    <div class="w-1/6">
                        <x-button
                            type="submit"
                            label="Update"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-main hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700" />
                    </div>
                </div>
            </form>
        </div>
    </x-card>
</x-modal>