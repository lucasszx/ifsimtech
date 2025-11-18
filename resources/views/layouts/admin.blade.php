<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin — {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-100 font-sans">

    {{-- Navbar separada só do admin --}}
    @include('layouts.admin-nav')

    <main class="p-6">
        {{ $slot }}
    </main>

</body>
</html>
