<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                <x-input id="email" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-main focus:ring-opacity-50" type="email" name="email" required autofocus />
            </div>

            <div>
                <x-button type="submit" label="Send Password Reset Link" class="w-full bg-main font-semibold text-sm text-white uppercase tracking-widest py-2 rounded-lg hover:bg-main_hover transition duration-150" />
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
