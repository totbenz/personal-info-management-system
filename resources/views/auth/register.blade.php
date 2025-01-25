<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('store') }}">
            @csrf

            <div>
                <x-input id="personnel_id" label="Personnel ID" class="block mt-1 w-full" type="number" name="personnel_id" :value="old('personnel_id')" required/>
            </div>

            <div class="mt-4">
                <x-input id="email" label="Email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-input id="password" label="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-input id="password_confirmation" label="Confirm Password" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button type="submit" label="Register" class="bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover"/>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
