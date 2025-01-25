<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="card-body">
                <form action="{{ route('excel.convert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="excel" class="form-control">
                    <br>
                    <button type="submit" class="btn btn-success">Import User Data</button>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
