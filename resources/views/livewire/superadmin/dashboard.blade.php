
<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Dashboard Superadmin</flux:heading>
            <p class="text-sm text-zinc-500 mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        @if($lowStockCount > 0)
        <flux:badge variant="red" size="lg" icon="exclamation-triangle">
            {{ $lowStockCount }} barang stok menipis!
        </flux:badge>
        @endif
    </div>

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <flux:card class="p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <flux:icon.banknotes class="size-5 text-green-600"/>
                </div>
                <span class="text-xs text-zinc-500 uppercase tracking-wide">Pendapatan Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white">
                Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}
            </p>
            <p class="text-xs mt-1 {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-500' }}">
                {{ $revenueGrowth >= 0 ? '▲' : '▼' }} {{ abs($revenueGrowth) }}% vs bulan lalu
            </p>
        </flux:card>

        <flux:card class="p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <flux:icon.clipboard-document-list class="size-5 text-blue-600"/>
                </div>
                <span class="text-xs text-zinc-500 uppercase tracking-wide">Order Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $ordersThisMonth }}</p>
            <p class="text-xs text-zinc-400 mt-1">Hari ini: {{ $ordersToday }} order</p>
        </flux:card>

        <flux:card class="p-5">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <flux:icon.users class="size-5 text-purple-600"/>
                </div>
                <span class="text-xs text-zinc-500 uppercase tracking-wide">Admin Aktif</span>
            </div>
            <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $activeAdmins }}</p>
            <p class="text-xs text-zinc-400 mt-1">dari {{ $totalAdmins }} total admin</p>
        </flux:card>

        <flux:card class="p-5 {{ $lowStockCount > 0 ? 'border-red-200 dark:border-red-900' : '' }}">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-lg {{ $lowStockCount > 0 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-zinc-100 dark:bg-zinc-800' }} flex items-center justify-center">
                    <flux:icon.archive-box class="size-5 {{ $lowStockCount > 0 ? 'text-red-600' : 'text-zinc-500' }}"/>
                </div>
                <span class="text-xs text-zinc-500 uppercase tracking-wide">Stok Menipis</span>
            </div>
            <p class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-zinc-900 dark:text-white' }}">
                {{ $lowStockCount }}
            </p>
            <p class="text-xs text-zinc-400 mt-1">barang perlu restock</p>
        </flux:card>
    </div>

    {{-- ===== CHART + RECENT ORDERS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Grafik Pendapatan 6 Bulan --}}
        <flux:card class="p-5 lg:col-span-2">
            <flux:heading size="sm" class="mb-4">Grafik Pendapatan 6 Bulan Terakhir</flux:heading>
            <div class="relative h-56">
                <canvas id="revenueChart"
                    x-data="{
                        init() {
                            new Chart(this.$el, {
                                type: 'line',
                                data: {
                                    labels: {{ json_encode($chartLabels) }},
                                    datasets: [{
                                        label: 'Pendapatan',
                                        data: {{ json_encode($chartData) }},
                                        borderColor: 'rgb(249, 115, 22)',
                                        backgroundColor: 'rgba(249, 115, 22, 0.08)',
                                        borderWidth: 2.5,
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: 'rgb(249, 115, 22)',
                                        pointRadius: 4,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            callbacks: {
                                                label: (ctx) => ' Rp ' + ctx.raw.toLocaleString('id-ID')
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            ticks: {
                                                callback: (v) => 'Rp ' + (v/1000000).toFixed(1) + 'jt'
                                            },
                                            grid: { color: 'rgba(0,0,0,0.05)' }
                                        },
                                        x: { grid: { display: false } }
                                    }
                                }
                            });
                        }
                    }">
                </canvas>
            </div>
        </flux:card>

        {{-- Order Terbaru --}}
        <flux:card class="p-5">
            <div class="flex items-center justify-between mb-4">
                <flux:heading size="sm">Order Terbaru</flux:heading>
                <a href="{{ route('superadmin.service-history') }}" class="text-xs text-orange-500 hover:underline">Lihat semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="flex items-start gap-3 pb-3 border-b border-zinc-100 dark:border-zinc-700 last:border-0 last:pb-0">
                    <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 text-xs font-bold flex-shrink-0">
                        {{ substr($order->customer_name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-sm truncate">{{ $order->customer_name }}</p>
                        <p class="text-xs text-zinc-400 truncate">{{ $order->vehicle_type }}</p>
                        <p class="text-xs text-zinc-500 mt-0.5">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs font-semibold text-green-700 dark:text-green-400 flex-shrink-0">
                        Rp {{ number_format($order->grand_total/1000, 0, ',', '.') }}rb
                    </span>
                </div>
                @empty
                <p class="text-zinc-400 text-sm text-center py-4">Belum ada order.</p>
                @endforelse
            </div>
        </flux:card>
    </div>
</div>
