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
        $users = User::all();
        return view('admin.edit_users', compact('users'));
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
