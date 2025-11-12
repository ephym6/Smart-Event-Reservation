@extends('layouts.app')

@section('title', 'Admin Reports')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Reports</h1>
            <p class="text-slate-600">Export users and reservations. Use the date range to filter reservations.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.export.csv', ['dataset' => 'users']) }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm hover:bg-slate-200">Download Users (CSV)</a>
            <a href="{{ route('admin.reports.export.csv', ['dataset' => 'reservations']) }}" class="rounded-md bg-slate-100 px-3 py-2 text-sm hover:bg-slate-200">Download Reservations (CSV)</a>
            <a href="{{ route('admin.reports.export.pdf') }}" class="rounded-md bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700">Download PDF</a>
        </div>
    </div>

    <form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-5 gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <div>
            <label for="start" class="block text-sm font-medium text-slate-700">Start</label>
            <input id="start" name="start" type="datetime-local" value="{{ $start ? \Illuminate\Support\Carbon::parse($start)->format('Y-m-d\TH:i') : '' }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"/>
        </div>
        <div>
            <label for="end" class="block text-sm font-medium text-slate-700">End</label>
            <input id="end" name="end" type="datetime-local" value="{{ $end ? \Illuminate\Support\Carbon::parse($end)->format('Y-m-d\TH:i') : '' }}" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"/>
        </div>
        <div class="self-end">
            <button class="w-full rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700" type="submit">Filter</button>
        </div>
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm overflow-x-auto">
            <h2 class="text-lg font-semibold mb-3">Users</h2>
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

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm overflow-x-auto">
            <h2 class="text-lg font-semibold mb-3">Reservations</h2>
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
    </div>
@endsection
