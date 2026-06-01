<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black" style="color: #f1f5f9;">Kelola <span class="text-gradient">Barang</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">Manajemen inventaris & harga</p>
        </div>
        <button wire:click="openCreate"
                class="btn-gold px-4 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
            ➕ Tambah Barang
        </button>
    </div>

    @if(session('success'))
    <div class="alert-success mb-4">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-danger mb-4">❌ {{ session('error') }}</div>
    @endif

    <div class="mb-5">
        <input wire:model.live.debounce="search" placeholder="🔍 Cari nama / SKU barang..."
               class="px-4 py-2.5 text-sm rounded-xl w-full max-w-sm"
               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
    </div>

    <div class="card-dark overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background: rgba(15,23,42,0.6); border-bottom: 1px solid rgba(234,179,8,0.15);">
                        <th class="text-left px-4 py-3.5 text-xs font-bold uppercase" style="color: #eab308;">Gambar</th>
                        <th class="text-left px-4 py-3.5 text-xs font-bold uppercase" style="color: #eab308;">Nama Barang</th>
                        <th class="text-left px-4 py-3.5 text-xs font-bold uppercase" style="color: #eab308;">SKU</th>
                        <th class="text-right px-4 py-3.5 text-xs font-bold uppercase" style="color: #eab308;">Harga</th>
                        <th class="text-center px-4 py-3.5 text-xs font-bold uppercase" style="color: #eab308;">Stok</th>
                        <th class="text-left px-4 py-3.5 text-xs font-bold uppercase" style="color: #eab308;">Satuan</th>
                        <th class="px-4 py-3.5"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr class="table-dark-row" style="border-bottom: 1px solid rgba(234,179,8,0.05);">
                        <td class="px-4 py-3">
                            @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 class="w-12 h-12 rounded-xl object-cover" style="border: 1px solid rgba(234,179,8,0.2);" />
                            @else
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg"
                                 style="background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.12);">📦</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold" style="color: #e2e8f0;">{{ $item->name }}</p>
                            @if($item->description)
                            <p class="text-xs truncate max-w-xs" style="color: #475569;">{{ $item->description }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-mono text-xs" style="color: #64748b;">{{ $item->sku ?? '-' }}</td>
                        <td class="px-4 py-3 text-right font-bold" style="color: #34d399;">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold"
                                  style="{{ $item->stock == 0 ? 'background:rgba(239,68,68,0.18);color:#f87171;' : ($item->stock < 5 ? 'background:rgba(234,179,8,0.18);color:#fde047;' : 'background:rgba(16,185,129,0.18);color:#34d399;') }}">
                                {{ $item->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3" style="color: #94a3b8;">{{ $item->unit }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5 justify-end">
                                <button wire:click="openEdit({{ $item->id }})"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold"
                                        style="background: rgba(59,130,246,0.12); color: #93c5fd; border: 1px solid rgba(59,130,246,0.2);">✏</button>
                                <button wire:click="openStock({{ $item->id }}, 'in')"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold"
                                        style="background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.2);"
                                        title="Barang Masuk">↓ In</button>
                                <button wire:click="openStock({{ $item->id }}, 'out')"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold"
                                        style="background: rgba(234,179,8,0.12); color: #fde047; border: 1px solid rgba(234,179,8,0.2);"
                                        title="Barang Keluar">↑ Out</button>
                                <button wire:click="delete({{ $item->id }})"
                                        wire:confirm="Nonaktifkan barang ini?"
                                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold"
                                        style="background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.2);">🗑</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center" style="color: #334155;">Belum ada barang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
        <div class="px-5 py-4" style="border-top: 1px solid rgba(234,179,8,0.1);">
            {{ $items->links() }}
        </div>
        @endif
    </div>

    {{-- Modal Item --}}
    @if($showItemModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
         style="background: rgba(2,6,23,0.85); backdrop-filter: blur(8px);"
         wire:click.self="$set('showItemModal', false)">
        <div class="w-full max-w-md rounded-2xl p-6 my-4"
             style="background: linear-gradient(135deg,#0f172a,#1e1b4b); border: 1px solid rgba(234,179,8,0.3);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black" style="color: #fde047;">
                    {{ $editingId ? '✏ Edit Barang' : '➕ Tambah Barang Baru' }}
                </h3>
                <button wire:click="$set('showItemModal', false)" style="color: #475569;">✕</button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Nama Barang *</label>
                    <input wire:model="name" type="text" placeholder="Oli Mesin 1L"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('name') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">SKU</label>
                        <input wire:model="sku" type="text" placeholder="OLI-001"
                               class="w-full px-3 py-2.5 rounded-xl text-sm"
                               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                        @error('sku') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Satuan *</label>
                        <select wire:model="unit"
                                class="w-full px-3 py-2.5 rounded-xl text-sm"
                                style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0;">
                            <option value="pcs">pcs</option>
                            <option value="liter">liter</option>
                            <option value="kg">kg</option>
                            <option value="gram">gram</option>
                            <option value="set">set</option>
                            <option value="roll">roll</option>
                            <option value="botol">botol</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Harga Jual *</label>
                    <input wire:model="price" type="number" min="0" placeholder="25000"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('price') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Deskripsi</label>
                    <textarea wire:model="description" rows="2" placeholder="Opsional..."
                              class="w-full px-3 py-2.5 rounded-xl text-sm resize-none"
                              style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;"></textarea>
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Foto Barang</label>
                    <input wire:model="image" type="file" accept="image/*"
                           class="w-full text-xs py-2 rounded-xl"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #94a3b8;" />
                    @if($existingImage)
                    <p class="text-xs mt-1" style="color: #475569;">Foto saat ini tersedia. Upload baru untuk mengganti.</p>
                    @endif
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="saveItem" class="flex-1 py-2.5 rounded-xl font-bold text-sm btn-gold">
                    {{ $editingId ? 'Simpan Perubahan' : 'Tambah Barang' }}
                </button>
                <button wire:click="$set('showItemModal', false)"
                        class="px-5 py-2.5 rounded-xl font-semibold text-sm"
                        style="background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.15);">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Stok --}}
    @if($showStockModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(2,6,23,0.85); backdrop-filter: blur(8px);"
         wire:click.self="$set('showStockModal', false)">
        <div class="w-full max-w-sm rounded-2xl p-6"
             style="background: linear-gradient(135deg,#0f172a,#1e1b4b); border: 1px solid rgba(234,179,8,0.3);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-black" style="color: #fde047;">
                    {{ $stockType === 'in' ? '↓ Barang Masuk' : '↑ Barang Keluar' }}
                </h3>
                <button wire:click="$set('showStockModal', false)" style="color: #475569;">✕</button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Jumlah *</label>
                    <input wire:model="stockQty" type="number" min="1"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('stockQty') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Catatan</label>
                    <input wire:model="stockNote" type="text" placeholder="Opsional..."
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button wire:click="saveStock" class="flex-1 py-2.5 rounded-xl font-bold text-sm btn-gold">
                    Simpan
                </button>
                <button wire:click="$set('showStockModal', false)"
                        class="px-4 py-2.5 rounded-xl font-semibold text-sm"
                        style="background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.15);">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>