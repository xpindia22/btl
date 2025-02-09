<?php 

protected $routeMiddleware = [
    // ...
    'secondary.admin' => \App\Http\Middleware\AdminSecondaryAuth::class,
    
];

protected $middleware = [
    \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    
];



protected $routeMiddleware = [
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
