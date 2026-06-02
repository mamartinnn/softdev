<div>
    <div class="mb-8">
        <h1 class="text-2xl font-black" style="color: #f1f5f9;">⚠ Stok <span class="text-gradient">Menipis</span></h1>
        <p class="text-sm mt-1" style="color: #475569;">Barang dengan stok di bawah {{ $threshold }} unit</p>
    </div>

    @if(session('success'))
    <div class="alert-success mb-5 flex items-center gap-2">✅ {{ session('success') }}</div>
    @endif

    @if($items->isEmpty())
    <div class="card-dark p-10 text-center">
        <p class="text-5xl mb-4">🎉</p>
        <p class="font-bold text-lg" style="color: #34d399;">Semua stok dalam kondisi aman!</p>
        <p class="text-sm mt-1" style="color: #475569;">Tidak ada barang yang perlu di-restock saat ini.</p>
    </div>
    @else

    {{-- Summary Banner --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="card-stat p-5" style="border-left: 3px solid #ef4444;">
            <div class="w-10 h-10 rounded-xl icon-red flex items-center justify-center text-lg mb-3">🚫</div>
            <p class="text-2xl font-black" style="color: #f87171;">{{ $outOfStock }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Barang Habis (0 unit)</p>
        </div>
        <div class="card-stat p-5" style="border-left: 3px solid #f97316;">
            <div class="w-10 h-10 rounded-xl icon-yellow flex items-center justify-center text-lg mb-3">⚠</div>
            <p class="text-2xl font-black" style="color: #fde047;">{{ $criticalCnt }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Kritis (1–{{ $threshold - 1 }} unit)</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-blue flex items-center justify-center text-lg mb-3">📦</div>
            <p class="text-2xl font-black" style="color: #60a5fa;">{{ $items->count() }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Total Perlu Restock</p>
        </div>
    </div>

    {{-- Item Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($items as $item)
        @php
            $isOut    = $item->stock === 0;
            $pct      = $item->stock > 0 ? min(100, ($item->stock / $threshold) * 100) : 0;
            $barColor = $isOut ? '#ef4444' : ($item->stock <= 2 ? '#f97316' : '#eab308');
        @endphp
        <div class="card-dark p-5 rounded-2xl"
             style="border-color: {{ $isOut ? 'rgba(239,68,68,0.4)' : 'rgba(234,179,8,0.25)' }};">
            {{-- Header --}}
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"
                             class="w-10 h-10 rounded-xl object-cover mb-2"
                             style="border: 1px solid rgba(234,179,8,0.2);" alt="{{ $item->name }}">
                    @else
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg mb-2"
                             style="background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.15);">📦</div>
                    @endif
                    <p class="font-semibold text-sm truncate" style="color: #e2e8f0;">{{ $item->name }}</p>
                    <p class="text-xs" style="color: #475569;">SKU: {{ $item->sku ?? '-' }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold flex-shrink-0 ml-2"
                      style="{{ $isOut ? 'background:rgba(239,68,68,0.18);color:#f87171;' : 'background:rgba(234,179,8,0.18);color:#fde047;' }}">
                    {{ $isOut ? 'HABIS' : $item->stock . ' ' . $item->unit }}
                </span>
            </div>

            {{-- Progress bar --}}
            <div class="w-full rounded-full mb-3" style="background: rgba(255,255,255,0.06); height: 6px;">
                <div class="rounded-full" style="width: {{ $pct }}%; height: 6px; background: {{ $barColor }};"></div>
            </div>

            <div class="flex items-center justify-between text-xs mb-4" style="color: #475569;">
                <span>{{ $item->stock }} / {{ $threshold }} unit</span>
                <span>Rp {{ number_format($item->price, 0, ',', '.') }}</span>
            </div>

            <button wire:click="openRestock({{ $item->id }})"
                    class="w-full py-2.5 rounded-xl text-sm font-bold transition-all"
                    style="{{ $isOut ? 'background: rgba(239,68,68,0.18); color: #f87171; border: 1px solid rgba(239,68,68,0.35);' : 'background: linear-gradient(135deg,#d97706,#eab308); color: #0a0f1e;' }}">
                {{ $isOut ? '🚨 Segera Restock' : '↓ Restock Sekarang' }}
            </button>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Modal Restock --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(2,6,23,0.85); backdrop-filter: blur(8px);"
         wire:click.self="$set('showModal', false)">
        <div class="w-full max-w-sm rounded-2xl p-6"
             style="background: linear-gradient(135deg,#0f172a,#1e1b4b); border: 1px solid rgba(234,179,8,0.35);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-black" style="color: #fde047;">↓ Restock: {{ $restockName }}</h3>
                <button wire:click="$set('showModal', false)" style="color: #475569;">✕</button>
            </div>
            <p class="text-xs mb-5" style="color: #64748b;">Masukkan jumlah unit yang akan ditambahkan ke stok.</p>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Jumlah Restock *</label>
                    <input wire:model="restockQty" type="number" min="1" placeholder="10"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('restockQty') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Harga Beli per Unit *</label>
                    <input wire:model="restockPrice" type="number" step="0.01" min="0" placeholder="0"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('restockPrice') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Keterangan (opsional)</label>
                    <input wire:model="restockNote" type="text" placeholder="Beli dari Toko Abadi"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="restock" wire:loading.attr="disabled"
                        class="flex-1 py-2.5 rounded-xl font-bold text-sm btn-gold">
                    <span wire:loading.remove>Tambah Stok</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
                <button wire:click="$set('showModal', false)"
                        class="px-4 py-2.5 rounded-xl font-semibold text-sm"
                        style="background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.15);">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>