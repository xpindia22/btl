<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SinglesMatchController;
use App\Http\Controllers\DoublesMatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\DoublesMixedMatchController;

// ✅ Redirect root to dashboard if authenticated, else to login.
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// ✅ Authentication Routes
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// ✅ Public Players Listing
Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

// ✅ Player Registration
Route::prefix('players')->group(function () {
    Route::get('/register', [PlayerController::class, 'showRegistrationForm'])->name('player.register');
    Route::post('/register', [PlayerController::class, 'register']);
});

// ✅ Protected Routes (Only for Authenticated Users)
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
        Route::get('/', [UserController::class, 'index'])->name('users.index'); // Read-only
        Route::get('/edit', [UserController::class, 'editUsers'])->name('users.edit'); // Editable List
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit.user');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // ✅ **Admin Panel (Only for Admins)**
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/edit_users', [AdminController::class, 'editUsers'])->name('admin.edit_users');
        Route::get('/edit_users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.edit_users.edit');
        Route::put('/edit_users/{id}', [AdminController::class, 'updateUser'])->name('admin.edit_users.update');
        Route::delete('/edit_users/{id}', [AdminController::class, 'deleteUser'])->name('admin.edit_users.destroy');
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
        Route::post('/lock-tournament', [SinglesMatchController::class, 'lockTournament'])->name('matches.singles.lockTournament');
    });

    // ✅ **Doubles Matches**
    Route::prefix('matches/doubles')->group(function () {
        Route::get('/', [DoublesMatchController::class, 'index'])->name('matches.doubles.index');
        Route::get('/edit', [DoublesMatchController::class, 'indexWithEdit'])->name('matches.doubles.edit');
        Route::get('/create', [DoublesMatchController::class, 'createDoubles'])->name('matches.doubles.create');
        Route::post('/store', [DoublesMatchController::class, 'storeDoubles'])->name('matches.doubles.store');
        Route::put('/{id}', [DoublesMatchController::class, 'update'])->name('matches.doubles.update');
        Route::delete('/{id}', [DoublesMatchController::class, 'softDelete'])->name('matches.doubles.delete');
        Route::post('/{id}/restore', [DoublesMatchController::class, 'restore'])->name('matches.doubles.restore');
        Route::delete('/{id}/force-delete', [DoublesMatchController::class, 'forceDelete'])->name('matches.doubles.forceDelete');
        Route::post('/lock-tournament', [TournamentController::class, 'lock'])->name('matches.doubles.lockTournament');
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

    // ✅ **Filtered Players**
    Route::get('/matches/filtered-players', [SinglesMatchController::class, 'filteredPlayers'])->name('matches.filteredPlayers');
});
