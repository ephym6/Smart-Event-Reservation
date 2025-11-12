<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Event Reservation')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full font-sans text-slate-800 bg-white">

<header class="bg-white/90 backdrop-blur border-b border-slate-200 sticky top-0 z-40">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="inline-block h-8 w-8 rounded bg-blue-600 shadow ring-1 ring-blue-500/50"></span>
            <span class="font-semibold text-lg tracking-tight">Smart Event Reservation</span>
        </a>

        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
            <a href="{{ route('venues.index') }}" class="hover:text-blue-600">Venues</a>
            <a href="{{ route('events.index') }}" class="hover:text-blue-600">Events</a>
            <a href="{{ route('reservations.index') }}" class="hover:text-blue-600">Reservations</a>

            @auth
                <details class="relative group">
                    <summary class="list-none inline-flex cursor-pointer items-center gap-2 rounded-md bg-slate-100 px-3 py-1.5 hover:bg-slate-200">
                        <span class="font-medium">{{ Auth::user()->name ?? 'Account' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-open:rotate-180 transition" viewBox="0 0 24 24" fill="currentColor"><path d="M12 15 6 9h12l-6 6Z"/></svg>
                    </summary>
<div class="absolute right-0 mt-2 w-44 rounded-md border border-slate-200 bg-white shadow-lg">
                        @if(in_array(Auth::user()->role ?? '', ['admin','manager']))
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-slate-700 hover:bg-slate-50">Dashboard</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </details>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700">Login</a>
                @endif
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white shadow hover:shadow-md hover:from-blue-700 hover:to-blue-800">Sign Up</a>
                @endif
            @endauth
        </nav>

        <div class="md:hidden">
            <details class="relative">
                <summary class="list-none inline-flex items-center p-2 rounded hover:bg-slate-100" aria-label="Open Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M3.75 6.75h16.5m-16.5 5.25h16.5m-16.5 5.25h16.5"/></svg>
                </summary>
                <div class="absolute right-0 mt-2 w-56 rounded-md border border-slate-200 bg-white shadow-lg p-2 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded hover:bg-slate-50">Home</a>
                    <a href="{{ route('venues.index') }}" class="block px-3 py-2 rounded hover:bg-slate-50">Venues</a>
                    <a href="{{ route('events.index') }}" class="block px-3 py-2 rounded hover:bg-slate-50">Events</a>
                    <a href="{{ route('reservations.index') }}" class="block px-3 py-2 rounded hover:bg-slate-50">Reservations</a>
@auth
                        @if(in_array(Auth::user()->role ?? '', ['admin','manager']))
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-50">Dashboard</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-3 py-2 rounded hover:bg-red-50 text-red-600">Logout</button>
                        </form>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded hover:bg-slate-50">Login</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-3 py-2 rounded hover:bg-slate-50">Sign Up</a>
                        @endif
                    @endauth
                </div>
            </details>
        </div>
    </div>
</header>

<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
    @yield('content')
</main>

<footer class="mt-16 border-t border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
        <div>
            <p class="font-semibold text-slate-900">Smart Event Reservation</p>
            <p class="mt-2 text-slate-600">Book and manage venues, events, and reservations with ease.</p>
        </div>
        <div>
            <p class="font-semibold text-slate-900">Contact</p>
            <p class="mt-2 text-slate-600">Email: support@smartevents.example</p>
            <p class="text-slate-600">Phone: +254 700 000 000</p>
        </div>
        <div>
            <p class="font-semibold text-slate-900">Follow</p>
            <div class="mt-2 flex items-center gap-4">
                <a href="#" class="text-slate-500 hover:text-slate-800" aria-label="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.75c-.7.3-1.4.5-2.2.6.8-.5 1.3-1.2 1.6-2.1-.8.5-1.7.8-2.6 1-1.6-1.7-4.5-.7-4.5 1.9 0 .3 0 .5.1.8-3.5-.1-6.7-1.8-8.8-4.6-.4.7-.6 1.4-.6 2.2 0 1.5.8 2.7 2 3.4-.6 0-1.2-.2-1.7-.5 0 2.2 1.6 4 3.7 4.4-.4.1-.8.2-1.2.2-.3 0-.6 0-.8-.1.6 1.9 2.3 3.2 4.4 3.3-1.6 1.3-3.6 2.1-5.8 2.1H3c2.1 1.3 4.7 2.1 7.4 2.1 8.9 0 13.8-7.5 13.8-14 0-.2 0-.3 0-.5.9-.6 1.6-1.3 2.2-2.1z"/></svg>
                </a>
                <a href="#" class="text-slate-500 hover:text-slate-800" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21.75v-8.25h2.625l.375-3H13.5V8.25c0-.87.29-1.5 1.5-1.5H16.5V4.2c-.26-.04-1.15-.12-2.19-.12-2.59 0-4.31 1.57-4.31 4.45v2.17H7.5v3h2.5v8.05c.81.14 1.64.2 2.5.2.7 0 1.39-.05 2.08-.15Z"/></svg>
                </a>
                <a href="#" class="text-slate-500 hover:text-slate-800" aria-label="GitHub">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .75A11.25 11.25 0 0 0 .75 12c0 4.95 3.21 9.16 7.67 10.64.56.12.77-.24.77-.54v-1.95c-3.12.68-3.78-1.33-3.78-1.33-.52-1.3-1.27-1.65-1.27-1.65-1.04-.7.08-.68.08-.68 1.15.08 1.75 1.18 1.75 1.18 1.03 1.77 2.71 1.26 3.37.97.1-.76.4-1.26.72-1.55-2.49-.28-5.11-1.26-5.11-5.6 0-1.24.45-2.25 1.18-3.04-.12-.29-.51-1.46.11-3.05 0 0 .97-.31 3.19 1.16a11.1 11.1 0 0 1 5.82 0c2.22-1.47 3.19-1.16 3.19-1.16.62 1.59.23 2.76.11 3.05.73.79 1.18 1.8 1.18 3.04 0 4.36-2.62 5.31-5.12 5.6.41.35.77 1.04.77 2.1v3.11c0 .3.2.66.78.54A11.26 11.26 0 0 0 23.25 12 11.25 11.25 0 0 0 12 .75Z"/></svg>
                </a>
            </div>
        </div>
    </div>
    <div class="bg-slate-50 border-t border-slate-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-center text-slate-500 text-xs">
            © {{ date('Y') }} Smart Event Reservation. All rights reserved.
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
