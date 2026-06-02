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

    
    public string  $name        = '';
    public string  $sku         = '';
    public float   $price       = 0;
    public int     $stock       = 0;
    public string  $unit        = 'pcs';
    public string  $description = '';
    public         $image; // file upload
    public ?string $existingImage = null;

    
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
            'unit'        => 'required|string',
            'description' => 'nullable|string',
            'image'       => $this->editingId ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'sku', 'price', 'stock', 'unit', 'description', 'image', 'editingId', 'existingImage']);
        $this->unit      = 'pcs';
        $this->showItemModal = true;
    }

    public function openEdit(Item $item): void
    {
        // Prevent editing of deactivated items
        if (!$item->is_active) {
            session()->flash('error', 'Barang yang sudah dinonaktifkan tidak bisa diedit atau diaktifkan kembali.');
            return;
        }

        $this->editingId      = $item->id;
        $this->name           = $item->name;
        $this->sku            = $item->sku ?? '';
        $this->price          = $item->price;
        $this->unit           = $item->unit;
        $this->description    = $item->description ?? '';
        $this->existingImage  = $item->image;
        $this->showItemModal  = true;
    }

    public function saveItem(): void
    {
        $this->validate();

        $data = [
            'name'        => $this->name,
            'sku'         => $this->sku ?: null,
            'price'       => $this->price,
            'unit'        => $this->unit,
            'description' => $this->description,
        ];

        // Handle gambar
        if ($this->image) {
            $path         = $this->image->store('items', 'public');
            $data['image'] = $path;
        }

        if ($this->editingId) {
            Item::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Barang berhasil diperbarui.');
        } else {
            $data['stock'] = 0; // stok awal 0, tambah via transaksi masuk
            Item::create($data);
            session()->flash('success', 'Barang baru berhasil ditambahkan.');
        }

        $this->showItemModal = false;
    }

    public function openStock(Item $item, string $type = 'in'): void
    {
        // Prevent stock changes for deactivated items
        if (!$item->is_active) {
            session()->flash('error', 'Tidak bisa mengubah stok barang yang sudah dinonaktifkan.');
            return;
        }

        $this->stockItemId = $item->id;
        $this->stockType   = $type;
        $this->stockQty    = 1;
        $this->stockNote   = '';
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

        // Catat transaksi stok
        StockTransaction::create([
            'item_id'       => $item->id,
            'type'          => $this->stockType,
            'quantity'      => $this->stockQty,
            'price_per_unit'=> $item->price,
            'note'          => $this->stockNote,
            'user_id'       => auth()->id(),
        ]);

        // Update stok
        if ($this->stockType === 'in') {
            $item->increment('stock', $this->stockQty);
        } else {
            $item->decrement('stock', $this->stockQty);
        }

        session()->flash('success', 'Stok berhasil diperbarui.');
        $this->showStockModal = false;
    }

    public function delete(Item $item): void
    {
        $item->update(['is_active' => false]);
        session()->flash('success', 'Barang berhasil dinonaktifkan.');
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