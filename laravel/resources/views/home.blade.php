<x-layouts.app :title="'Home – Smart Event Reservation'">
    <section class="relative overflow-hidden rounded-2xl bg-[url('https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=1920&q=80&auto=format&fit=crop')] bg-cover bg-center">
        <div class="absolute inset-0 bg-slate-900/60"></div>
        <div class="relative mx-auto max-w-5xl px-6 py-24 text-center text-white">
            <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight">Find Your Perfect Event Space</h1>
            <p class="mt-4 text-lg text-slate-100">Book unique venues for weddings, conferences, and special events.</p>
            <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-4 bg-white/90 backdrop-blur rounded-xl p-3">
                <select class="w-full rounded-md border border-slate-300 px-3 py-2 text-slate-700">
                    <option>Location</option>
                    <option>Nairobi CBD</option>
                    <option>Westlands</option>
                    <option>Kilimani</option>
                    <option>Karen</option>
                    <option>Upper Hill</option>
                </select>
                <input type="date" class="w-full rounded-md border border-slate-300 px-3 py-2 text-slate-700"/>
                <input type="number" min="1" placeholder="Guests" class="w-full rounded-md border border-slate-300 px-3 py-2 text-slate-700"/>
                <button class="w-full rounded-md bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700">Search</button>
            </div>
        </div>
    </section>

    <section class="mt-12">
        <h2 class="text-xl font-semibold">Featured Venues</h2>
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-venue-card name="Karen Country Club" location="Karen, Nairobi" capacity="400" rating="4.7"/>
            <x-venue-card name="Kenyatta International Convention Centre (KICC)" location="Nairobi CBD, Nairobi" capacity="5000" rating="4.6"/>
            <x-venue-card name="Bomas of Kenya" location="Lang'ata, Nairobi" capacity="2000" rating="4.5"/>
        </div>
    </section>

    <section class="mt-14 rounded-2xl bg-white p-8 shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
            <div>
                <div class="mx-auto h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Zm3 0.75v9h12v-9H6Z"/></svg>
                </div>
                <p class="mt-3 text-2xl font-bold">100+ Premium Venues</p>
            </div>
            <div>
                <div class="mx-auto h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M6.75 3A.75.75 0 0 0 6 3.75v16.5a.75.75 0 0 0 1.2.6L12 17.25l4.8 3.6a.75.75 0 0 0 1.2-.6V3.75A.75.75 0 0 0 17.25 3H6.75Z"/></svg>
                </div>
                <p class="mt-3 text-2xl font-bold">5,000+ Successful Events</p>
            </div>
            <div>
                <div class="mx-auto h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M5.25 4.5A2.25 2.25 0 0 0 3 6.75v10.5A2.25 2.25 0 0 0 5.25 19.5h4.586a1.5 1.5 0 0 0 1.06-.44l8.164-8.164a1.5 1.5 0 0 0 0-2.121L14.457 3.562a1.5 1.5 0 0 0-2.121 0L4.172 11.726a1.5 1.5 0 0 0-.44 1.06V17.25"/></svg>
                </div>
                <p class="mt-3 text-2xl font-bold">24/7 Customer Support</p>
            </div>
        </div>
    </section>
</x-layouts.app>
