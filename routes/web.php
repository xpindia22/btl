<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SinglesMatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\DoublesMatchController;

// Redirect root to dashboard if authenticated, else to login.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Public Players Listing
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

// Player Registration
Route::get('/players/register', [PlayerController::class, 'showRegistrationForm'])->name('player.register');
Route::post('/players/register', [PlayerController::class, 'register']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ✅ **Tournaments Management**
    Route::prefix('tournaments')->group(function () {
        Route::get('/create', [TournamentController::class, 'create'])->name('tournaments.create');
        Route::get('/manage', [TournamentController::class, 'index'])->name('tournaments.manage');
        Route::get('/{id}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
        Route::put('/{id}', [TournamentController::class, 'update'])->name('tournaments.update');
        Route::post('/add', [TournamentController::class, 'storeTournament'])->name('tournaments.add');
        Route::delete('/{id}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    });

    // ✅ **User Management**
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // ✅ **Admin Panel**
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/edit_users', [AdminController::class, 'editUsers'])->name('admin.edit_users');
        Route::get('/edit_users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.edit_users.edit');
        Route::put('/edit_users/{id}', [AdminController::class, 'updateUser'])->name('admin.edit_users.update');
        Route::delete('/edit_users/{id}', [AdminController::class, 'deleteUser'])->name('admin.edit_users.destroy');
        Route::get('/admin/edit_users', [AdminController::class, 'editUsers'])->name('admin.edit_users');

        // ✅ FIXED: Ensure players edit route exists
        Route::get('/edit_players', [AdminController::class, 'editPlayers'])->name('admin.edit_players');

        Route::get('/add_moderator', [AdminController::class, 'addModerator'])->name('admin.add_moderator');
    });

    // ✅ **Singles Matches**
    Route::prefix('matches/singles')->group(function () {
        Route::get('/', [SinglesMatchController::class, 'indexSingles'])->name('matches.singles.index');
        Route::get('/edit', [SinglesMatchController::class, 'indexSinglesWithEdit'])->name('matches.singles.edit');
        Route::get('/create', [SinglesMatchController::class, 'createSingles'])->name('matches.singles.create');
        Route::post('/store', [SinglesMatchController::class, 'storeSingles'])->name('matches.singles.store');
        Route::put('/update/{id}', [SinglesMatchController::class, 'updateSingle'])->name('matches.singles.update');
        Route::delete('/delete/{id}', [SinglesMatchController::class, 'deleteSingleMatch'])->name('matches.singles.delete');
    });

    // ✅ **Doubles Matches**
    Route::prefix('matches/doubles')->group(function () {
        Route::get('/', [DoublesMatchController::class, 'index'])->name('matches.doubles.index');
        Route::get('/edit', [DoublesMatchController::class, 'indexWithEdit'])->name('matches.doubles.edit');
        Route::get('/create', [DoublesMatchController::class, 'createDoubles'])->name('matches.doubles.create');
        Route::post('/store', [DoublesMatchController::class, 'storeDoubles'])->name('matches.doubles.store');
        Route::get('/filtered-players', [DoublesMatchController::class, 'getFilteredPlayers'])->name('matches.doubles.filteredPlayers');
        Route::post('/lock', [DoublesMatchController::class, 'lockTournament'])->name('matches.doubles.lockTournament');
        Route::post('/unlock', [DoublesMatchController::class, 'unlockTournament'])->name('matches.doubles.unlockTournament');
        Route::put('/{id}/update', [DoublesMatchController::class, 'update'])->name('matches.doubles.update');
        Route::delete('/{id}/delete', [DoublesMatchController::class, 'softDelete'])->name('matches.doubles.delete');
        Route::post('/{id}/restore', [DoublesMatchController::class, 'restore'])->name('matches.doubles.restore');
        Route::delete('/{id}/force-delete', [DoublesMatchController::class, 'forceDelete'])->name('matches.doubles.forceDelete');

        Route::put('/matches/doubles/update/{id}', [DoublesMatchController::class, 'updateMatch'])->name('matches.doubles.update');
        Route::delete('/matches/doubles/delete/{id}', [DoublesMatchController::class, 'softDelete'])->name('matches.doubles.delete');
        




    });

    // ✅ **Results**
    Route::prefix('results')->group(function () {
        Route::get('/', [ResultsController::class, 'index'])->name('results.index');
        Route::get('/singles', [ResultsController::class, 'singles'])->name('results.singles');
        Route::get('/doubles', [ResultsController::class, 'doubles'])->name('results.doubles');
        Route::get('/mixed-doubles', [DoublesMixedMatchController::class, 'index'])->name('results.mixed_doubles');
    });

    // ✅ **Categories**
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    

    Route::post('/matches/singles/lock-tournament', [SinglesMatchController::class, 'lockTournament'])
    ->name('matches.singles.lockTournament');
    Route::get('/matches/filtered-players', [MatchController::class, 'filteredPlayers'])
    ->name('matches.filteredPlayers');


});
