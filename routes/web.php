<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products (Inventory)
    Route::resource('products', ProductController::class);

    // Inbound - FULL CRUD
    Route::get('/inbound', [InboundController::class, 'index'])->name('inbound.index');
    Route::post('/inbound', [InboundController::class, 'store'])->name('inbound.store');
    Route::get('/inbound/{id}/edit', [InboundController::class, 'edit'])->name('inbound.edit');
    Route::put('/inbound/{id}', [InboundController::class, 'update'])->name('inbound.update');
    Route::delete('/inbound/{id}', [InboundController::class, 'destroy'])->name('inbound.destroy');

    // Outbound - FULL CRUD
    Route::get('/outbound', [OutboundController::class, 'index'])->name('outbound.index');
    Route::post('/outbound', [OutboundController::class, 'store'])->name('outbound.store');
    Route::get('/outbound/{id}/edit', [OutboundController::class, 'edit'])->name('outbound.edit');
    Route::put('/outbound/{id}', [OutboundController::class, 'update'])->name('outbound.update');
    Route::delete('/outbound/{id}', [OutboundController::class, 'destroy'])->name('outbound.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// AUTHENTICATION ROUTES (dari Breeze)
require __DIR__ . '/auth.php';
