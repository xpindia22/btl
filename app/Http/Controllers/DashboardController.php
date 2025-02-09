<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard', [
            'username' => $user->username ?? 'Guest',
            'is_admin' => $user->role === 'admin',
            'is_user' => $user->role === 'user',
            'is_player' => $user->role === 'player',
            'is_visitor' => $user->role === 'visitor',
        ]);
    }
}
