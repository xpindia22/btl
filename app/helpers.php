<?php
// app/Helpers/helpers.php

if (! function_exists('redirect_if_not_logged_in')) {
    /**
     * Redirect the user to the login route if not authenticated.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    function redirect_if_not_logged_in() {
        if (! auth()->check()) {  
            return redirect()->route('login')->send();
        }
    }
}

if (! function_exists('verify_password')) {
    /**
     * Verify a plain text password against a hashed value.
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }
}

if (! function_exists('is_admin')) {
    /**
     * Determine if the current authenticated user has an admin Role.
     *
     * @return bool
     */
    function is_admin() {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}

if (! function_exists('favorite_route_name')) {
    /**
     * Get the correct route name based on the favorited model type.
     *
     * @param string $modelType
     * @return string|null
     */
    function favorite_route_name($modelType)
    {
        return match (class_basename($modelType)) { 
            'Matches' => 'matches.singles.index', // ✅ Use correct route
            'Tournament' => 'tournaments.show',
            'Category' => 'categories.show',
            'Player' => 'players.show',
            default => null
        };
    }
}

