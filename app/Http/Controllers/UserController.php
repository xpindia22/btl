<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // ✅ Show Users List Based on Role
    public function index()
    {
        $authUser = Auth::user();

        if ($authUser->isAdmin()) {
            $users = User::orderBy('created_at', 'desc')->get();
        } else {
            $users = User::where('created_by', $authUser->id)->orderBy('created_at', 'desc')->get();
        }

        return view('users.index', compact('users'));
    }

    // ✅ Show User Creation Form (Role Restricted)
    public function create()
    {
        $authUser = Auth::user();
        $roles = $authUser->isAdmin() 
            ? ['admin', 'user', 'player', 'visitor']
            : ['user', 'player', 'visitor'];

        return view('users.create', compact('roles'));
    }

    // ✅ Store New User in Database
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'mobile_no' => 'nullable|digits:10',
            'role' => 'required|in:admin,user,player,visitor',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a user.');
        }

        $loggedInUserId = Auth::id();
        
        Log::info('Creating User', [
            'creator_id' => $loggedInUserId,
            'new_user' => $request->username
        ]);

        Log::info('User Store Request:', $request->all());

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'role' => $request->role,
            'created_by' => $loggedInUserId, // ✅ Assign creator
        ]);

        Log::info('User Created Successfully:', $user->toArray());

        return redirect()->route('users.index')->with('success', 'User registered successfully!');
    }

    // ✅ Show Edit User Form (Restricted)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        // ✅ Only Admin or Creator Can Edit
        if (!$authUser->isAdmin() && $authUser->id !== $user->created_by) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $roles = $authUser->isAdmin() 
            ? ['admin', 'user', 'player', 'visitor']
            : ['user', 'player', 'visitor'];

        return view('users.edit', compact('user', 'roles'));
    }

    // ✅ Update User (Restricted)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        // ✅ Only Admin or Creator Can Update
        if (!$authUser->isAdmin() && $authUser->id !== $user->created_by) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile_no' => 'nullable|digits:10',
            'role' => 'required|in:admin,user,player,visitor',
        ]);

        // ✅ Prevent Non-Admins from Changing Roles
        if (!$authUser->isAdmin() && $request->role !== $user->role) {
            return redirect()->route('users.index')->with('error', 'You cannot change user roles.');
        }

        // ✅ Prevent Users from Changing Their Own Role
        if ($authUser->id === $user->id && $request->role !== $user->role) {
            return redirect()->route('users.index')->with('error', 'You cannot update your own role.');
        }

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // ✅ Delete User (Restricted)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $authUser = Auth::user();

        // ✅ Only Admin or Creator Can Delete
        if (!$authUser->isAdmin() && $authUser->id !== $user->created_by) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized');
        }

        // ✅ Prevent Self-Deletion
        if ($user->id === $authUser->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
