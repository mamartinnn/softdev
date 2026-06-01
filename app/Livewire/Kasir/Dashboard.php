<?php

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