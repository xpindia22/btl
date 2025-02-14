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
use App\Http\Controllers\DoublesBoysMatchController;
use App\Http\Controllers\DoublesGirlsMatchController;
use App\Http\Controllers\DoublesMixedMatchController;

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

// 🔹 Fix for `player.register` Route
Route::get('/players/register', [PlayerController::class, 'showRegistrationForm'])->name('player.register');
Route::post('/players/register', [PlayerController::class, 'register']);

// 🔹 Protected Routes (Only for Authenticated Users)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🔹 Matches Routes (Including FIX for missing `matches.singles.create`)
    Route::prefix('matches')->group(function () {
        Route::get('/', [MatchController::class, 'index'])->name('matches.index');
        Route::post('/', [MatchController::class, 'store'])->name('matches.store');
        Route::get('/create', [MatchController::class, 'create'])->name('matches.create');
    });

    // 🔹 Singles Matches
    Route::prefix('matches/singles')->group(function () {
        Route::get('/', [MatchController::class, 'indexSingles'])->name('matches.singles.index');
        Route::get('/create', [MatchController::class, 'createSingles'])->name('matches.singles.create');
        Route::post('/', [MatchController::class, 'storeSingles'])->name('matches.singles.store');
        Route::post('/lockTournament', [MatchController::class, 'lockTournament'])->name('matches.singles.lockTournament');
        Route::post('/unlockTournament', [MatchController::class, 'unlockTournament'])->name('matches.singles.unlockTournament');
        Route::get('/edit/{id}', [MatchController::class, 'editSingles'])->name('matches.singles.edit');
        Route::put('/{id}', [MatchController::class, 'updateSingles'])->name('matches.singles.update');
        Route::delete('/{id}', [MatchController::class, 'destroySingles'])->name('matches.singles.destroy');
    });

    // 🔹 Doubles Boys Matches
    Route::prefix('matches/doubles_boys')->group(function () {
        Route::get('/', [DoublesBoysMatchController::class, 'index'])->name('matches.doubles_boys.index');
        Route::get('/create', [DoublesBoysMatchController::class, 'create'])->name('matches.doubles_boys.create');
        Route::post('/', [DoublesBoysMatchController::class, 'store'])->name('matches.doubles_boys.store');
        Route::get('/edit', [DoublesBoysMatchController::class, 'edit'])->name('matches.doubles_boys.edit');
        Route::put('/{id}', [DoublesBoysMatchController::class, 'update'])->name('matches.doubles_boys.update');
        Route::delete('/{id}', [DoublesBoysMatchController::class, 'destroy'])->name('matches.doubles_boys.destroy');

        // 🔹 Lock & Unlock Tournament
        Route::post('/lockTournament', [DoublesBoysMatchController::class, 'lockTournament'])->name('matches.doubles_boys.lockTournament');
        Route::post('/unlockTournament', [DoublesBoysMatchController::class, 'unlockTournament'])->name('matches.doubles_boys.unlockTournament');
    });

    // 🔹 Doubles Girls Matches
    Route::prefix('matches/doubles_girls')->group(function () {
        Route::get('/', [DoublesGirlsMatchController::class, 'index'])->name('matches.doubles_girls.index');
        Route::get('/create', [DoublesGirlsMatchController::class, 'create'])->name('matches.doubles_girls.create');
        Route::post('/', [DoublesGirlsMatchController::class, 'store'])->name('matches.doubles_girls.store');
        Route::post('/lockTournament', [DoublesGirlsMatchController::class, 'lockTournament'])->name('matches.doubles_girls.lockTournament');
        Route::post('/unlockTournament', [DoublesGirlsMatchController::class, 'unlockTournament'])->name('matches.doubles_girls.unlockTournament');
        Route::put('/{id}', [DoublesGirlsMatchController::class, 'update'])->name('matches.doubles_girls.update');
        Route::delete('/{id}', [DoublesGirlsMatchController::class, 'destroy'])->name('matches.doubles_girls.destroy');
    });
    
    // 🔹 Doubles Mixed Matches
    Route::prefix('matches/doubles_mixed')->group(function () {
        Route::get('/', [DoublesMixedMatchController::class, 'index'])->name('matches.doubles_mixed.index');
        Route::get('/create', [DoublesMixedMatchController::class, 'create'])->name('matches.doubles_mixed.create');
        Route::post('/', [DoublesMixedMatchController::class, 'store'])->name('matches.doubles_mixed.store');
        Route::put('/{id}', [DoublesMixedMatchController::class, 'update'])->name('matches.doubles_mixed.update');
        Route::delete('/{id}', [DoublesMixedMatchController::class, 'destroy'])->name('matches.doubles_mixed.destroy');
    });

    // 🔹 Tournament Management
    Route::prefix('tournaments')->group(function () {
        Route::get('/create', [TournamentController::class, 'create'])->name('tournaments.create');
        Route::get('/manage', [TournamentController::class, 'index'])->name('tournaments.manage');
        Route::get('/{id}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
        Route::put('/{id}', [TournamentController::class, 'update'])->name('tournaments.update');
        Route::delete('/{id}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    });

    // 🔹 User Management (For Admin)
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // 🔹 Get Players Route
    Route::get('/get_players', [PlayerController::class, 'getPlayers']);

    // 🔹 Admin Routes (Only Admins Can Access)
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/edit_users', [AdminController::class, 'editUsers'])->name('admin.edit_users');
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');
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

    // 🔹 Results Routes
    Route::prefix('results')->group(function () {
        Route::get('/', [ResultsController::class, 'index'])->name('results.index');
        Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
        Route::get('/doubles', [ResultsController::class, 'doubles'])->name('results.doubles');
        Route::get('/mixed-doubles', [DoublesMixedMatchController::class, 'index'])->name('results.mixed_doubles');
        Route::get('/boys-doubles', [ResultsController::class, 'boysDoubles'])->name('results.boys_doubles');
    });

});
