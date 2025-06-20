<x-modal name="edit-position-modal" wire:model="showEditModal">
    <x-card title="Update Position">
        <div x-data="{}" class="space-y-4">
            <div class="flex flex-col w-full space-y-2">
                <x-input-label for="title" value="Title" />
                <x-text-input wire:model="editingPosition.title" id="title" type="text" name="title" required />
                <x-input-error for="editingPosition.title" class="mt-2" />
            </div>
            <div class="flex flex-col w-full space-y-2">
                <x-input-label for="classification" value="Classification" />
                <x-native-select wire:model="editingPosition.classification" id="classification" name="classification">
                    <option value="">Select classification</option>
                    <option value="teaching">Teaching</option>
                    <option value="teaching-related">Teaching-related</option>
                    <option value="non-teaching">Non-teaching</option>
                </x-native-select>
                <x-input-error for="editingPosition.classification" class="mt-2" />
            </div>
            <div class="flex justify-end space-x-2">
                <x-button x-on:click="$wire.set('showEditModal', false)" type="button" class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105">
                    Cancel
                </x-button>
                <x-button wire:click="save" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150">
                    Update
                </x-button>
            </div>
        </div>
    </x-card>
</x-modal>