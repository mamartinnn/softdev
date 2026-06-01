
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
            icon="magnifying-glass"
            class="w-60"
        />
        <flux:select wire:model.live="filterStatus" class="w-36">
            <option value="">Semua Status</option>
            <option value="open">Antrian</option>
            <option value="in_progress">Dikerjakan</option>
            <option value="done">Selesai</option>
            <option value="cancelled">Dibatalkan</option>
        </flux:select>
        <flux:input wire:model.live="filterDate" type="date" class="w-44" />

        <a href="{{ route('kasir.orders.create') }}">
            <flux:button variant="primary" icon="plus">Order Baru</flux:button>
        </a>
    </div>

    {{-- Tabel Order --}}
    <flux:table>
        <flux:columns>
            <flux:column>No. Order</flux:column>
            <flux:column>Pelanggan</flux:column>
            <flux:column>Kendaraan</flux:column>
            <flux:column>Status</flux:column>
            <flux:column>Total</flux:column>
            <flux:column>Tanggal</flux:column>
            <flux:column>Aksi</flux:column>
        </flux:columns>
        <flux:rows>
            @php
                $statusConfig = [
                    'open'        => ['label' => 'Antrian',    'variant' => 'yellow'],
                    'in_progress' => ['label' => 'Dikerjakan', 'variant' => 'blue'],
                    'done'        => ['label' => 'Selesai',    'variant' => 'green'],
                    'cancelled'   => ['label' => 'Batal',      'variant' => 'red'],
                ];
            @endphp
            @forelse($orders as $order)
            <flux:row>
                <flux:cell class="font-mono text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    {{ $order->order_number }}
                </flux:cell>
                <flux:cell>{{ $order->customer_name }}</flux:cell>
                <flux:cell>
                    <p class="font-medium text-sm">{{ $order->vehicle_type }}</p>
                    <p class="text-xs text-zinc-400">{{ $order->plate_number }}</p>
                </flux:cell>
                <flux:cell>
                    @php $sc = $statusConfig[$order->status] ?? ['label' => $order->status, 'variant' => 'zinc']; @endphp
                    <flux:badge variant="{{ $sc['variant'] }}" size="sm">{{ $sc['label'] }}</flux:badge>
                </flux:cell>
                <flux:cell class="font-semibold text-green-700 dark:text-green-400">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </flux:cell>
                <flux:cell class="text-xs text-zinc-400">
                    {{ $order->created_at->format('d/m/Y H:i') }}
                </flux:cell>
                <flux:cell>
                    <div class="flex gap-1">
                        <flux:button wire:click="viewDetail({{ $order->id }})" size="sm" icon="eye" variant="ghost" />
                        @if($order->status === 'done')
                        <a href="{{ route('kasir.orders.receipt', $order->id) }}" target="_blank">
                            <flux:button size="sm" icon="printer" variant="ghost" title="Cetak Struk" />
                        </a>
                        @endif
                    </div>
                </flux:cell>
            </flux:row>
            @empty
            <flux:row>
                <flux:cell colspan="7" class="text-center text-zinc-400 py-10">
                    Belum ada order. <a href="{{ route('kasir.orders.create') }}" class="text-orange-500 hover:underline">Buat order baru →</a>
                </flux:cell>
            </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>
    <div class="mt-4">{{ $orders->links() }}</div>

    {{-- Modal Detail --}}
    <flux:modal wire:model="showDetail" class="max-w-xl w-full">
        @if($viewingOrder)
        <div class="flex items-center justify-between mb-4">
            <flux:heading>{{ $viewingOrder->order_number }}</flux:heading>
            @php $sc = $statusConfig[$viewingOrder->status] ?? ['label' => $viewingOrder->status, 'variant' => 'zinc']; @endphp
            <flux:badge variant="{{ $sc['variant'] }}">{{ $sc['label'] }}</flux:badge>
        </div>

        <div class="grid grid-cols-2 gap-3 text-sm mb-4">
            <div><p class="text-zinc-500 text-xs">Pelanggan</p><p class="font-semibold">{{ $viewingOrder->customer_name }}</p></div>
            <div><p class="text-zinc-500 text-xs">Kendaraan</p><p class="font-semibold">{{ $viewingOrder->vehicle_type }}</p></div>
            <div><p class="text-zinc-500 text-xs">No. Plat</p><p class="font-semibold">{{ $viewingOrder->plate_number }}</p></div>
            <div><p class="text-zinc-500 text-xs">Tanggal</p><p class="font-semibold">{{ $viewingOrder->created_at->format('d/m/Y H:i') }}</p></div>
            @if($viewingOrder->complaint)
            <div class="col-span-2">
                <p class="text-zinc-500 text-xs">Keluhan</p>
                <p class="font-medium">{{ $viewingOrder->complaint }}</p>
            </div>
            @endif
        </div>

        <flux:separator class="my-3" />
        <p class="text-sm font-semibold mb-2">Barang Digunakan:</p>
        <div class="space-y-1 mb-3">
            @forelse($viewingOrder->items as $item)
            <div class="flex justify-between text-sm border-b border-zinc-100 dark:border-zinc-700 py-1">
                <span>{{ $item->item_name }} × {{ $item->quantity }}</span>
                <span class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
            @empty <p class="text-zinc-400 text-sm">Tidak ada barang.</p>
            @endforelse
        </div>

        <div class="bg-zinc-50 dark:bg-zinc-900 rounded-lg p-3 space-y-1.5 text-sm">
            <div class="flex justify-between"><span class="text-zinc-500">Subtotal Barang</span><span>Rp {{ number_format($viewingOrder->total_items_cost, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-zinc-500">Biaya Jasa</span><span>Rp {{ number_format($viewingOrder->service_fee, 0, ',', '.') }}</span></div>
            <flux:separator />
            <div class="flex justify-between font-bold text-base">
                <span>Grand Total</span>
                <span class="text-green-600">Rp {{ number_format($viewingOrder->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-5">
            @if($viewingOrder->status === 'done')
            <a href="{{ route('kasir.orders.receipt', $viewingOrder->id) }}" target="_blank">
                <flux:button icon="printer" variant="ghost" size="sm">Cetak Struk</flux:button>
            </a>
            @endif
            <flux:button wire:click="closeDetail" variant="primary">Tutup</flux:button>
        </div>
        @endif
    </flux:modal>
</div>