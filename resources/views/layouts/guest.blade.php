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

    <body class="font-sans text-gray-900 antialiased">

        <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100">

            <!-- LOGO GLOBAL DO LOGIN -->
            <div class="flex items-center mb-6">
                <img src="/logo.png" alt="IFsimTech logo" class="h-12 mb-2">
                <h1 class="text-3xl font-bold text-green-700">IFsimTech</h1>
            </div>

            <!-- CARD -->
            <div class="w-full sm:max-w-md mt-2 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

    </body>
</html>
