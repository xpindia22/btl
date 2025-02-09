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
            'username' => $user->username,
            'is_admin' => $user->isAdmin(), // ✅ Use helper functions
            'is_user' => $user->isUser(),
            'is_player' => $user->isPlayer(),
        ]);
    }
}
 