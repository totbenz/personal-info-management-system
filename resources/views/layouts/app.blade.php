<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Human Resource Information System</title>

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
        @livewire('navigation-menu')
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
</body>

</html>
