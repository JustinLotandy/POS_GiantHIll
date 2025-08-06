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
use App\Http\Controllers\strukController;


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
    Route::resource('payment_methods', PaymentMethodController::class);

    // CRUD user
    Route::resource('users', UserController::class);

    // CRUD role
    // Route::resource('roles', RoleController::class);

    Route::resource('transactions', TransactionController::class);

    // POS (Point of Sale)
    Route::get('/pos', [TransactionController::class, 'pos'])->name('pos.index');
    Route::post('/pos/add', [TransactionController::class, 'addToCart'])->name('pos.add');
    Route::post('/pos/update-qty', [TransactionController::class, 'updateQty'])->name('pos.updateQty');
    Route::post('/pos/remove', [TransactionController::class, 'removeFromCart'])->name('pos.remove');

    Route::get('/pos/checkout', [TransactionController::class, 'showcheckout'])->name('pos.checkout');
   
    Route::post('/pos/checkout', [TransactionController::class, 'checkout'])->name('pos.checkout');

    Route::get('/pos/cari-barcode', [TransactionController::class, 'cariBarcode'])->name('pos.cariBarcode');
    
    Route::get('/pos/struk/{id}', [TransactionController::class, 'struk'])->name('pos.struk');


    Route::get('/pos/struk/{id}', [strukController::class, 'struk'])->name('pos.struk');

    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/harian', [ReportController::class, 'harian'])->name('laporan.harian');
    Route::get('/laporan/mingguan', [ReportController::class, 'mingguan'])->name('laporan.mingguan');
    Route::get('/laporan/bulanan', [ReportController::class, 'bulanan'])->name('laporan.bulanan');

    Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    
    // Assign role ke user
    Route::get('/users/{user}/roles', [UserController::class, 'editRole'])->name('users.editRole');
    Route::post('/users/{user}/roles', [UserController::class, 'updateRole'])->name('users.updateRole');


});

});


    // Laporan
    // Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

//     Route::get('/clear-cart', function () {
//     session()->forget('cart');
//     return back();
    
// });



require __DIR__.'/auth.php';

