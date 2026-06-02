<?php

namespace App\Livewire\Superadmin;

use App\Models\ServiceOrder;
use App\Models\StockTransaction;
use Livewire\Component;

class RevenueReport extends Component
{
    public string $filterMonth;
    public string $filterYear;

    public function mount(): void
    {
        $this->filterMonth = now()->format('m');
        $this->filterYear  = now()->format('Y');
    }

    public function render()
    {
        $orders = ServiceOrder::with('user')
            ->where('status', 'done')
            ->whereYear('completed_at', $this->filterYear)
            ->whereMonth('completed_at', $this->filterMonth)
            ->latest('completed_at')
            ->get();

        $totalRevenue     = $orders->sum('grand_total');
        $totalServiceFee  = $orders->sum('service_fee');
        $totalItemsCost   = $orders->sum('total_items_cost');
        $totalOrders      = $orders->count();

        // Calculate total monthly expenses (stock purchases)
        $totalExpenses = StockTransaction::where('type', 'in')
            ->whereYear('created_at', $this->filterYear)
            ->whereMonth('created_at', $this->filterMonth)
            ->selectRaw('SUM(quantity * price_per_unit) as total')
            ->value('total') ?? 0;

        // Calculate net profit (laba bersih)
        $netProfit = $totalRevenue - $totalExpenses;

        return view('livewire.superadmin.revenue-report', compact(
            'orders', 'totalRevenue', 'totalServiceFee', 'totalItemsCost', 'totalOrders', 'totalExpenses', 'netProfit'
        ))->layout('layouts.app');
    }
}