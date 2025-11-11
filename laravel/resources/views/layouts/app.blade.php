<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Smart Event Reservation' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full text-slate-800">
<header class="bg-white border-b border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <span class="inline-block h-8 w-8 rounded bg-blue-600"></span>
            <span class="font-semibold text-lg">Smart Event Reservation</span>
        </a>
        <nav class="hidden md:flex items-center gap-6">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
            <a href="#" class="hover:text-blue-600">Venues</a>
            <a href="#" class="hover:text-blue-600">Login</a>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Dashboard</a>
        </nav>
        <button class="md:hidden inline-flex items-center p-2 rounded hover:bg-slate-100" aria-label="Open Menu">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path d="M3.75 6.75h16.5m-16.5 5.25h16.5m-16.5 5.25h16.5"/></svg>
        </button>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    {{ $slot }}
</main>

<footer class="mt-16 border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 text-sm text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p>© {{ date('Y') }} Smart Event Reservation</p>
        <div class="flex items-center gap-6">
            <a href="#" class="hover:text-slate-700">Privacy</a>
            <a href="#" class="hover:text-slate-700">Terms</a>
            <a href="#" class="hover:text-slate-700">Contact</a>
        </div>
    </div>
</footer>
</body>
</html>
