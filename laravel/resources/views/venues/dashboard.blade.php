<x-layouts.app :title="'Dashboard – Smart Event Reservation'">
    <section>
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Welcome back, <span class="text-blue-600">User</span>!</h1>
            <p class="text-slate-600">Here’s a quick summary of your activity.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-600">Upcoming Events</p>
                    <span class="text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.75 3A.75.75 0 0 0 6 3.75v16.5a.75.75 0 0 0 1.2.6L12 17.25l4.8 3.6a.75.75 0 0 0 1.2-.6V3.75A.75.75 0 0 0 17.25 3H6.75Z"/></svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold">3</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-600">Total Bookings</p>
                    <span class="text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M11.25 3.75A2.25 2.25 0 0 0 9 6v1.5H6.75A2.25 2.25 0 0 0 4.5 9.75v8.25A2.25 2.25 0 0 0 6.75 20.25h10.5A2.25 2.25 0 0 0 19.5 18V9.75A2.25 2.25 0 0 0 17.25 7.5H15V6a2.25 2.25 0 0 0-3.75-2.25Z"/></svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold">12</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-600">Favorite Venues</p>
                    <span class="text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M11.645 20.91a.75.75 0 0 1-.79 0C9.12 19.66 3 15.748 3 10.5a5.25 5.25 0 0 1 9-3.61 5.25 5.25 0 0 1 9 3.61c0 5.248-6.12 9.16-7.855 10.41Z"/></svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold">5</p>
            </div>
        </div>
    </section>

    <section class="mt-10">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold">Upcoming Bookings</h2>
            <a href="#" class="text-sm text-blue-600 hover:underline">View all</a>
        </div>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ([
                ['name' => 'Grand Ballroom', 'date' => 'Dec 20, 2025', 'time' => '6:00 PM', 'type' => 'Wedding', 'status' => 'Confirmed'],
                ['name' => 'Tech Hub Conference Center', 'date' => 'Jan 12, 2026', 'time' => '9:00 AM', 'type' => 'Conference', 'status' => 'Pending'],
                ['name' => 'Garden Pavilion', 'date' => 'Feb 05, 2026', 'time' => '1:00 PM', 'type' => 'Birthday', 'status' => 'Confirmed'],
            ] as $b)
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $b['name'] }}</p>
                            <p class="text-sm text-slate-600">{{ $b['date'] }} · {{ $b['time'] }}</p>
                            <p class="mt-1 text-sm text-slate-700">{{ $b['type'] }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $b['status'] === 'Confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $b['status'] }}</span>
                    </div>
                    <div class="mt-4">
                        <a href="#" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-10">
        <h2 class="text-xl font-semibold">Quick Actions</h2>
        <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="#" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <span class="rounded-lg bg-blue-50 text-blue-600 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3.75a.75.75 0 0 1 .75.75v5.25H18a.75.75 0 0 1 0 1.5h-5.25V18a.75.75 0 0 1-1.5 0v-6.75H6a.75.75 0 0 1 0-1.5h5.25V4.5A.75.75 0 0 1 12 3.75Z"/></svg>
                    </span>
                    <div>
                        <p class="font-semibold">Book New Event</p>
                        <p class="text-xs text-slate-600">Start a new reservation</p>
                    </div>
                </div>
            </a>
            <a href="#" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <span class="rounded-lg bg-blue-50 text-blue-600 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Zm3 .75v9h12v-9H6Z"/></svg>
                    </span>
                    <div>
                        <p class="font-semibold">Browse Venues</p>
                        <p class="text-xs text-slate-600">Find the perfect space</p>
                    </div>
                </div>
            </a>
            <a href="#" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <span class="rounded-lg bg-blue-50 text-blue-600 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5.25 5.25 0 1 0 0-10.5 5.25 5.25 0 0 0 0 10.5ZM4.5 20.25A7.5 7.5 0 0 1 12 12.75a7.5 7.5 0 0 1 7.5 7.5.75.75 0 0 1-.75.75h-13.5a.75.75 0 0 1-.75-.75Z"/></svg>
                    </span>
                    <div>
                        <p class="font-semibold">Manage Profile</p>
                        <p class="text-xs text-slate-600">Update your details</p>
                    </div>
                </div>
            </a>
            <a href="#" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <span class="rounded-lg bg-blue-50 text-blue-600 p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4.5 5.25A2.25 2.25 0 0 1 6.75 3h10.5A2.25 2.25 0 0 1 19.5 5.25v13.5A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75V5.25Z"/></svg>
                    </span>
                    <div>
                        <p class="font-semibold">View All Bookings</p>
                        <p class="text-xs text-slate-600">See your history</p>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <section class="mt-10">
        <h2 class="text-xl font-semibold">Recommended Venues</h2>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-venue-card name="Riverside Hall" location="Riverside, Nairobi" capacity="220" rating="4.5"/>
            <x-venue-card name="Skyline Terrace" location="Upper Hill, Nairobi" capacity="180" rating="4.4"/>
            <x-venue-card name="Heritage Hall" location="Karen, Nairobi" capacity="200" rating="4.6"/>
        </div>
    </section>
</x-layouts.app>
