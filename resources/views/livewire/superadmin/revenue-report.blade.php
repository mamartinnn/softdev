
<div>
    <flux:heading size="xl" class="mb-6">Laporan Pendapatan</flux:heading>

    {{-- Filter --}}
    <div class="flex gap-3 mb-6">
        <flux:select wire:model.live="filterMonth" class="w-40">
            @foreach(range(1, 12) as $m)
            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
            </option>
            @endforeach
        </flux:select>
        <flux:select wire:model.live="filterYear" class="w-28">
            @foreach(range(now()->year, now()->year - 3) as $y)
            <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </flux:select>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <flux:card class="p-4 text-center">
            <p class="text-sm text-zinc-500">Total Pendapatan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </flux:card>
        <flux:card class="p-4 text-center">
            <p class="text-sm text-zinc-500">Biaya Jasa</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalServiceFee, 0, ',', '.') }}</p>
        </flux:card>
        <flux:card class="p-4 text-center">
            <p class="text-sm text-zinc-500">Biaya Barang</p>
            <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($totalItemsCost, 0, ',', '.') }}</p>
        </flux:card>
        <flux:card class="p-4 text-center">
            <p class="text-sm text-zinc-500">Total Order</p>
            <p class="text-2xl font-bold text-zinc-800">{{ $totalOrders }}</p>
        </flux:card>
    </div>

    {{-- Tabel Detail --}}
    <flux:table>
        <flux:columns>
            <flux:column>No. Order</flux:column>
            <flux:column>Pelanggan</flux:column>
            <flux:column>Kendaraan</flux:column>
            <flux:column>Kasir</flux:column>
            <flux:column>Tanggal</flux:column>
            <flux:column>Total</flux:column>
        </flux:columns>
        <flux:rows>
            @forelse($orders as $order)
            <flux:row>
                <flux:cell class="font-mono text-sm">{{ $order->order_number }}</flux:cell>
                <flux:cell>{{ $order->customer_name }}</flux:cell>
                <flux:cell>{{ $order->vehicle_type }} ({{ $order->plate_number }})</flux:cell>
                <flux:cell>{{ $order->user->name ?? '-' }}</flux:cell>
                <flux:cell>{{ $order->completed_at->format('d/m/Y H:i') }}</flux:cell>
                <flux:cell class="font-semibold text-green-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</flux:cell>
            </flux:row>
            @empty
            <flux:row>
                <flux:cell colspan="6" class="text-center text-zinc-400 py-8">Belum ada data untuk periode ini.</flux:cell>
            </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>
</div>