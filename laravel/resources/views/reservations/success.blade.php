@extends('layouts.app')

@section('title', 'Reservation Successful')

@section('content')
    <div class="mx-auto max-w-2xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm text-center">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M9 12.75 6.75 10.5l-1.5 1.5L9 15.75l9-9-1.5-1.5L9 12.75Z"/></svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight">Reservation Confirmed</h1>
        <p class="mt-2 text-slate-600">Your reservation has been created successfully.</p>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-left">
            <div class="rounded-lg border border-slate-200 p-3">
                <p class="text-slate-500">Venue</p>
                <p class="font-medium text-slate-900">{{ $reservation->venue->venue_name ?? '—' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 p-3">
                <p class="text-slate-500">Event</p>
                <p class="font-medium text-slate-900">{{ $reservation->event->event_name ?? '—' }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 p-3">
                <p class="text-slate-500">Start</p>
                <p class="font-medium text-slate-900">{{ optional($reservation->start_time)->format('M d, Y H:i') }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 p-3">
                <p class="text-slate-500">End</p>
                <p class="font-medium text-slate-900">{{ optional($reservation->end_time)->format('M d, Y H:i') }}</p>
            </div>
        </div>

        @php $backDate = optional($reservation->start_time)->toDateString(); @endphp
        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('venues.index', ['date' => $backDate]) }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Back to Venues</a>
            <a href="{{ route('reservations.index') }}" class="inline-flex items-center rounded-md bg-slate-100 px-4 py-2 text-slate-700 hover:bg-slate-200">View Reservations</a>
        </div>

        <p class="mt-3 text-xs text-slate-500">You will be redirected to Venues shortly.</p>
    </div>

    <script>
        setTimeout(function(){
            window.location.href = "{{ route('venues.index', ['date' => $backDate]) }}";
        }, 3000);
    </script>
@endsection
