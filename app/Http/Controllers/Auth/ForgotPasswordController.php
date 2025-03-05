<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;


class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password request form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending reset link to the user and store it for admins.
     */
   
public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    $user = DB::table('users')->where('email', $request->email)->first();
    if (!$user) {
        return back()->withErrors(['email' => 'No account found with this email.']);
    }

    // Generate Reset Token
    $token = Password::getRepository()->create($user);
    
    // Set expiration time (10 minutes)
    $expiresAt = Carbon::now()->addMinutes(10);

    // Store token in password_resets table
    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        ['email' => $request->email, 'token' => $token, 'created_at' => now()]
    );

    // Store reset link in admin_password_resets table
    DB::table('admin_password_resets')->updateOrInsert(
        ['email' => $request->email],
        [
            'email' => $request->email,
            'token' => $token,
            'reset_link' => URL::to('/reset-password/' . $token . '?email=' . urlencode($request->email)),
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ]
    );

    return back()->with('success', 'Password reset link has been sent.');
}

}
