<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;

public function register(Request $request)
{
    $request->validate([
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'dob' => 'required|date|before:today',
        'sex' => 'required|in:Male,Female,Other',
        'mobile_no' => 'nullable|digits:10',
        'role' => 'required|in:admin,user,visitor',
    ]);

    if (Auth::check() && Auth::user()->role === 'admin') {
        $role = $request->role;
    } else {
        $role = in_array($request->role, ['user', 'visitor']) ? $request->role : 'visitor';
    }

    $createdBy = Auth::id() ?? 1;

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

    Auth::loginUsingId($createdBy, true);

    // ✅ Log before sending the email
    \Log::info("Preparing to send User Created Email for: " . $user->email);

    try {
        Mail::to($user->email)
            ->cc('xpindia@gmail.com')
            ->send(new UserCreatedMail($user, Auth::user()->username ?? 'Admin'));

        \Log::info("✅ User Created Email sent to: " . $user->email . " & Admin");
    } catch (\Exception $e) {
        \Log::error("❌ Failed to send User Created Email: " . $e->getMessage());
    }

    return redirect()->route('users.index')->with('success', 'User registered successfully!');
}
