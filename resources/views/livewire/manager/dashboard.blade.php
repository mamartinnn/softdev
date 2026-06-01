
<div>
    <div class="mb-6">
        <flux:heading size="xl">Dashboard Manager</flux:heading>
        <p class="text-sm text-zinc-500 mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <flux:card class="p-5">
            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3">
                <flux:icon.archive-box class="size-5 text-blue-600"/>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $totalItems }}</p>
            <p class="text-xs text-zinc-500 mt-1">Total Jenis Barang</p>
        </flux:card>

        <flux:card class="p-5 {{ $lowStockItems > 0 ? 'border-orange-200 dark:border-orange-900' : '' }}">
            <div class="w-9 h-9 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mb-3">
                <flux:icon.exclamation-triangle class="size-5 text-orange-500"/>
            </div>
            <p class="text-2xl font-bold {{ $lowStockItems > 0 ? 'text-orange-500' : 'text-zinc-900 dark:text-white' }}">
                {{ $lowStockItems }}
            </p>
            <p class="text-xs text-zinc-500 mt-1">Stok Menipis</p>
        </flux:card>

        <flux:card class="p-5 {{ $outOfStock > 0 ? 'border-red-200 dark:border-red-900' : '' }}">
            <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center mb-3">
                <flux:icon.x-circle class="size-5 text-red-500"/>
            </div>
            <p class="text-2xl font-bold {{ $outOfStock > 0 ? 'text-red-600' : 'text-zinc-900 dark:text-white' }}">
                {{ $outOfStock }}
            </p>
            <p class="text-xs text-zinc-500 mt-1">Barang Habis</p>
        </flux:card>

        <flux:card class="p-5">
            <div class="w-9 h-9 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mb-3">
                <flux:icon.currency-dollar class="size-5 text-green-600"/>
            </div>
            <p class="text-lg font-bold text-zinc-900 dark:text-white">
                Rp {{ number_format($totalStockValue / 1000000, 1, ',', '.') }}jt
            </p>
            <p class="text-xs text-zinc-500 mt-1">Nilai Total Stok</p>
        </flux:card>
    </div>

    {{-- Alert stok kritis --}}
    @if($outOfStock > 0 || $lowStockItems > 0)
    <flux:callout variant="{{ $outOfStock > 0 ? 'danger' : 'warning' }}" icon="exclamation-triangle" class="mb-6">
        @if($outOfStock > 0)
            <strong>{{ $outOfStock }} barang telah habis!</strong>
        @endif
        @if($lowStockItems > 0)
            <span>{{ $lowStockItems }} barang stok menipis (< 5 unit).</span>
        @endif
        <a href="{{ route('manager.stock.low') }}" class="ml-2 underline font-semibold">Lihat & Restock →</a>
    </flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Riwayat Transaksi Terbaru --}}
        <flux:card class="p-5 lg:col-span-3">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="sm">Transaksi Stok Terbaru</flux:heading>
                <a href="{{ route('manager.stock.in') }}" class="text-xs text-orange-500 hover:underline">+ Tambah →</a>
            </div>
            <div class="space-y-2">
                @forelse($recentTransactions as $tx)
                <div class="flex items-center gap-3 py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                        {{ $tx->type === 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $tx->type === 'in' ? '↓' : '↑' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ $tx->item->name ?? '-' }}</p>
                        <p class="text-xs text-zinc-400">{{ $tx->user->name ?? '-' }} · {{ $tx->created_at->diffForHumans() }}</p>
                    </div>
                    <flux:badge variant="{{ $tx->type === 'in' ? 'green' : 'red' }}" size="sm">
                        {{ $tx->type === 'in' ? '+' : '-' }}{{ $tx->quantity }}
                    </flux:badge>
                </div>
                @empty
                <p class="text-zinc-400 text-sm text-center py-6">Belum ada transaksi stok.</p>
                @endforelse
            </div>
        </flux:card>

        {{-- Top Barang Terpakai --}}
        <flux:card class="p-5 lg:col-span-2">
            <flux:heading size="sm" class="mb-4">Top Barang Bulan Ini</flux:heading>
            @if($topItems->isEmpty())
            <p class="text-zinc-400 text-sm text-center py-6">Belum ada data bulan ini.</p>
            @else
            <div class="space-y-3">
                @foreach($topItems as $i => $topItem)
                @php $maxUsed = $topItems->first()->total_used; @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium truncate">{{ $i+1 }}. {{ $topItem->item->name ?? 'Barang dihapus' }}</span>
                        <span class="text-zinc-500 flex-shrink-0 ml-2">{{ $topItem->total_used }} unit</span>
                    </div>
                    <div class="w-full bg-zinc-100 dark:bg-zinc-700 rounded-full h-1.5">
                        <div class="bg-orange-400 h-1.5 rounded-full" style="width: {{ ($topItem->total_used / $maxUsed) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </flux:card>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-6 flex gap-3">
        <a href="{{ route('manager.items.index') }}">
            <flux:button icon="archive-box" variant="ghost">Kelola Barang</flux:button>
        </a>
        <a href="{{ route('manager.stock.in') }}">
            <flux:button icon="arrow-down-tray" variant="ghost">Barang Masuk</flux:button>
        </a>
        <a href="{{ route('manager.stock.low') }}">
            <flux:button icon="exclamation-triangle" variant="{{ $outOfStock + $lowStockItems > 0 ? 'danger' : 'ghost' }}">
                Stok Menipis {{ $outOfStock + $lowStockItems > 0 ? '('.(($outOfStock + $lowStockItems)).')' : '' }}
            </flux:button>
        </a>
    </div>
</div>


{{-- ============================================================ --}}
{{-- FILE B — LOKASI: resources/views/livewire/kasir/dashboard.blade.php --}}
{{-- ============================================================ --}}
{{-- Pisahkan menjadi file tersendiri saat copy ke project! --}}
{{-- ============================================================ --}}
{{-- <div>
    <div class="mb-6">
        <flux:heading size="xl">Dashboard Kasir</flux:heading>
        <p class="text-sm text-zinc-500 mt-1">Halo, {{ auth()->user()->name }}! {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <flux:card class="p-5 border-l-4 border-blue-400">
            <p class="text-xs text-zinc-500 uppercase tracking-wide">Order Hari Ini</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $todayOrders }}</p>
        </flux:card>
        <flux:card class="p-5 border-l-4 border-green-400">
            <p class="text-xs text-zinc-500 uppercase tracking-wide">Pendapatan Hari Ini</p>
            <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        </flux:card>
        <flux:card class="p-5 border-l-4 border-orange-400">
            <p class="text-xs text-zinc-500 uppercase tracking-wide">Order Bulan Ini</p>
            <p class="text-3xl font-bold text-orange-500 mt-1">{{ $monthOrders }}</p>
        </flux:card>
        <flux:card class="p-5 border-l-4 border-purple-400">
            <p class="text-xs text-zinc-500 uppercase tracking-wide">Pendapatan Bulan Ini</p>
            <p class="text-xl font-bold text-purple-600 mt-1">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <flux:card class="p-6 lg:col-span-1 flex flex-col items-center justify-center text-center gap-4">
            <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-3xl">🔧</div>
            <div>
                <p class="font-semibold text-zinc-700 dark:text-zinc-300">Buat Order Servis Baru</p>
                <p class="text-xs text-zinc-400 mt-1">Catat kendaraan masuk & barang terpakai</p>
            </div>
            <a href="{{ route('kasir.orders.create') }}" class="w-full">
                <flux:button variant="primary" class="w-full" icon="plus">Buat Order Baru</flux:button>
            </a>
            <a href="{{ route('kasir.orders.index') }}" class="w-full">
                <flux:button variant="ghost" class="w-full" icon="list-bullet">Lihat Semua Order</flux:button>
            </a>
        </flux:card>

        <flux:card class="p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="sm">Order Terakhir Kamu</flux:heading>
                <a href="{{ route('kasir.orders.index') }}" class="text-xs text-orange-500 hover:underline">Lihat semua →</a>
            </div>
            @php
                $statusConfig = [
                    'open' => ['label'=>'Antrian','variant'=>'yellow'],
                    'in_progress' => ['label'=>'Dikerjakan','variant'=>'blue'],
                    'done' => ['label'=>'Selesai','variant'=>'green'],
                    'cancelled' => ['label'=>'Batal','variant'=>'red'],
                ];
            @endphp
            <div class="space-y-2">
                @forelse($recentOrders as $order)
                <div class="flex items-center gap-3 py-2.5 border-b border-zinc-100 dark:border-zinc-700 last:border-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-sm truncate">{{ $order->customer_name }}</p>
                            @php $sc = $statusConfig[$order->status] ?? ['label'=>$order->status,'variant'=>'zinc']; @endphp
                            <flux:badge variant="{{ $sc['variant'] }}" size="sm">{{ $sc['label'] }}</flux:badge>
                        </div>
                        <p class="text-xs text-zinc-400">{{ $order->vehicle_type }} · {{ $order->order_number }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-semibold text-sm text-green-700">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                        <p class="text-xs text-zinc-400">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    @if($order->status === 'done')
                    <a href="{{ route('kasir.orders.receipt', $order->id) }}" target="_blank">
                        <flux:button size="sm" icon="printer" variant="ghost" />
                    </a>
                    @endif
                </div>
                @empty
                <p class="text-zinc-400 text-sm text-center py-8">
                    Belum ada order. <a href="{{ route('kasir.orders.create') }}" class="text-orange-500 hover:underline">Buat yang pertama →</a>
                </p>
                @endforelse
            </div>
        </flux:card>
    </div>
</div> --}}