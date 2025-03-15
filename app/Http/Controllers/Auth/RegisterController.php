<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\UserCreatedMail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('users.register');
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

        // ✅ Create the new user (WITHOUT logging them in)
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

        // ✅ KEEP THE ORIGINAL CREATOR LOGGED IN
        Auth::loginUsingId($createdBy, true);

        // ✅ Send Email Notification (New User Registration)
        try {
            Mail::to($user->email)
                ->cc('xpindia@gmail.com') // ✅ Always notify the admin
                ->send(new UserCreatedMail($user, Auth::user()->username ?? 'Admin'));

            \Log::info("User Created Email sent to: " . $user->email . " & Admin");
        } catch (\Exception $e) {
            \Log::error("Failed to send User Created Email: " . $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'User registered successfully!');
    }

    return redirect()->route('users.index')->with('success', 'User registered successfully!');
}
