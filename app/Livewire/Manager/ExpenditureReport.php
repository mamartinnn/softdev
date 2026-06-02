<?php

namespace App\Livewire\Manager;

use App\Models\StockTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenditureReport extends Component
{
    use WithPagination;

    public string $filterMonth = '';
    public string $filterYear  = '';
    public string $search      = '';

    public function mount(): void
    {
        $this->filterMonth = now()->format('m');
        $this->filterYear  = now()->format('Y');
    }

    public function render()
    {
        $query = StockTransaction::with(['item', 'user'])
            ->where('type', 'in')
            ->whereYear('created_at', $this->filterYear)
            ->whereMonth('created_at', $this->filterMonth);

        // Search by item name
        if ($this->search) {
            $query->whereHas('item', fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%")
            );
        }

        $transactions = $query->latest()->paginate(15);

        // Calculate totals
        $totalQuantity = $query->sum('quantity');
        $totalCost = $query->selectRaw('SUM(quantity * price_per_unit) as total')->value('total') ?? 0;

        // Month/Year options
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $years = range(now()->year - 2, now()->year);

        return view('livewire.manager.expenditure-report', compact(
            'transactions', 'totalQuantity', 'totalCost', 'months', 'years'
        ))->layout('layouts.app');
    }
}
