<nav class="fixed w-full z-10 bg-[#0f152a] shadow-2xl backdrop-blur-sm bg-opacity-95">
    <div class="mx-2 px-4 sm:px-6 lg:px-3">
        <div class="flex justify-between items-center h-12 text-white">
            <div class="flex w-full items-center justify-between p-0">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('image/kagawaran-ng-edukasyon-logo.png') }}" alt="DepEd Logo" class="h-8 w-8 rounded-full object-contain bg-white p-0.5">
                    <img src="{{ asset('image/division-logo.png') }}" alt="Division Logo" class="h-8 w-8 rounded-full object-contain bg-white p-0.5">
                    <span class="ml-2 font-bold text-base tracking-tight whitespace-nowrap transition-all duration-300 hover:text-blue-300" style="font-family: 'Times New Roman', Times, serif;">
                        <div class="flex flex-col leading-tight">
                            HRIS
                            <span class="text-[9px] font-normal tracking-normal mt-0.3 text-gray-400 transition-all duration-300 hover:text-gray-300" style="font-family: Arial, sans-serif;">
                                Human Resource Information System
                            </span>
                        </div>
                    </span>
                </div>

                <div class="flex space-x-3 items-center justify-center flex-1">
                    @if (Auth::user()->role == "admin")
                    <x-nav-link
                        href="{{ route('admin.home') }}"
                        :active="request()->routeIs('admin.home')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @endif

                    @if (Auth::user()->role == "school_head")
                    <x-nav-link
                        href="{{ route('school_head.dashboard') }}"
                        :active="request()->routeIs('school_head.dashboard')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('School Head Dashboard') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('schools.show', ['school' => Auth::user()->personnel->school]) }}"
                        :active="request()->routeIs('schools.show')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ Auth::user()->personnel->school->school_name }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('school_head.monetization.history') }}"
                        :active="request()->routeIs('school_head.monetization.history')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Monetization') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('personnels.profile', ['personnel' => Auth::user()->personnel->id]) }}"
                        :active="request()->routeIs('personnels.profile', ['personnel' => Auth::user()->personnel->id])"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Profile') }}
                    </x-nav-link>
                    @elseif(Auth::user()->role == "admin")
                    <x-nav-link
                        href="{{ route('schools.index') }}"
                        :active="request()->routeIs('schools.index')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Schools') }}
                    </x-nav-link>
                    @endif

                    @if (Auth::user()->role == 'teacher')
                    <x-nav-link
                        href="{{ route('teacher.dashboard') }}"
                        :active="request()->routeIs('teacher.dashboard')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Teacher Dashboard') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('personnel.profile', ['personnel' => Auth::user()->personnel->id]) }}"
                        :active="request()->routeIs('personnel.profile', ['personnel' => Auth::user()->personnel->id])"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Profile') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('teacher.monetization.history') }}"
                        :active="request()->routeIs('teacher.monetization.history')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Monetization') }}
                    </x-nav-link>
                    @elseif (Auth::user()->role == 'non_teaching')
                    <x-nav-link
                        href="{{ route('non_teaching.dashboard') }}"
                        :active="request()->routeIs('non_teaching.dashboard')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('personnel.profile2', ['personnel' => Auth::user()->personnel->id]) }}"
                        :active="request()->routeIs('personnels.profile2', ['personnel' => Auth::user()->personnel->id])"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Profile') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('non_teaching.monetization.history') }}"
                        :active="request()->routeIs('non_teaching.monetization.history')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Monetization') }}
                    </x-nav-link>
                    @elseif(Auth::user()->role == "admin")
                    <x-nav-link
                        href="{{ route('personnels.index') }}"
                        :active="request()->routeIs('personnels.index')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Personnels') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('positions.index') }}"
                        :active="request()->routeIs('positions.index')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Positions') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('accounts.index') }}"
                        :active="request()->routeIs('accounts.index')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Accounts') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('salary_steps.index') }}"
                        :active="request()->routeIs('salary_steps.index')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Salary Table') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('admin.monetization-requests') }}"
                        :active="request()->routeIs('admin.monetization-requests')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Monetization') }}
                    </x-nav-link>
                    <x-nav-link
                        href="{{ route('admin.loyalty-awards') }}"
                        :active="request()->routeIs('admin.loyalty-awards')"
                        wire:navigate
                        class="relative px-4 py-2 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105"
                        active-class="bg-white text-[#0f152a] shadow-lg font-bold"
                        inactive-class="hover:bg-[#1a223a] hover:text-white hover:shadow-md">
                        {{ __('Loyalty Awards') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <div class="flex flex-col items-end mr-2 transition-all duration-300">
                    <span class="text-xs text-gray-300 font-semibold uppercase hover:text-white transition-colors duration-200">{{ __(ucfirst(Auth::user()->role)) }}</span>
                    <span class="text-xs text-gray-400 hover:text-gray-300 transition-colors duration-200">{{ Auth::user()->email }}</span>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center rounded-full transition-all duration-300 hover:scale-110 hover:ring-2 hover:ring-white hover:ring-opacity-50">
                        <x-user-icon></x-user-icon>
                    </button>

                    <ul x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-90 -translate-y-2"
                        @click.away="open = false"
                        class="absolute z-20 right-0 w-52 mt-3 py-2 bg-white rounded-xl shadow-2xl border border-gray-100 font-normal text-gray-700 overflow-hidden">

                        <li class="border-b border-gray-100 last:border-0">
                            <a href="{{ route('settings') }}"
                               class="flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 transition-all duration-200 group"
                               @click="setTimeout(() => window.location.href = '{{ route('settings') }}', 100)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.01c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.01 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.01 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.01c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.01c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.01-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.01-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.01z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                </svg>
                                <span class="ml-3 text-sm font-medium">Change Password</span>
                            </a>
                        </li>

                        @if(Auth::user()->role === 'admin')
                        <li class="border-b border-gray-100 last:border-0">
                            <a href="{{ route('admin.signatures.edit') }}"
                               class="flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 transition-all duration-200 group"
                               @click="setTimeout(() => window.location.href = '{{ route('admin.signatures.edit') }}', 100)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75A2.25 2.25 0 0 0 14.25 4.5h-4.5A2.25 2.25 0 0 0 7.5 6.75v10.5A2.25 2.25 0 0 0 9.75 19.5h4.5A2.25 2.25 0 0 0 16.5 17.25V13.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75v-6" />
                                </svg>
                                <span class="ml-3 text-sm font-medium">Signatures Settings</span>
                            </a>
                        </li>

                        <li class="border-b border-gray-100 last:border-0">
                            <button
                                onclick="exportDatabaseToCsv()"
                                class="flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 transition-all duration-200 group w-full text-left"
                                id="csv-export-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span class="ml-3 text-sm font-medium">Export Database to CSV</span>
                            </button>
                        </li>
                        @endif

                        <li class="border-b border-gray-100 last:border-0">
                            <button type="button" onclick="confirmLogout(event)" class="flex items-center px-4 py-3 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 transition-all duration-200 group w-full text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-4 text-gray-400 group-hover:text-red-600 transition-colors duration-200">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
                                </svg>
                                <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-red-700 transition-colors duration-200">Logout</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Initialize SweetAlert2 if not already loaded
    if (typeof Swal === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        script.onload = function() {
            console.log('SweetAlert2 loaded');
        };
        document.head.appendChild(script);
    }

    function confirmLogout(e) {
        e.preventDefault();
        if (typeof Swal === 'undefined') {
            // Fallback if SweetAlert2 is not loaded
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = "{{ route('logout') }}";
            }
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'You will be logged out of your account.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show Toaster message before logout
                if (typeof Toaster !== 'undefined') {
                    Toaster.info('Logging out...');
                    // Small delay to show the toast before redirecting
                    setTimeout(() => {
                        window.location.href = "{{ route('logout') }}";
                    }, 500);
                } else {
                    // Fallback if Toaster is not available
                    window.location.href = "{{ route('logout') }}";
                }
            }
        });
    }

    function exportDatabaseToCsv() {
        const button = document.getElementById('csv-export-btn');
        const originalText = button.innerHTML;

        // Show loading state
        button.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-2 text-sm">Exporting...</span>
        `;
        button.disabled = true;

        if (typeof Swal === 'undefined') {
            // Fallback if SweetAlert2 is not loaded
            window.location.href = "{{ route('csv.export.all') }}";
            return;
        }

        // Show confirmation dialog
        Swal.fire({
            title: 'Export Database',
            text: 'This will export all database tables to CSV files. The process may take a few minutes depending on data size. Continue?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Export',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show progress dialog
                Swal.fire({
                    title: 'Exporting Database',
                    html: `
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p>Exporting all database tables to CSV...</p>
                            <p class="text-sm text-gray-500 mt-2">Please wait, this may take a few minutes.</p>
                        </div>
                    `,
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                // Start the export
                window.location.href = "{{ route('csv.export.all') }}";

                // Close the progress dialog after a delay
                setTimeout(() => {
                    Swal.close();
                }, 3000);
            } else {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
    }
</script>
