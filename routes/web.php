<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Superadmin\Dashboard      as SuperadminDashboard;
use App\Livewire\Superadmin\ManageAdmins;
use App\Livewire\Superadmin\RevenueReport;
use App\Livewire\Superadmin\ServiceHistory;
use App\Livewire\Manager\Dashboard         as ManagerDashboard;
use App\Livewire\Manager\ManageItems;
use App\Livewire\Manager\StockIn;
use App\Livewire\Manager\LowStock;
use App\Livewire\Kasir\Dashboard           as KasirDashboard;
use App\Livewire\Kasir\CreateServiceOrder;
use App\Livewire\Kasir\OrderList;

// Root route
Route::get('/', fn() => auth()->check() 
    ? match(auth()->user()->role) {
        'superadmin' => redirect()->route('superadmin.dashboard'),
        'manager'    => redirect()->route('manager.dashboard'),
        'kasir'      => redirect()->route('kasir.dashboard'),
        default      => redirect('/'),
    }
    : view('welcome-new')
)->name('welcome');

// Auth routes (Breeze Volt)
require __DIR__.'/auth.php';

// ============================================================
// Generic /dashboard — redirect sesuai role
// (dipakai oleh navigation.blade.php link logo)
// ============================================================
Route::middleware('auth')->get('/dashboard', function () {
    return match(auth()->user()->role) {
        'superadmin' => redirect()->route('superadmin.dashboard'),
        'manager'    => redirect()->route('manager.dashboard'),
        'kasir'      => redirect()->route('kasir.dashboard'),
        default      => redirect('/'),
    };
})->name('dashboard');

// ============================================================
// SUPERADMIN routes
// ============================================================
Route::middleware(['auth', 'role:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard',            SuperadminDashboard::class)->name('dashboard');
        Route::get('/admins',               ManageAdmins::class)->name('admins.index');
        Route::get('/service-history',      ServiceHistory::class)->name('service-history');
        Route::get('/reports',              RevenueReport::class)->name('reports');
        // Superadmin bisa cetak semua struk
        Route::get('/receipt/{order}', function (\App\Models\ServiceOrder $order) {
            return view('kasir.receipt', compact('order'));
        })->name('receipt');
    });

// ============================================================
// MANAGER routes
// ============================================================
Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', ManagerDashboard::class)->name('dashboard');
        Route::get('/items',     ManageItems::class)->name('items.index');
        Route::get('/stock/in',  StockIn::class)->name('stock.in');
        Route::get('/stock/low', LowStock::class)->name('stock.low');
    });

// ============================================================
// KASIR routes
// ============================================================
Route::middleware(['auth', 'role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {
        Route::get('/dashboard',          KasirDashboard::class)->name('dashboard');
        Route::get('/orders/create',      CreateServiceOrder::class)->name('orders.create');
        Route::get('/orders',             OrderList::class)->name('orders.index');
        Route::get('/orders/{order}/receipt', function (\App\Models\ServiceOrder $order) {
            abort_unless($order->user_id === auth()->id(), 403);
            return view('kasir.receipt', compact('order'));
        })->name('orders.receipt');
    });

// Logout (POST) — juga di auth.php tapi tambahkan fallback
Route::middleware('auth')->post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Profile route (dipakai navigation sidebar)
Route::middleware('auth')->get('/profile', function () {
    return redirect()->route('dashboard');
})->name('profile');