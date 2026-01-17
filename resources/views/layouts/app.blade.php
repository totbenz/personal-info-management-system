<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HRIS - Human Resource Information System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('systems.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- fonts.css is loaded via Vite below --}}

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/fonts.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.css"
        integrity="sha512-4OzqLjfh1aJa7M33b5+h0CSx0Q3i9Qaxlrr1T/Z+Vz+9zs5A7GM3T3MFKXoreghi3iDOSbkPMXiMBhFO7UBW/g==" crossorigin="anonymous"
        referrerpolicy="no-referrer" />

    {{-- <link rel="stylesheet" href="{{ URL::asset('assets/apexcharts/apexcharts.css') }}" /> --}}

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-slate-100 text-black">
    <div class="max-h-screen">
        @livewire('improved-navigation')
        <x-banner />
        <div class="pt-12">
            <!-- Page Heading -->
            @if (isset($header))
            <header class="bg-white shadow">
                <div class="py-3 px-2 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('modals')
    <x-confirm-modal />
    @livewireScripts
    <wireui:scripts />
    <x-toaster-hub />

    <script>
        // Fix for WireUI components and wire:navigate issues
        let wireuiInitialized = false;

        function initializeWireUI() {
            if (wireuiInitialized) return;

            if (window.WireUi && window.Alpine) {
                // Store original Alpine.data
                const originalData = window.Alpine.data;

                // Ensure all WireUI components are registered
                if (window.WireUi.select && !window.Alpine.data('wireui_select')) {
                    window.Alpine.data('wireui_select', window.WireUi.select);
                }
                if (window.WireUi.nativeSelect && !window.Alpine.data('wireui_native_select')) {
                    window.Alpine.data('wireui_native_select', window.WireUi.nativeSelect);
                }

                wireuiInitialized = true;
                console.log('WireUI components initialized');
            }
        }

        // Initialize on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(initializeWireUI, 100);
        });

        // Re-initialize on Livewire navigation
        if (window.Livewire) {
            window.Livewire.hook('component.init', () => {
                setTimeout(initializeWireUI, 50);
            });
        }

        // Also try on Alpine init
        document.addEventListener('alpine:init', () => {
            setTimeout(initializeWireUI, 50);
        });
    </script>
</body>

</html>
