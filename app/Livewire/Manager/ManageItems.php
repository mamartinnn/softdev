<?php

namespace App\Livewire\Manager;

use App\Models\Item;
use App\Models\StockTransaction;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ManageItems extends Component
{
    use WithPagination, WithFileUploads;

    // Form fields
    public string  $name        = '';
    public string  $sku         = '';
    public float   $price       = 0;
    public float   $costPrice   = 0;
    public int     $stock       = 0;
    public string  $unit        = 'pcs';
    public string  $description = '';
    public         $image;
    public ?string $existingImage = null;

    // UI state
    public bool   $showItemModal  = false;
    public bool   $showStockModal = false;
    public ?int   $editingId      = null;
    public ?int   $stockItemId    = null;
    public string $stockType      = 'in';
    public int    $stockQty       = 1;
    public string $stockNote      = '';
    public string $search         = '';

    protected function rules(): array
    {
        $skuRule = $this->editingId
            ? "nullable|unique:items,sku,{$this->editingId}"
            : 'nullable|unique:items,sku';

        return [
            'name'        => 'required|string|max:150',
            'sku'         => $skuRule,
            'price'       => 'required|numeric|min:0',
            'costPrice'   => 'required|numeric|min:0',
            'unit'        => 'required|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'sku', 'price', 'costPrice', 'stock', 'unit', 'description', 'image', 'editingId', 'existingImage']);
        $this->unit          = 'pcs';
        $this->showItemModal = true;
    }

    public function openEdit(int $itemId): void
    {
        // Selalu ambil data fresh dari DB untuk menghindari state Livewire yang stale
        $item = Item::findOrFail($itemId);

        if (!$item->is_active) {
            $this->dispatch('notify', type: 'error', message: 'Barang yang sudah dinonaktifkan tidak bisa diedit. Aktifkan terlebih dahulu.');
            return;
        }

        $this->editingId     = $item->id;
        $this->name          = $item->name;
        $this->sku           = $item->sku ?? '';
        $this->price         = $item->price;
        $this->costPrice     = $item->cost_price;
        $this->unit          = $item->unit;
        $this->description   = $item->description ?? '';
        $this->existingImage = $item->image;
        $this->showItemModal = true;
    }

    public function saveItem(): void
    {
        $this->validate();

        $data = [
            'name'        => $this->name,
            'sku'         => $this->sku ?: null,
            'price'       => $this->price,
            'cost_price'  => $this->costPrice,
            'unit'        => $this->unit,
            'description' => $this->description,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('items', 'public');
        }

        if ($this->editingId) {
            Item::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Barang berhasil diperbarui.');
        } else {
            $data['stock'] = 0;
            Item::create($data);
            $this->dispatch('notify', type: 'success', message: 'Barang baru berhasil ditambahkan.');
        }

        $this->showItemModal = false;
    }

    public function openStock(int $itemId, string $type = 'in'): void
    {
        // Selalu ambil data fresh dari DB
        $item = Item::findOrFail($itemId);

        if (!$item->is_active) {
            $this->dispatch('notify', type: 'error', message: 'Tidak bisa mengubah stok barang yang sudah dinonaktifkan.');
            return;
        }

        $this->stockItemId    = $item->id;
        $this->stockType      = $type;
        $this->stockQty       = 1;
        $this->stockNote      = '';
        $this->showStockModal = true;
    }

    public function saveStock(): void
    {
        $this->validate([
            'stockQty'  => 'required|integer|min:1',
            'stockNote' => 'nullable|string',
        ]);

        $item = Item::findOrFail($this->stockItemId);

        if ($this->stockType === 'out' && $item->stock < $this->stockQty) {
            $this->addError('stockQty', 'Stok tidak mencukupi! Stok saat ini: ' . $item->stock);
            return;
        }

        StockTransaction::create([
            'item_id'        => $item->id,
            'type'           => $this->stockType,
            'quantity'       => $this->stockQty,
            'price_per_unit' => $item->price,
            'note'           => $this->stockNote,
            'user_id'        => auth()->id(),
        ]);

        if ($this->stockType === 'in') {
            $item->increment('stock', $this->stockQty);
        } else {
            $item->decrement('stock', $this->stockQty);
        }

        $this->dispatch('notify', type: 'success', message: 'Stok berhasil diperbarui.');
        $this->showStockModal = false;
    }

    public function delete(int $itemId): void
    {
        $item = Item::findOrFail($itemId);
        $item->update(['is_active' => false]);
        $this->dispatch('notify', type: 'success', message: 'Barang berhasil dinonaktifkan.');
    }

    public function reactivate(int $itemId): void
    {
        // Ambil fresh dari DB supaya status pasti terbaru
        $item = Item::findOrFail($itemId);
        $item->update(['is_active' => true]);
        $this->resetPage(); // paksa render ulang tabel agar tombol Edit muncul
        $this->dispatch('notify', type: 'success', message: 'Barang berhasil diaktifkan kembali.');
    }

    public function render()
    {
        $items = Item::when($this->search, fn($q) =>
            $q->where('name', 'like', "%{$this->search}%")
              ->orWhere('sku', 'like', "%{$this->search}%")
        )->latest()->paginate(10);

        return view('livewire.manager.manage-items', compact('items'))
            ->layout('layouts.app');
    }
}