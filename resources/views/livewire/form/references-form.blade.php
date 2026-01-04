<div class="px-8 py-6">
    @if (!$updateMode)
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h4 class="font-bold text-2xl text-gray-800 mb-1">References</h4>
                <p class="text-sm text-gray-500">View your professional references</p>
            </div>
            <button wire:click.prevent="edit" type="button" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-700 border-0 rounded-xl hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897L8.863 9.83A3.75 3.75 0 0 0 7.5 6.75v-.75m0 0a3.75 3.75 0 0 1 7.5 0v.75m-7.5 0H18A2.25 2.25 0 0 1 20.25 9v.75m-8.5 6.75h.008v.008h-.008v-.008Z" />
                    </svg>
                    Edit Information
                </span>
            </button>
        </div>
        <div class="mt-5">
            <div class="mt-3">
                <div class="w-full flex h-10 border border-gray-100 bg-gray-50 items-center rounded-t-lg">
                    <div class="w-3/12 px-4">
                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Fullname</span>
                    </div>
                    <div class="w-4/12 px-4">
                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Address</span>
                    </div>
                    <div class="w-2/12 px-4">
                        <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Phone</span>
                    </div>
                </div>
                <div class="mt-2">
                    @if (count($personnel->references))
                        @foreach ($personnel->references as $index => $old_reference)
                            <div class="mb-2 w-full flex h-12 border border-gray-200 rounded-lg hover:border-blue-300 transition-colors duration-200">
                                <div class="w-3/12 px-4 flex items-center">
                                    <span class="text-sm text-gray-900">{{ $old_reference->full_name }}</span>
                                </div>
                                <div class="w-4/12 px-4 flex items-center">
                                    <span class="text-sm text-gray-900">{{ $old_reference->address }}</span>
                                </div>
                                <div class="w-2/12 px-4 flex items-center">
                                    <span class="text-sm text-gray-900">{{ $old_reference->tel_no }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="w-full">
                            <p class="mt-3 w-full py-2 font-medium text-sm text-center bg-gray-50 text-gray-500 rounded-lg">No References Found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h4 class="font-bold text-2xl text-gray-800 mb-1">Edit References</h4>
                <p class="text-sm text-gray-500">Update your professional references</p>
            </div>
            <button wire:click.prevent="back" type="button" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 transform hover:scale-105 transition-all duration-200 shadow-sm">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mr-2 -ml-1 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Cancel
                </span>
            </button>
        </div>
        @livewire('form.update-references-form', ['id' => $personnel->id])
    </div>
    @endif
</div>
