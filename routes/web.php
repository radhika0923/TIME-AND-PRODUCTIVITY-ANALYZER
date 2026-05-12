<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\TimeTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect authenticated users appropriately
    if (auth()->check()) {
        // If email not verified, go to verification page
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        // If email verified, go to dashboard
        return redirect('/dashboard');
    }
    
    return view('welcome');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, $id, $hash) {
    // Get the user
    $user = \App\Models\User::findOrFail($id);
    
    // Verify the hash is correct
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        throw new \Illuminate\Auth\Access\AuthorizationException;
    }
    
    // If already verified, redirect to dashboard
    if ($user->hasVerifiedEmail()) {
        return redirect('/dashboard');
    }
    
    // Mark email as verified
    $user->markEmailAsVerified();
    
    // Log the user in
    \Illuminate\Support\Facades\Auth::login($user);
    
    // Redirect to dashboard
    return redirect('/dashboard');
})->middleware('signed')->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::patch('/tasks/{id}/complete', [TaskController::class, 'markComplete'])->name('tasks.complete');

    // Category Routes
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Time Tracking Routes
    Route::get('/time-tracking', [TimeTrackingController::class, 'index'])->name('time.index');
    Route::get('/time-tracking/export', [TimeLogController::class, 'export'])->name('time.export');
    Route::patch('/time-logs/{time_log}', [TimeLogController::class, 'update'])->name('time-logs.update');
    Route::delete('/time-logs/{time_log}', [TimeLogController::class, 'destroy'])->name('time-logs.destroy');
    Route::post('/time/start', [TimeTrackingController::class, 'start'])
        ->middleware('throttle:30,1')
        ->name('time.start');
    Route::post('/time/stop', [TimeTrackingController::class, 'stop'])
        ->middleware('throttle:30,1')
        ->name('time.stop');

    // Analytics Route
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');

    // Reminder Routes
    Route::resource('reminders', ReminderController::class)->except(['create', 'edit', 'show', 'update']);
    Route::patch('/reminders/{id}/read', [ReminderController::class, 'markAsRead'])->name('reminders.read');

    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/account', [SettingsController::class, 'destroy'])->name('settings.destroy');

    // Planner Routes
    Route::get('/planner', [\App\Http\Controllers\PlannerController::class, 'index'])->name('planner.index');
    Route::get('/planner/events', [\App\Http\Controllers\PlannerController::class, 'events'])->name('planner.events');
    Route::patch('/planner/tasks/{id}', [\App\Http\Controllers\PlannerController::class, 'update'])->name('planner.update');
});

Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest')->name('register.form');
Route::post('/register', [AuthController::class, 'store'])->middleware('guest')->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
