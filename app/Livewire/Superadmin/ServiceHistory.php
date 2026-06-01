<?php

namespace App\Livewire\Superadmin;

use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceHistory extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filterStatus = '';
    public string $filterKasir  = '';
    public string $filterDate   = '';
    public ?int   $viewingId    = null;
    public bool   $showDetail   = false;

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterKasir(): void  { $this->resetPage(); }

    public function viewDetail(int $id): void
    {
        $this->viewingId  = $id;
        $this->showDetail = true;
    }

    public function closeDetail(): void
    {
        $this->showDetail = false;
        $this->viewingId  = null;
    }

    public function render()
    {
        $orders = ServiceOrder::with(['user', 'items'])
            ->when($this->search, fn ($q) =>
                $q->where('customer_name', 'like', "%{$this->search}%")
                  ->orWhere('order_number', 'like', "%{$this->search}%")
                  ->orWhere('plate_number', 'like', "%{$this->search}%")
            )
            ->when($this->filterStatus,  fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterKasir,   fn ($q) => $q->where('user_id', $this->filterKasir))
            ->when($this->filterDate,    fn ($q) => $q->whereDate('created_at', $this->filterDate))
            ->latest()
            ->paginate(15);

        $kasirList    = User::where('role', 'kasir')->get(['id', 'name']);
        $viewingOrder = $this->viewingId
            ? ServiceOrder::with(['user', 'items'])->find($this->viewingId)
            : null;

        return view('livewire.superadmin.service-history', compact('orders', 'kasirList', 'viewingOrder'))
            ->layout('layouts.app');
    }
}