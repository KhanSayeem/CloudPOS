<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if (method_exists($user, 'hasRole') && $user->hasRole('Cashier') && !$user->hasRole('Admin')) {
            return redirect()->route('pos.index');
        }
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/* Profile routes (any logged-in user) */
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* Admin-only routes */
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Inventory Control
    Route::resource('inventory', \App\Http\Controllers\Admin\InventoryController::class);
    
    // Sales Reports & Analytics
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/sales', [\App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/revenue', [\App\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/products', [\App\Http\Controllers\Admin\ReportController::class, 'products'])->name('reports.products');
    
    // Transaction Management
    Route::resource('transactions', \App\Http\Controllers\Admin\TransactionController::class);
    
    // Admin Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::get('settings/export', [\App\Http\Controllers\Admin\SettingController::class, 'export'])->name('settings.export');
    Route::post('settings/import', [\App\Http\Controllers\Admin\SettingController::class, 'import'])->name('settings.import');
    Route::post('settings/backup', [\App\Http\Controllers\Admin\SettingController::class, 'backup'])->name('settings.backup');
});

/* Admin-only (Products) */
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('products', ProductController::class);
});

/* Admin or Cashier (Sales) */
Route::middleware(['auth', 'role:Admin|Cashier'])->group(function () {
    Route::resource('sales', SaleController::class)->only(['index','show']);
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])
        ->name('sales.receipt');
});

/* Cashier-only (POS) */
Route::middleware(['auth', 'role:Cashier'])->group(function () {
    Route::get('/pos', [\App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/add', [\App\Http\Controllers\PosController::class, 'add'])->name('pos.add');
    Route::post('/pos/update', [\App\Http\Controllers\PosController::class, 'update'])->name('pos.update');
    Route::post('/pos/remove', [\App\Http\Controllers\PosController::class, 'remove'])->name('pos.remove');
    Route::post('/pos/checkout', [\App\Http\Controllers\PosController::class, 'checkout'])->name('pos.checkout');
    Route::post('/pos/clear', [\App\Http\Controllers\PosController::class, 'clear'])->name('pos.clear');
});

require __DIR__.'/auth.php';
