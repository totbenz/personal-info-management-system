
<x-modal name="edit-position-modal">
    <x-card title="Edit Position">
        <form action="{{ route('positions.update', ['position' => $position->id]) }}" method="POST">
            @csrf
            @method('PUT') <!-- Use PUT or PATCH method for updates -->
            <div class="mb-4">
                <x-input type="text" id="title" name="title" value="{{ old('title', $position->title) }}" class="form-control shadow-sm rounded-md w-full px-3 py-2 border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"/>
            </div>
            <div class="mb-4">
                <x-native-select id="classification" name="classification" class="form-control">
                    <option value="teaching" {{ $position->classification == 'teaching' ? 'selected' : '' }}>Teaching</option>
                    <option value="teaching-related" {{ $position->classification == 'teaching-related' ? 'selected' : '' }}>Teaching-related</option>
                    <option value="non-teaching" {{ $position->classification == 'non-teaching' ? 'selected' : '' }}>Non-teaching</option>
                </x-native-select>
            </div>
            <div class="flex justify-end gap-x-4">
                <div class="w-1/6">
                    <x-button x-on:click="close" label="Cancel" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-danger hover:bg-danger-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-700"/>
                </div>
                <div class="w-1/6">
                    <x-button type="submit" label="Save" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-main hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700"/>
                </div>
            </div>
        </form>
    </x-card>
</x-modal>
