@extends('layouts.app')

@section('title', ($event->event_name ?? 'Event').' – Event')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            <h1 class="text-3xl font-bold tracking-tight">{{ $event->event_name ?? 'Event' }}</h1>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-700">
                    <div><span class="font-medium text-slate-900">Date:</span> {{ $event->event_date ? \Illuminate\Support\Carbon::parse($event->event_date)->format('M d, Y') : 'TBD' }}</div>
                    <div><span class="font-medium text-slate-900">Time:</span> {{ $event->start_time ? \Illuminate\Support\Carbon::parse($event->start_time)->format('H:i') : '—' }} – {{ $event->end_time ? \Illuminate\Support\Carbon::parse($event->end_time)->format('H:i') : '—' }}</div>
                    <div class="sm:col-span-2"><span class="font-medium text-slate-900">Venue:</span> {{ $event->venue->venue_name ?? '—' }}</div>
                </dl>
                <p class="mt-4 text-slate-700">{{ $event->description ?? 'No description.' }}</p>
            </div>
        </div>
        <div class="space-y-4" id="reserve">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold tracking-tight mb-3">Reserve a spot</h2>

                @if ($errors->any())
                    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('reservations.store') }}" class="grid grid-cols-1 gap-4">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->event_id }}" />
                    <input type="hidden" name="venue_id" value="{{ $event->venue->venue_id ?? '' }}" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-slate-700">Start</label>
                            <input id="start_time" name="start_time" type="datetime-local" required value="{{ old('start_time') }}"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-slate-700">End</label>
                            <input id="end_time" name="end_time" type="datetime-local" required value="{{ old('end_time') }}"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="guests" class="block text-sm font-medium text-slate-700">Guests</label>
                            <input id="guests" name="guests" type="number" min="1" step="1" inputmode="numeric" value="{{ old('guests') }}"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <label for="total_cost" class="block text-sm font-medium text-slate-700">Estimated Total (KSh)</label>
                            <input id="total_cost" name="total_cost" type="number" min="0" step="0.01" inputmode="decimal" value="{{ old('total_cost') }}"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('events.index') }}" class="text-slate-600 hover:text-slate-800">Cancel</a>
                        <button type="submit" class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white shadow hover:shadow-md hover:from-blue-700 hover:to-blue-800">Reserve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
