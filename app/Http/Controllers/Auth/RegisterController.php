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
        // ✅ Validate user input
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'dob' => 'required|date|before:today',
            'sex' => 'required|in:Male,Female,Other',
            'mobile_no' => 'nullable|digits:10',
            'role' => 'required|in:admin,user,visitor',
        ]);

        // ✅ Ensure only admins can assign the 'admin' role
        if (Auth::check() && Auth::user()->role === 'admin') {
            $role = $request->role; // Admin can assign any role
        } else {
            $role = in_array($request->role, ['user', 'visitor']) ? $request->role : 'visitor'; // Regular users can only register as user or visitor
        }

        // ✅ Assign `created_by` to the logged-in user or default to Admin ID 1
        $createdBy = Auth::id() ?? 1;

        // ✅ Create the new user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dob' => $request->dob,
            'sex' => $request->sex,
            'mobile_no' => $request->mobile_no,
            'role' => $role,
            'created_by' => $createdBy,
        ]);

        // ✅ Log the user in after registration
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
