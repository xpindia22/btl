<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'mobile_no' => 'nullable|digits:10',
            'Role' => 'required|in:admin,user,visitor',
        ]);

        // Ensure only admins can assign the 'admin' Role
        if (Auth::check() && Auth::user()->Role === 'admin') {
            $Role = $request->Role; // Admin can assign any Role
        } else {
            $Role = in_array($request->Role, ['user', 'visitor']) ? $request->Role : 'visitor'; // Regular users can only register as user or visitor
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'Role' => $Role,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
