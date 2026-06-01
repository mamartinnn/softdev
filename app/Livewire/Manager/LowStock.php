<?php


namespace App\Livewire\Manager;

use App\Models\Item;
use App\Models\StockTransaction;
use Livewire\Component;

class LowStock extends Component
{
    public int    $threshold   = 5;
    public bool   $showModal   = false;
    public ?int   $restockId   = null;
    public string $restockName = '';
    public int    $restockQty  = 10;
    public string $restockNote = '';

    protected function rules(): array
    {
        return [
            'restockQty'  => 'required|integer|min:1',
            'restockNote' => 'nullable|string|max:255',
        ];
    }

    public function openRestock(Item $item): void
    {
        $this->restockId   = $item->id;
        $this->restockName = $item->name;
        $this->restockQty  = 10;
        $this->restockNote = '';
        $this->showModal   = true;
    }

    public function restock(): void
    {
        $this->validate();

        $item = Item::findOrFail($this->restockId);

        StockTransaction::create([
            'item_id'        => $item->id,
            'type'           => 'in',
            'quantity'       => $this->restockQty,
            'price_per_unit' => $item->price,
            'note'           => $this->restockNote ?: 'Restock — stok menipis',
            'user_id'        => auth()->id(),
        ]);

        $item->increment('stock', $this->restockQty);

        session()->flash('success', "Stok \"{$item->name}\" berhasil ditambah {$this->restockQty} unit.");
        $this->showModal = false;
    }

    public function render()
    {
        $items = Item::active()
            ->where('stock', '<', $this->threshold)
            ->orderBy('stock')
            ->get();

        $outOfStock  = $items->where('stock', 0)->count();
        $criticalCnt = $items->where('stock', '>', 0)->count();

        return view('livewire.manager.low-stock', compact('items', 'outOfStock', 'criticalCnt'))
            ->layout('layouts.app');
    }
}