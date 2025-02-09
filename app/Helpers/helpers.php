<?php
// app/Helpers/helpers.php

if (! function_exists('redirect_if_not_logged_in')) {
    /**
     * Redirect the user to the login route if not authenticated.
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    function redirect_if_not_logged_in() {
        if (! auth()->check()) {  // Use Laravel's auth() helper
            // send() forces the redirect immediately
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
     * Determine if the current authenticated user has an admin role.
     *
     * @return bool
     */
    function is_admin() {
        return auth()->check() && auth()->user()->role === 'admin';
    }
}

// ... Add other helper functions as needed.
