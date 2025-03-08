<?php 

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    // ✅ Show Users List Based on Role
    public function index()
    {
        $authUser = Auth::user();

        if ($authUser->isAdmin()) {
            $users = User::with(['moderatedTournaments', 'createdTournaments'])
                ->orderBy('id', 'asc')
                ->paginate(10);
        } else {
            $users = User::where('id', $authUser->id)->paginate(1);
        }

        return view('users.index', compact('users'));
    }

    // ✅ Show Profile Edit Form for Self-Editing
    public function editProfile()
    {
        return view('users.profile', ['user' => Auth::user()]);
    }

    // ✅ Update Own Profile (Name, Age, Sex, Email, Password)
    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'dob' => 'required|date',
            'sex' => 'required|string|in:Male,Female,Other',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        try {
            $user = Auth::user();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->dob = $request->dob;
            $user->sex = $request->sex;
    
            // ✅ Update password only if a new password is provided
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
    
            $user->save();
    
            // ✅ Log the successful update
            \Log::info('Profile updated successfully for user ID: ' . $user->id);
    
            // ✅ Redirect back with a success message
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
    
            // ❌ Redirect back with an error message
            return redirect()->back()->with('error', 'Profile update failed. Please try again.');
        }
    }
    

    // ✅ Forgot Password - Send Reset Link
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            // ✅ Save reset link for Admins to see
            $token = DB::table('password_resets')->where('email', $request->email)->first()->token;
            DB::table('admin_password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'reset_link' => URL::to('/reset-password/' . $token . '?email=' . urlencode($request->email)),
                'created_at' => now(),
            ]);

            return back()->with('success', 'Reset link sent.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    // ✅ Admin View for Reset Password Requests
    public function showPasswordResets()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access!');
        }

        $passwordResets = DB::table('admin_password_resets')->orderBy('created_at', 'desc')->get();

        return view('admin.password-resets', compact('passwordResets'));
    }

    public function editUsers()
    {
        // ✅ Ensure only admins can access
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access!');
        }
    
        // ✅ Fetch users with their relationships
        $users = User::with(['moderatedTournaments', 'createdTournaments'])
                    ->orderByDesc('id')
                    ->paginate(10);
    
        // ✅ Fetch tournaments ordered by year descending for selection dropdowns
        $tournaments = Tournament::orderByDesc('year')->get();
    
        // ✅ Return inline editing view with data
        return view('users.edit', [
            'users' => $users,
            'tournaments' => $tournaments
        ]);
    }
    

    // ✅ Update User (For Admin)
    
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
        return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
    }

    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'dob' => 'required|date|before:today',
        'sex' => 'required|in:Male,Female,Other',
        'mobile_no' => 'nullable|digits:10',
        'role' => 'required|in:admin,user,visitor,player',
        'password' => 'nullable|min:8|confirmed',
        'moderated_tournaments' => 'sometimes|array',
        'created_tournaments' => 'sometimes|array',
    ]);

    $user->username  = $validated['username'];
    $user->email     = $validated['email'];
    $user->dob       = $validated['dob'];
    $user->sex       = $validated['sex'];
    $user->mobile_no = $validated['mobile_no'];
    $user->role      = $validated['role'];

    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    // Moderated Tournaments
    $user->moderatedTournaments()->sync($validated['moderated_tournaments'] ?? []);

    // Created Tournaments
    $defaultAdminId = User::where('username', 'xxx')->where('role', 'admin')->value('id');
    Tournament::where('created_by', $user->id)->update(['created_by' => $defaultAdminId]);

    if (!empty($validated['created_tournaments'])) {
        Tournament::whereIn('id', $validated['created_tournaments'])
            ->update(['created_by' => $user->id]);
    }

    $user->load('moderatedTournaments', 'createdTournaments');

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}





    // ✅ Delete User
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('users.index')->with('error', 'Unauthorized action.');
        }

        // Prevent self-delete
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
