<x-modal name="create-position-modal">
    <x-card title="Create New Position">
        <form action="{{ route('positions.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <x-input type="text" id="title" name="title" class="form-control shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"/>
            </div>
            <div class="mb-4">
                <x-native-select class="form-control" name="classification" id="classification">
                    <option value="teaching">Teaching</option>
                    <option value="teaching-related">Teaching-related</option>
                    <option value="non-teaching">Non-teaching</option>
                </x-native-select>
            </div>

            <div class="flex justify-end gap-x-4">
                <div class="w-1/6">
                    <x-button
                        type="button"
                        label="Cancel"
                        class="px-5 py-2.5 w-full bg-danger font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-red-600 hover:scale-105"
                        x-on:click="
                            $dispatch('close');
                            $nextTick(() => setTimeout(() => { window.location.href = '{{ route('positions.index') }}'; }, 100));
                        "
                    />
                </div>
                <div class="w-1/6">
                    <x-button type="submit" label="Create" class="px-5 py-2.5 w-full bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover hover:scale-105 duration-150"/>
                </div>
            </div>
        </form>
    </x-card>
</x-modal>
