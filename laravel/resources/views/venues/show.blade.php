@extends('layouts.app')

@section('title', ($venue->venue_name ?? 'Venue').' – Venue')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-4">
            <h1 class="text-3xl font-bold tracking-tight">{{ $venue->venue_name ?? 'Venue' }}</h1>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-slate-700">{{ $venue->description ?? 'No description' }}</p>
                <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-600">
                    <div><span class="font-medium text-slate-900">Location:</span> {{ $venue->location ?? '—' }}</div>
                    <div><span class="font-medium text-slate-900">Capacity:</span> {{ $venue->capacity ?? '—' }}</div>
                    <div><span class="font-medium text-slate-900">Price/Hour:</span> {{ isset($venue->price_per_hour) ? 'KSh '.number_format($venue->price_per_hour, 2) : '—' }}</div>
                    <div><span class="font-medium text-slate-900">Status:</span> {{ ucfirst($venue->status ?? 'available') }}</div>
                </dl>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-semibold tracking-tight mb-3">Upcoming Events</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse (($venue->events ?? collect()) as $event)
                        <x-event-card
                            :name="$event->event_name"
                            :date="$event->event_date"
                            :venue="$venue->venue_name ?? ''"
                            :url="route('events.show', $event->event_id)"
                        />
                    @empty
                        <p class="text-slate-500">No events scheduled.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-4" id="reserve">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold mb-3">Book this venue</h2>
                <p class="text-sm text-slate-600 mb-4">Choose your dates and create a reservation.</p>

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
                    <input type="hidden" name="venue_id" value="{{ $venue->venue_id }}" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-slate-700">Start</label>
<input id="start_time" name="start_time" type="datetime-local" required value="{{ old('start_time', request('date') ? request('date').'T10:00' : '') }}" min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-slate-700">End (or leave empty and set Duration)</label>
<input id="end_time" name="end_time" type="datetime-local" value="{{ old('end_time', request('date') ? request('date').'T12:00' : '') }}" min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="duration_hours" class="block text-sm font-medium text-slate-700">Duration (hours)</label>
                            <input id="duration_hours" name="duration_hours" type="number" min="0.5" step="0.5" inputmode="decimal" value="{{ old('duration_hours') }}"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
                            <p class="mt-1 text-xs text-slate-500">Provide End or Duration.</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="guests" class="block text-sm font-medium text-slate-700">Guests</label>
                            <input id="guests" name="guests" type="number" min="1" step="1" inputmode="numeric" value="{{ old('guests') }}"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
                        </div>
                    </div>

                    <div class="text-sm text-slate-600">
                        Estimated: <span id="est-cost">—</span> · Duration: <span id="est-duration">—</span> hrs
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('venues.index') }}" class="text-slate-600 hover:text-slate-800">Cancel</a>
                        <button type="submit" class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white shadow hover:shadow-md hover:from-blue-700 hover:to-blue-800">Reserve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startEl = document.getElementById('start_time');
            const endEl = document.getElementById('end_time');
            const durEl = document.getElementById('duration_hours');
            const estCost = document.getElementById('est-cost');
            const estDur = document.getElementById('est-duration');
            const rate = parseFloat("{{ (float)($venue->price_per_hour ?? 0) }}");

            function toDate(v){ return v ? new Date(v) : null; }
            function minsDiff(a,b){ return Math.max(0, Math.round((b - a) / 60000)); }

            function updateMinConstraints(){
                if (startEl.value) { endEl.min = startEl.value; }
            }

            function compute() {
                const s = toDate(startEl.value);
                const e = toDate(endEl.value);
                let hours = 0;
                if (s && e && e > s) {
                    const mins = minsDiff(s,e);
                    hours = (mins/60);
                    if (durEl) durEl.value = (Math.round(hours*100)/100).toFixed(2);
                } else if (s && durEl && durEl.value) {
                    const mins = Math.max(0, Math.round(parseFloat(durEl.value)*60));
                    const d = new Date(s.getTime() + mins*60000);
                    endEl.value = d.toISOString().slice(0,16);
                    hours = mins/60;
                }
                estDur.textContent = hours ? hours.toFixed(2) : '—';
                estCost.textContent = (hours && rate) ? ('KSh ' + (hours*rate).toFixed(2)) : 'KSh 0.00';
            }

            ['change','input'].forEach(evt => {
                startEl.addEventListener(evt, () => { updateMinConstraints(); compute(); });
                endEl.addEventListener(evt, compute);
                if (durEl) durEl.addEventListener(evt, compute);
            });

            updateMinConstraints();
            compute();
        });
    </script>
    @endpush
@endsection
