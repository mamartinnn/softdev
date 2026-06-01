<?php

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