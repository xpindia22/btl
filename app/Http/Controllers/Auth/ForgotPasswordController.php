<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form.
     * If the user is logged in, redirect them to the dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLinkRequestForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('status', 'You are already logged in. Please use your profile page to change your password.');
        }
        
        return view('auth.forgot-password'); // ✅ Use the new view
    }

    /**
     * Process the request and send the password reset link email.
     * If the user is logged in, redirect them to the dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('status', 'You are already logged in. Please use your profile page to change your password.');
        }
        
        // Validate the email input.
        $request->validate(['email' => 'required|email']);

        // Apply rate limiting: 5 attempts per minute per IP/email combination.
        $key = 'forgot-password:' . $request->ip() . '|' . $request->email;
        $maxAttempts = 5;
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => 'Too many attempts. Please try again in ' . $seconds . ' seconds.'
            ], 429);
        }

        RateLimiter::hit($key, $decaySeconds);

        // Retrieve the user using Eloquent.
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Generate a secure reset token.
            $token = Password::getRepository()->create($user);

            // Set token expiration time (10 minutes from now).
            $expiresAt = Carbon::now()->addMinutes(10);

            // Store the token and expiration in the password_resets table.
            // Make sure the table has an 'expires_at' column.
            DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email'      => $request->email,
                    'token'      => $token,
                    'created_at' => now(),
                    'expires_at' => $expiresAt,
                ]
            );

            // Log the email address being used for debugging.
            \Log::info('Sending reset password email to: ' . $user->email);

            // Send the reset link email using the mailable class.
            Mail::to($user->email)->send(new ResetPasswordMail($token, $expiresAt));
        }

        // Return a generic success message regardless of whether the email exists.
        return redirect()->back()->with('status', 'If an account with that email exists, you will receive a password reset link shortly.');
    }
}
