<div>
    <div class="mb-8">
        <h1 class="text-2xl font-black" style="color: #f1f5f9;">Barang <span class="text-gradient">Masuk</span></h1>
        <p class="text-sm mt-1" style="color: #475569;">Catat penerimaan stok barang baru</p>
    </div>

    @if(session('success'))
    <div class="alert-success mb-5">✅ {{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Form --}}
        <div class="card-dark p-6 lg:col-span-2">
            <h3 class="text-sm font-bold mb-5" style="color: #fde047;">📥 Form Barang Masuk</h3>
            <div class="space-y-4">

                {{-- Search Item --}}
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Cari Barang *</label>
                    <div class="relative">
                        <input wire:model.live.debounce="itemSearch" type="text"
                               placeholder="Ketik nama / SKU barang..."
                               class="w-full px-3 py-2.5 rounded-xl text-sm pr-8"
                               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                        @if($selectedItem)
                        <button wire:click="clearItem" class="absolute right-3 top-1/2 -translate-y-1/2 text-xs" style="color: #f87171;">✕</button>
                        @endif
                    </div>
                    @if(!empty($searchResults))
                    <div class="mt-1 rounded-xl overflow-hidden" style="border: 1px solid rgba(234,179,8,0.2); background: rgba(15,23,42,0.95);">
                        @foreach($searchResults as $r)
                        <button wire:click="selectItem({{ $r['id'] }})"
                                class="w-full text-left px-4 py-3 flex justify-between items-center text-sm transition-colors"
                                style="border-bottom: 1px solid rgba(234,179,8,0.06);"
                                onmouseover="this.style.background='rgba(234,179,8,0.06)'"
                                onmouseout="this.style.background='transparent'">
                            <div>
                                <p class="font-semibold" style="color: #e2e8f0;">{{ $r['name'] }}</p>
                                <p class="text-xs" style="color: #475569;">SKU: {{ $r['sku'] ?? '-' }} | Stok: {{ $r['stock'] }} {{ $r['unit'] }}</p>
                            </div>
                            <span class="text-xs font-bold" style="color: #34d399;">Rp {{ number_format($r['price'],0,',','.') }}</span>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                @if($selectedItem)
                <div class="p-3 rounded-xl" style="background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2);">
                    <p class="text-sm font-bold" style="color: #34d399;">✅ {{ $selectedItem['name'] }}</p>
                    <p class="text-xs mt-0.5" style="color: #475569;">Stok saat ini: {{ $selectedItem['stock'] }} {{ $selectedItem['unit'] }}</p>
                </div>
                @endif
                @error('itemId') <p class="text-xs" style="color: #f87171;">{{ $message }}</p> @enderror

                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Jumlah Masuk *</label>
                    <input wire:model="quantity" type="number" min="1"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('quantity') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Harga Beli per Unit</label>
                    <input wire:model="pricePerUnit" type="number" min="0"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                </div>

                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Nama Supplier</label>
                    <input wire:model="supplierName" type="text" placeholder="Opsional..."
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                </div>

                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Catatan</label>
                    <input wire:model="note" type="text" placeholder="Opsional..."
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                </div>

                <button wire:click="save" class="w-full py-3 rounded-xl font-bold text-sm btn-gold mt-2">
                    ✅ Simpan Barang Masuk
                </button>
            </div>
        </div>

        {{-- Riwayat --}}
        <div class="card-dark p-5 lg:col-span-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold" style="color: #e2e8f0;">🕐 Riwayat Barang Masuk</h3>
                <input wire:model.live.debounce="search" placeholder="🔍 Cari barang..."
                       class="px-3 py-1.5 text-xs rounded-xl"
                       style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.18); color: #e2e8f0; outline: none;" />
            </div>
            <div class="space-y-1">
                @forelse($recentTransactions as $tx)
                <div class="flex items-center gap-3 py-3 px-2 rounded-lg"
                     style="border-bottom: 1px solid rgba(234,179,8,0.06);">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm font-black"
                         style="background: rgba(16,185,129,0.15); color: #34d399;">↓</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" style="color: #e2e8f0;">{{ $tx->item->name ?? '-' }}</p>
                        <p class="text-xs" style="color: #475569;">
                            {{ $tx->user->name ?? '-' }} · {{ $tx->created_at->diffForHumans() }}
                            @if($tx->note) · {{ $tx->note }} @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black" style="color: #34d399;">+{{ $tx->quantity }}</span>
                        <p class="text-xs" style="color: #475569;">Rp {{ number_format($tx->price_per_unit,0,',','.') }}/unit</p>
                    </div>
                </div>
                @empty
                <p class="text-center py-10 text-sm" style="color: #334155;">Belum ada riwayat barang masuk.</p>
                @endforelse
            </div>
            @if($recentTransactions->hasPages())
            <div class="mt-4" style="border-top: 1px solid rgba(234,179,8,0.1); padding-top: 1rem;">
                {{ $recentTransactions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>