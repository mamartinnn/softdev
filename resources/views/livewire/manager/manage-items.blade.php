
<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">Kelola Barang</flux:heading>
        <flux:button wire:click="openCreate" variant="primary" icon="plus">Tambah Barang</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-4">{{ session('success') }}</flux:callout>
    @endif

    <flux:input wire:model.live.debounce="search" placeholder="Cari nama / SKU barang..." icon="magnifying-glass" class="mb-4 max-w-xs" />

    <flux:table>
        <flux:columns>
            <flux:column>Gambar</flux:column>
            <flux:column>Nama Barang</flux:column>
            <flux:column>SKU</flux:column>
            <flux:column>Harga</flux:column>
            <flux:column>Stok</flux:column>
            <flux:column>Satuan</flux:column>
            <flux:column>Aksi</flux:column>
        </flux:columns>
        <flux:rows>
            @forelse($items as $item)
            <flux:row>
                <flux:cell>
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="w-12 h-12 rounded-lg object-cover" alt="{{ $item->name }}">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-400 text-xs">N/A</div>
                    @endif
                </flux:cell>
                <flux:cell class="font-medium">{{ $item->name }}</flux:cell>
                <flux:cell class="font-mono text-sm text-zinc-500">{{ $item->sku ?? '-' }}</flux:cell>
                <flux:cell>Rp {{ number_format($item->price, 0, ',', '.') }}</flux:cell>
                <flux:cell>
                    <flux:badge variant="{{ $item->stock < 5 ? 'red' : 'green' }}" size="sm">
                        {{ $item->stock }}
                    </flux:badge>
                </flux:cell>
                <flux:cell>{{ $item->unit }}</flux:cell>
                <flux:cell>
                    <div class="flex gap-1">
                        <flux:button wire:click="openEdit({{ $item->id }})" size="sm" icon="pencil" />
                        <flux:button wire:click="openStock({{ $item->id }}, 'in')" size="sm" variant="ghost" icon="arrow-down-tray" title="Barang Masuk" />
                        <flux:button wire:click="openStock({{ $item->id }}, 'out')" size="sm" variant="ghost" icon="arrow-up-tray" title="Barang Keluar" />
                        <flux:button wire:click="delete({{ $item->id }})" size="sm" variant="danger" icon="trash" wire:confirm="Nonaktifkan barang ini?" />
                    </div>
                </flux:cell>
            </flux:row>
            @empty
            <flux:row>
                <flux:cell colspan="7" class="text-center text-zinc-400 py-8">Belum ada barang.</flux:cell>
            </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>
    <div class="mt-4">{{ $items->links() }}</div>

    {{-- Modal Item --}}
    <flux:modal wire:model="showItemModal" class="max-w-lg w-full">
        <flux:heading>{{ $editingId ? 'Edit Barang' : 'Tambah Barang Baru' }}</flux:heading>
        <div class="mt-4 space-y-4">
            <flux:input wire:model="name" label="Nama Barang" placeholder="Contoh: Oli Mesin 10W-40" />
            <flux:input wire:model="sku" label="SKU / Kode Barang" placeholder="Opsional" />
            <div class="grid grid-cols-2 gap-3">
                <flux:input wire:model="price" label="Harga (Rp)" type="number" min="0" />
                <flux:select wire:model="unit" label="Satuan">
                    <option value="pcs">Pcs</option>
                    <option value="liter">Liter</option>
                    <option value="set">Set</option>
                    <option value="kg">Kg</option>
                    <option value="meter">Meter</option>
                    <option value="botol">Botol</option>
                </flux:select>
            </div>
            <flux:textarea wire:model="description" label="Deskripsi" rows="2" placeholder="Opsional" />
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Gambar Barang</label>
                <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" />
                @if($existingImage)
                    <img src="{{ asset('storage/' . $existingImage) }}" class="mt-2 w-20 h-20 rounded-lg object-cover">
                @endif
            </div>
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <flux:button wire:click="$set('showItemModal', false)" variant="ghost">Batal</flux:button>
            <flux:button wire:click="saveItem" variant="primary">Simpan</flux:button>
        </div>
    </flux:modal>

    {{-- Modal Stok --}}
    <flux:modal wire:model="showStockModal" class="max-w-sm w-full">
        <flux:heading>{{ $stockType === 'in' ? '⬇️ Barang Masuk' : '⬆️ Barang Keluar' }}</flux:heading>
        <div class="mt-4 space-y-4">
            <flux:input wire:model="stockQty" label="Jumlah" type="number" min="1" />
            <flux:input wire:model="stockNote" label="Keterangan" placeholder="Opsional, misal: Pembelian dari supplier X" />
            @error('stockQty') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
        <div class="flex justify-end gap-3 mt-6">
            <flux:button wire:click="$set('showStockModal', false)" variant="ghost">Batal</flux:button>
            <flux:button wire:click="saveStock" variant="{{ $stockType === 'in' ? 'primary' : 'danger' }}">
                {{ $stockType === 'in' ? 'Tambah Stok' : 'Kurangi Stok' }}
            </flux:button>
        </div>
    </flux:modal>
</div>
