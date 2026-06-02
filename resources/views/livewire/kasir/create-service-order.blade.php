{{-- resources/views/livewire/kasir/create-service-order.blade.php --}}
<div>
    <div class="flex items-center justify-center mb-8">
        <div class="text-center">
            <h1 class="text-2xl font-black" style="color: #000000;">Buat Order <span class="text-gradient">Servis Baru</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">Catat kendaraan masuk dan barang yang digunakan</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success mb-4">✅ {{ session('success') }}
            @if($savedOrderId)
                — <a href="{{ route('kasir.orders.receipt', $savedOrderId) }}" target="_blank" class="underline font-semibold">Cetak Struk</a>
            @endif
        </div>
    @endif
    @if(session('error'))
        <div class="alert-danger mb-4">❌ {{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Data & Barang --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Data Pelanggan --}}
            <div class="card-dark p-6 rounded-2xl">
                <h3 class="text-base font-black mb-4" style="color: #000000;">👤 Data Pelanggan & Kendaraan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Nama Pelanggan *</label>
                        <input wire:model="customerName" type="text" placeholder="Budi Santoso"
                               class="w-full px-3 py-2.5 rounded-xl text-sm"
                               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    </div>
                    <div>
                        <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Nomor Plat *</label>
                        <input wire:model="plateNumber" type="text" placeholder="B 1234 ABC"
                               class="w-full px-3 py-2.5 rounded-xl text-sm"
                               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Jenis Kendaraan *</label>
                        <input wire:model="vehicleType" type="text" placeholder="Honda Beat 2019"
                               class="w-full px-3 py-2.5 rounded-xl text-sm"
                               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Keluhan</label>
                        <textarea wire:model="complaint" rows="2" placeholder="Deskripsi masalah kendaraan..."
                                  class="w-full px-3 py-2.5 rounded-xl text-sm"
                                  style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none; resize: vertical;"></textarea>
                    </div>
                </div>
            </div>

            {{-- Pencarian Barang --}}
            <div class="card-dark p-6 rounded-2xl">
                <h3 class="text-base font-black mb-4" style="color: #000000;">📦 Barang yang Digunakan</h3>
                <div class="relative mb-4">
                    <input wire:model.live.debounce="itemSearch" type="text" placeholder="Cari nama / SKU barang..."
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @if(!empty($searchResults))
                    <div class="absolute z-10 top-full left-0 right-0 rounded-xl overflow-hidden shadow-lg mt-1 max-h-60 overflow-y-auto"
                         style="background: rgba(15,23,42,0.95); border: 1px solid rgba(234,179,8,0.2);">
                        @foreach($searchResults as $result)
                        <button wire:click="addItem({{ $result['id'] }})" class="w-full text-left px-4 py-3 hover:bg-opacity-50 transition-colors flex justify-between items-center"
                                style="border-bottom: 1px solid rgba(234,179,8,0.08); color: #e2e8f0;">
                            <div>
                                <p class="font-medium text-sm">{{ $result['name'] }}</p>
                                <p class="text-xs" style="color: #94a3b8;">SKU: {{ $result['sku'] ?? '-' }} | Stok: {{ $result['stock'] }} {{ $result['unit'] }}</p>
                            </div>
                            <span class="text-sm font-semibold" style="color: #fde047;">Rp {{ number_format($result['price'], 0, ',', '.') }}</span>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Daftar Barang Dipilih --}}
                @if(!empty($selectedItems))
                <div class="space-y-2">
                    @foreach($selectedItems as $index => $si)
                    <div class="flex items-center gap-3 p-3 rounded-lg transition-all"
                         style="background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.15);">
                        <div class="flex-1">
                            <p class="font-medium text-sm" style="color: #e2e8f0;">{{ $si['item_name'] }}</p>
                            <p class="text-xs" style="color: #94a3b8;">@ Rp {{ number_format($si['price'], 0, ',', '.') }} / {{ $si['unit'] }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input
                                type="number"
                                wire:model.live="selectedItems.{{ $index }}.qty"
                                min="1"
                                max="{{ $si['max_stock'] }}"
                                class="w-16 text-center rounded-lg py-1.5 px-2 text-sm"
                                style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #fde047; font-weight: bold;"
                            />
                            <span class="text-sm font-semibold w-28 text-right" style="color: #34d399;">
                                Rp {{ number_format($si['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>
                        <button wire:click="removeItem({{ $index }})" 
                                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold"
                                style="background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.2);">✕</button>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center py-8 text-sm" style="color: #475569;">Belum ada barang ditambahkan.</p>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Summary & Submit --}}
        <div class="space-y-4">
            <div class="card-dark p-6 rounded-2xl sticky top-6">
                <h3 class="text-base font-black mb-4" style="color: #000000;">💰 Ringkasan Biaya</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm" style="border-bottom: 1px solid rgba(234,179,8,0.1); padding-bottom: 0.75rem;">
                        <span style="color: #94a3b8;">Total Barang</span>
                        <span class="font-medium" style="color: #e2e8f0;">Rp {{ number_format($this->totalItemsCost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span style="color: #94a3b8;">Biaya Jasa</span>
                        <input
                            type="number"
                            wire:model.live="serviceFee"
                            min="0"
                            class="w-32 text-right rounded-lg py-1.5 px-2 text-sm"
                            style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #fde047; font-weight: bold;"
                        />
                    </div>
                    <div style="border-top: 1px solid rgba(234,179,8,0.15); padding-top: 0.75rem; margin-top: 0.75rem;"></div>
                    <div class="flex justify-between font-black text-lg">
                        <span style="color: #000000;">TOTAL</span>
                        <span style="color: #34d399;">Rp {{ number_format($this->grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                <button wire:click="saveOrder"
                        class="w-full mt-6 py-3 px-4 rounded-xl font-bold text-sm btn-gold transition-all"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove>💾 Simpan & Selesai</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>
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