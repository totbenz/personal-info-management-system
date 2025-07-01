<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ $value }}
        </div>
        @endsession

        <form method="POST" action="{{ route('authenticate') }}">
            @csrf
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <div class="mt-4">
                <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                <div class="relative">
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-600">
                        <i id="password-icon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mt-4 flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-main shadow-sm focus:ring focus:ring-main focus:ring-opacity-50">
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                {{-- <p class="text-sm font-light text-gray-500">
                    <a href="{{ route('register') }}" class="font-medium text-main-600 hover:underline duration-150">Activate Account</a>
                </p> --}}

                <x-button type="submit" label="Login" class="bg-main font-semibold text-xs text-white uppercase tracking-widest hover:hover:bg-main_hover" />
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rememberMeCheckbox = document.getElementById('remember_me');
        const emailInput = document.getElementById('email');
        const savedRememberMe = localStorage.getItem('remember_me');
        const savedEmail = localStorage.getItem('email');

        if (savedRememberMe === 'true') {
            rememberMeCheckbox.checked = true;
            if (savedEmail) {
                emailInput.value = savedEmail;
            }
        }

        rememberMeCheckbox.addEventListener('change', function() {
            localStorage.setItem('remember_me', rememberMeCheckbox.checked);
            if (!rememberMeCheckbox.checked) {
                localStorage.removeItem('email');
            }
        });

        emailInput.addEventListener('input', function() {
            if (rememberMeCheckbox.checked) {
                localStorage.setItem('email', emailInput.value);
            }
        });

        // Show SweetAlert notifications for session messages
        const successMessage = "{{ session('success_message') }}";
        const errorMessage = "{{ session('error_message') }}";
        const warningMessage = "{{ session('warning_message') }}";
        const redirectUrl = "{{ session('redirect_url') }}";
        const showDelayedRedirect = "{{ session('show_delayed_redirect') }}";

        if (successMessage) {
            showSuccessAlert(successMessage);

            // If we need to redirect after showing success message
            if (showDelayedRedirect && redirectUrl) {
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 2000);
            }
        }

        if (errorMessage) {
            showErrorAlert(errorMessage);
        }

        if (warningMessage) {
            showWarningAlert(warningMessage);
        }
    });

    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('password-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    }
</script>