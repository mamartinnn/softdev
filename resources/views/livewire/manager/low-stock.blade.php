php artisan make:livewire Kasir/OrderList
<div>
    <flux:heading size="xl" class="mb-2">⚠️ Stok Menipis</flux:heading>
    <p class="text-zinc-500 text-sm mb-6">Barang dengan stok di bawah {{ $threshold }} unit.</p>

    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-5">{{ session('success') }}</flux:callout>
    @endif

    {{-- Summary Banner --}}
    @if($items->isEmpty())
        <flux:callout variant="success" icon="check-circle">
            Semua stok barang dalam kondisi aman! 🎉
        </flux:callout>
    @else
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <flux:card class="p-4 border-l-4 border-red-400">
                <p class="text-xs text-zinc-500 uppercase tracking-wide">Habis (0 unit)</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ $outOfStock }}</p>
                <p class="text-xs text-zinc-400 mt-1">barang</p>
            </flux:card>
            <flux:card class="p-4 border-l-4 border-orange-400">
                <p class="text-xs text-zinc-500 uppercase tracking-wide">Kritis (1-{{ $threshold-1 }} unit)</p>
                <p class="text-3xl font-bold text-orange-500 mt-1">{{ $criticalCnt }}</p>
                <p class="text-xs text-zinc-400 mt-1">barang</p>
            </flux:card>
            <flux:card class="p-4 border-l-4 border-zinc-300">
                <p class="text-xs text-zinc-500 uppercase tracking-wide">Total Butuh Restock</p>
                <p class="text-3xl font-bold text-zinc-700 dark:text-zinc-300 mt-1">{{ $items->count() }}</p>
                <p class="text-xs text-zinc-400 mt-1">barang</p>
            </flux:card>
        </div>

        {{-- Item Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($items as $item)
            @php
                $isOut    = $item->stock === 0;
                $pct      = $item->stock > 0 ? min(100, ($item->stock / $threshold) * 100) : 0;
                $barColor = $isOut ? 'bg-red-500' : ($item->stock <= 2 ? 'bg-orange-500' : 'bg-yellow-400');
            @endphp
            <flux:card class="p-5 {{ $isOut ? 'border-red-200 dark:border-red-900' : 'border-orange-200 dark:border-orange-900' }} border">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1 min-w-0">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 class="w-10 h-10 rounded-lg object-cover mb-2" alt="{{ $item->name }}">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-2 text-lg">📦</div>
                        @endif
                        <p class="font-semibold text-sm truncate">{{ $item->name }}</p>
                        <p class="text-xs text-zinc-400">SKU: {{ $item->sku ?? '-' }}</p>
                    </div>
                    @if($isOut)
                        <flux:badge variant="red" size="sm">HABIS</flux:badge>
                    @else
                        <flux:badge variant="yellow" size="sm">{{ $item->stock }} {{ $item->unit }}</flux:badge>
                    @endif
                </div>

                {{-- Progress bar --}}
                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2 mb-3">
                    <div class="{{ $barColor }} h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>

                <div class="flex items-center justify-between text-xs text-zinc-500 mb-4">
                    <span>{{ $item->stock }} / {{ $threshold }} unit</span>
                    <span>Harga: Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </div>

                <flux:button
                    wire:click="openRestock({{ $item->id }})"
                    variant="{{ $isOut ? 'danger' : 'primary' }}"
                    size="sm"
                    class="w-full"
                    icon="arrow-down-tray"
                >
                    {{ $isOut ? 'Segera Restock' : 'Restock Sekarang' }}
                </flux:button>
            </flux:card>
            @endforeach
        </div>
    @endif

    {{-- Modal Restock --}}
    <flux:modal wire:model="showModal" class="max-w-sm w-full">
        <flux:heading>Restock: {{ $restockName }}</flux:heading>
        <p class="text-sm text-zinc-500 mt-1 mb-4">Masukkan jumlah unit yang akan ditambahkan ke stok.</p>

        <div class="space-y-4">
            <flux:input
                wire:model="restockQty"
                label="Jumlah Restock"
                type="number"
                min="1"
                placeholder="10"
            />
            @error('restockQty') <p class="text-red-500 text-xs -mt-3">{{ $message }}</p> @enderror

            <flux:input
                wire:model="restockNote"
                label="Keterangan (opsional)"
                placeholder="Contoh: Beli dari Toko Abadi"
            />
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <flux:button wire:click="$set('showModal', false)" variant="ghost">Batal</flux:button>
            <flux:button wire:click="restock" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Tambah Stok</span>
                <span wire:loading>Menyimpan...</span>
            </flux:button>
        </div>
    </flux:modal>
</div>