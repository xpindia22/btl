<?php 

protected $routeMiddleware = [
    // ...
    'secondary.admin' => \App\Http\Middleware\AdminSecondaryAuth::class,
    
];

protected $middleware = [
    \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    // \App\Http\Middleware\EncryptCookies::class,  // ❌ Comment out for debugging
    // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // ❌
];
