<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\MatchmakingController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TeamScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MatchCostController;
use App\Http\Controllers\VenueRecommendationController;
use App\Http\Controllers\RefereeController;
use App\Http\Controllers\PaymentController;

// Landing
Route::get('/', fn() => view('landingPage.index'))->name('home');

Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
Route::redirect('/admin/filament-login', '/admin/login')->name('filament.admin.auth.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');
Route::post('/midtrans/callback', [PaymentController::class, 'callback'])->name('midtrans.callback');

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

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/team', [ProfileController::class, 'updateTeam'])->name('updateTeam');
        Route::put('/photo', [ProfileController::class, 'updatePhoto'])->name('photo');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    Route::prefix('matchmaking')->name('matchmaking.')->group(function () {
        Route::match(['get', 'post'], '/', [MatchmakingController::class, 'index'])->name('index');
        Route::post('/search',             [MatchmakingController::class, 'search'])->name('search');
        Route::post('/challenge/{opponent}',[MatchmakingController::class, 'challenge'])->name('challenge');
        Route::get('/incoming',            [MatchmakingController::class, 'incomingChallenges'])->name('incoming');
        Route::get('/outgoing',            [MatchmakingController::class, 'outgoingChallenges'])->name('outgoing');
        Route::post('/accept/{matchRequest}', [MatchmakingController::class, 'acceptChallenge'])->name('accept');
        Route::post('/reject/{matchRequest}', [MatchmakingController::class, 'rejectChallenge'])->name('reject');
        Route::delete('/cancel/{matchRequest}',[MatchmakingController::class, 'cancelChallenge'])->name('cancel');
    });

    Route::prefix('matches')->name('matches.')->group(function () {
        Route::get('/', [MatchController::class, 'index'])->name('index');
        Route::get('/poll', [MatchController::class, 'poll'])->name('poll');
        Route::post('/challenge/{matchRequest}/accept', [MatchController::class, 'acceptChallenge'])->name('challenge.accept');
        Route::post('/challenge/{matchRequest}/reject', [MatchController::class, 'rejectChallenge'])->name('challenge.reject');
        Route::get('/{match}', [MatchController::class, 'show'])->name('show');
        Route::post('/{match}/cancel', [MatchController::class, 'cancel'])->name('cancel');

        // Fake payment (semua match)
        Route::post('/{match}/payments/create', [\App\Http\Controllers\FakePaymentController::class, 'createPayment'])->name('payments.create');
        Route::post('/{match}/payment/fake/paid', [\App\Http\Controllers\FakePaymentController::class, 'markPaid'])->name('payment.fake.paid');

        // (Gateway asli masih ada, tapi UI akan diarahkan ke fake)
        Route::get('/{match}/payment/success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/{match}/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

        Route::post('/{match}/score', [MatchController::class, 'inputScore'])->name('score');
    });

    Route::prefix('venues')->name('venues.')->group(function () {
        Route::match(['get', 'post'], '/', [VenueRecommendationController::class, 'index'])->name('index');
        Route::post('/search', [VenueRecommendationController::class, 'ajaxSearch'])->name('search');
        Route::get('/{venue}', [VenueRecommendationController::class, 'show'])->name('show');
    });

    Route::prefix('split-bill')->name('match-cost.')->middleware(['auth'])->group(function () {
 
        Route::get('/', [MatchCostController::class, 'index'])->name('index');
        Route::get('/create', [MatchCostController::class, 'create'])->name('create');
        Route::post('/', [MatchCostController::class, 'store'])->name('store');
        Route::get('/{matchCost}', [MatchCostController::class, 'show'])->name('show');
        Route::get('/{matchCost}/edit', [MatchCostController::class, 'edit'])->name('edit');
        Route::put('/{matchCost}', [MatchCostController::class, 'update'])->name('update');
        Route::patch('/{matchCost}/finalize',  [MatchCostController::class, 'finalize'])->name('finalize');
        Route::delete('/{matchCost}', [MatchCostController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/read-all', [NotificationController::class, 'readAll'])->name('readAll');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::get('/poll', [NotificationController::class, 'poll'])->name('poll');
    });

    Route::prefix('referees')->name('referees.')->group(function () {
        Route::get('/', [RefereeController::class, 'index'])->name('index');
        Route::get('/{referee}', [RefereeController::class, 'show'])->name('show');
        Route::post('/matches/{match}/available', [RefereeController::class, 'getAvailableReferees'])->name('available');
        Route::post('/matches/{match}/assign', [RefereeController::class, 'assignReferee'])->name('assign');
        Route::delete('/matches/{match}/remove', [RefereeController::class, 'removeReferee'])->name('remove');
    });
});
