<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function editUsers()
    {
        return view('admin.edit_users');
    }

    public function editPlayers()
    {
        return view('admin.edit_players');
    }

    public function addModerator()
    {
        return view('admin.add_moderator');
    }

    public function index()
    {
        return view('admin.dashboard', [
            'username' => Auth::user()->username,
        ]);
    }

}
