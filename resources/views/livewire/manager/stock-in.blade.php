{{-- ============================================================ --}}
{{-- LOKASI: resources/views/livewire/manager/stock-in.blade.php --}}
{{-- ============================================================ --}}
<div>
    <flux:heading size="xl" class="mb-6">Barang Masuk dari Supplier</flux:heading>

    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-5">{{ session('success') }}</flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ===== FORM PANEL (kiri) ===== --}}
        <flux:card class="p-6 lg:col-span-2">
            <flux:heading size="sm" class="mb-5">📦 Form Penerimaan Barang</flux:heading>

            <div class="space-y-4">
                {{-- Pencarian Barang --}}
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <flux:input
                            wire:model.live.debounce.300ms="itemSearch"
                            placeholder="Ketik nama atau SKU barang..."
                            icon="magnifying-glass"
                        />
                        @if(!empty($searchResults))
                        <div class="absolute z-20 top-full left-0 right-0 mt-1 bg-white dark:bg-zinc-800
                                    border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-xl
                                    max-h-56 overflow-y-auto">
                            @foreach($searchResults as $r)
                            <button
                                wire:click="selectItem({{ $r['id'] }})"
                                class="w-full text-left px-4 py-3 flex justify-between items-center
                                       hover:bg-orange-50 dark:hover:bg-zinc-700 transition-colors
                                       border-b border-zinc-100 dark:border-zinc-700 last:border-0"
                            >
                                <div>
                                    <p class="font-semibold text-sm">{{ $r['name'] }}</p>
                                    <p class="text-xs text-zinc-400">
                                        SKU: {{ $r['sku'] ?? '-' }} &middot; Stok: {{ $r['stock'] }} {{ $r['unit'] }}
                                    </p>
                                </div>
                                <flux:badge variant="{{ $r['stock'] < 5 ? 'red' : 'green' }}" size="sm">
                                    {{ $r['stock'] }}
                                </flux:badge>
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @error('itemId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Selected Item Preview --}}
                @if($selectedItem)
                <div class="flex items-start justify-between bg-orange-50 dark:bg-orange-900/20
                            border border-orange-200 dark:border-orange-800 rounded-lg p-3">
                    <div>
                        <p class="font-semibold text-sm text-orange-900 dark:text-orange-300">
                            {{ $selectedItem['name'] }}
                        </p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 mt-0.5">
                            Stok sekarang: <strong>{{ $selectedItem['stock'] }} {{ $selectedItem['unit'] }}</strong>
                        </p>
                    </div>
                    <button wire:click="clearItem" class="text-zinc-400 hover:text-zinc-600 mt-0.5">
                        <flux:icon.x-mark class="size-4"/>
                    </button>
                </div>
                @endif

                {{-- Jumlah --}}
                <flux:input
                    wire:model.live="quantity"
                    label="Jumlah Masuk"
                    type="number"
                    min="1"
                    placeholder="1"
                />
                @error('quantity') <p class="text-red-500 text-xs -mt-3">{{ $message }}</p> @enderror

                {{-- Harga Beli --}}
                <flux:input
                    wire:model.live="pricePerUnit"
                    label="Harga Beli per Unit (Rp)"
                    type="number"
                    min="0"
                    placeholder="0"
                />

                {{-- Supplier --}}
                <flux:input
                    wire:model="supplierName"
                    label="Nama Supplier"
                    placeholder="CV Maju Jaya (opsional)"
                />

                {{-- Keterangan --}}
                <flux:textarea
                    wire:model="note"
                    label="Keterangan Tambahan"
                    rows="2"
                    placeholder="Catatan opsional..."
                />

                {{-- Preview kalkulasi --}}
                @if($selectedItem && $quantity > 0)
                <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3 text-sm space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Stok setelah masuk</span>
                        <span class="font-bold text-green-600">
                            {{ ($selectedItem['stock'] ?? 0) + $quantity }} {{ $selectedItem['unit'] }}
                        </span>
                    </div>
                    @if($pricePerUnit > 0)
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Total nilai</span>
                        <span class="font-bold">
                            Rp {{ number_format($pricePerUnit * $quantity, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>
                @endif

                <flux:button
                    wire:click="save"
                    variant="primary"
                    class="w-full"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>⬇️ Konfirmasi Barang Masuk</span>
                    <span wire:loading>Menyimpan...</span>
                </flux:button>
            </div>
        </flux:card>

        {{-- ===== RIWAYAT (kanan) ===== --}}
        <div class="lg:col-span-3">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="sm">Riwayat Barang Masuk</flux:heading>
                <flux:input
                    wire:model.live.debounce="search"
                    placeholder="Cari barang..."
                    icon="magnifying-glass"
                    class="w-48"
                />
            </div>

            <flux:table>
                <flux:columns>
                    <flux:column>Barang</flux:column>
                    <flux:column>Jml</flux:column>
                    <flux:column>Harga/Unit</flux:column>
                    <flux:column>Oleh</flux:column>
                    <flux:column>Keterangan</flux:column>
                    <flux:column>Tanggal</flux:column>
                </flux:columns>
                <flux:rows>
                    @forelse($recentTransactions as $tx)
                    <flux:row>
                        <flux:cell>
                            <p class="font-medium text-sm">{{ $tx->item->name ?? '-' }}</p>
                            <p class="text-xs text-zinc-400">{{ $tx->item->sku ?? '' }}</p>
                        </flux:cell>
                        <flux:cell>
                            <flux:badge variant="green" size="sm">+{{ $tx->quantity }}</flux:badge>
                        </flux:cell>
                        <flux:cell class="text-sm">
                            Rp {{ number_format($tx->price_per_unit, 0, ',', '.') }}
                        </flux:cell>
                        <flux:cell class="text-sm">{{ $tx->user->name ?? '-' }}</flux:cell>
                        <flux:cell class="text-xs text-zinc-500 max-w-[140px] truncate" :title="$tx->note">
                            {{ $tx->note ?? '-' }}
                        </flux:cell>
                        <flux:cell class="text-xs text-zinc-400 whitespace-nowrap">
                            {{ $tx->created_at->format('d/m/Y H:i') }}
                        </flux:cell>
                    </flux:row>
                    @empty
                    <flux:row>
                        <flux:cell colspan="6" class="text-center text-zinc-400 py-10">
                            Belum ada riwayat barang masuk.
                        </flux:cell>
                    </flux:row>
                    @endforelse
                </flux:rows>
            </flux:table>
            <div class="mt-4">{{ $recentTransactions->links() }}</div>
        </div>
    </div>
</div>