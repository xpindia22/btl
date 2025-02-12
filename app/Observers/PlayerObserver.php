<?php

namespace App\Observers;

use App\Models\Player;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class PlayerObserver
{
    /**
     * Handle the Player "creating" event.
     * This event is fired before a new player is saved.
     */
    public function creating(Player $player): void
    {
        // Automatically set the ip_address if it's not already set.
        if (empty($player->ip_address)) {
            $player->ip_address = Request::ip();
        }
    }

    /**
     * Handle the Player "created" event.
     * This event is fired after a new player is saved.
     */
    public function created(Player $player): void
    {
        Log::info('Player created', [
            'player_id'  => $player->id,
            'ip_address' => Request::ip(),
        ]);
    }

    /**
     * Handle the Player "updated" event.
     */
    public function updated(Player $player): void
    {
        Log::info('Player updated', [
            'player_id'  => $player->id,
            'ip_address' => Request::ip(),
        ]);
    }

    /**
     * Handle the Player "deleted" event.
     */
    public function deleted(Player $player): void
    {
        Log::info('Player deleted', [
            'player_id'  => $player->id,
            'ip_address' => Request::ip(),
        ]);
    }

    /**
     * Handle the Player "restored" event.
     */
    public function restored(Player $player): void
    {
        Log::info('Player restored', [
            'player_id'  => $player->id,
            'ip_address' => Request::ip(),
        ]);
    }

    /**
     * Handle the Player "force deleted" event.
     */
    public function forceDeleted(Player $player): void
    {
        Log::info('Player force deleted', [
            'player_id'  => $player->id,
            'ip_address' => Request::ip(),
        ]);
    }
}
