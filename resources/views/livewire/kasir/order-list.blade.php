
<div>
    <flux:heading size="xl" class="mb-6">Daftar Order Servis</flux:heading>

    {{-- Stat Hari Ini --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <flux:card class="p-4 border-l-4 border-blue-400">
            <p class="text-xs text-zinc-500 uppercase tracking-wide">Order Hari Ini</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $todayOrders }}</p>
        </flux:card>
        <flux:card class="p-4 border-l-4 border-green-400">
            <p class="text-xs text-zinc-500 uppercase tracking-wide">Pendapatan Hari Ini</p>
            <p class="text-2xl font-bold text-green-600 mt-1">
                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
            </p>
        </flux:card>
    </div>

    {{-- Filter Bar --}}
    <div class="flex flex-wrap gap-3 mb-5">
        <flux:input
            wire:model.live.debounce="search"
            placeholder="Cari nama / no. order / plat..."
            class="w-60"
        />
        <flux:select wire:model.live="filterStatus" class="w-36" style="color: #000000;">
            <option value=""style="color: #000000;">Semua Status</option>
            <option value="open"style="color: #000000;">Antrian</option>
            <option value="in_progress"style="color: #000000;">Dikerjakan</option>
            <option value="done"style="color: #000000;">Selesai</option>
            <option value="cancelled"style="color: #000000;">Dibatalkan</option>
        </flux:select>
        <flux:input wire:model.live="filterDate" type="date" class="w-44"style="color: #000000;" />

        <a href="{{ route('kasir.orders.create') }}">
            <flux:button variant="primary" icon="plus">Order Baru</flux:button>
        </a>
    </div>

    {{-- Tabel Order --}}
    <div class="card-dark rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead style="background: rgba(15,23,42,0.6); border-bottom: 2px solid rgba(234,179,8,0.2);">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold" style="color: #eab308;">No. Order</th>
                    <th class="px-6 py-4 text-left font-semibold" style="color: #eab308;">Pelanggan</th>
                    <th class="px-6 py-4 text-left font-semibold" style="color: #eab308;">Kendaraan</th>
                    <th class="px-6 py-4 text-left font-semibold" style="color: #eab308;">Status</th>
                    <th class="px-6 py-4 text-left font-semibold" style="color: #eab308;">Total</th>
                    <th class="px-6 py-4 text-left font-semibold" style="color: #eab308;">Tanggal</th>
                    <th class="px-6 py-4 text-left font-semibold" style="color: #eab308;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusConfig = [
                        'open'        => ['label' => 'Antrian',    'color' => '#fde047', 'bg' => 'rgba(234,179,8,0.15)'],
                        'in_progress' => ['label' => 'Dikerjakan', 'color' => '#60a5fa', 'bg' => 'rgba(59,130,246,0.15)'],
                        'done'        => ['label' => 'Selesai',    'color' => '#34d399', 'bg' => 'rgba(16,185,129,0.15)'],
                        'cancelled'   => ['label' => 'Batal',      'color' => '#f87171', 'bg' => 'rgba(239,68,68,0.15)'],
                    ];
                @endphp
                @forelse($orders as $order)
                <tr class="table-dark-row">
                    <td class="px-6 py-4 font-mono text-xs font-semibold" style="color: #e2e8f0;">{{ $order->order_number }}</td>
                    <td class="px-6 py-4" style="color: #000000;">{{ $order->customer_name }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium" style="color: #000000;">{{ $order->vehicle_type }}</p>
                        <p class="text-xs" style="color: #000000;">{{ $order->plate_number }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @php $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'color' => '#94a3b8', 'bg' => 'rgba(148,163,184,0.15)']; @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background: {{ $sc['bg'] }}; color: {{ $sc['color'] }};">{{ $sc['label'] }}</span>
                    </td>
                    <td class="px-6 py-4 font-semibold" style="color: #34d399;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-xs" style="color: #000000;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button wire:click="viewDetail({{ $order->id }})" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all" style="background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2);">👁️</button>
                            @if($order->status === 'done')
                            <a href="{{ route('kasir.orders.receipt', $order->id) }}" target="_blank">
                                <button class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all" style="background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.2);">🖨️</button>
                            </a>
                            @endif
                            <button wire:click="deleteOrder({{ $order->id }})" wire:confirm="Hapus order ini? Stok barang akan dikembalikan."
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2);">🗑️</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center" style="color: #94a3b8;">
                        Belum ada order. <a href="{{ route('kasir.orders.create') }}" class="underline font-semibold" style="color: #60a5fa;">Buat order baru →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $orders->links() }}</div>

    {{-- Modal Detail --}}
    @if($showDetail && $viewingOrder)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-xl w-full mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold" style="color: #1f2937;">{{ $viewingOrder->order_number }}</h2>
                    @php $sc = $statusConfig[$viewingOrder->status] ?? ['label' => $viewingOrder->status, 'color' => '#94a3b8', 'bg' => 'rgba(148,163,184,0.15)']; @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background: {{ $sc['bg'] }}; color: {{ $sc['color'] }};">{{ $sc['label'] }}</span>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                    <div><p class="text-xs" style="color: #94a3b8;">Pelanggan</p><p class="font-semibold">{{ $viewingOrder->customer_name }}</p></div>
                    <div><p class="text-xs" style="color: #94a3b8;">Kendaraan</p><p class="font-semibold">{{ $viewingOrder->vehicle_type }}</p></div>
                    <div><p class="text-xs" style="color: #94a3b8;">No. Plat</p><p class="font-semibold">{{ $viewingOrder->plate_number }}</p></div>
                    <div><p class="text-xs" style="color: #94a3b8;">Tanggal</p><p class="font-semibold">{{ $viewingOrder->created_at->format('d/m/Y H:i') }}</p></div>
                    @if($viewingOrder->complaint)
                    <div class="col-span-2">
                        <p class="text-xs" style="color: #94a3b8;">Keluhan</p>
                        <p class="font-medium">{{ $viewingOrder->complaint }}</p>
                    </div>
                    @endif
                </div>

                <div style="border-top: 1px solid #e5e7eb; padding-top: 0.75rem; margin-top: 0.75rem;"></div>
                <p class="text-sm font-semibold mb-2" style="color: #1f2937;">Barang Digunakan:</p>
                <div class="space-y-1 mb-3">
                    @forelse($viewingOrder->items as $item)
                    <div class="flex justify-between text-sm py-1" style="border-bottom: 1px solid #e5e7eb;">
                        <span>{{ $item->item_name }} × {{ $item->quantity }}</span>
                        <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <p class="text-sm" style="color: #94a3b8;">Tidak ada barang.</p>
                    @endforelse
                </div>

                <div class="rounded-lg p-3 space-y-1.5 text-sm" style="background: rgba(15,23,42,0.3);">
                    <div class="flex justify-between"><span style="color: #94a3b8;">Subtotal Barang</span><span style="color: #e2e8f0;">Rp {{ number_format($viewingOrder->total_items_cost, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span style="color: #94a3b8;">Biaya Jasa</span><span style="color: #e2e8f0;">Rp {{ number_format($viewingOrder->service_fee, 0, ',', '.') }}</span></div>
                    <div style="border-top: 1px solid rgba(234,179,8,0.1); margin-top: 0.5rem; padding-top: 0.5rem;"></div>
                    <div class="flex justify-between font-bold text-base">
                        <span style="color: #e2e8f0;">Grand Total</span>
                        <span style="color: #34d399;">Rp {{ number_format($viewingOrder->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-5">
                    @if($viewingOrder->status === 'done')
                    <a href="{{ route('kasir.orders.receipt', $viewingOrder->id) }}" target="_blank">
                        <button class="px-4 py-2 rounded-lg text-sm font-semibold transition-all" style="background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.2);">🖨️ Cetak Struk</button>
                    </a>
                    @endif
                    <button wire:click="deleteOrder({{ $viewingOrder->id }})" wire:confirm="Hapus order ini? Stok barang akan dikembalikan."
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-all" style="background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.2);">🗑️ Hapus</button>
                    <button wire:click="closeDetail" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all" style="background: rgba(102,126,234,0.15); color: #667eea; border: 1px solid rgba(102,126,234,0.2);">Tutup</button>
                </div>
            </div>
        </div>
        @endif
    
</div>