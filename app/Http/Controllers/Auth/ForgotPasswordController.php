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
    public function showLinkRequestForm(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('status', 'You are already logged in. Please use your profile page to change your password.');
        }
        
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('status', 'You are already logged in. Please use your profile page to change your password.');
        }
        
        $request->validate(['email' => 'required|email']);

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

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Password::getRepository()->create($user);
            $expiresAt = Carbon::now()->addMinutes(10);
            $ipAddress = $request->ip();

            DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email'      => $request->email,
                    'token'      => $token,
                    'ip_address' => $ipAddress,
                    'created_at' => now(),
                    'expires_at' => $expiresAt,
                ]
            );

            Mail::to($user->email)->send(new ResetPasswordMail($token, $expiresAt));
        }

        return redirect()->back()->with('status', 'If an account with that email exists, you will receive a password reset link shortly.');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid or expired token.']);
        }

        if ($record->ip_address !== $request->ip()) {
            return back()->withErrors(['email' => 'This reset link can only be used on the device where it was requested.']);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successful. You may now log in.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
