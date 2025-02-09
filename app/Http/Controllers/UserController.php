<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $users = User::all();
        return view('users_index', compact('users'));
    }

    public function create()
    {
        return view('users_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'mobile_no' => 'nullable|digits:10',
            'role' => 'in:visitor,user'
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'role' => $request->role ?? 'visitor',
        ]);

        return redirect()->route('users.index')->with('success', 'User registered successfully!');
    }

    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $user = User::find($request->user_id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile_no' => 'nullable|digits:10',
            'role' => 'in:admin,user,visitor'
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $user = User::find($request->user_id);
        if (!$user || $user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Cannot delete user.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
