@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="mx-auto max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-bold mb-4 tracking-tight">Create an account</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                <input id="name" name="name" type="text" required autocomplete="name"
                       value="{{ old('name') }}"
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"/>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" required autocomplete="email"
                       value="{{ old('email') }}"
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"/>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-700">Account type</label>
                <select id="role" name="role" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2">
                    <option value="user" {{ old('role','user')==='user' ? 'selected' : '' }}>User</option>
                    <option value="manager" {{ old('role')==='manager' ? 'selected' : '' }}>Manager</option>
                    <option value="admin" {{ old('role')==='admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <p class="mt-1 text-xs text-slate-500">Choose Admin/Manager for administrative access. You can change this later in the database.</p>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" minlength="8"
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"/>
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" minlength="8"
                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2"/>
            </div>
            <button type="submit" class="w-full rounded-md bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2 text-white shadow hover:shadow-md hover:from-blue-700 hover:to-blue-800">
                Sign up
            </button>
            <p class="mt-3 text-center text-sm text-slate-600">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700">Sign in</a></p>
        </form>
    </div>
@endsection
