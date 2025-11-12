<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // If user does not exist, create a basic account on-the-fly (auto signup on first login)
        if (! $user) {
            $generatedName = Str::of($credentials['email'])->before('@')->headline();
            $user = User::create([
                'name' => (string) $generatedName,
                'email' => $credentials['email'],
                'password_hash' => Hash::make($credentials['password']),
                'role' => 'user',
                'is_verified' => true,
            ]);
        } else {
            // Validate password for existing users
            if (! Hash::check($credentials['password'], $user->password_hash)) {
                return back()->withErrors(['email' => 'The provided credentials are incorrect.'])->withInput();
            }
        }

        // Optional: persist last login metadata if columns exist
        $dirty = false;
        if (Schema::hasColumn('users', 'last_login_at')) {
            $user->last_login_at = now();
            $dirty = true;
        }
        if (Schema::hasColumn('users', 'last_login_ip')) {
            $user->last_login_ip = $request->ip();
            $dirty = true;
        }
        if (Schema::hasColumn('users', 'last_login_user_agent')) {
            $user->last_login_user_agent = (string) $request->userAgent();
            $dirty = true;
        }
        if ($dirty) {
            $user->save();
        }

        Auth::login($user, (bool) $request->boolean('remember'));
        $request->session()->regenerate();

        // After login redirect
        $default = in_array($user->role, ['admin', 'manager']) ? route('dashboard') : route('home');
        return redirect()->intended($default);
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'role' => 'user',
            'is_verified' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Welcome, '.$user->name.'!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
