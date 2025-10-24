<?php

use App\Http\Controllers\Admin\{
    ActivityController,
    UserController,
    IdleController,
    PenaltyController,
};
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingController;





Route::middleware(['auth'])->group(function () {
    // Common routes for all users
    Route::get('/', fn() => redirect()->route('admin.dashboard'));

    // Admin routes with admin middleware
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users CRUD
        Route::resource('users', UserController::class);
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::delete('/users/{id}/activities', [UserController::class, 'deleteActivities'])->name('admin.users.deleteActivities');
        Route::delete('/users/{id}/penalties', [UserController::class, 'deletePenalties'])->name('admin.users.deletePenalties');
        Route::delete('/users/{id}/idle-sessions', [UserController::class, 'deleteIdleSessions'])->name('admin.users.deleteIdleSessions');


        // Activities
        Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Penalties
        Route::get('/penalties', [PenaltyController::class, 'index'])->name('penalties.index');
    });

    // User routes
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    });

    // Common routes for both roles
    Route::get('/settings/get', [SettingController::class, 'getSettings'])->name('settings.get');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Idle monitoring routes
    Route::post('/idle/record', [IdleController::class, 'record'])->name('idle.record');
    Route::post('/idle/{id}/end', [IdleController::class, 'end'])->name('idle.end');

    //penalty routes
    Route::post('/penalties/apply', [PenaltyController::class, 'store'])->name('penalties.store');
    // Route::delete('/penalties/{id}', [PenaltyController::class, 'destroy'])->name('penalties.destroy');
});
require __DIR__ . '/auth.php';
