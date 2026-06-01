<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black" style="color: #f1f5f9;">Dashboard <span class="text-gradient">Manager</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">{{ now()->format('l, d F Y') }}</p>
        </div>
        @if($outOfStock > 0 || $lowStockItems > 0)
        <a href="{{ route('manager.stock.low') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold"
           style="background: rgba(234,179,8,0.15); color: #fde047; border: 1px solid rgba(234,179,8,0.3);">
            ⚠ {{ $outOfStock + $lowStockItems }} item perlu perhatian
        </a>
        @endif
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-blue flex items-center justify-center text-lg mb-3">📦</div>
            <p class="text-2xl font-black" style="color: #60a5fa;">{{ $totalItems }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Total Jenis Barang</p>
        </div>

        <div class="card-stat p-5" style="{{ $lowStockItems > 0 ? 'border-color: rgba(234,179,8,0.4);' : '' }}">
            <div class="w-10 h-10 rounded-xl icon-yellow flex items-center justify-center text-lg mb-3">⚠</div>
            <p class="text-2xl font-black" style="color: {{ $lowStockItems > 0 ? '#fde047' : '#e2e8f0' }};">{{ $lowStockItems }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Stok Menipis (&lt;5)</p>
        </div>

        <div class="card-stat p-5" style="{{ $outOfStock > 0 ? 'border-color: rgba(239,68,68,0.4);' : '' }}">
            <div class="w-10 h-10 rounded-xl icon-red flex items-center justify-center text-lg mb-3">❌</div>
            <p class="text-2xl font-black" style="color: {{ $outOfStock > 0 ? '#f87171' : '#e2e8f0' }};">{{ $outOfStock }}</p>
            <p class="text-xs mt-1" style="color: #475569;">Barang Habis</p>
        </div>

        <div class="card-stat p-5">
            <div class="w-10 h-10 rounded-xl icon-green flex items-center justify-center text-lg mb-3">💵</div>
            <p class="text-lg font-black" style="color: #34d399;">Rp {{ number_format($totalStockValue / 1000000, 1, ',', '.') }}jt</p>
            <p class="text-xs mt-1" style="color: #475569;">Nilai Total Stok</p>
        </div>
    </div>

    {{-- Alert --}}
    @if($outOfStock > 0 || $lowStockItems > 0)
    <div class="mb-6 p-4 rounded-xl flex items-center gap-3"
         style="background: rgba(234,179,8,0.08); border: 1px solid rgba(234,179,8,0.25);">
        <span class="text-xl">⚠</span>
        <div class="flex-1">
            @if($outOfStock > 0)<strong style="color: #fde047;">{{ $outOfStock }} barang telah HABIS!</strong> @endif
            @if($lowStockItems > 0)<span style="color: #fbbf24;"> {{ $lowStockItems }} barang stok menipis (&lt;5 unit).</span>@endif
        </div>
        <a href="{{ route('manager.stock.low') }}" class="text-sm font-bold underline" style="color: #eab308;">Lihat & Restock →</a>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Transaksi Stok Terbaru --}}
        <div class="card-dark p-5 lg:col-span-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold" style="color: #e2e8f0;">🔄 Transaksi Stok Terbaru</h3>
                <a href="{{ route('manager.stock.in') }}" class="text-xs font-semibold" style="color: #eab308;">+ Tambah →</a>
            </div>
            <div class="space-y-1">
                @forelse($recentTransactions as $tx)
                <div class="flex items-center gap-3 py-2.5 rounded-lg px-2"
                     style="border-bottom: 1px solid rgba(234,179,8,0.06);">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black"
                         style="{{ $tx->type === 'in' ? 'background: rgba(16,185,129,0.18); color: #34d399;' : 'background: rgba(239,68,68,0.18); color: #f87171;' }}">
                        {{ $tx->type === 'in' ? '↓' : '↑' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" style="color: #e2e8f0;">{{ $tx->item->name ?? '-' }}</p>
                        <p class="text-xs" style="color: #475569;">{{ $tx->user->name ?? '-' }} · {{ $tx->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                          style="{{ $tx->type === 'in' ? 'background: rgba(16,185,129,0.15); color: #34d399;' : 'background: rgba(239,68,68,0.15); color: #f87171;' }}">
                        {{ $tx->type === 'in' ? '+' : '-' }}{{ $tx->quantity }}
                    </span>
                </div>
                @empty
                <p class="text-center py-8 text-sm" style="color: #334155;">Belum ada transaksi stok.</p>
                @endforelse
            </div>
        </div>

        {{-- Top Barang --}}
        <div class="card-dark p-5 lg:col-span-2">
            <h3 class="text-sm font-bold mb-4" style="color: #e2e8f0;">🏆 Top Barang Bulan Ini</h3>
            @if($topItems->isEmpty())
            <p class="text-center py-8 text-sm" style="color: #334155;">Belum ada data bulan ini.</p>
            @else
            <div class="space-y-4">
                @foreach($topItems as $i => $topItem)
                @php $maxUsed = $topItems->first()->total_used; @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="font-semibold truncate" style="color: #e2e8f0;">{{ $i+1 }}. {{ $topItem->item->name ?? 'Barang dihapus' }}</span>
                        <span class="flex-shrink-0 ml-2 font-bold" style="color: #eab308;">{{ $topItem->total_used }} unit</span>
                    </div>
                    <div class="w-full rounded-full" style="background: rgba(234,179,8,0.08); height: 5px;">
                        <div class="progress-gold rounded-full" style="width: {{ ($topItem->total_used / $maxUsed) * 100 }}%; height: 5px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-3 gap-3 mt-6">
        <a href="{{ route('manager.items.index') }}"
           class="card-dark p-4 flex items-center gap-3 rounded-xl hover:border-blue-400/40 transition-all" style="text-decoration:none;">
            <span class="text-xl">📦</span>
            <span class="text-sm font-semibold" style="color: #93c5fd;">Kelola Barang</span>
        </a>
        <a href="{{ route('manager.stock.in') }}"
           class="card-dark p-4 flex items-center gap-3 rounded-xl hover:border-green-400/40 transition-all" style="text-decoration:none;">
            <span class="text-xl">↓</span>
            <span class="text-sm font-semibold" style="color: #34d399;">Barang Masuk</span>
        </a>
        <a href="{{ route('manager.stock.low') }}"
           class="card-dark p-4 flex items-center gap-3 rounded-xl transition-all" style="text-decoration:none;
           {{ $outOfStock + $lowStockItems > 0 ? 'border-color: rgba(234,179,8,0.35);' : '' }}">
            <span class="text-xl">⚠</span>
            <span class="text-sm font-semibold" style="color: {{ $outOfStock + $lowStockItems > 0 ? '#fde047' : '#475569' }};">
                Stok Menipis {{ $outOfStock + $lowStockItems > 0 ? '('.(($outOfStock + $lowStockItems)).')' : '' }}
            </span>
        </a>
    </div>
</div>