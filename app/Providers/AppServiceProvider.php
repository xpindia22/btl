<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Player;
use App\Observers\PlayerObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix migration issue with MariaDB & MySQL
        Schema::defaultStringLength(191);

        // Ensure the Player model exists before observing
        if (class_exists(Player::class)) {
            Player::observe(PlayerObserver::class);
        } else {
            \Log::error("⚠️ Player model not found in AppServiceProvider! Check if 'app/Models/Player.php' exists.");
        }
    }
}
