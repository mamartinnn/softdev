<?php


namespace App\Livewire\Superadmin;

use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $thisMonth   = now()->format('Y-m');
        $lastMonth   = now()->subMonth()->format('Y-m');

        // Revenue stats
        $revenueThisMonth = ServiceOrder::where('status', 'done')
            ->whereYear('completed_at', now()->year)
            ->whereMonth('completed_at', now()->month)
            ->sum('grand_total');

        $revenueLastMonth = ServiceOrder::where('status', 'done')
            ->whereYear('completed_at', now()->subMonth()->year)
            ->whereMonth('completed_at', now()->subMonth()->month)
            ->sum('grand_total');

        $revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : 0;

        // Orders stats
        $ordersThisMonth = ServiceOrder::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $ordersToday = ServiceOrder::whereDate('created_at', today())->count();

        // Admin count
        $totalAdmins  = User::whereIn('role', ['kasir', 'manager'])->count();
        $activeAdmins = User::whereIn('role', ['kasir', 'manager'])->where('is_active', true)->count();

        // Low stock alert
        $lowStockCount = Item::active()->where('stock', '<', 5)->count();

        // Chart data: last 6 months
        $chartLabels = [];
        $chartData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $month         = now()->subMonths($i);
            $chartLabels[] = $month->translatedFormat('M Y');
            $chartData[]   = ServiceOrder::where('status', 'done')
                ->whereYear('completed_at', $month->year)
                ->whereMonth('completed_at', $month->month)
                ->sum('grand_total');
        }

        // Recent orders
        $recentOrders = ServiceOrder::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.superadmin.dashboard', compact(
            'revenueThisMonth', 'revenueLastMonth', 'revenueGrowth',
            'ordersThisMonth', 'ordersToday', 'totalAdmins', 'activeAdmins',
            'lowStockCount', 'chartLabels', 'chartData', 'recentOrders'
        ))->layout('layouts.app');
    }
}


// ============================================================
// FILE 2/3 — LOKASI: app/Livewire/Manager/Dashboard.php
// BUAT    : php artisan make:livewire Manager/Dashboard
// ============================================================

namespace App\Livewire\Manager;

use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\ServiceOrderItem;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalItems    = Item::active()->count();
        $lowStockItems = Item::active()->where('stock', '<', 5)->where('stock', '>', 0)->count();
        $outOfStock    = Item::active()->where('stock', 0)->count();

        $totalStockValue = Item::active()->selectRaw('SUM(price * stock) as total')->value('total') ?? 0;

        // Riwayat transaksi stok terbaru
        $recentTransactions = StockTransaction::with(['item', 'user'])
            ->latest()
            ->limit(8)
            ->get();

        // Top 5 barang paling banyak dipakai bulan ini
        $topItems = ServiceOrderItem::with('item')
            ->selectRaw('item_id, SUM(quantity) as total_used')
            ->whereHas('serviceOrder', fn ($q) =>
                $q->whereYear('completed_at', now()->year)
                  ->whereMonth('completed_at', now()->month)
            )
            ->groupBy('item_id')
            ->orderByDesc('total_used')
            ->limit(5)
            ->get();

        return view('livewire.manager.dashboard', compact(
            'totalItems', 'lowStockItems', 'outOfStock',
            'totalStockValue', 'recentTransactions', 'topItems'
        ))->layout('layouts.app');
    }
}


// ============================================================
// FILE 3/3 — LOKASI: app/Livewire/Kasir/Dashboard.php
// BUAT    : php artisan make:livewire Kasir/Dashboard
// ============================================================

namespace App\Livewire\Kasir;

use App\Models\ServiceOrder;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $userId = auth()->id();

        $todayOrders   = ServiceOrder::where('user_id', $userId)
            ->whereDate('created_at', today())->count();

        $todayRevenue  = ServiceOrder::where('user_id', $userId)
            ->where('status', 'done')
            ->whereDate('completed_at', today())
            ->sum('grand_total');

        $monthOrders   = ServiceOrder::where('user_id', $userId)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $monthRevenue  = ServiceOrder::where('user_id', $userId)
            ->where('status', 'done')
            ->whereYear('completed_at', now()->year)
            ->whereMonth('completed_at', now()->month)
            ->sum('grand_total');

        $recentOrders  = ServiceOrder::where('user_id', $userId)
            ->with('items')
            ->latest()
            ->limit(5)
            ->get();

        return view('livewire.kasir.dashboard', compact(
            'todayOrders', 'todayRevenue', 'monthOrders', 'monthRevenue', 'recentOrders'
        ))->layout('layouts.app');
    }
}