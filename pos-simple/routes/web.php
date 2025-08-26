<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/* Profile routes (any logged-in user) */
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* Admin-only (Products) */
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('products', ProductController::class);
});

/* Admin or Cashier (POS & Sales) */
Route::middleware(['auth', 'role:Admin|Cashier'])->group(function () {
    // NOTE: no Cart::class here yet — just a view wrapper
    Route::view('/pos', 'pos.page')->name('pos.index');

    Route::resource('sales', SaleController::class)->only(['index','show']);
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
        ->name('sales.receipt');
});

require __DIR__.'/auth.php';
