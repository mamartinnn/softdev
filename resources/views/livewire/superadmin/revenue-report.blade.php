<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black" style="color: #000000;">Laporan <span class="text-gradient">Pendapatan</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">Ringkasan keuangan per bulan</p>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="filterMonth"
                    class="px-3 py-2 rounded-xl text-sm"
                    style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0;">
                @foreach(range(1,12) as $m)
                <option value="{{ str_pad($m,2,'0',STR_PAD_LEFT) }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterYear"
                    class="px-3 py-2 rounded-xl text-sm"
                    style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0;">
                @foreach(range(date('Y'), date('Y')-3) as $y)
                <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-green flex items-center justify-center text-lg mb-3">💰</div>
            <p class="text-lg font-black" style="color: #34d399;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Total Pendapatan</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-blue flex items-center justify-center text-lg mb-3">🔧</div>
            <p class="text-lg font-black" style="color: #60a5fa;">Rp {{ number_format($totalServiceFee, 0, ',', '.') }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Biaya Jasa</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-yellow flex items-center justify-center text-lg mb-3">📦</div>
            <p class="text-lg font-black" style="color: #fde047;">Rp {{ number_format($totalItemsCost, 0, ',', '.') }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Biaya Barang</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-red flex items-center justify-center text-lg mb-3">📉</div>
            <p class="text-lg font-black" style="color: #f87171;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Total Pengeluaran</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-cyan flex items-center justify-center text-lg mb-3">📈</div>
            <p class="text-lg font-black" style="color: {{ $netProfit >= 0 ? '#10b981' : '#f87171' }};">Rp {{ number_format($netProfit, 0, ',', '.') }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Laba Bersih</p>
        </div>
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-purple flex items-center justify-center text-lg mb-3">📋</div>
            <p class="text-2xl font-black" style="color: #c4b5fd;">{{ $totalOrders }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Total Order</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-dark overflow-hidden">
        <div class="px-5 py-4" style="border-bottom: 1px solid rgba(234,179,8,0.12);">
            <h3 class="text-sm font-bold" style="color: #000000;">Rincian Order Bulan Ini</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background: rgba(15,23,42,0.6);">
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">No. Order</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Pelanggan</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Kasir</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Biaya Jasa</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Biaya Barang</th>
                        <th class="text-right px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Grand Total</th>
                        <th class="text-left px-5 py-3 text-xs font-bold uppercase" style="color: #eab308;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="table-dark-row" style="border-bottom: 1px solid rgba(234,179,8,0.05);">
                        <td class="px-5 py-3 font-mono text-xs" style="color: #000000;">{{ $order->order_number }}</td>
                        <td class="px-5 py-3 font-semibold" style="color: #000000;">{{ $order->customer_name }}</td>
                        <td class="px-5 py-3" style="color: #94a3b8;">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-right" style="color: #60a5fa;">Rp {{ number_format($order->service_fee,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right" style="color: #fde047;">Rp {{ number_format($order->total_items_cost,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right font-bold" style="color: #34d399;">Rp {{ number_format($order->grand_total,0,',','.') }}</td>
                        <td class="px-5 py-3 text-xs" style="color: #475569;">{{ $order->completed_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center" style="color: #334155;">Tidak ada order selesai pada bulan ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>