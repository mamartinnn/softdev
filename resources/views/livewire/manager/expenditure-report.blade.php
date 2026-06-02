<div>
    <div class="flex items-center justify-center mb-8">
        <div class="text-center">
            <h1 class="text-2xl font-black" style="color: #f1f5f9;">Laporan <span class="text-gradient">Pengeluaran</span></h1>
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="card-stat p-5" style="border-left: 3px solid #3b82f6;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: #475569;">Total Pengeluaran</p>
            <p class="text-2xl font-black" style="color: #60a5fa;">Rp {{ number_format($totalCost, 0, ',', '.') }}</p>
        </div>
        <div class="card-stat p-5" style="border-left: 3px solid #10b981;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: #475569;">Total Unit Dibeli</p>
            <p class="text-2xl font-black" style="color: #34d399;">{{ number_format($totalQuantity, 0, ',', '.') }}</p>
        </div>
        <div class="card-stat p-5" style="border-left: 3px solid #f59e0b;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: #475569;">Rata-rata per Item</p>
            <p class="text-2xl font-black" style="color: #fbbf24;">
                Rp {{ $totalQuantity > 0 ? number_format($totalCost / $totalQuantity, 0, ',', '.') : '0' }}
            </p>
        </div>
    </div>

    {{-- Transactions Table --}}
    <flux:card class="p-5">
        <flux:heading size="sm" class="mb-4">📊 Detail Pengeluaran</flux:heading>
        
        @if($transactions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(234,179,8,0.15);">
                            <th class="text-left py-3 px-3" style="color: #94a3b8;">Tanggal</th>
                            <th class="text-left py-3 px-3" style="color: #94a3b8;">Nama Barang</th>
                            <th class="text-left py-3 px-3" style="color: #94a3b8;">SKU</th>
                            <th class="text-right py-3 px-3" style="color: #94a3b8;">Qty</th>
                            <th class="text-right py-3 px-3" style="color: #94a3b8;">Harga/Unit</th>
                            <th class="text-right py-3 px-3" style="color: #94a3b8;">Total</th>
                            <th class="text-left py-3 px-3" style="color: #94a3b8;">Keterangan</th>
                            <th class="text-left py-3 px-3" style="color: #94a3b8;">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                        @php
                            $subtotal = $tx->quantity * $tx->price_per_unit;
                        @endphp
                        <tr style="border-bottom: 1px solid rgba(234,179,8,0.07);">
                            <td class="py-3 px-3" style="color: #e2e8f0;">
                                {{ $tx->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-3">
                                <p class="font-medium" style="color: #e2e8f0;">{{ $tx->item->name ?? '-' }}</p>
                            </td>
                            <td class="py-3 px-3" style="color: #94a3b8;">
                                {{ $tx->item->sku ?? '-' }}
                            </td>
                            <td class="py-3 px-3 text-right font-semibold" style="color: #e2e8f0;">
                                {{ $tx->quantity }} {{ $tx->item->unit ?? 'pcs' }}
                            </td>
                            <td class="py-3 px-3 text-right" style="color: #94a3b8;">
                                Rp {{ number_format($tx->price_per_unit, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-right font-bold" style="color: #fde047;">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-3 text-xs" style="color: #64748b;">
                                {{ $tx->note ?? '-' }}
                            </td>
                            <td class="py-3 px-3" style="color: #94a3b8;">
                                {{ $tx->user->name ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-4xl mb-2">📭</p>
                <p class="text-sm" style="color: #475569;">Tidak ada pengeluaran untuk periode ini</p>
            </div>
        @endif
    </flux:card>
</div>
