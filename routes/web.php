<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SharedController;

// Home Route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Results & Rankings
Route::prefix('results')->group(function () {
    Route::get('/', [ResultController::class, 'index'])->name('results.index');
    Route::get('/bd', [ResultController::class, 'bd'])->name('results.bd');
    Route::get('/xd', [ResultController::class, 'xd'])->name('results.xd');
    Route::get('/singles', [ResultController::class, 'singles'])->name('results.singles');
});

Route::prefix('rankings')->group(function () {
    Route::get('/singles', [RankingController::class, 'singles'])->name('rankings.singles');
    Route::get('/doubles', [RankingController::class, 'doubles'])->name('rankings.doubles');
});

// Player Profile
Route::get('/player/profile', [ProfileController::class, 'index'])->name('player.profile');

// Role-Based Routes
Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin')->get('/admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::middleware('role:user')->get('/user-dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::middleware('role:player')->get('/player-dashboard', [PlayerController::class, 'index'])->name('player.dashboard');
    Route::middleware('role:admin,user')->get('/shared-access', [SharedController::class, 'index'])->name('shared.access');
});

// Tournament, Matches, and Players
Route::resources([
    'tournaments' => TournamentController::class,
    'matches' => MatchController::class,
    'players' => PlayerController::class,
]);

// Additional User Routes
Route::get('/user/data', [UserController::class, 'data'])->name('user.data');

// Include Laravel Breeze Authentication Routes
require __DIR__.'/auth.php';
