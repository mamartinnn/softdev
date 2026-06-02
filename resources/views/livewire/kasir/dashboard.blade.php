<div>
    <div class="flex items-center justify-center mb-8">
        <div class="text-center">
            <h1 class="text-2xl font-black" style="color: #000000;">Dashboard <span class="text-gradient">Kasir</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">Halo, {{ auth()->user()->name }}! {{ now()->format('l, d F Y') }}</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card-stat p-5" style="border-left: 3px solid #3b82f6;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: #475569;">Order Hari Ini</p>
            <p class="text-3xl font-black" style="color: #60a5fa;">{{ $todayOrders }}</p>
        </div>
        <div class="card-stat p-5" style="border-left: 3px solid #10b981;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: #475569;">Pendapatan Hari Ini</p>
            <p class="text-xl font-black" style="color: #34d399;">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="card-stat p-5" style="border-left: 3px solid #eab308;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: #475569;">Order Bulan Ini</p>
            <p class="text-3xl font-black" style="color: #fde047;">{{ $monthOrders }}</p>
        </div>
        <div class="card-stat p-5" style="border-left: 3px solid #a855f7;">
            <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: #475569;">Pendapatan Bulan Ini</p>
            <p class="text-xl font-black" style="color: #c4b5fd;">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Quick Action --}}
        <div class="card-dark p-6 flex flex-col items-center justify-center text-center gap-4">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl"
                 style="background: linear-gradient(135deg, rgba(234,179,8,0.18), rgba(37,99,235,0.18)); border: 1px solid rgba(234,179,8,0.25);">
                🔧
            </div>
            <div>
                <p class="font-bold" style="color: #000000;">Buat Order Servis Baru</p>
                <p class="text-xs mt-1" style="color: #475569;">Catat kendaraan masuk & barang terpakai</p>
            </div>
            <a href="{{ route('kasir.orders.create') }}" class="w-full">
                <button class="w-full py-2.5 px-4 rounded-xl font-bold text-sm btn-gold transition-all">
                    ➕ Buat Order Baru
                </button>
            </a>
            <a href="{{ route('kasir.orders.index') }}" class="w-full">
                <button class="w-full py-2 px-4 rounded-xl font-semibold text-sm transition-all"
                        style="background: rgba(59,130,246,0.12); color: #93c5fd; border: 1px solid rgba(59,130,246,0.25);">
                    📄 Lihat Semua Order
                </button>
            </a>
        </div>

        {{-- Recent Orders --}}
        <div class="card-dark p-5 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold" style="color: #000000;">🕐 Order Terakhir Kamu</h3>
                <a href="{{ route('kasir.orders.index') }}" class="text-xs font-semibold hover:underline" style="color: #eab308;">Lihat semua →</a>
            </div>
            @php
                $statusConfig = [
                    'open'        => ['label'=>'Antrian',   'color'=>'#fde047', 'bg'=>'rgba(234,179,8,0.15)'],
                    'in_progress' => ['label'=>'Dikerjakan','color'=>'#93c5fd', 'bg'=>'rgba(59,130,246,0.15)'],
                    'done'        => ['label'=>'Selesai',   'color'=>'#34d399', 'bg'=>'rgba(16,185,129,0.15)'],
                    'cancelled'   => ['label'=>'Batal',     'color'=>'#f87171', 'bg'=>'rgba(239,68,68,0.15)'],
                ];
            @endphp
            <div class="space-y-2">
                @forelse($recentOrders as $order)
                @php $sc = $statusConfig[$order->status] ?? ['label'=>$order->status,'color'=>'#94a3b8','bg'=>'rgba(148,163,184,0.15)']; @endphp
                <div class="flex items-center gap-3 py-2.5 px-2 rounded-lg"
                     style="border-bottom: 1px solid rgba(234,179,8,0.07);">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold text-sm" style="color: #000000;">{{ $order->customer_name }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                  style="background: {{ $sc['bg'] }}; color: {{ $sc['color'] }};">{{ $sc['label'] }}</span>
                        </div>
                        <p class="text-xs mt-0.5" style="color: #475569;">{{ $order->vehicle_type }} · {{ $order->order_number }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-bold text-sm" style="color: #34d399;">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                        <p class="text-xs" style="color: #334155;">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                    @if($order->status === 'done')
                    <a href="{{ route('kasir.orders.receipt', $order->id) }}" target="_blank">
                        <button class="p-1.5 rounded-lg transition-all text-xs"
                                style="background: rgba(234,179,8,0.1); color: #eab308; border: 1px solid rgba(234,179,8,0.2);">🖨</button>
                    </a>
                    @endif
                </div>
                @empty
                <p class="text-center py-10 text-sm" style="color: #334155;">
                    Belum ada order.
                    <a href="{{ route('kasir.orders.create') }}" class="font-semibold underline" style="color: #eab308;">Buat yang pertama →</a>
                </p>
                @endforelse
            </div>
        </div>
    </div>
</div>