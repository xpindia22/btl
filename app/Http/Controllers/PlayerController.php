<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;

class PlayerController extends Controller
{
    /**
     * Show the Player Dashboard.
     */
    public function dashboard()
    {
        return view('player.dashboard'); // Ensure this view exists
    }

    public function index()
    {
        $this->authorize('viewAnyPlayers', Player::class);
        return view('players.index');
    }

    public function edit(Player $player)
    {
        $this->authorize('managePlayer', $player);
        return view('players.edit', compact('player'));
    }

    public function update(Request $request, Player $player)
    {
        $this->authorize('managePlayer', $player);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:players,email,' . $player->id,
        ]);

        $player->update($request->all());

        return redirect()->route('players.index')->with('success', 'Player updated successfully.');
    }
}
