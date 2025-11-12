@extends('layouts.app')

@section('title', 'Venues')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold tracking-tight">Venues</h1>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('venues.index') }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $date ?? request('date') }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm"/>
                <button class="rounded-md bg-blue-600 px-3 py-2 text-white hover:bg-blue-700 text-sm" type="submit">Check date</button>
            </form>
            @if (auth()->check() && in_array(auth()->user()->role, ['admin','manager']) && Route::has('venues.create'))
                <a href="{{ route('venues.create') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Add Venue</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse(($venues ?? collect()) as $venue)
            @php
                $reserved = ($venue->active_reservations_count ?? 0) > 0 || ($venue->status ?? 'available') !== 'available';
            @endphp
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-900 tracking-tight">{{ $venue->venue_name }}</h3>
                        <p class="text-sm text-slate-600 mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.25a7.5 7.5 0 0 0-7.5 7.5c0 5.25 7.5 12 7.5 12s7.5-6.75 7.5-12a7.5 7.5 0 0 0-7.5-7.5Z"/><path d="M12 12.375a2.625 2.625 0 1 1 0-5.25 2.625 2.625 0 0 1 0 5.25Z"/></svg>
                            {{ $venue->location ?? '—' }}
                        </p>
                        <p class="text-sm text-slate-600 mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M6.75 3A.75.75 0 0 0 6 3.75v16.5a.75.75 0 0 0 1.2.6L12 17.25l4.8 3.6a.75.75 0 0 0 1.2-.6V3.75A.75.75 0 0 0 17.25 3H6.75Z"/></svg>
                            Capacity: {{ $venue->capacity ?? '—' }}
                        </p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $reserved ? 'bg-slate-100 text-slate-700' : 'bg-green-100 text-green-700' }}">
                        {{ $reserved ? 'Reserved' : 'Available' }}
                    </span>
                </div>
                <div class="mt-4 flex items-center justify-between">
                    <a href="{{ route('venues.show', $venue->venue_id) }}" class="text-blue-600 hover:text-blue-700 text-sm">View details</a>
                    @if($reserved)
                        <button class="inline-flex items-center rounded-md bg-slate-200 px-3 py-1.5 text-sm text-slate-600 cursor-not-allowed" disabled>Reserve</button>
                    @else
                        <a href="{{ route('venues.show', $venue->venue_id) }}#reserve{{ $date ? '?date='.$date : '' }}"
                           class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-3 py-1.5 text-sm font-medium text-white shadow hover:shadow-md hover:from-blue-700 hover:to-blue-800">Reserve</a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-slate-500">No venues found.</p>
        @endforelse
    </div>
@endsection
