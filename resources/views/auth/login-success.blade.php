<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Successful - Human Resource Information System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('systems.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: rgb(30 27 75);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Figtree', sans-serif;
        }

        .success-container {
            background: white;
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="success-container">
        <div class="loading-spinner"></div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Login Successful!</h2>
        <p class="text-gray-600 mb-4">Welcome back to the system.</p>
        <p class="text-sm text-gray-500">Redirecting you to your account...</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = "{{ session('success_message') }}";
            const redirectUrl = "{{ session('redirect_url') }}";
            const showDelayedRedirect = "{{ session('show_delayed_redirect') }}";

            if (successMessage) {
                showSuccessAlert(successMessage);
            }

            // Always redirect after 3 seconds
            setTimeout(() => {
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                } else {
                    window.location.href = '/dashboard';
                }
            }, 3000);
        });
    </script>
</body>

</html>
