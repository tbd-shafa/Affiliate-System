<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\SubscriptionController;

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


    // Manage Affiliate - Only for affiliate_user
    Route::middleware('check.role:affiliate_user')->group(function () {
        Route::get('/affiliate/commission-balance', [AffiliateController::class, 'commissionBalance'])->name('affiliate.commission.balance');
        Route::get('/affiliate/referred-users', [AffiliateController::class, 'referredUsers'])->name('affiliate.referred.users');
        Route::get('/affiliate/earn-history', [AffiliateController::class, 'earnHistory'])->name('affiliate.earn.history');
        Route::get('/affiliate/payout-history', [AffiliateController::class, 'payoutHistory'])->name('affiliate.payout.history');
        Route::get('/affiliate/panel', [AffiliateController::class, 'panel'])->name('affiliate.panel');
        Route::get('/affiliate/link', [AffiliateController::class, 'link'])->name('affiliate.link');
    });

   

    // Manage subscription and create affiliate

    Route::middleware('check.role:user,affiliate_user')->group(function () {
        Route::get('/become-affiliate', [AffiliateController::class, 'create'])->name('affiliate.create');
        Route::post('/become-affiliate', [AffiliateController::class, 'store'])->name('affiliate.store');
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::post('/subscriptions/buy', [SubscriptionController::class, 'buySubscription'])->name('subscriptions.buy');
    });

    //manage commision percentage && Manage Affiliate
    Route::middleware('check.role:admin')->group(function () {
        Route::get('/admin/affiliate-requests', [AffiliateController::class, 'showPendingRequests'])->name('affiliate.requests');
        Route::post('/admin/affiliate-requests/{id}/approve', [AffiliateController::class, 'approveRequest'])->name('affiliate.approve');
        Route::post('/admin/affiliate-requests/{id}/reject', [AffiliateController::class, 'rejectRequest'])->name('affiliate.reject');
        Route::get('/commission-percentage', [SubscriptionController::class, 'viewCommisionPercentage'])->name('commission.percentage');
        Route::get('/commission-percentage/{id}/edit', [SubscriptionController::class, 'editCommisionPercentage'])->name('commission.edit');
        Route::put('/commission-percentage/{id}/update', [SubscriptionController::class, 'updateCommisionPercentage'])->name('commission.update');
        Route::get('/affiliate-commission/{role}', [SubscriptionController::class, 'viewAffiliateCommision'])->name('affiliate.commission');
        Route::post('/affiliate-commission/{id}/payout', [SubscriptionController::class, 'commissionPayout'])->name('commission.payout');
        Route::get('/admin/affiliate/earn-history/{user}', [SubscriptionController::class, 'earnHistory'])->name('admin.affiliate.earn.history');
        Route::get('/admin/affiliate/payout-history/{user}', [SubscriptionController::class, 'payoutHistory'])->name('admin.affiliate.payout.history');
        //toggle affiliate user status to enable or disable
        Route::post('/toggle-affiliate-status', [UserController::class, 'toggleAffiliateStatus'])->name('toggle.affiliate.status');
        Route::get('/check-commissions/{userId}', [UserController::class, 'checkCommissions'])->name('check-commissions');;


    });


    // User Management - Only for admin
    Route::prefix('users')->name('users.')->middleware('check.role:admin')->group(function () {
        Route::get('/{role}', [UserController::class, 'index'])->name('index'); // Dynamic route for all users based on role
        Route::get('/{role}/create', [UserController::class, 'create'])->name('create');
        Route::post('/{role}', [UserController::class, 'store'])->name('store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
