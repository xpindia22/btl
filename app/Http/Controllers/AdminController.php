<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Player;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensure only logged-in users can access
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Unauthorized Access - Admins Only');
            }
            return $next($request);
        });
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Manage Users - Admin Only
     */
    public function editUsers()
    {
        $users = User::all(); // Fetch all users from the database
        return view('admin.edit_users', compact('users')); // Pass users to the view
    }

    /**
     * Edit Single User Page (Admin Only)
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        // Use a separate view (admin.edit_user) so that the view expects a single user variable.
        return view('admin.edit_user', compact('user'));
    }

    /**
     * Update User via AJAX (Admin or User Creator Only)
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Check if the logged-in user is Admin or the user who created this user
        if (Auth::user()->role !== 'admin' && Auth::user()->id !== $user->created_by) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'username' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $id,
            'role' => 'sometimes|in:admin,user,visitor',
        ]);

        // Update the user
        $user->update($request->only(['username', 'email', 'role']));

        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
    }

    /**
     * Delete User (Admin Only)
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Allow deletion only if the logged-in user is an Admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    /**
     * Manage Players - Admin Only
     */
    public function editPlayers()
    {
        $players = Player::all();
        return view('admin.edit_players', compact('players'));
    }

    /**
     * Add Moderator - Admin Only
     */
    public function addModerator()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.add_moderator', compact('users'));
    }
}
