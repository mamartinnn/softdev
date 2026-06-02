<div>
    <div class="flex items-center justify-center mb-8">
        <div class="text-center">
            <h1 class="text-2xl font-black" style="color: #060606;">Histori <span class="text-gradient">Servis</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">Semua catatan service order</p>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="card-dark p-4 mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input wire:model.live.debounce="search"
               placeholder="🔍 Cari nama/plat/no.order..."
               class="input-dark px-3 py-2 text-sm rounded-lg w-full"
               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
        <select wire:model.live="filterStatus"
                class="input-dark px-3 py-2 text-sm rounded-lg w-full"
                style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0;">
            <option value="">Semua Status</option>
            <option value="open">Antrian</option>
            <option value="in_progress">Dikerjakan</option>
            <option value="done">Selesai</option>
            <option value="cancelled">Batal</option>
        </select>
        <select wire:model.live="filterKasir"
                class="input-dark px-3 py-2 text-sm rounded-lg w-full"
                style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0;">
            <option value="">Semua Kasir</option>
            @foreach($kasirList as $kasir)
            <option value="{{ $kasir->id }}">{{ $kasir->name }}</option>
            @endforeach
        </select>
        <input wire:model.live="filterDate" type="date"
               class="input-dark px-3 py-2 text-sm rounded-lg w-full"
               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0;" />
    </div>

    {{-- Table --}}
    <div class="card-dark overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(234,179,8,0.15); background: rgba(15,23,42,0.6);">
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">No. Order</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Pelanggan</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Kendaraan</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Kasir</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Status</th>
                        <th class="text-right px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Total</th>
                        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Tanggal</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $statusConfig = [
                            'open'        => ['label'=>'Antrian',   'color'=>'#fde047','bg'=>'rgba(234,179,8,0.12)'],
                            'in_progress' => ['label'=>'Dikerjakan','color'=>'#93c5fd','bg'=>'rgba(59,130,246,0.12)'],
                            'done'        => ['label'=>'Selesai',   'color'=>'#34d399','bg'=>'rgba(16,185,129,0.12)'],
                            'cancelled'   => ['label'=>'Batal',     'color'=>'#f87171','bg'=>'rgba(239,68,68,0.12)'],
                        ];
                        $sc = $statusConfig[$order->status] ?? ['label'=>$order->status,'color'=>'#94a3b8','bg'=>'rgba(148,163,184,0.12)'];
                    @endphp
                    <tr class="table-dark-row cursor-pointer" wire:click="viewDetail({{ $order->id }})"
                        style="border-bottom: 1px solid rgba(234,179,8,0.06);">
                        <td class="px-4 py-3 font-mono text-xs" style="color: #0e0e0e;">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 font-semibold" style="color: #090909;">{{ $order->customer_name }}</td>
                        <td class="px-4 py-3" style="color: #000000;">{{ $order->vehicle_type }}<br><span class="text-xs font-mono" style="color: #475569;">{{ $order->plate_number }}</span></td>
                        <td class="px-4 py-3" style="color: #060606;">{{ $order->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                  style="background: {{ $sc['bg'] }}; color: {{ $sc['color'] }};">{{ $sc['label'] }}</span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold" style="color: #34d399;">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-xs" style="color: #475569;">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            @if($order->status === 'done')
                            <a href="{{ route('superadmin.receipt', $order->id) }}" target="_blank"
                               wire:click.stop=""
                               class="text-xs px-2 py-1 rounded-lg" style="background: rgba(234,179,8,0.12); color: #eab308;">🖨</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center" style="color: #334155;">Tidak ada data order.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-4 py-4" style="border-top: 1px solid rgba(234,179,8,0.1);">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if($showDetail && $viewingOrder)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(2,6,23,0.85); backdrop-filter: blur(8px);"
         wire:click.self="closeDetail">
        <div class="w-full max-w-lg rounded-2xl p-6" style="background: linear-gradient(135deg,#0f172a,#1e1b4b); border: 1px solid rgba(234,179,8,0.25);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-black" style="color: #fde047;">{{ $viewingOrder->order_number }}</h3>
                <button wire:click="closeDetail" style="color: #475569;">✕</button>
            </div>
            <div class="space-y-2 text-sm mb-4">
                <div class="flex justify-between"><span style="color: #475569;">Pelanggan</span><span class="font-semibold" style="color: #e2e8f0;">{{ $viewingOrder->customer_name }}</span></div>
                <div class="flex justify-between"><span style="color: #475569;">Kendaraan</span><span style="color: #e2e8f0;">{{ $viewingOrder->vehicle_type }}</span></div>
                <div class="flex justify-between"><span style="color: #475569;">Plat Nomor</span><span class="font-mono" style="color: #93c5fd;">{{ $viewingOrder->plate_number }}</span></div>
                <div class="flex justify-between"><span style="color: #475569;">Kasir</span><span style="color: #e2e8f0;">{{ $viewingOrder->user->name ?? '-' }}</span></div>
                @if($viewingOrder->complaint)
                <div><span style="color: #475569;">Keluhan:</span> <span style="color: #94a3b8;">{{ $viewingOrder->complaint }}</span></div>
                @endif
            </div>
            <div class="rounded-xl overflow-hidden mb-4" style="border: 1px solid rgba(234,179,8,0.12);">
                <table class="w-full text-xs">
                    <thead><tr style="background: rgba(234,179,8,0.08);">
                        <th class="text-left px-3 py-2" style="color: #eab308;">Barang</th>
                        <th class="text-center px-3 py-2" style="color: #eab308;">Qty</th>
                        <th class="text-right px-3 py-2" style="color: #eab308;">Subtotal</th>
                    </tr></thead>
                    <tbody>
                        @foreach($viewingOrder->items as $item)
                        <tr style="border-top: 1px solid rgba(234,179,8,0.06);">
                            <td class="px-3 py-2" style="color: #e2e8f0;">{{ $item->item_name }}</td>
                            <td class="px-3 py-2 text-center" style="color: #94a3b8;">{{ $item->quantity }}</td>
                            <td class="px-3 py-2 text-right font-semibold" style="color: #34d399;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between"><span style="color: #475569;">Biaya Barang</span><span style="color: #e2e8f0;">Rp {{ number_format($viewingOrder->total_items_cost,0,',','.') }}</span></div>
                <div class="flex justify-between"><span style="color: #475569;">Biaya Jasa</span><span style="color: #e2e8f0;">Rp {{ number_format($viewingOrder->service_fee,0,',','.') }}</span></div>
                <div class="flex justify-between font-black text-base pt-2" style="border-top: 1px solid rgba(234,179,8,0.2);">
                    <span style="color: #fde047;">TOTAL</span>
                    <span style="color: #34d399;">Rp {{ number_format($viewingOrder->grand_total,0,',','.') }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>