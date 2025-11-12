<x-layouts.app :title="'Home – Smart Event Reservation'">
    {{-- Hero Section --}}
    <section class="relative overflow-hidden rounded-2xl bg-[url('https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=1920&q=80&auto=format&fit=crop')] bg-cover bg-center">
        <div class="absolute inset-0 bg-slate-900/60"></div>
        <div class="relative mx-auto max-w-5xl px-6 py-24 text-center text-white">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Find Your Perfect Event Space</h1>
            <p class="mt-4 text-lg text-slate-100">Book unique venues for weddings, conferences, and special events.</p>

            <div class="mt-8 rounded-xl bg-white/90 backdrop-blur p-3">
                <form action="{{ route('venues.index') }}" method="GET" role="search" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                    <label for="location" class="sr-only">Location</label>
                    <select
                        id="location"
                        name="location"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        aria-label="Location"
                    >
                        <option value="">Location</option>
                        <option value="Nairobi CBD" @selected(request('location') === 'Nairobi CBD')>Nairobi CBD</option>
                        <option value="Westlands" @selected(request('location') === 'Westlands')>Westlands</option>
                        <option value="Kilimani" @selected(request('location') === 'Kilimani')>Kilimani</option>
                        <option value="Karen" @selected(request('location') === 'Karen')>Karen</option>
                        <option value="Upper Hill" @selected(request('location') === 'Upper Hill')>Upper Hill</option>
                    </select>

                    <label for="date" class="sr-only">Event date</label>
                    <input
                        id="date"
                        type="date"
                        name="date"
                        value="{{ request('date') }}"
                        min="{{ now()->toDateString() }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        aria-label="Event date"
                    />

                    <label for="guests" class="sr-only">Guests</label>
                    <input
                        id="guests"
                        type="number"
                        name="guests"
                        min="1"
                        step="1"
                        inputmode="numeric"
                        placeholder="Guests"
                        value="{{ request('guests') }}"
                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        aria-label="Guests"
                    />

                    <button
                        type="submit"
                        class="w-full rounded-md bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Featured Venues --}}
    <section class="mt-12">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-xl font-semibold">Featured Venues</h2>
            @if($featuredVenues->count())
                <a href="{{ route('venues.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
            @endif
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($featuredVenues as $venue)
                <x-venue-card
                    :name="$venue->venue_name"
                    :location="$venue->location"
                    :capacity="$venue->capacity"
                    :rating="$venue->average_rating ?? number_format(rand(40, 50) / 10, 1)"
                />
            @empty
                <p class="text-slate-500">No venues available at the moment.</p>
            @endforelse
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="mt-14 rounded-2xl bg-white p-8 shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
            <div>
                <div class="mx-auto h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="mt-3 text-2xl font-bold">100+ Premium Venues</p>
            </div>
            <div>
                <div class="mx-auto h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="mt-3 text-2xl font-bold">5,000+ Successful Events</p>
            </div>
            <div>
                <div class="mx-auto h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7-7h11v18H10l-7-7z" />
                    </svg>
                </div>
                <p class="mt-3 text-2xl font-bold">24/7 Customer Support</p>
            </div>
        </div>
    </section>
</x-layouts.app>
