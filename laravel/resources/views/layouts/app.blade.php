<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Event Reservation')</title>
    <!-- CSS frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
    @vite()
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-900">

{{-- Navigation --}}
<nav class="bg-white shadow-sm border-bottom border-gray-200">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center" style="height: 64px;">
            <div class="d-flex align-items-center">
                <a href="{{ route('home') }}" class="text-decoration-none fw-bold text-primary">Smart Event Reservation</a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('venues.index') }}" class="text-decoration-none text-dark">Venues</a>
                <a href="{{ route('events.index') }}" class="text-decoration-none text-dark">Events</a>
                <a href="{{ route('reservations.index') }}" class="text-decoration-none text-dark">Reservations</a>
                @auth
                    <a href="{{ route('logout') }}" class="text-decoration-none text-danger">Logout</a>
                @else
                    <a href="{{ route('login') }}" class="text-decoration-none text-primary">Login</a>
                    <a href="{{ route('register') }}" class="text-decoration-none text-primary">Sign Up</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Main Content --}}
<main class="py-4">
    <div class="container">
        @yield('content')
    </div>
</main>

{{-- Footer --}}
<footer class="bg-white border-top border-gray-200 mt-5 py-3">
    <div class="container text-center text-muted small">
        &copy; {{ date('Y') }} Smart Event Reservation. All rights reserved.
    </div>
</footer>

<!-- JS (for Bootstrap components like dismissible alerts) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
