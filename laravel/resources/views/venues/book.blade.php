<x-layouts.app :title="'Book a Venue'">
    <div class="max-w-lg mx-auto bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Book a Venue</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-700 border border-red-200 rounded p-3">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 bg-green-50 text-green-700 border border-green-200 rounded p-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('venues.reserve') }}" class="space-y-4">
            @csrf

            <div>
                <label for="venue_id" class="block text-sm font-medium text-slate-700 mb-1">Select Venue</label>
                <select name="venue_id" id="venue_id" required
                    class="w-full border border-slate-300 rounded-md p-2">
                    <option value="">-- Choose --</option>
                    @foreach ($venues as $venue)
                        <option value="{{ $venue->venue_id }}" 
                            {{ (old('venue_id', $preselect) == $venue->venue_id) ? 'selected' : '' }}>
                            {{ $venue->venue_name }} ({{ $venue->location }}) - Ksh.{{ number_format($venue->price_per_hour, 2) }}/hr
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_time" class="block text-sm font-medium text-slate-700 mb-1">Start Time</label>
                <input type="datetime-local" name="start_time" id="start_time"
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       value="{{ old('start_time') }}"
                       required
                       class="w-full border border-slate-300 rounded-md p-2">
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-slate-700 mb-1">End Time</label>
                <input type="datetime-local" name="end_time" id="end_time"
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       value="{{ old('end_time') }}"
                       required
                       class="w-full border border-slate-300 rounded-md p-2">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Reserve
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('venues.list') }}" class="text-blue-600 hover:underline">← Back to Venues</a>
        </div>
    </div>
</x-layouts.app>
