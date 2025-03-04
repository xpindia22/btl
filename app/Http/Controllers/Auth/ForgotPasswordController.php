<<<<<<< HEAD
<?php
=======
>>>>>>> 2cb62146d248c9fb1fded5d6b30e24a8e11a9823
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
<<<<<<< HEAD

class ForgotPasswordController extends Controller
{
=======
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password request form.
     */
>>>>>>> 2cb62146d248c9fb1fded5d6b30e24a8e11a9823
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

<<<<<<< HEAD
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Password reset link sent to your email.')
            : back()->withErrors(['email' => 'Unable to send reset link.']);
=======
    /**
     * Handle sending reset link to the user and store it for admins.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found.']);
        }

        // Generate Reset Token
        $token = Password::getRepository()->create($user);

        // Store token in password_resets table (default Laravel behavior)
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['email' => $request->email, 'token' => $token, 'created_at' => now()]
        );

        // Store the reset link securely for admin access
        DB::table('admin_password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'reset_link' => url("/btl/reset-password/{$token}?email=" . urlencode($request->email)),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Password reset link has been generated. Admins can access it.');
>>>>>>> 2cb62146d248c9fb1fded5d6b30e24a8e11a9823
    }
}
