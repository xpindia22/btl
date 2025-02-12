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
use App\Http\Controllers\PlayerController;

// 🔹 Redirect root to dashboard if authenticated, else to login.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// 🔹 Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// 🔹 Public Players Listing
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

// 🔹 Protected Routes (Only for Authenticated Users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔹 User Management Routes (For Admin Only)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 🔹 Admin Routes (Only Admins Can Access)
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/edit_users', [AdminController::class, 'editUsers'])->name('admin.edit_users');
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');
    });

    // 🔹 Match Routes
    Route::prefix('matches')->group(function () {
        Route::get('/', [MatchController::class, 'index'])->name('matches.index');
        Route::post('/', [MatchController::class, 'store'])->name('matches.store');
        Route::get('/create', [MatchController::class, 'create'])->name('matches.create'); // ✅ Added this route
    });

    // 🔹 Singles Match Routes
    Route::prefix('singles')->group(function () {
        Route::get('/create', [MatchController::class, 'createSingles'])->name('singles.create');
        Route::get('/edit', [MatchController::class, 'editSingles'])->name('singles.edit');
    });

    // 🔹 Doubles Match Routes
    Route::prefix('doubles')->group(function () {
        Route::get('/create', [MatchController::class, 'createDoubles'])->name('doubles.create');
        Route::get('/edit', [MatchController::class, 'editDoubles'])->name('doubles.edit');
    });

    // 🔹 Mixed Doubles Match Routes
    Route::prefix('mixed_doubles')->group(function () {
        Route::get('/create', [MatchController::class, 'createMixedDoubles'])->name('mixed_doubles.create');
        Route::get('/edit', [MatchController::class, 'editMixedDoubles'])->name('mixed_doubles.edit');
    });

    // 🔹 Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // 🔹 Tournament Routes (Tournament Management)
    Route::prefix('tournaments')->group(function () {
        Route::get('/create', [TournamentController::class, 'create'])->name('tournaments.create');
        Route::get('/manage', [TournamentController::class, 'index'])->name('tournaments.manage');
        Route::get('/{id}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
        Route::put('/{id}', [TournamentController::class, 'update'])->name('tournaments.update');
        Route::delete('/{id}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    });

    // 🔹 Results Routes (✅ Fixed Missing `results.boys_doubles`)
    Route::prefix('results')->group(function () {
        Route::get('/', [ResultsController::class, 'index'])->name('results.index');
        Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
        Route::get('/doubles', [ResultsController::class, 'doubles'])->name('results.doubles');
        Route::get('/mixed-doubles', [ResultsController::class, 'mixedDoubles'])->name('results.mixed_doubles');
        Route::get('/boys-doubles', [ResultsController::class, 'boysDoubles'])->name('results.boys_doubles'); // ✅ Added missing route
    });

    // 🔹 Player Routes (✅ Fixed Missing `player.register`)
    Route::prefix('players')->group(function () {
        Route::get('/create', [PlayerController::class, 'create'])->name('players.create');
        Route::post('/', [PlayerController::class, 'store'])->name('players.store');

        Route::get('/register', [PlayerController::class, 'showRegistrationForm'])->name('player.register');
        Route::post('/register', [PlayerController::class, 'register']);

        Route::get('/{id}/edit', [PlayerController::class, 'edit'])->name('players.edit');
        Route::put('/{id}', [PlayerController::class, 'update'])->name('players.update');
        Route::delete('/{id}', [PlayerController::class, 'destroy'])->name('players.destroy');
    });
});
