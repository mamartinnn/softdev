<?php


namespace App\Livewire\Kasir;

use App\Models\ServiceOrder;
use App\Models\StockTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $filterStatus  = '';
    public string $filterDate    = '';
    public ?int   $viewingId     = null;
    public bool   $showDetail    = false;

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

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

    public function deleteOrder(int $id): void
    {
        $order = ServiceOrder::find($id);
        if (!$order || $order->user_id !== auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'Order tidak ditemukan atau Anda tidak memiliki akses.');
            return;
        }

        // Restore stok barang
        foreach ($order->items as $serviceOrderItem) {
            $item = $serviceOrderItem->item;
            if ($item) {
                $item->increment('stock', $serviceOrderItem->quantity);
            }
        }

        // Stock transactions akan otomatis terhapus via cascadeOnDelete
        $order->delete();
        $this->dispatch('notify', type: 'success', message: 'Order berhasil dihapus dan stok telah dikembalikan.');
    }

    public function render()
    {
        // Kasir hanya melihat order miliknya
        $orders = ServiceOrder::with(['items'])
            ->where('user_id', auth()->id())
            ->when($this->search, fn ($q) =>
                $q->where('customer_name', 'like', "%{$this->search}%")
                  ->orWhere('order_number', 'like', "%{$this->search}%")
                  ->orWhere('plate_number', 'like', "%{$this->search}%")
            )
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterDate,   fn ($q) => $q->whereDate('created_at', $this->filterDate))
            ->latest()
            ->paginate(12);

        $viewingOrder = $this->viewingId
            ? ServiceOrder::with(['items'])->find($this->viewingId)
            : null;

        // Stat ringkas hari ini
        $todayOrders  = ServiceOrder::where('user_id', auth()->id())
                            ->whereDate('created_at', today())
                            ->count();
        $todayRevenue = ServiceOrder::where('user_id', auth()->id())
                            ->where('status', 'done')
                            ->whereDate('completed_at', today())
                            ->sum('grand_total');

        return view('livewire.kasir.order-list', compact('orders', 'viewingOrder', 'todayOrders', 'todayRevenue'))
            ->layout('layouts.app');
    }
}