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

// Redirect root to dashboard if authenticated, else to login.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Public Players Listing
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

// Player Registration
Route::prefix('players')->group(function () {
    Route::get('/register', [PlayerController::class, 'showRegistrationForm'])->name('player.register');
    Route::post('/register', [PlayerController::class, 'register']);
});

// Protected Routes (Only for Authenticated Users)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tournaments Management
    Route::resource('tournaments', TournamentController::class)->except(['show']);

    // User Management (Fix: Removed 'show' method)
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/users/edit', [UserController::class, 'editUsers'])->name('users.edit');

    // Admin Panel (Only for Admins)
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::resource('admin/edit_users', AdminController::class)->except(['create', 'store', 'show']);
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');
        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');
    });



    // Lock & Restore Matches
    Route::post('/matches/lock-tournament', [MatchController::class, 'lockTournament'])->name('matches.lockTournament');
    Route::post('/matches/{id}/restore', [MatchController::class, 'restore'])->name('matches.restore');
    Route::delete('/matches/{id}/force-delete', [MatchController::class, 'forceDelete'])->name('matches.forceDelete');

    // Filtering Players for Matches
    Route::get('/matches/filtered-players', [MatchController::class, 'filteredPlayers'])->name('matches.filteredPlayers');

// Singles Matches Routes
Route::prefix('matches/singles')->group(function () {
    Route::get('/', [MatchController::class, 'indexSingles'])->name('matches.singles.index');
    Route::get('/create', [MatchController::class, 'createSingles'])->name('matches.singles.create');
    Route::post('/store', [MatchController::class, 'storeSingles'])->name('matches.singles.store');

    // Lock/Unlock Tournament
    Route::post('/lock-tournament', [MatchController::class, 'lockSinglesTournament'])->name('matches.singles.lockTournament');
    Route::post('/unlock-tournament', [MatchController::class, 'unlockSinglesTournament'])->name('matches.singles.unlockTournament');

    // Fetch Players for Singles
    Route::get('/filtered-players', [MatchController::class, 'filteredPlayersSingles'])->name('matches.singles.filteredPlayers');

    // ✅ Correct Edit & Update Routes
    Route::get('/edit', [MatchController::class, 'editSingles'])->name('matches.singles.edit');
    Route::put('/{match}/update', [MatchController::class, 'updateSingles'])->name('matches.singles.update');

    // ✅ Delete Singles Match
    Route::delete('/{match}/delete', [MatchController::class, 'deleteSingles'])->name('matches.singles.delete');
});

 // Doubles Matches Routes (BD, GD, XD)
 Route::prefix('matches/doubles')->group(function () {
    Route::get('/', [MatchController::class, 'indexDoubles'])->name('matches.doubles.index');
    Route::get('/create', [MatchController::class, 'createDoubles'])->name('matches.doubles.create');
    Route::post('/store', [MatchController::class, 'storeDoubles'])->name('matches.doubles.store');
    Route::post('/lock-tournament', [MatchController::class, 'lockDoublesTournament'])->name('matches.doubles.lockTournament');
    Route::post('/unlock-tournament', [MatchController::class, 'unlockDoublesTournament'])->name('matches.doubles.unlockTournament');
    Route::get('/filtered-players', [MatchController::class, 'filteredPlayersDoubles'])->name('matches.doubles.filteredPlayers');
    Route::get('matches/doubles/edit/{id}', [MatchController::class, 'editDoubles'])->name('matches.doubles.edit');
Route::post('matches/doubles/update/{id}', [MatchController::class, 'updateDoubles'])->name('matches.doubles.update');
Route::prefix('matches/doubles')->group(function () {
    
    // ...other routes
});

});

// Results Management
Route::prefix('results')->group(function () {
    Route::get('/', [ResultsController::class, 'index'])->name('results.index');
    Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
    Route::get('/doubles', [ResultsController::class, 'doubles'])->name('results.doubles');
    Route::get('/mixed-doubles', [ResultsController::class, 'mixedDoubles'])->name('results.mixed_doubles');
});

    // Categories Management
    Route::resource('categories', CategoryController::class)->except(['show']);});

