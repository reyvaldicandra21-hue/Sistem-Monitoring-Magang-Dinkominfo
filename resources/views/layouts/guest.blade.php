<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-6xl bg-white rounded-2xl shadow-lg flex overflow-hidden">

        <!-- LEFT SIDE -->
        <div class="hidden md:flex w-1/2 bg-gradient-to-br from-indigo-400 to-indigo-600 text-white p-10 flex-col justify-between">
            <div>
                <h2 class="text-lg font-semibold">PKL System</h2>
                <p class="text-sm opacity-80">Sistem Manajemen PKL</p>
            </div>

            <div>
                <h1 class="text-3xl font-bold leading-snug">
                    Kelola Data PKL Lebih Mudah 🚀
                </h1>
                <p class="mt-3 text-sm opacity-80">
                    Monitoring peserta, absensi, dan laporan dalam satu sistem.
                </p>
            </div>

            <div class="text-sm opacity-70">
                © {{ date('Y') }}
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="w-full md:w-1/2 p-8 md:p-12">

            <!-- Logo -->
            <div class="mb-6">
                <a href="/" class="flex items-center gap-2">
                    <x-application-logo class="w-10 h-10 text-indigo-500" />
                    <span class="font-semibold text-gray-700">App</span>
                </a>
            </div>

            <!-- CONTENT -->
            {{ $slot }}

        </div>
    </div>
</div>

</body>
</html>
