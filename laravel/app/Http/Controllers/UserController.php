<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        
        if (request()->wantsJson()) {
            return response()->json($users, 200);
        }
        
        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password_hash' => 'required|string|min:6',
            'role' => 'in:admin,user,manager',
        ]);

        $validated['password_hash'] = Hash::make($validated['password_hash']);

        $user = User::create($validated);
        
        if (request()->wantsJson()) {
            return response()->json($user, 201);
        }
        
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        
        if (request()->wantsJson()) {
            return response()->json($user);
        }
        
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        User::destroy($id);
        
        if (request()->wantsJson()) {
            return response()->json(['message' => 'User deleted successfully']);
        }
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
