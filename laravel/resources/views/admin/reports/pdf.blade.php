@extends('layouts.app')

@section('title', 'Report (Printable/PDF)')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Smart Event Reservation — Report</h1>
        <div class="flex items-center gap-2">
            @if (!empty($pdfFallback))
                <button onclick="window.print()" class="rounded-md bg-blue-600 px-3 py-1.5 text-white hover:bg-blue-700">Print</button>
            @endif
        </div>
    </div>

    <style>
        @media print {
            nav, header, footer, .no-print { display: none !important; }
            main { padding: 0 !important; }
        }
    </style>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm mb-6">
        <h2 class="text-lg font-semibold mb-2">Users</h2>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Role</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @foreach ($users as $u)
                    <tr>
                        <td class="px-3 py-2 text-sm">{{ $u->user_id }}</td>
                        <td class="px-3 py-2 text-sm">{{ $u->name }}</td>
                        <td class="px-3 py-2 text-sm">{{ $u->email }}</td>
                        <td class="px-3 py-2 text-sm">{{ ucfirst($u->role ?? 'user') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <h2 class="text-lg font-semibold mb-2">Reservations</h2>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">User</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Venue</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Event</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Start</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">End</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @foreach ($reservations as $r)
                    <tr>
                        <td class="px-3 py-2 text-sm">{{ $r->reservation_id }}</td>
                        <td class="px-3 py-2 text-sm">{{ $r->user->name ?? '—' }}</td>
                        <td class="px-3 py-2 text-sm">{{ $r->venue->venue_name ?? '—' }}</td>
                        <td class="px-3 py-2 text-sm">{{ $r->event->event_name ?? '—' }}</td>
                        <td class="px-3 py-2 text-sm">{{ optional($r->start_time)->format('M d, Y H:i') }}</td>
                        <td class="px-3 py-2 text-sm">{{ optional($r->end_time)->format('M d, Y H:i') }}</td>
                        <td class="px-3 py-2 text-sm">{{ ucfirst($r->status ?? 'pending') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection