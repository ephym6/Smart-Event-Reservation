@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-slate-600">Manage venues, events, reservations, and inventory.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-600">Venues</p>
                <span class="text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.75 3A.75.75 0 0 0 6 3.75v16.5a.75.75 0 0 0 1.2.6L12 17.25l4.8 3.6a.75.75 0 0 0 1.2-.6V3.75A.75.75 0 0 0 17.25 3H6.75Z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-2xl font-bold">{{ $venuesCount }}</p>
            <a href="{{ route('venues.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-700 text-sm">Manage</a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-600">Reservations</p>
                <span class="text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M11.25 3.75A2.25 2.25 0 0 0 9 6v1.5H6.75A2.25 2.25 0 0 0 4.5 9.75v8.25A2.25 2.25 0 0 0 6.75 20.25h10.5A2.25 2.25 0 0 0 19.5 18V9.75A2.25 2.25 0 0 0 17.25 7.5H15V6a2.25 2.25 0 0 0-3.75-2.25Z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-2xl font-bold">{{ $reservationsCount }}</p>
            <a href="{{ route('reservations.index') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-700 text-sm">Manage</a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-600">Actions</p>
                <span class="text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3.75a.75.75 0 0 1 .75.75v5.25H18a.75.75 0 0 1 0 1.5h-5.25V18a.75.75 0 0 1-1.5 0v-6.75H6a.75.75 0 0 1 0-1.5h5.25V4.5A.75.75 0 0 1 12 3.75Z"/></svg>
                </span>
            </div>
            <a href="{{ route('venues.create') }}" class="mt-3 inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700 text-sm">Add Venue</a>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-600">Reserved Venues Today</p>
                <span class="text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.75 2.25A.75.75 0 0 0 6 3v.75H4.5A2.25 2.25 0 0 0 2.25 6v12A2.25 2.25 0 0 0 4.5 20.25h15a2.25 2.25 0 0 0 2.25-2.25V6A2.25 2.25 0 0 0 19.5 3.75H18V3a.75.75 0 0 0-1.5 0v.75h-9V3a.75.75 0 0 0-1.5 0v.75H6V3a.75.75 0 0 0-.75-.75Z"/></svg>
                </span>
            </div>
            <p class="mt-2 text-2xl font-bold">{{ ($reservedVenues ?? collect())->count() }}</p>
        </div>
    </div>

    <div class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Reports</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.index') }}" class="rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700 text-sm">Open Reports</a>
                <a href="{{ route('admin.reports.export.csv', ['dataset' => 'users']) }}" class="rounded-md bg-slate-100 px-3 py-1.5 text-slate-700 hover:bg-slate-200 text-sm">Users CSV</a>
                <a href="{{ route('admin.reports.export.csv', ['dataset' => 'reservations']) }}" class="rounded-md bg-slate-100 px-3 py-1.5 text-slate-700 hover:bg-slate-200 text-sm">Reservations CSV</a>
                <a href="{{ route('admin.reports.export.pdf') }}" class="rounded-md bg-slate-100 px-3 py-1.5 text-slate-700 hover:bg-slate-200 text-sm">PDF</a>
            </div>
        </div>
        <p class="mt-2 text-sm text-slate-600">Filter, download and print full reports of users and reservations.</p>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold mb-3">Reserved Venues Today</h2>
            <ul class="space-y-2">
                @forelse(($reservedVenues ?? collect()) as $v)
                    <li class="flex items-center justify-between text-sm">
                        <span>{{ $v->venue_name }}</span>
                        <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-700 px-2 py-0.5 text-xs">Reserved</span>
                    </li>
                @empty
                    <li class="text-slate-500">No reserved venues today.</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm overflow-x-auto">
            <h2 class="text-lg font-semibold mb-3">Recent Reservations</h2>
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Venue</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Event</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Start</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">End</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse(($recentReservations ?? collect()) as $r)
                        <tr>
                            <td class="px-3 py-2 text-sm">{{ $r->user->name ?? '—' }}</td>
                            <td class="px-3 py-2 text-sm">{{ $r->venue->venue_name ?? '—' }}</td>
                            <td class="px-3 py-2 text-sm">{{ $r->event->event_name ?? '—' }}</td>
                            <td class="px-3 py-2 text-sm">{{ optional($r->start_time)->format('M d, Y H:i') }}</td>
                            <td class="px-3 py-2 text-sm">{{ optional($r->end_time)->format('M d, Y H:i') }}</td>
                            <td class="px-3 py-2 text-sm">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ ($r->status ?? 'pending') === 'approved' ? 'bg-green-100 text-green-700' : (($r->status ?? 'pending') === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-700') }}">
                                    {{ ucfirst($r->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-sm text-right">
                                @if(($r->status ?? 'pending') === 'pending')
                                    <form action="{{ route('admin.reservations.approve', $r->reservation_id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="inline-flex items-center rounded-md bg-green-600 px-2 py-1 text-white hover:bg-green-700 text-xs">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.reservations.decline', $r->reservation_id) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        <button class="inline-flex items-center rounded-md bg-red-600 px-2 py-1 text-white hover:bg-red-700 text-xs">Decline</button>
                                    </form>
                                @else
                                    <span class="text-slate-500">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-4 text-center text-slate-500">No reservations yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
