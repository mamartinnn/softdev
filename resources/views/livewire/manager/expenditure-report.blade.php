<div>
    <div class="flex items-center justify-center mb-8">
        <div class="text-center">
            <h1 class="text-2xl font-black" style="color: #000000;">Laporan <span class="text-gradient">Pengeluaran</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">Catatan pembelian barang dari supplier</p>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <flux:select wire:model.live="filterMonth" class="w-40">
            @foreach($months as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </flux:select>
        <flux:select wire:model.live="filterYear" class="w-40">
            @foreach($years as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </flux:select>
        <flux:input wire:model.live.debounce="search" placeholder="Cari nama / SKU barang..." icon="magnifying-glass" class="flex-1 min-w-60" />
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-blue flex items-center justify-center text-lg mb-3">💰</div>
            <p class="text-xl font-black" style="color: #60a5fa;">Rp {{ number_format($totalCost, 0, ',', '.') }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Total Pengeluaran</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-green flex items-center justify-center text-lg mb-3">📦</div>
            <p class="text-xl font-black" style="color: #34d399;">{{ number_format($totalQuantity, 0, ',', '.') }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Total Unit Dibeli</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-yellow flex items-center justify-center text-lg mb-3">📊</div>
            <p class="text-xl font-black" style="color: #fde047;">
                Rp {{ $totalQuantity > 0 ? number_format($totalCost / $totalQuantity, 0, ',', '.') : '0' }}
            </p>
            <p class="text-xs mt-1" style="color: #475569;">Rata-rata per Item</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-purple flex items-center justify-center text-lg mb-3">📋</div>
            <p class="text-3xl font-black" style="color: #c4b5fd;">{{ $transactions->count() }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Total Transaksi</p>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="card-dark overflow-hidden">
        <div class="px-5 py-4" style="border-bottom: 1px solid rgba(234,179,8,0.12);">
            <h3 class="text-sm font-bold" style="color: #000000;">📊 Detail Pengeluaran Bulan Ini</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background: rgba(15,23,42,0.6);">
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Tanggal</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Nama Barang</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">SKU</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Qty</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Harga/Unit</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Total</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Keterangan</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    @php
                        $subtotal = $tx->quantity * $tx->price_per_unit;
                    @endphp
                    <tr class="table-dark-row" style="border-bottom: 1px solid rgba(234,179,8,0.05);">
                        <td class="px-5 py-3 text-xs" style="color: #000000;">
                            {{ $tx->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-3 font-semibold" style="color: #000000;">{{ $tx->item->name ?? '-' }}</td>
                        <td class="px-5 py-3 font-mono text-xs" style="color: #94a3b8;">
                            {{ $tx->item->sku ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-right" style="color: #60a5fa;">
                            {{ $tx->quantity }} {{ $tx->item->unit ?? 'pcs' }}
                        </td>
                        <td class="px-5 py-3 text-right" style="color: #fde047;">
                            Rp {{ number_format($tx->price_per_unit, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-right font-bold" style="color: #34d399;">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-xs" style="color: #64748b;">
                            {{ $tx->note ?? '-' }}
                        </td>
                        <td class="px-5 py-3" style="color: #94a3b8;">
                            {{ $tx->user->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center" style="color: #334155;">Tidak ada pengeluaran untuk periode ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-5 py-4" style="border-top: 1px solid rgba(234,179,8,0.1);">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
