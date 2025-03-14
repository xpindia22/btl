<?php 

namespace App\Http\Controllers;
use App\Mail\UserCreatedMail;
use App\Mail\UserEditedMail; // ✅ Ensure this is imported correctly
use App\Mail\UserDeletedMail; // ✅ Ensure this is imported

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
    $sortDirection = $request->get('direction', 'desc'); // Default sorting direction

    // Validate sort column to prevent SQL injection
    $allowedSortColumns = ['id', 'username', 'email', 'role', 'created_tournaments_count', 'moderated_tournaments_count'];
    if (!in_array($sortColumn, $allowedSortColumns)) {
        $sortColumn = 'id';
    }

    // Query users based on role
    $query = User::with(['moderatedTournaments', 'createdTournaments'])
                 ->withCount(['moderatedTournaments', 'createdTournaments']); // ✅ Get tournament counts

    if (!$authUser->isAdmin()) {
        // ✅ Regular users see only the users they created (or those created by admin)
        $query->where(function ($q) use ($authUser) {
            $q->where('created_by', $authUser->id)
              ->orWhere('created_by', 1); // ✅ Allow viewing users created by admin
        });
    }

    // Apply sorting
    $users = $query->orderBy($sortColumn, $sortDirection)->paginate(10);

    // Load paginated matches
    $matches = Matches::paginate(10);

    return view('users.index', compact('users', 'matches', 'sortColumn', 'sortDirection'));
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

    // ✅ Create the new user
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

    // ✅ Send Email Notifications (To User & Admin)
    try {
        Mail::to($user->email)
            ->cc('xpindia@gmail.com') // ✅ Admin always gets a copy
            ->send(new UserCreatedMail($user, Auth::user()->username ?? 'Admin'));

        \Log::info("User Created Email sent to: " . $user->email . " & Admin");
    } catch (\Exception $e) {
        \Log::error("Failed to send User Created Email: " . $e->getMessage());
    }

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
        'dob' => 'nullable|date|before:today',
        'sex' => 'required|in:Male,Female,Other',
        'mobile_no' => 'nullable|digits:10',
        'role' => 'required|in:admin,user,visitor,player',
        'moderated_tournaments' => 'array', // ✅ Ensure array input
        'created_tournaments' => 'array',   // ✅ Ensure array input
    ]);

    DB::beginTransaction();

    try {
        // Store Original Data Before Update
        $originalData = $user->getOriginal();

        // ✅ Update User Basic Information
        $user->update($validated);

        // ✅ Update Moderated Tournaments
        DB::table('tournament_moderators')->where('user_id', $user->id)->delete();
        if (!empty($request->input('moderated_tournaments', []))) {
            foreach ($request->input('moderated_tournaments') as $tournamentId) {
                DB::table('tournament_moderators')->insert([
                    'tournament_id' => $tournamentId,
                    'user_id'       => $user->id,
                ]);
            }
        }

        // ✅ Update Created Tournaments
        DB::table('tournaments')->where('created_by', $user->id)->update(['created_by' => null]); // Remove old assignments
        if (!empty($request->input('created_tournaments', []))) {
            DB::table('tournaments')->whereIn('id', $request->input('created_tournaments'))->update([
                'created_by' => $user->id,
            ]);
        }

        DB::commit();

        // ✅ Track Changes
        $updatedFields = [];
        foreach ($validated as $key => $value) {
            if (isset($originalData[$key]) && $originalData[$key] != $user->$key) {
                $updatedFields[$key] = ['old' => $originalData[$key], 'new' => $user->$key];
            }
        }

        // ✅ Send Email Notification on Changes
        if (!empty($updatedFields)) {
            try {
                Mail::to($user->email)
                    ->cc('xpindia@gmail.com') // ✅ Notify Admin
                    ->send(new UserEditedMail($user, Auth::user()->username, $updatedFields));

                \Log::info("User Edited Email sent to: " . $user->email . " & Admin");
            } catch (\Exception $e) {
                \Log::error("Failed to send User Edited Email: " . $e->getMessage());
            }
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("User update failed: " . $e->getMessage());
        return redirect()->route('users.index')->with('error', 'User update failed.');
    }
}

    // ✅ Delete User.
 

public function destroy($id)
{
    $user = User::findOrFail($id);

    // ✅ Track deleted user details before deleting
    $deletedUserDetails = [
        'ID' => $user->id,
        'Username' => $user->username,
        'Email' => $user->email,
        'Mobile No' => $user->mobile_no ?? 'N/A',
        'Role' => ucfirst($user->role),
        'Deleted By' => Auth::user()->username ?? 'Admin'
    ];

    $user->delete();

    // ✅ Send Email Notification to Admin for Deletion
    try {
        Mail::to('xpindia@gmail.com') // ✅ Only admin gets notified for deletion
            ->send(new UserDeletedMail($deletedUserDetails));

        \Log::info("User Deleted Email sent to Admin for User: " . $deletedUserDetails['Username']);
    } catch (\Exception $e) {
        \Log::error("Failed to send User Deleted Email: " . $e->getMessage());
    }

    return redirect()->route('users.index')->with('success', 'User deleted successfully.');
}
}
