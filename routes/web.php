<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController; // ✅ Added missing controller

// Redirect root to dashboard if authenticated
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// 🔹 Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// 🔹 Protected Routes (Only for Authenticated Users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔹 User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // 🔹 Register Player Route
    Route::get('/register_player', function () {
        return view('auth.register_player');
    })->name('register_player');

    // 🔹 Admin Routes (Restricted to Admins)
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/edit_users', [AdminController::class, 'editUsers'])->name('edit_users');
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('add_moderator');

        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 🔹 Match Routes (For Admin & Moderators)
    Route::middleware(['role:admin', 'role:moderator'])->group(function () {
        Route::get('/matches/create', [MatchController::class, 'create'])->name('matches.create');
        Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');

        Route::get('/matches/create_singles', [MatchController::class, 'createSingles'])->name('matches.create_singles');
        Route::get('/matches/edit_singles', [MatchController::class, 'editSingles'])->name('matches.edit_singles');

        Route::get('/matches/create_boys_doubles', [MatchController::class, 'createBoysDoubles'])->name('matches.create_boys_doubles');
        Route::get('/matches/edit_boys_doubles', [MatchController::class, 'editBoysDoubles'])->name('matches.edit_boys_doubles');

        Route::get('/matches/edit_all_doubles', [MatchController::class, 'editAllDoubles'])->name('matches.edit_all_doubles');
    });

    // 🔹 Results Routes
    Route::get('/results/singles', [ResultsController::class, 'singles'])->name('results.singles');
    Route::get('/results/boys_doubles', [ResultsController::class, 'boysDoubles'])->name('results.boys_doubles');

    // 🔹 Category Routes (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    });

    // 🔹 Tournament Routes (Admin & Moderator)
    Route::middleware(['role:admin', 'role:moderator'])->group(function () {
        Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index'); // ✅ Fixed Missing Route
        Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
        Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    });

    // 🔹 Role-Based Access Routes
    Route::get('/admin', [AdminController::class, 'index'])->middleware('role:admin')->name('admin.dashboard');
    Route::get('/user-dashboard', [UserController::class, 'index'])->middleware('role:user')->name('user.dashboard');
    Route::get('/player-profile', [PlayerController::class, 'index'])->middleware('role:player')->name('player.profile');

// 🔹 Category Routes (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); // ✅ Added this
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
});

// 🔹 Match Routes (For Admin & Moderators)
Route::middleware(['role:admin', 'role:moderator'])->group(function () {
    Route::get('/matches', [MatchController::class, 'index'])->name('matches.index'); // ✅ Added this
    Route::get('/matches/create', [MatchController::class, 'create'])->name('matches.create');
    Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');

    Route::get('/matches/create_singles', [MatchController::class, 'createSingles'])->name('matches.create_singles');
    Route::get('/matches/edit_singles', [MatchController::class, 'editSingles'])->name('matches.edit_singles');

    Route::get('/matches/create_boys_doubles', [MatchController::class, 'createBoysDoubles'])->name('matches.create_boys_doubles');
    Route::get('/matches/edit_boys_doubles', [MatchController::class, 'editBoysDoubles'])->name('matches.edit_boys_doubles');

    Route::get('/matches/edit_all_doubles', [MatchController::class, 'editAllDoubles'])->name('matches.edit_all_doubles');
});


// 🔹 Player Routes (For Admin & Moderators)
Route::middleware(['role:admin', 'role:moderator'])->group(function () {
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index'); // ✅ Added this
    Route::get('/players/create', [PlayerController::class, 'create'])->name('players.create');
    Route::post('/players', [PlayerController::class, 'store'])->name('players.store');
});


// 🔹 Results Routes (Accessible to All Users)
Route::get('/results', [ResultsController::class, 'index'])->name('results.index'); // ✅ Added this

Route::get('/results/singles', [ResultsController::class, 'singles'])->name('results.singles');
Route::get('/results/boys_doubles', [ResultsController::class, 'boysDoubles'])->name('results.boys_doubles');


});
