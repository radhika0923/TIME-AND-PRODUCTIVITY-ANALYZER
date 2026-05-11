<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\TaskController;
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

    // Time Tracking Routes
    Route::get('/time-tracking', [TimeTrackingController::class, 'index'])->name('time.index');
    Route::post('/time/start', [TimeTrackingController::class, 'start'])->name('time.start');
    Route::post('/time/stop', [TimeTrackingController::class, 'stop'])->name('time.stop');

    // Analytics Route
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');

    // Reminder Routes
    Route::resource('reminders', ReminderController::class)->except(['create', 'edit', 'show', 'update']);
    Route::patch('/reminders/{id}/read', [ReminderController::class, 'markAsRead'])->name('reminders.read');
});

Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest')->name('register.form');
Route::post('/register', [AuthController::class, 'store'])->middleware('guest')->name('register');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
