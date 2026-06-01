{{-- resources/views/livewire/kasir/create-service-order.blade.php --}}
<div>
    <flux:heading size="xl" class="mb-6">Buat Order Servis Baru</flux:heading>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4" icon="check-circle">
            {{ session('success') }}
            @if($savedOrderId)
                — <a href="{{ route('kasir.orders.receipt', $savedOrderId) }}" target="_blank" class="underline font-semibold">Cetak Struk</a>
            @endif
        </flux:callout>
    @endif
    @if(session('error'))
        <flux:callout variant="danger" class="mb-4">{{ session('error') }}</flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Data & Barang --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Data Pelanggan --}}
            <flux:card class="p-5">
                <flux:heading size="sm" class="mb-4">Data Pelanggan & Kendaraan</flux:heading>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <flux:input wire:model="customerName" label="Nama Pelanggan" placeholder="Budi Santoso" />
                    <flux:input wire:model="plateNumber" label="Nomor Plat" placeholder="B 1234 ABC" />
                    <flux:input wire:model="vehicleType" label="Jenis Kendaraan" placeholder="Honda Beat 2019" class="sm:col-span-2"/>
                    <flux:textarea wire:model="complaint" label="Keluhan" rows="2" placeholder="Deskripsi masalah kendaraan..." class="sm:col-span-2"/>
                </div>
            </flux:card>

            {{-- Pencarian Barang --}}
            <flux:card class="p-5">
                <flux:heading size="sm" class="mb-4">Barang yang Digunakan</flux:heading>
                <div class="relative mb-4">
                    <flux:input wire:model.live.debounce="itemSearch" placeholder="Ketik nama / SKU barang..." icon="magnifying-glass" />
                    @if(!empty($searchResults))
                    <div class="absolute z-10 top-full left-0 right-0 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                        @foreach($searchResults as $result)
                        <button wire:click="addItem({{ $result['id'] }})" class="w-full text-left px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700 flex justify-between items-center border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                            <div>
                                <p class="font-medium text-sm">{{ $result['name'] }}</p>
                                <p class="text-xs text-zinc-400">SKU: {{ $result['sku'] ?? '-' }} | Stok: {{ $result['stock'] }} {{ $result['unit'] }}</p>
                            </div>
                            <span class="text-sm font-semibold text-orange-600">Rp {{ number_format($result['price'], 0, ',', '.') }}</span>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Daftar Barang Dipilih --}}
                @if(!empty($selectedItems))
                <div class="space-y-2">
                    @foreach($selectedItems as $index => $si)
                    <div class="flex items-center gap-3 p-3 bg-zinc-50 dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-700">
                        <div class="flex-1">
                            <p class="font-medium text-sm">{{ $si['item_name'] }}</p>
                            <p class="text-xs text-zinc-500">@ Rp {{ number_format($si['price'], 0, ',', '.') }} / {{ $si['unit'] }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                type="number"
                                wire:model.live="selectedItems.{{ $index }}.qty"
                                min="1"
                                max="{{ $si['max_stock'] }}"
                                class="w-16 text-center border border-zinc-300 dark:border-zinc-600 rounded-md py-1 text-sm bg-white dark:bg-zinc-800"
                            />
                            <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 w-28 text-right">
                                Rp {{ number_format($si['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>
                        <flux:button wire:click="removeItem({{ $index }})" size="sm" variant="ghost" icon="x-mark" />
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-zinc-400 py-6 text-sm">Belum ada barang ditambahkan.</p>
                @endif
            </flux:card>
        </div>

        {{-- Kolom Kanan: Summary & Submit --}}
        <div class="space-y-4">
            <flux:card class="p-5 sticky top-6">
                <flux:heading size="sm" class="mb-4">Ringkasan Biaya</flux:heading>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500">Total Barang</span>
                        <span class="font-medium">Rp {{ number_format($this->totalItemsCost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-zinc-500">Biaya Jasa</span>
                        <input
                            type="number"
                            wire:model.live="serviceFee"
                            min="0"
                            class="w-32 text-right border border-zinc-300 dark:border-zinc-600 rounded-md py-1 px-2 text-sm bg-white dark:bg-zinc-800"
                        />
                    </div>
                    <flux:separator />
                    <div class="flex justify-between font-bold text-base">
                        <span>TOTAL</span>
                        <span class="text-orange-600">Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                <flux:button wire:click="saveOrder" variant="primary" class="w-full mt-5" wire:loading.attr="disabled">
                    <span wire:loading.remove>💾 Simpan & Selesai</span>
                    <span wire:loading>Menyimpan...</span>
                </flux:button>
            </flux:card>
        </div>
    </div>
</div>