<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard tetap
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile (dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD produk
    Route::resource('products', ProductController::class)->middleware('auth');
    // CRUD kategori
    Route::resource('categories', CategoryController::class);

    // CRUD payment method
    Route::resource('payment-methods', PaymentMethodController::class);

    // CRUD user
    Route::resource('users', UserController::class);

    // CRUD role
    Route::resource('roles', RoleController::class);

    // POS (Transaksi)
    Route::get('/pos', [TransactionController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [TransactionController::class, 'checkout'])->name('pos.checkout');

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';

