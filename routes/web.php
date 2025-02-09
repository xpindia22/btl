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

    // 🔹 User Management Routes (For Admin Only)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');  // ✅ Ensured correct route
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 🔹 Register Player Route
    Route::get('/register_player', function () {
        return view('auth.register_player');
    })->name('register_player');

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
        Route::get('/create', [MatchController::class, 'create'])->name('matches.create');
        Route::post('/', [MatchController::class, 'store'])->name('matches.store');

        Route::get('/create_singles', [MatchController::class, 'createSingles'])->name('matches.create_singles');
        Route::get('/edit_singles', [MatchController::class, 'editSingles'])->name('matches.edit_singles');

        Route::get('/create_boys_doubles', [MatchController::class, 'createBoysDoubles'])->name('matches.create_boys_doubles');
        Route::get('/edit_boys_doubles', [MatchController::class, 'editBoysDoubles'])->name('matches.edit_boys_doubles');

        Route::get('/edit_all_doubles', [MatchController::class, 'editAllDoubles'])->name('matches.edit_all_doubles');
    });

    // 🔹 Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    });

    // 🔹 Tournament Routes
    Route::prefix('tournaments')->group(function () {
        Route::get('/', [TournamentController::class, 'index'])->name('tournaments.index');
        Route::get('/create', [TournamentController::class, 'create'])->name('tournaments.create');
        Route::post('/', [TournamentController::class, 'store'])->name('tournaments.store');
    });

    // 🔹 Player Routes
    Route::prefix('players')->group(function () {
        Route::get('/', [PlayerController::class, 'index'])->name('players.index');
        Route::get('/create', [PlayerController::class, 'create'])->name('players.create');
        Route::post('/', [PlayerController::class, 'store'])->name('players.store');
    });

    // 🔹 Results Routes
    Route::prefix('results')->group(function () {
        Route::get('/', [ResultsController::class, 'index'])->name('results.index');
        Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
        Route::get('/boys_doubles', [ResultsController::class, 'boysDoubles'])->name('results.boys_doubles');
    });
});
