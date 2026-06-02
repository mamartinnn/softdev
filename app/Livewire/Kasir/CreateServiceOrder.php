<?php

namespace App\Livewire\Kasir;

use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\StockTransaction;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateServiceOrder extends Component
{
    // Data pelanggan
    public string $customerName  = '';
    public string $phoneNumber   = '';
    public string $vehicleType   = '';
    public string $plateNumber   = '';
    public string $complaint     = '';

    // Biaya jasa
    public float  $serviceFee    = 0.0;

    // Daftar barang yang dipakai
    public array  $selectedItems = [];  // [{item_id, item_name, price, qty, subtotal}]

    // State pencarian barang
    public string $itemSearch    = '';
    public array  $searchResults = [];

    // Order yang sudah disimpan (untuk struk)
    public ?int   $savedOrderId  = null;

    protected $rules = [
        'customerName' => 'required|string|max:100',
        'phoneNumber'  => 'nullable|string|max:20',
        'vehicleType'  => 'required|string|max:100',
        'plateNumber'  => 'required|string|max:20',
        'serviceFee'   => 'required|numeric|min:0',
    ];

    public function updatedItemSearch(): void
    {
        if (strlen($this->itemSearch) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Item::active()
            ->where(function($q) {
                $q->where('name', 'like', "%{$this->itemSearch}%")
                  ->orWhere('sku', 'like', "%{$this->itemSearch}%");
            })
            ->whereNotIn('id', array_column($this->selectedItems, 'item_id'))
            ->limit(8)
            ->get(['id', 'name', 'sku', 'price', 'stock', 'unit'])
            ->toArray();
    }

    public function addItem(int $itemId): void
    {
        $item = Item::find($itemId);
        if (!$item) return;

        // Cek apakah sudah ada
        foreach ($this->selectedItems as $si) {
            if ($si['item_id'] === $itemId) return;
        }

        $this->selectedItems[] = [
            'item_id'    => $item->id,
            'item_name'  => $item->name,
            'unit'       => $item->unit,
            'price'      => $item->price,
            'max_stock'  => $item->stock,
            'qty'        => 1,
            'subtotal'   => $item->price,
        ];

        $this->itemSearch    = '';
        $this->searchResults = [];
    }

    public function removeItem(int $index): void
    {
        array_splice($this->selectedItems, $index, 1);
        $this->selectedItems = array_values($this->selectedItems);
    }

    public function updatedSelectedItems(): void
    {
        foreach ($this->selectedItems as &$si) {
            $si['subtotal'] = $si['price'] * $si['qty'];
        }
    }

    #[Computed]
    public function totalItemsCost(): float
    {
        return array_sum(array_column($this->selectedItems, 'subtotal'));
    }

    #[Computed]
    public function grandTotal(): float
    {
        return $this->totalItemsCost + $this->serviceFee;
    }

    public function saveOrder(): void
    {
        $this->validate();

        if (empty($this->selectedItems) && $this->serviceFee <= 0) {
            session()->flash('error', 'Tambahkan minimal satu barang atau isi biaya jasa.');
            return;
        }

        // Cek stok semua barang
        foreach ($this->selectedItems as $si) {
            $item = Item::find($si['item_id']);
            if ($item->stock < $si['qty']) {
                session()->flash('error', "Stok {$item->name} tidak cukup! Tersedia: {$item->stock} {$item->unit}.");
                return;
            }
        }

        // Buat order
        $order = ServiceOrder::create([
            'order_number'      => ServiceOrder::generateOrderNumber(),
            'customer_name'     => $this->customerName,
            'phone_number'      => $this->phoneNumber,
            'vehicle_type'      => $this->vehicleType,
            'plate_number'      => $this->plateNumber,
            'complaint'         => $this->complaint,
            'status'            => 'done',
            'service_fee'       => $this->serviceFee,
            'total_items_cost'  => $this->totalItemsCost,
            'grand_total'       => $this->grandTotal,
            'user_id'           => auth()->id(),
            'completed_at'      => now(),
        ]);

        // Simpan item & kurangi stok
        foreach ($this->selectedItems as $si) {
            ServiceOrderItem::create([
                'service_order_id' => $order->id,
                'item_id'          => $si['item_id'],
                'item_name'        => $si['item_name'],
                'price_at_time'    => $si['price'],
                'quantity'         => $si['qty'],
                'subtotal'         => $si['subtotal'],
            ]);

            // Kurangi stok + catat transaksi
            $item = Item::find($si['item_id']);
            $item->decrement('stock', $si['qty']);

            StockTransaction::create([
                'item_id'        => $si['item_id'],
                'type'           => 'out',
                'quantity'       => $si['qty'],
                'price_per_unit' => $si['price'],
                'note'           => "Servis order #{$order->order_number}",
                'user_id'        => auth()->id(),
            ]);
        }

        $this->savedOrderId = $order->id;
        session()->flash('success', 'Order berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.kasir.create-service-order')
            ->layout('layouts.app');
    }
}