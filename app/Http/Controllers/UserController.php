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
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedNotification;
use App\Models\Matches;


class UserController extends Controller
{
    // ✅ Show Users List Based on Role
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $sortColumn = $request->get('sort', 'id'); // Default sorting by ID
    
        if ($authUser->isAdmin()) {
            // ✅ Admin sees all users
            $users = User::with(['moderatedTournaments', 'createdTournaments'])
                         ->orderByDesc($sortColumn)
                         ->paginate(10);
        } else {
            // ✅ Regular users see only the users they created (or assigned to admin)
            $users = User::with(['moderatedTournaments', 'createdTournaments'])
                         ->where('created_by', $authUser->id)
                         ->orWhere('created_by', 1) // ✅ Allow viewing users created by admin
                         ->orderByDesc($sortColumn)
                         ->paginate(10);
        }
    
        $matches = Matches::paginate(10);
    
        return view('users.index', compact('users', 'matches'));
    }
    

public function editUsers()
{
    if (!auth()->user()->isAdmin()) {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access!');
    }

    $users = User::with(['moderatedTournaments', 'createdTournaments'])->orderByDesc('id')->paginate(10); // ✅ Sorted Desc
    $tournaments = Tournament::orderByDesc('year')->get();
    $matches = Matches::paginate(10);

    return view('users.edit', compact('users', 'tournaments', 'matches'));
}

// ✅ Update User Inline via AJAX
public function updateUserInline(Request $request, $id)
{
    $user = User::findOrFail($id);

    if (!Auth::user()->isAdmin()) {
        return response()->json(['error' => 'Unauthorized action.'], 403);
    }

    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $user->id,
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'dob' => 'required|date|before:today',
        'sex' => 'required|in:Male,Female,Other',
        'mobile_no' => 'nullable|digits:10',
        'role' => 'required|in:admin,user,visitor,player',
        'password' => 'nullable|min:8|confirmed', // ✅ Password is optional
    ]);

    // ✅ Only update password if provided
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']); // ✅ Remove password key if not provided
    }

    $user->update($validated);

    return response()->json(['success' => 'User updated successfully.']);
}

    // ✅ Show Profile Edit Form for Self-Editing
    public function editProfile()
    {
        return view('users.profile', ['user' => Auth::user()]);
    }

    // ✅ Update Own Profile (Username, DOB, Sex, Email, Password)
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
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'dob' => $request->dob,
                'sex' => $request->sex,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);

            Log::info('Profile updated successfully for user ID: ' . $user->id);
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
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

    // ✅ Store User (Create New User & Send Emails)
    public function store(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|max:255|unique:users,email',
        'dob' => 'required|date|before:today',
        'sex' => 'required|in:Male,Female,Other',
        'mobile_no' => 'nullable|digits:10',
        'role' => 'required|in:admin,user,visitor,player',
        'password' => 'required|min:8|confirmed',
    ]);

    // ✅ Assign `created_by` to the logged-in user
    $createdBy = Auth::id() ?? 1; // Default to admin ID 1 if no user is logged in

    // ✅ Create the new user WITHOUT logging them in
    $user = User::create([
        'username' => $validated['username'],
        'email' => $validated['email'],
        'dob' => $validated['dob'],
        'sex' => $validated['sex'],
        'mobile_no' => $validated['mobile_no'],
        'role' => $validated['role'],
        'password' => Hash::make($validated['password']),
        'created_by' => $createdBy,
    ]);

    // ✅ Force re-authentication of the creator (to prevent session issues)
    Auth::loginUsingId($createdBy);

    // ✅ Send Email Notifications
    $this->sendUserNotification($user, 'created');

    // ✅ Redirect to `/btl/users` while keeping the creator logged in
    return redirect()->route('users.index')->with('success', 'User created successfully.');
}



    // ✅ Update User & Send Emails
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
        'password' => 'nullable|min:8|confirmed', // ✅ Password is optional
    ]);

    // ✅ Only update password if provided
    if (!empty($validated['password'])) {
        $validated['password'] = Hash::make($validated['password']);
    } else {
        unset($validated['password']); // ✅ Remove password key if not provided
    }

    $user->update($validated);

    // ✅ Send Update Notifications
    $this->sendUserNotification($user, 'updated');

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}


    // ✅ Send Email Notifications
    private function sendUserNotification(User $user, $action)
{
    $adminEmail = 'xpindia@gmail.com';
    $moderator = User::find($user->created_by);
    $moderatorEmail = $moderator ? $moderator->email : null;

    try {
        Mail::to($user->email)->send(new UserCreatedNotification($user, 'user', $action));
        Mail::to($adminEmail)->send(new UserCreatedNotification($user, 'admin', $action));

        if ($moderatorEmail && $moderatorEmail !== $adminEmail) {
            Mail::to($moderatorEmail)->send(new UserCreatedNotification($user, 'moderator', $action));
        }

        Log::info("✅ Email sent successfully to {$user->email}, Admin, and Moderator.");
    } catch (\Exception $e) {
        Log::error("❌ Email sending failed: " . $e->getMessage());
    }
}

    // ✅ Delete User
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (!Auth::user()->isAdmin()) {
            return redirect()->route('users.index')->with('error', 'Unauthorized action.');
        }

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
