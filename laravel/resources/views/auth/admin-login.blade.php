@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
    <div class="mx-auto max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold mb-4 tracking-tight">Admin Login</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Admin Email</label>
                <input id="email" name="email" type="email" required autocomplete="email"
                       value="{{ old('email') }}"
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" minlength="8"
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2" />
            </div>
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300" /> Remember me
                </label>
                <a href="{{ route('login') }}" class="text-sm text-slate-600 hover:text-slate-800">User login</a>
            </div>
            <button type="submit" class="w-full rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white shadow hover:shadow-md hover:from-blue-700 hover:to-blue-800">
                Sign in
            </button>
        </form>
    </div>
@endsection
