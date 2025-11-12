@extends('layouts.app')

@section('title', 'Events')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold tracking-tight">Events</h1>
        @if (auth()->check() && in_array(auth()->user()->role, ['admin','manager']) && Route::has('events.create'))
            <a href="{{ route('events.create') }}" class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white shadow hover:shadow-md hover:from-blue-700 hover:to-blue-800">Add Event</a>
        @endif
    </div>

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Event</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Start</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">End</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Venue</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse(($events ?? collect()) as $event)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm text-slate-800">{{ $event->event_name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $event->event_date ? $event->event_date->format('M d, Y') : 'TBD' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $event->start_time ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $event->end_time ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-700">{{ $event->venue->venue_name ?? '—' }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('events.show', $event->event_id) }}#reserve" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700">Reserve</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
