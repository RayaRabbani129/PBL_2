<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TeamScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Landing
Route::get('/', fn() => view('landingPage.index'))->name('home');

// ── Player Auth ──────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth:web')
    ->name('logout');

// ── Player Dashboard ─────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tim
    Route::prefix('team')->name('team.')->group(function () {

        // Halaman utama "Tim Saya"
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::get('/create', [TeamController::class, 'create'])->name('create');
        Route::post('/', [TeamController::class, 'store'])->name('store');
        Route::get('/{team}/edit', [TeamController::class, 'edit'])->name('edit');
        Route::put('/{team}', [TeamController::class, 'update'])->name('update');
        Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');

        // Anggota Tim
        Route::prefix('members')->name('members.')->group(function () {
            Route::get('/create', [TeamMemberController::class, 'create'])->name('create');
            Route::post('/', [TeamMemberController::class, 'store'])->name('store');
            Route::get('/{member}/edit', [TeamMemberController::class, 'edit'])->name('edit');
            Route::put('/{member}', [TeamMemberController::class, 'update'])->name('update');
            Route::delete('/{member}', [TeamMemberController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', [TeamScheduleController::class, 'index'])->name('index');
        Route::get('/create', [TeamScheduleController::class, 'create'])->name('create');
        Route::post('/', [TeamScheduleController::class, 'store'])->name('store');
        Route::get('/{schedule}/edit', [TeamScheduleController::class, 'edit'])->name('edit');
        Route::put('/{schedule}', [TeamScheduleController::class, 'update'])->name('update');
        Route::delete('/{schedule}', [TeamScheduleController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::put('/profile/team', [ProfileController::class, 'updateTeam'])
    ->name('profile.updateTeam');

    Route::put('/profile/update', [ProfileController::class, 'update'])
    ->name('profile.update');
});