<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Superadmin\ManageAdmins;
use App\Livewire\Superadmin\RevenueReport;
use App\Livewire\Superadmin\ServiceHistory;
use App\Livewire\Manager\ManageItems;
use App\Livewire\Manager\StockIn;
use App\Livewire\Manager\LowStock;
use App\Livewire\Kasir\CreateServiceOrder;
use App\Livewire\Kasir\OrderList;





Route::get('/', fn() => redirect()->route('login'));

// Auth routes (dari Breeze)
require __DIR__.'/auth.php';

// ===================== SUPERADMIN =====================
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', fn() => view('superadmin.dashboard'))->name('dashboard');
    Route::get('/admins',          ManageAdmins::class)->name('admins.index');
    // Route::get('/service-history', ServiceHistory::class)->name('service-history');
    Route::get('/reports',         RevenueReport::class)->name('reports');
});

// ===================== MANAGER =====================
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', fn() => view('manager.dashboard'))->name('dashboard');
    Route::get('/items',     ManageItems::class)->name('items.index');
    // Route::get('/stock/in',  StockIn::class)->name('stock.in');
    // Route::get('/stock/low', LowStock::class)->name('stock.low');
});

// ===================== KASIR =====================
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/dashboard',    fn() => view('kasir.dashboard'))->name('dashboard');
    Route::get('/orders/create', CreateServiceOrder::class)->name('orders.create');
    // Route::get('/orders',        OrderList::class)->name('orders.index');
    Route::get('/orders/{order}/receipt', function (\App\Models\ServiceOrder $order) {
        return view('kasir.receipt', compact('order'));
    })->name('orders.receipt');
});