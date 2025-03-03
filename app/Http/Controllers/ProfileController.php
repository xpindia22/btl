<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'age' => 'nullable|integer|min:10|max:100',
            'sex' => 'nullable|in:Male,Female,Other',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->age = $request->age;
        $user->sex = $request->sex;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
