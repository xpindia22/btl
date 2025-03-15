<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\UserCreatedMail;

class UserRegisterController extends Controller
{
    // Show registration form
    public function showRegistrationForm()
    {
        return view('users.create');
    }

    // Handle user registration
    public function register(Request $request)
    {
        // Validate input data
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'dob' => 'required|date',
            'sex' => 'required|string|in:Male,Female,Other',
            'mobile_no' => 'nullable|string|max:15',
            'role' => 'required|string|in:admin,user,visitor',
        ]);

        // Get the currently logged-in user's ID (or default to 1 if not logged in)
        $createdBy = Auth::id() ?? 1;

        // Create the new user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dob' => $request->dob,
            'sex' => $request->sex,
            'mobile_no' => $request->mobile_no,
            'role' => $request->role, // Fixed role assignment
            'created_by' => $createdBy, // Set the creator of this user
        ]);

        // Keep the original creator logged in
        Auth::loginUsingId($createdBy, true);

        // Send Email Notification
        try {
            Mail::to($user->email)
                ->cc('xpindia@gmail.com') // Notify the admin
                ->send(new UserCreatedMail($user, Auth::user()->username ?? 'Admin'));

            \Log::info("User Created Email sent to: " . $user->email . " & Admin");
        } catch (\Exception $e) {
            \Log::error("Failed to send User Created Email: " . $e->getMessage());
        }

        // Redirect back to user listing page with success message
        return redirect()->route('users.index')->with('success', 'User registered successfully!');
    }
}
