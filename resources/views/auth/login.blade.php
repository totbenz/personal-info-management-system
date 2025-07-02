<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-6xl bg-white rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2">
            <!-- Left: Login Form -->
            <div class="p-8 md:p-12">
                <div class="flex justify-center mb-6">
                    <x-authentication-card-logo />
                </div>

                <!-- Welcome Message -->
                <div class="bg-white rounded-xl shadow-sm p-3 mb-8 border border-gray-100">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-gray-900 mb-3">Welcome Back!</h1>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Please enter your credentials to access the<br>
                            Human Resource Information System
                        </p>
                    </div>
                </div>

                <x-validation-errors class="mb-4" />

                @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
                @endif

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <form method="POST" action="{{ route('authenticate') }}">
                        @csrf

                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        </div>

                        <div class="mt-4">
                            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                            <div class="relative">
                                <x-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                                    <i id="password-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-main shadow-sm focus:ring-main focus:ring-opacity-50">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-main hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-md w-full uppercase tracking-wider"> Login</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: System Info Cards -->
            <div class="bg-main text-white p-8 md:p-12 flex flex-col gap-4 justify-center">
                <h2 class="text-2xl font-bold text-white mb-4 text-center">System Features</h2>
                <div class="bg-white text-gray-700 p-6 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold mb-2">📋 Personnel Management</h2>
                    <p class="text-sm">Comprehensive personnel data management including personal information, employment details, education history, work experience, and government IDs. Generate PDS (Personal Data Sheet) reports and manage service records.</p>
                </div>
                <div class="bg-white text-gray-700 p-6 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold mb-2">🏫 School & District Management</h2>
                    <p class="text-sm">Manage schools, districts, and positions. Generate School Form 7 reports, track personnel by school, and maintain organizational hierarchy with role-based access control.</p>
                </div>
                <div class="bg-white text-gray-700 p-6 rounded-xl shadow-md">
                    <h2 class="text-lg font-semibold mb-2">💰 Salary & Awards System</h2>
                    <p class="text-sm">Automated salary calculation with grade/step increments, loyalty awards tracking (10, 15, 20+ years), NOSA/NOSI documentation, and comprehensive salary change history management.</p>
                </div>
            </div>
        </div>
    </div>
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

        const successMessage = "{{ session('success_message') }}";
        const errorMessage = "{{ session('error_message') }}";
        const warningMessage = "{{ session('warning_message') }}";
        const redirectUrl = "{{ session('redirect_url') }}";
        const showDelayedRedirect = "{{ session('show_delayed_redirect') }}";

        if (successMessage) {
            showSuccessAlert(successMessage);
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
            passwordIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>