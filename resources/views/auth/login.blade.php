<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-8">
        <div class="w-full max-w-7xl bg-white rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2">
            <!-- Left: Login Form -->
            <div class="p-8 md:p-12 flex flex-col justify-center">
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
                            <button type="submit" class="bg-main hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-md w-full uppercase tracking-wider">Login</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: System Info Cards -->
            <div class="bg-main text-white p-8 md:p-12 flex flex-col gap-4 justify-start overflow-y-auto max-h-screen">
                <!-- Personnel Management Section -->
                <div class="bg-white text-gray-700 p-5 rounded-xl shadow-md">
                    <h2 class="text-lg font-bold mb-2">📋 Personnel Management</h2>
                    <p class="text-xs mb-3">Comprehensive personnel data management designed to centralize and maintain accurate employee records.</p>
                    
                    <div class="text-xs space-y-2">
                        <p class="font-semibold text-gray-800">Key Features:</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>Management of personal information and employee profiles</li>
                            <li>Employment details and appointment history</li>
                            <li>Educational background and eligibility records</li>
                            <li>Work experience and training history</li>
                            <li>Government identification records and compliance tracking</li>
                        </ul>

                        <p class="font-semibold text-gray-800 mt-2">Official HR Documents:</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>Personal Data Sheet (PDS)</li>
                            <li>Notice of Salary Adjustment (NOSA)</li>
                            <li>Notice of Salary Increment (NOSI)</li>
                            <li>Service Records</li>
                        </ul>

                        <p class="font-semibold text-gray-800 mt-2">Administrative Forms:</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>Form 6</li>
                            <li>Compensatory Time Off (CTO)</li>
                        </ul>
                    </div>
                </div>

                <!-- Salary & Awards System Section -->
                <div class="bg-white text-gray-700 p-5 rounded-xl shadow-md">
                    <h2 class="text-lg font-bold mb-2">💰 Salary & Awards System</h2>
                    <p class="text-xs mb-3">Module automates compensation monitoring and recognition tracking to ensure accurate salary processing and employee service recognition.</p>
                    
                    <div class="text-xs">
                        <p class="font-semibold text-gray-800">Key Features:</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li>Automated salary computation based on salary grade and step increments</li>
                            <li>Salary adjustment and increment tracking</li>
                            <li>Loyalty awards monitoring for long-term service (10, 15, 20+ years)</li>
                            <li>Historical salary records and reporting</li>
                        </ul>
                    </div>
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
