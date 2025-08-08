<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Change Password
        </h2>
    </x-slot>

    <div class="w-[40%] mx-auto sm:px-6 lg:px-8 mt-8">

    <form method="POST" action="{{ route('settings.changePassword') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h3 class="text-lg font-semibold mb-2">Change Password</h3>
        @if (session()->has('success'))
        <div
            class="mb-1 text-green-600 font-medium "
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
        >
            {{ session('success') }}
        </div>
        @endif
        <hr class="mb-4">
        <!-- Display current login email (read-only) -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" value="{{ Auth::user()->email }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 cursor-not-allowed" readonly>
        </div>
        @csrf
        <div class="mb-4" x-data="{ showError: true }">
            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                @input="showError = false"
            >
            @error('current_password')
                <span class="text-red-600 text-sm" x-show="showError">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="mb-4" x-data="{ showError: true }">
            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" id="new_password" name="new_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                @input="showError = false"
            >
            @error('new_password')
                <span class="text-red-600 text-sm" x-show="showError">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Password</button>
        </div>
    </form>
    </div>
    </div>
</x-app-layout>
