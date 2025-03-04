namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
    }
}
