<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        // Optionally pass the email from the request
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle the reset password form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
{
    // Validate the request data.
    $request->validate([
        'token'                 => 'required',
        'email'                 => 'required|email',
        'password'              => 'required|confirmed|min:8',
    ]);

    // Attempt to reset the user's password.
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();

            // Send the confirmation email after resetting the password.
            \Mail::to($user->email)->send(new \App\Mail\PasswordResetSuccessMail($user));
        }
    );

    // Check the reset status.
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : redirect()->back()->withErrors(['email' => __($status)]);
}
}
