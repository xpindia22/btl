<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthHelper
{
    /**
     * Check if the user is logged in.
     *
     * @return bool
     */
    public static function isLoggedIn()
    {
        return Auth::check();
    }

    /**
     * Hash a password securely.
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword($password)
    {
        return Hash::make($password);
    }

    /**
     * Verify a password against a stored hash.
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verifyPassword($password, $hash)
    {
        return Hash::check($password, $hash);
    }

    /**
     * Check if the logged-in user has admin privileges.
     *
     * @return bool
     */
    public static function isAdmin()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Check if the logged-in user has regular user privileges.
     *
     * @return bool
     */
    public static function isUser()
    {
        return Auth::check() && in_array(Auth::user()->role, ['user', 'moderator']);
    }

    /**
     * Check if the logged-in user is a player.
     *
     * @return bool
     */
    public static function isPlayer()
    {
        return Auth::check() && Auth::user()->role === 'player';
    }

    /**
     * Check if the logged-in user is a visitor (neither admin nor user).
     *
     * @return bool
     */
    public static function isVisitor()
    {
        return !self::isAdmin() && !self::isUser() && !self::isPlayer();
    }
}
