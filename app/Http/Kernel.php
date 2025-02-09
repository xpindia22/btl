<?php 

protected $middleware = [
    \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
];

protected $routeMiddleware = [
    'secondary.admin' => \App\Http\Middleware\AdminSecondaryAuth::class, 
    'role' => \App\Http\Middleware\RoleMiddleware::class, // Keep only this role middleware
];
