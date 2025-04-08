<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [AuctionController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->middleware('role:admin')->name('users.index');

    Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('auctions.store');
    Route::get('/auctions/{auction}/edit', [AuctionController::class, 'edit'])->name('auctions.edit');
    Route::put('/auctions/{auction}', [AuctionController::class, 'update'])->name('auctions.update');
    Route::delete('/auctions/{auction}', [AuctionController::class, 'destroy'])->name('auctions.destroy');
    Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');

    Route::post('/auctions/{auction}/bids', [BidController::class, 'store'])->middleware('permission:place bids')->name('bids.store');
    Route::get('/bids/history', [BidController::class, 'history'])->name('bids.history');
    Route::get('/bids/won', [BidController::class, 'wonAuctions'])->name('bids.won');
    Route::get('/bids/activity', [BidController::class, 'activity'])->middleware('role:admin')->name('bids.activity');
});

require __DIR__.'/auth.php';
