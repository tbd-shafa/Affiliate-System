<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AffiliateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // manage affiliate
    Route::get('/become-affiliate', [AffiliateController::class, 'create'])->name('affiliate.create');
    Route::post('/become-affiliate', [AffiliateController::class, 'store'])->name('affiliate.store');
    Route::get('/admin/affiliate-requests', [AffiliateController::class, 'showPendingRequests'])->name('affiliate.requests');
    Route::post('/admin/affiliate-requests/{id}/approve', [AffiliateController::class, 'approveRequest'])->name('affiliate.approve');
    Route::post('/admin/affiliate-requests/{id}/reject', [AffiliateController::class, 'rejectRequest'])->name('affiliate.reject');


    // User Management Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/{role}', [UserController::class, 'index'])->name('index'); // Dynamic route for all users based on role
        Route::get('/{role}/create', [UserController::class, 'create'])->name('create');
        Route::post('/{role}', [UserController::class, 'store'])->name('store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
