<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global Middleware (applies to all requests)
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class, // ✅ Corrected
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // ✅ Validate request sizes
        \Illuminate\Session\Middleware\StartSession::class, // ✅ Ensures sessions persist globally
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // ✅ Prevents empty strings
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class, // ✅ Trims input fields
    ];

    /**
     * Route Middleware (applies to specific routes)
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class, // ✅ Ensures authentication
        'role' => \App\Http\Middleware\RoleMiddleware::class, // ✅ Role management middleware
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class, // ✅ Prevents session logouts
        'secondary.admin' => \App\Http\Middleware\AdminSecondaryAuth::class, // ✅ Ensure this exists
    ];

    /**
     * Middleware Groups (applies to specific route groups like `web` and `api`)
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class, // ✅ Ensures Laravel session persistence
            \Illuminate\Auth\Middleware\AuthenticateSession::class, // ✅ Prevents logging out
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class, // ✅ Prevents CSRF issues
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':60,1', // ✅ Throttle API requests
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];
}
