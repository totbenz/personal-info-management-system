<x-modal name="myModal">
    <x-card>

        <div class="px-6 py-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Personnel {{ $personnel->personnel_id }}</h3>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    Are you sure you want to delete this personnel? All data associated with this personnel will be permanently removed. This action cannot be undone.
                </p>
            </div>
        </div>

        <x-slot name="footer">
            <form action="{{ route('personnels.destroy', ['personnel' => $personnel->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-x-4">
                    <button type="button" x-on:click.prevent="close" class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-gray-900 bg-gray-50 border border-slate-200 rounded-lg hover:bg-white hover:scale-105 duration-300">
                        <p>Cancel</p>
                    </button>

                    <button type="submit"
                        class="inline-flex items-center px-5 py-2 mb-2 mr-2 text-sm font-medium text-center text-white bg-danger border border-red-600 rounded-lg hover:bg-red-500 hover:scale-105 duration-300">
                        <p>Delete</p>
                    </button>
                </div>
            </form>
        </x-slot>
    </x-card>
</x-modal>
