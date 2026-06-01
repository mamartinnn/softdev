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





