@extends('layouts.app')

@section('title', 'Reservations')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">{{ (auth()->check() && !in_array(auth()->user()->role, ['admin','manager'])) ? 'My Reservations' : 'Reservations' }}</h1>
        @if (auth()->check() && in_array(auth()->user()->role, ['admin','manager']) && Route::has('reservations.create'))
            <a href="{{ route('reservations.create') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">New Reservation</a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md border border-blue-200 bg-blue-50 px-4 py-3 text-blue-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                    @if (auth()->check() && in_array(auth()->user()->role, ['admin','manager']))
                        <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                    @endif
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Venue</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Event</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Start</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">End</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse ($reservations as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-2 text-sm text-slate-700">{{ $r->reservation_id }}</td>
                        @if (auth()->check() && in_array(auth()->user()->role, ['admin','manager']))
                            <td class="px-4 py-2 text-sm text-slate-700">{{ $r->user->name ?? '—' }}</td>
                        @endif
                        <td class="px-4 py-2 text-sm text-slate-700">{{ $r->venue->venue_name ?? '—' }}</td>
                        <td class="px-4 py-2 text-sm text-slate-700">{{ $r->event->event_name ?? '—' }}</td>
                        <td class="px-4 py-2 text-sm text-slate-700">{{ optional($r->start_time)->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-2 text-sm text-slate-700">{{ optional($r->end_time)->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-2 text-sm">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                {{ ($r->status ?? 'pending') === 'approved' ? 'bg-green-100 text-green-700' : (($r->status ?? 'pending') === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-700') }}">
                                {{ ucfirst($r->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-slate-700">KSh {{ number_format($r->total_cost ?? 0, 2) }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('reservations.show', $r->reservation_id) }}" class="text-blue-600 hover:text-blue-700 text-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-slate-500">No reservations found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
