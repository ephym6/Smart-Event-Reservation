<x-layouts.app :title="'Register – Smart Event Reservation'">
    <div class="mx-auto max-w-md px-6 py-12">
        <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold">Register</h2>

            @if ($errors->any())
                <div class="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="mt-4">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label for="phone_number" class="block text-sm font-medium text-slate-700">Phone (optional)</label>
                    <input
                        id="phone_number"
                        type="text"
                        name="phone_number"
                        value="{{ old('phone_number') }}"
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"
                    />
                </div>

                <button type="submit" class="w-full rounded-md bg-blue-600 px-3 py-2 text-white font-medium hover:bg-blue-700">
                    Register
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-slate-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600">Login</a>
            </p>
        </div>
    </div>
</x-layouts.app>
