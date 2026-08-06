<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SystemStatusController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosticController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Desmos AI v2.1
|--------------------------------------------------------------------------
*/

// Home — landing page for guests, dashboard for authenticated users
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard.index')
        : app(MarketingController::class)->landing();
})->name('home');

// Guest-only routes with rate limiting
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->middleware('throttle:5,1')->name('password.update');
});

// Authenticated routes with general rate limiting
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // AI Tutor & Solver
    Route::get('/tutor', [TutorController::class, 'index'])->name('tutor.index');
    Route::post('/tutor/solve', [TutorController::class, 'solve'])->middleware('throttle:15,1')->name('tutor.solve');
    Route::get('/tutor/{session}', [TutorController::class, 'show'])->name('tutor.show');

    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::post('/history/save-graph', [HistoryController::class, 'saveGraph'])->name('history.save-graph');

    // Diagnostic Test
    Route::get('/diagnostic', [DiagnosticController::class, 'start'])->name('diagnostic.start');
    Route::get('/diagnostic/show', [DiagnosticController::class, 'show'])->name('diagnostic.show');
    Route::post('/diagnostic/submit', [DiagnosticController::class, 'submit'])->middleware('throttle:10,1')->name('diagnostic.submit');
    Route::get('/diagnostic/results/{result}', [DiagnosticController::class, 'results'])->name('diagnostic.results');

    // SAT Roadmap
    Route::get('/roadmap', [RoadmapController::class, 'show'])->name('roadmap.show');
    Route::post('/roadmap/generate', [RoadmapController::class, 'generate'])->name('roadmap.generate');
    Route::post('/roadmap/{roadmap}/toggle', [RoadmapController::class, 'toggleTask'])->name('roadmap.toggle');

    // Practice Bank
    Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
    Route::get('/practice/{topic:slug}', [PracticeController::class, 'topic'])->name('practice.topic');
    Route::get('/practice/{topic:slug}/quiz', [PracticeController::class, 'quiz'])->name('practice.quiz');
    Route::post('/practice/{question}/submit', [PracticeController::class, 'submit'])->middleware('throttle:30,1')->name('practice.submit');

    // AI Chat Tutor
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{thread}/send', [ChatController::class, 'send'])->middleware('throttle:20,1')->name('chat.send');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    // Platform: Roles & Users
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);

    // Admin Extensions
    Route::prefix('admin')->name('admin.')->middleware(['can:roles.view'])->group(function () {
        Route::get('/questions', [AdminQuestionController::class, 'index'])->name('questions.index');
        Route::get('/questions/create', [AdminQuestionController::class, 'create'])->name('questions.create');
        Route::post('/questions', [AdminQuestionController::class, 'store'])->name('questions.store');
        Route::get('/questions/{question}/edit', [AdminQuestionController::class, 'edit'])->name('questions.edit');
        Route::put('/questions/{question}', [AdminQuestionController::class, 'update'])->name('questions.update');
        Route::delete('/questions/{question}', [AdminQuestionController::class, 'destroy'])->name('questions.destroy');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/system-status', [SystemStatusController::class, 'index'])->name('system-status.index');
    });
});
