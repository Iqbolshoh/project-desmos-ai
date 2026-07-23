<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Home — landing page for guests, dashboard for authenticated users
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard.index')
        : app(MarketingController::class)->landing();
})->name('home');

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Tutor
    Route::get('/tutor', [\App\Http\Controllers\TutorController::class, 'index'])->name('tutor.index');
    Route::post('/tutor/solve', [\App\Http\Controllers\TutorController::class, 'solve'])->name('tutor.solve');
    Route::get('/tutor/{session}', [\App\Http\Controllers\TutorController::class, 'show'])->name('tutor.show');

    // History
    Route::get('/history', [\App\Http\Controllers\HistoryController::class, 'index'])->name('history.index');
    Route::post('/history/save-graph', [\App\Http\Controllers\HistoryController::class, 'saveGraph'])->name('history.save-graph');
    
    // Diagnostic
    Route::get('/diagnostic', [\App\Http\Controllers\DiagnosticController::class, 'start'])->name('diagnostic.start');
    Route::get('/diagnostic/show', [\App\Http\Controllers\DiagnosticController::class, 'show'])->name('diagnostic.show');
    Route::post('/diagnostic/submit', [\App\Http\Controllers\DiagnosticController::class, 'submit'])->name('diagnostic.submit');
    Route::get('/diagnostic/results/{result}', [\App\Http\Controllers\DiagnosticController::class, 'results'])->name('diagnostic.results');

    // Roadmap
    Route::get('/roadmap', [\App\Http\Controllers\RoadmapController::class, 'show'])->name('roadmap.show');
    Route::post('/roadmap/generate', [\App\Http\Controllers\RoadmapController::class, 'generate'])->name('roadmap.generate');
    Route::post('/roadmap/{roadmap}/toggle', [\App\Http\Controllers\RoadmapController::class, 'toggleTask'])->name('roadmap.toggle');

    // Practice
    Route::get('/practice', [\App\Http\Controllers\PracticeController::class, 'index'])->name('practice.index');
    Route::get('/practice/{topic:slug}', [\App\Http\Controllers\PracticeController::class, 'topic'])->name('practice.topic');
    Route::get('/practice/{topic:slug}/quiz', [\App\Http\Controllers\PracticeController::class, 'quiz'])->name('practice.quiz');
    Route::post('/practice/{question}/submit', [\App\Http\Controllers\PracticeController::class, 'submit'])->name('practice.submit');

    // Chat
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{thread}/send', [\App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');

    // Leaderboard
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');

    // Platform: Roles & Users
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    
    // Admin Extensions
    Route::prefix('admin')->name('admin.')->middleware(['can:roles.view'])->group(function() {
        Route::get('/questions', [\App\Http\Controllers\Admin\QuestionController::class, 'index'])->name('questions.index');
        Route::get('/questions/create', [\App\Http\Controllers\Admin\QuestionController::class, 'create'])->name('questions.create');
        Route::post('/questions', [\App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('questions.store');
        Route::delete('/questions/{question}', [\App\Http\Controllers\Admin\QuestionController::class, 'destroy'])->name('questions.destroy');
        
        // Mock views for reports and analytics
        Route::view('/reports', 'admin.reports.index')->name('reports.index');
        Route::view('/analytics', 'admin.analytics.index')->name('analytics.index');
    });
});
