<div>
    {{-- Header --}}
    <div class="flex items-center justify-center mb-8">
        <div class="text-center">
            <h1 class="text-2xl font-black" style="color: #000000;">Dashboard <span class="text-gradient">Superadmin</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">{{ now()->format('l, d F Y') }}</p>
        </div>
    </div>

    @if($monthlyExpensesCount > 0)
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold"
             style="background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.3);">
            📊 {{ $monthlyExpensesCount }} pengeluaran bulan ini
        </div>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        {{-- Pendapatan --}}
        <div class="card-stat p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl icon-green flex items-center justify-center text-lg">💰</div>
                <p class="text-xs font-semibold uppercase tracking-wide" style="color: #475569;">Pendapatan Bulan Ini</p>
            </div>
            <p class="text-2xl font-black" style="color: #34d399;">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</p>
            <p class="text-xs mt-1 font-semibold {{ $revenueGrowth >= 0 ? '' : '' }}"
               style="color: {{ $revenueGrowth >= 0 ? '#34d399' : '#f87171' }};">
                {{ $revenueGrowth >= 0 ? '▲' : '▼' }} {{ abs($revenueGrowth) }}% vs bulan lalu
            </p>
        </div>

        {{-- Order --}}
        <div class="card-stat p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl icon-blue flex items-center justify-center text-lg">📋</div>
                <p class="text-xs font-semibold uppercase tracking-wide" style="color: #475569;">Order Bulan Ini</p>
            </div>
            <p class="text-2xl font-black" style="color: #60a5fa;">{{ $ordersThisMonth }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Hari ini: <span style="color: #93c5fd;">{{ $ordersToday }}</span> order</p>
        </div>

        {{-- Admin --}}
        <div class="card-stat p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl icon-purple flex items-center justify-center text-lg">👥</div>
                <p class="text-xs font-semibold uppercase tracking-wide" style="color: #475569;">Admin Aktif</p>
            </div>
            <p class="text-2xl font-black" style="color: #c4b5fd;">{{ $activeAdmins }}</p>
            <p class="text-xs mt-1" style="color: #475569;">dari {{ $totalAdmins }} total admin</p>
        </div>

        {{-- Pengeluaran Bulan Ini --}}
        <div class="card-stat p-5" style="{{ $monthlyExpensesCount > 0 ? 'border-color: rgba(59,130,246,0.35);' : '' }}">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl {{ $monthlyExpensesCount > 0 ? 'icon-blue' : 'icon-gray' }} flex items-center justify-center text-lg">📊</div>
                <p class="text-xs font-semibold uppercase tracking-wide" style="color: #475569;">Pengeluaran Bulan Ini</p>
            </div>
            <p class="text-2xl font-black" style="color: {{ $monthlyExpensesCount > 0 ? '#60a5fa' : '#9ca3af' }};">{{ $monthlyExpensesCount }}</p>
            <p class="text-xs mt-1" style="color: #475569;">transaksi pembelian</p>
        </div>
    </div>

    {{-- Chart + Recent Orders --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Revenue Chart --}}
        <div class="card-dark p-6 lg:col-span-2">
            <h3 class="text-sm font-bold mb-4" style="color: #e2e8f0;">📈 Grafik Pendapatan 6 Bulan Terakhir</h3>
            <div class="relative" style="height: 220px;">
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
                                        borderColor: '#eab308',
                                        backgroundColor: 'rgba(234,179,8,0.08)',
                                        borderWidth: 2.5,
                                        tension: 0.4,
                                        fill: true,
                                        pointBackgroundColor: '#eab308',
                                        pointBorderColor: '#0a0f1e',
                                        pointRadius: 5,
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            backgroundColor: 'rgba(15,23,42,0.95)',
                                            borderColor: 'rgba(234,179,8,0.3)',
                                            borderWidth: 1,
                                            titleColor: '#fde047',
                                            bodyColor: '#e2e8f0',
                                            callbacks: { label: (ctx) => ' Rp ' + ctx.raw.toLocaleString('id-ID') }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            ticks: { color: '#475569', callback: (v) => 'Rp ' + (v/1000000).toFixed(1) + 'jt' },
                                            grid: { color: 'rgba(234,179,8,0.06)' },
                                            border: { color: 'transparent' }
                                        },
                                        x: {
                                            ticks: { color: '#475569' },
                                            grid: { display: false },
                                            border: { color: 'transparent' }
                                        }
                                    }
                                }
                            });
                        }
                    }">
                </canvas>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="card-dark p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold" style="color: #000000;">🕐 Order Terbaru</h3>
                <a href="{{ route('superadmin.service-history') }}" class="text-xs font-semibold hover:underline" style="color: #eab308;">Semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="flex items-start gap-3 pb-3 last:pb-0" style="border-bottom: 1px solid rgba(234,179,8,0.08);">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black text-white flex-shrink-0"
                         style="background: linear-gradient(135deg,#1d4ed8,#7c3aed);">
                        {{ strtoupper(substr($order->customer_name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" style="color: #121213;">{{ $order->customer_name }}</p>
                        <p class="text-xs truncate" style="color: #475569;">{{ $order->vehicle_type }}</p>
                        <p class="text-xs mt-0.5" style="color: #334155;">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs font-bold flex-shrink-0" style="color: #34d399;">
                        Rp {{ number_format($order->grand_total/1000, 0, ',', '.') }}rb
                    </span>
                </div>
                @empty
                <p class="text-center py-8 text-sm" style="color: #334155;">Belum ada order.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mt-6">
        <a href="{{ route('superadmin.admins.index') }}"
           class="card-dark p-4 flex items-center gap-3 rounded-xl transition-all hover:border-yellow-400/40"
           style="text-decoration: none;">
            <span class="text-2xl">👥</span>
            <span class="text-sm font-semibold" style="color: #93c5fd;">Kelola Admin</span>
        </a>
        <a href="{{ route('superadmin.service-history') }}"
           class="card-dark p-4 flex items-center gap-3 rounded-xl transition-all hover:border-yellow-400/40"
           style="text-decoration: none;">
            <span class="text-2xl">📋</span>
            <span class="text-sm font-semibold" style="color: #93c5fd;">Histori Servis</span>
        </a>
        <a href="{{ route('superadmin.reports') }}"
           class="card-dark p-4 flex items-center gap-3 rounded-xl transition-all hover:border-yellow-400/40"
           style="text-decoration: none;">
            <span class="text-2xl">📊</span>
            <span class="text-sm font-semibold" style="color: #93c5fd;">Laporan</span>
        </a>
    </div>
</div>