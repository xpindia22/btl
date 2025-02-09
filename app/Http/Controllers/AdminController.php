<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.edit_users');
    }

    /**
     * Manage Players - Admin Only
     */
    public function editPlayers()
    {
        return view('admin.edit_players');
    }

    /**
     * Add Moderator - Admin Only
     */
    public function addModerator()
    {
        return view('admin.add_moderator');
    }
}
