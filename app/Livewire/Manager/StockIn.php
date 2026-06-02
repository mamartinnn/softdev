<?php
namespace App\Livewire\Manager;

use App\Models\Item;
use App\Models\StockTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class StockIn extends Component
{
    use WithPagination;

    public ?int    $itemId        = null;
    public int     $quantity      = 1;
    public float   $pricePerUnit  = 0;
    public string  $supplierName  = '';
    public string  $note          = '';
    public string  $itemSearch    = '';
    public array   $searchResults = [];
    public ?array  $selectedItem  = null;
    public string  $search        = '';

    protected function rules(): array
    {
        return [
            'itemId'       => 'required|exists:items,id',
            'quantity'     => 'required|integer|min:1',
            'pricePerUnit' => 'required|numeric|min:0',
            'supplierName' => 'nullable|string|max:100',
            'note'         => 'nullable|string|max:255',
        ];
    }

    public function updatedItemSearch(): void
    {
        if (strlen($this->itemSearch) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Item::active()
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->itemSearch}%")
                  ->orWhere('sku',  'like', "%{$this->itemSearch}%");
            })
            ->limit(8)
            ->get(['id', 'name', 'sku', 'price', 'stock', 'unit'])
            ->toArray();
    }

    public function selectItem(int $id): void
    {
        $item = Item::find($id);
        if (! $item) return;

        $this->selectedItem = [
            'id'    => $item->id,
            'name'  => $item->name,
            'sku'   => $item->sku,
            'stock' => $item->stock,
            'unit'  => $item->unit,
            'price' => (float) $item->price,
        ];
        $this->itemId        = $item->id;
        $this->pricePerUnit  = (float) $item->price;
        $this->itemSearch    = $item->name;
        $this->searchResults = [];
    }

    public function clearItem(): void
    {
        $this->selectedItem = null;
        $this->itemId       = null;
        $this->itemSearch   = '';
        $this->pricePerUnit = 0;
    }

    public function save(): void
    {
        $this->validate();

        $noteText  = $this->supplierName ? "Supplier: {$this->supplierName}" : '';
        $noteText .= ($noteText && $this->note) ? " | {$this->note}" : $this->note;

        StockTransaction::create([
            'item_id'        => $this->itemId,
            'type'           => 'in',
            'quantity'       => $this->quantity,
            'price_per_unit' => $this->pricePerUnit,
            'note'           => $noteText ?: null,
            'user_id'        => auth()->id(),
        ]);

        Item::find($this->itemId)->increment('stock', $this->quantity);

        session()->flash('success', "Stok berhasil ditambah {$this->quantity} unit.");

        $this->reset(['itemId', 'quantity', 'pricePerUnit', 'supplierName', 'note',
                      'itemSearch', 'searchResults', 'selectedItem']);
        $this->quantity = 1;
        $this->resetPage();
    }

    public function deleteTransaction(int $transactionId): void
    {
        $transaction = StockTransaction::find($transactionId);
        if (!$transaction) {
            session()->flash('error', 'Transaksi tidak ditemukan.');
            return;
        }

        // Hanya bisa delete transaksi masuk (type='in')
        if ($transaction->type !== 'in') {
            session()->flash('error', 'Hanya transaksi masuk yang bisa dihapus.');
            return;
        }

        $item = $transaction->item;
        if (!$item) {
            session()->flash('error', 'Item tidak ditemukan.');
            return;
        }

        // Reverse stock
        $item->decrement('stock', $transaction->quantity);

        // Delete transaction
        $transaction->delete();

        session()->flash('success', 'Transaksi berhasil dihapus dan stok telah dikurangi.');
        $this->resetPage();
    }

    public function render()
    {
        $recentTransactions = StockTransaction::with(['item', 'user'])
            ->where('type', 'in')
            ->when($this->search, fn ($q) =>
                $q->whereHas('item', fn ($q) =>
                    $q->where('name', 'like', "%{$this->search}%")
                )
            )
            ->latest()
            ->paginate(10);

        return view('livewire.manager.stock-in', compact('recentTransactions'))
            ->layout('layouts.app');
    }
}