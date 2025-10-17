<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\strukController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'permission:dashboard.lihat'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile (standar dari Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === Produk ===
    Route::resource('products', ProductController::class)->except(['show'])->middleware('permission:products.lihat');
    Route::get('/products/pdf', [ProductController::class, 'exportPDF'])
     ->name('products.pdf')
     ->middleware('permission:products.lihat');

    // === Kategori ===
    Route::resource('categories', CategoryController::class)->middleware('permission:categories.lihat');

    // === Payment Method ===
    Route::resource('payment_methods', PaymentMethodController::class)->middleware('permission:payment_methods.lihat');

    // === Users ===
    Route::resource('users', UserController::class)->middleware('permission:users.lihat');

    // Tambahan: role assignment ke user
    Route::get('/users/{user}/roles', [UserController::class, 'editRole'])
        ->middleware('permission:users.edit')
        ->name('users.editRole');

    Route::post('/users/{user}/roles', [UserController::class, 'updateRole'])
        ->middleware('permission:users.edit')
        ->name('users.updateRole');

    // === Roles ===
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:roles.lihat')->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:roles.tambah')->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:roles.tambah')->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:roles.edit')->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.edit')->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.hapus')->name('roles.destroy');

    // === Transaksi ===
    Route::resource('transactions', TransactionController::class)->middleware('permission:transactions.lihat');

    // === POS ===
    Route::get('/pos', [TransactionController::class, 'pos'])->middleware('permission:pos.transaksi')->name('pos.index');
    Route::post('/pos/add', [TransactionController::class, 'addToCart'])->name('pos.add');
    Route::post('/pos/update-qty', [TransactionController::class, 'updateQty'])->name('pos.updateQty');
    Route::post('/pos/remove', [TransactionController::class, 'removeFromCart'])->name('pos.remove');
    Route::get('/pos/checkout', [TransactionController::class, 'showcheckout'])->name('pos.checkout');
    Route::post('/pos/checkout', [TransactionController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/cari-barcode', [TransactionController::class, 'cariBarcode'])->name('pos.cariBarcode');
    Route::get('/pos/struk/{id}', [strukController::class, 'struk'])->name('pos.struk');

    // === Laporan ===
    Route::get('/laporan', [ReportController::class, 'index'])->middleware('permission:laporan.harian')->name('laporan.index');
    Route::get('/laporan/harian', [ReportController::class, 'harian'])->middleware('permission:laporan.harian')->name('laporan.harian');
    Route::get('/laporan/mingguan', [ReportController::class, 'mingguan'])->middleware('permission:laporan.mingguan')->name('laporan.mingguan');
    Route::get('/laporan/bulanan', [ReportController::class, 'bulanan'])->middleware('permission:laporan.bulanan')->name('laporan.bulanan');
    Route::get('/laporan/tahunan', [ReportController::class, 'tahunan'])->name('laporan.tahunan');

    Route::get('/laporan/preview/harian',   [ReportController::class, 'previewHarian'])->name('laporan.preview.harian');
    Route::get('/laporan/preview/mingguan', [ReportController::class, 'previewMingguan'])->name('laporan.preview.mingguan');
    Route::get('/laporan/preview/bulanan',  [ReportController::class, 'previewBulanan'])->name('laporan.preview.bulanan');
    Route::get('/laporan/preview/tahunan',  [ReportController::class, 'previewTahunan'])->name('laporan.preview.tahunan');


    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');

});

require __DIR__.'/auth.php';
