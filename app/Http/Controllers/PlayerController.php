<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player; // Ensure this model exists

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::all(); // Fetch all players
        return view('players.index', compact('players')); // Pass data to Blade
    }
}
