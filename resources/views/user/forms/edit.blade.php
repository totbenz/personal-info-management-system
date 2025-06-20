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
                    <x-input-error for="editingAccount.personnel.personnel_id" class="mt-2" />
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
                    <x-input-error for="editingAccount.email" class="mt-2" />
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
                    <x-input-error for="editingAccount.role" class="mt-2" />
                </div>

                <div class="mt-5 flex justify-end gap-x-4">
                    <div class="w-1/6">
                        <x-button
                            x-on:click="$wire.set('showEditModal', false); $dispatch('close-modal')"
                            type="button"
                            label="Cancel"
                            class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105" />
                    </div>
                    <div class="w-1/6">
                        <x-button
                            type="submit"
                            label="Update"
                            class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150" />
                    </div>
                </div>
            </form>
        </div>
    </x-card>
</x-modal>