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

    // ✅ Update Own Profile (Name, Age, Sex, Password)
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'sex' => 'required|string|in:Male,Female,Other',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->age = $request->age;
        $user->sex = $request->sex;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
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
    if (!auth()->user()->isAdmin()) {
        return redirect()->route('dashboard')->with('error', 'Unauthorized access!');
    }

    $users = User::with(['moderatedTournaments', 'createdTournaments'])->orderBy('id', 'asc')->paginate(10);
    $tournaments = Tournament::orderBy('year', 'desc')->get(); // ✅ Get all tournaments for dropdown

    return view('users.edit', compact('users', 'tournaments'));
}
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // ✅ Ensure only admins or the user themselves can edit
    if (Auth::id() !== $user->id && !Auth::user()->isAdmin()) {
        return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
    }

    // ✅ Validate input
    $request->validate([
        'username' => 'required|string|max:255|unique:users,username,' . $id,
        'email' => 'required|email|unique:users,email,' . $id,
        'mobile_no' => 'nullable|digits:10',
        'role' => 'required|in:admin,user,visitor,player',
    ]);

    // ✅ Update user details
    $user->username = $request->username;
    $user->email = $request->email;
    $user->mobile_no = $request->mobile_no;
    $user->role = $request->role;
    $user->save();

    // ✅ Sync Moderated Tournaments (BelongsToMany)
    if ($request->has('moderated_tournaments')) {
        $user->moderatedTournaments()->sync($request->moderated_tournaments);
    } else {
        $user->moderatedTournaments()->detach();
    }

    // ✅ Get Default Admin ID
    $defaultAdminId = User::where('username', 'xxx')->where('role', 'admin')->value('id');

    // ✅ Update Creator Field using the checkboxes submitted from the form
    $selectedTournaments = $request->input('created_tournaments', []);

    // For tournaments that previously had this user as creator but are now unchecked,
    // reassign them to the default admin.
    Tournament::where('created_by', $user->id)
        ->whereNotIn('id', $selectedTournaments)
        ->update(['created_by' => $defaultAdminId]);

    // For tournaments that are checked, assign this user as the creator.
    if (!empty($selectedTournaments)) {
        Tournament::whereIn('id', $selectedTournaments)
            ->update(['created_by' => $user->id]);
    }

    // ✅ Refresh relationships so the UI updates correctly
    $user->load('moderatedTournaments', 'createdTournaments');

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}


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
