<x-guest-layout>
    <div class="min-h-screen w-full bg-gradient-to-br from-gray-50 to-gray-100 flex">
        <!-- Left: Login Form -->
        <div class="w-2/5 bg-white flex flex-col justify-center px-16 py-12">
            <div class="flex justify-center mb-8">
                <x-authentication-card-logo />
            </div>

            <!-- Welcome Message -->
            <div class="mb-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-3">Welcome Back</h1>
                <p class="text-gray-500 text-base leading-relaxed">
                    Sign in to your HRIS account to manage personnel and payroll operations.
                </p>
            </div>

            <x-validation-errors class="mb-6" />

            @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg font-medium text-sm text-green-700">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('authenticate') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block font-semibold text-sm text-gray-800 mb-2">Email Address</label>
                    <x-input id="email" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-main focus:border-transparent transition" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <div>
                    <label for="password" class="block font-semibold text-sm text-gray-800 mb-2">Password</label>
                    <div class="relative">
                        <x-input id="password" class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-main focus:border-transparent transition" type="password" name="password" required autocomplete="current-password" />
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-main focus:ring-main focus:ring-offset-0">
                    <label for="remember_me" class="ml-3 text-sm text-gray-700">Keep me signed in</label>
                </div>

                <button type="submit" class="w-full bg-main hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg transition duration-200 shadow-md hover:shadow-lg mt-8">
                    Sign In
                </button>
            </form>
        </div>

        <!-- Right: System Features -->
        <div class="w-3/5 bg-main text-white flex flex-col justify-center px-12 py-12 overflow-y-auto">
            <div class="mb-12 text-center">
                <h1 class="text-5xl font-bold mb-3">Human Resources Information System</h1>
                <p class="text-lg text-white opacity-90 font-medium mb-6">DepEd Baybay City Division</p>
                <div class="w-full h-1 bg-white rounded mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 gap-10">
                <!-- Personnel Management Section -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-3xl">📋</span>
                        <h2 class="text-2xl font-bold">Personnel Management</h2>
                    </div>
                    <p class="text-white text-opacity-90 leading-relaxed mb-5 text-sm">Comprehensive personnel data management designed to centralize and maintain accurate employee records.</p>
                    
                    <div class="grid grid-cols-2 gap-6 ml-1">
                        <div>
                            <h3 class="font-semibold text-white mb-3 text-xs uppercase tracking-wide opacity-80">Key Features</h3>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Management of personal information and employee profiles</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Employment details and appointment history</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Educational background and eligibility records</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Work experience and training history</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Government identification records and compliance tracking</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-semibold text-white mb-3 text-xs uppercase tracking-wide opacity-80">HR Documents & Forms</h3>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Personal Data Sheet (PDS)</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Notice of Salary Adjustment (NOSA)</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Notice of Salary Increment (NOSI)</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Service Records</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <span class="text-white mt-1 flex-shrink-0">•</span>
                                    <span class="text-white text-opacity-90">Form 6 & Compensatory Time Off (CTO)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Salary & Awards System Section -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-3xl">💰</span>
                        <h2 class="text-2xl font-bold">Salary & Awards System</h2>
                    </div>
                    <p class="text-white text-opacity-90 leading-relaxed mb-5 text-sm">Automates compensation monitoring and recognition tracking for accurate salary processing and employee service recognition.</p>
                    
                    <div class="ml-1">
                        <h3 class="font-semibold text-white mb-3 text-xs uppercase tracking-wide opacity-80">Key Features</h3>
                        <ul class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                            <li class="flex items-start gap-3">
                                <span class="text-white mt-1 flex-shrink-0">•</span>
                                <span class="text-white text-opacity-90">Automated salary computation based on salary grade and step increments</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-white mt-1 flex-shrink-0">•</span>
                                <span class="text-white text-opacity-90">Salary adjustment and increment tracking</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-white mt-1 flex-shrink-0">•</span>
                                <span class="text-white text-opacity-90">Loyalty awards monitoring for long-term service (10, 15, 20+ years)</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-white mt-1 flex-shrink-0">•</span>
                                <span class="text-white text-opacity-90">Historical salary records and reporting</span>
                            </li>
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
