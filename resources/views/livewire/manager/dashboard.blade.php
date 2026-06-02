<div>
    {{-- Header Section --}}
    <div class="flex items-center justify-center mb-8">
        <div class="text-center">
            <h1 class="text-2xl font-black" style="color: #f1f5f9;">Dashboard <span class="text-gradient">Manager</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">📅 {{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Total Items Card --}}
        <div class="rounded-xl p-6 transition-all hover:shadow-lg hover:scale-105"
             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);">
            <div class="flex items-center justify-between">
                <div>
                    <p style="color: rgba(255,255,255,0.85); font-size: 0.875rem; margin-bottom: 0.5rem;">Total Barang</p>
                    <p class="text-3xl font-bold" style="color: #ffffff;">{{ $totalItems }}</p>
                </div>
                <div class="text-4xl opacity-50">📦</div>
            </div>
            <div style="height: 3px; background: rgba(255,255,255,0.3); border-radius: 2px; margin-top: 1rem;"></div>
        </div>

        {{-- Low Stock Card --}}
        <div class="rounded-xl p-6 transition-all hover:shadow-lg hover:scale-105"
             style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; box-shadow: 0 4px 20px rgba(245, 87, 108, 0.3);">
            <div class="flex items-center justify-between">
                <div>
                    <p style="color: rgba(255,255,255,0.85); font-size: 0.875rem; margin-bottom: 0.5rem;">Stok Menipis</p>
                    <p class="text-3xl font-bold" style="color: #ffffff;">{{ $lowStockItems }}</p>
                </div>
                <div class="text-4xl opacity-50">⚠️</div>
            </div>
            <div style="height: 3px; background: rgba(255,255,255,0.3); border-radius: 2px; margin-top: 1rem;"></div>
        </div>

        {{-- Out of Stock Card --}}
        <div class="rounded-xl p-6 transition-all hover:shadow-lg hover:scale-105"
             style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; box-shadow: 0 4px 20px rgba(79, 172, 254, 0.3);">
            <div class="flex items-center justify-between">
                <div>
                    <p style="color: rgba(255,255,255,0.85); font-size: 0.875rem; margin-bottom: 0.5rem;">Barang Habis</p>
                    <p class="text-3xl font-bold" style="color: #ffffff;">{{ $outOfStock }}</p>
                </div>
                <div class="text-4xl opacity-50">❌</div>
            </div>
            <div style="height: 3px; background: rgba(255,255,255,0.3); border-radius: 2px; margin-top: 1rem;"></div>
        </div>

        {{-- Stock Value Card --}}
        <div class="rounded-xl p-6 transition-all hover:shadow-lg hover:scale-105"
             style="background: linear-gradient(135deg, #2d3436 0%, #424242 100%); border: none; box-shadow: 0 4px 20px rgba(45, 52, 54, 0.3);">
            <div class="flex items-center justify-between">
                <div>
                    <p style="color: rgba(255,255,255,0.85); font-size: 0.875rem; margin-bottom: 0.5rem;">Nilai Total</p>
                    <p class="text-2xl font-bold" style="color: #fbbf24;">Rp {{ number_format($totalStockValue / 1000000, 1, ',', '.') }}jt</p>
                </div>
                <div class="text-4xl opacity-50">💰</div>
            </div>
            <div style="height: 3px; background: rgba(251, 191, 36, 0.3); border-radius: 2px; margin-top: 1rem;"></div>
        </div>
    </div>

    {{-- Alert Section --}}
    @if($outOfStock > 0 || $lowStockItems > 0)
    <div class="mb-8 rounded-xl p-5 flex items-start gap-4" 
         style="background: linear-gradient(135deg, #fff5e1 0%, #ffe0b2 100%); border-left: 4px solid #fbbf24;">
        <span class="text-2xl flex-shrink-0">⚠️</span>
        <div class="flex-1">
            <p class="font-bold mb-1" style="color: #b45309;">Perhatian Inventaris</p>
            <p class="text-sm" style="color: #92400e;">
                @if($outOfStock > 0)
                    <strong>{{ $outOfStock }} barang HABIS</strong>{{ $lowStockItems > 0 ? ' dan ' : '' }}
                @endif
                @if($lowStockItems > 0)
                    <strong>{{ $lowStockItems }} barang stok menipis (&lt;5 unit)</strong>
                @endif
            </p>
            <a href="{{ route('manager.stock.low') }}" 
               class="inline-block mt-2 text-sm font-bold transition-all hover:opacity-75 underline" 
               style="color: #b45309;">
                Lihat Detail & Restock →
            </a>
        </div>
    </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        {{-- Recent Transactions Card --}}
        <div class="lg:col-span-3 rounded-xl p-6 shadow-md"
             style="background: #ffffff; border: 1px solid #e5e7eb;">
            <div class="flex items-center justify-between mb-6 pb-4" style="border-bottom: 1px solid #e5e7eb;">
                <div>
                    <h3 class="text-base font-bold" style="color: #1f2937;">🔄 Transaksi Stok Terbaru</h3>
                    <p class="text-xs mt-1" style="color: #6b7280;">Aktivitas 30 hari terakhir</p>
                </div>
                <a href="{{ route('manager.stock.in') }}" 
                   class="text-sm font-bold flex items-center gap-1 transition-all hover:translate-x-1"
                   style="color: #667eea;">
                    <span>Tambah</span>
                    <span>→</span>
                </a>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
                @forelse($recentTransactions as $tx)
                <div class="flex items-center gap-4 p-4 rounded-lg transition-all"
                     style="background: {{ $tx->type === 'in' ? '#ecfdf5' : '#fef2f2' }}; border-left: 3px solid {{ $tx->type === 'in' ? '#10b981' : '#ef4444' }};">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0"
                         style="{{ $tx->type === 'in' ? 'background: rgba(16,185,129,0.2); color: #10b981;' : 'background: rgba(239,68,68,0.2); color: #ef4444;' }}">
                        {{ $tx->type === 'in' ? '↓' : '↑' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" style="color: #1f2937;">{{ $tx->item->name ?? 'Barang dihapus' }}</p>
                        <p class="text-xs" style="color: #6b7280;">{{ $tx->user->name ?? '-' }} • {{ $tx->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="text-sm font-bold px-3 py-1 rounded-lg"
                              style="{{ $tx->type === 'in' ? 'background: rgba(16,185,129,0.15); color: #10b981;' : 'background: rgba(239,68,68,0.15); color: #ef4444;' }}">
                            {{ $tx->type === 'in' ? '+' : '-' }}{{ $tx->quantity }} unit
                        </span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-16">
                    <p class="text-lg" style="color: #d1d5db;">📭</p>
                    <p class="text-sm mt-2" style="color: #6b7280;">Belum ada transaksi stok</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Top Items Card --}}
        <div class="lg:col-span-2 rounded-xl p-6 shadow-md"
             style="background: #ffffff; border: 1px solid #e5e7eb;">
            <div class="pb-4 mb-6" style="border-bottom: 1px solid #e5e7eb;">
                <h3 class="text-base font-bold" style="color: #1f2937;">🏆 Top Barang Bulan Ini</h3>
                <p class="text-xs mt-1" style="color: #6b7280;">Barang paling sering digunakan</p>
            </div>
            @if($topItems->isEmpty())
            <div class="flex flex-col items-center justify-center py-16">
                <p class="text-lg" style="color: #d1d5db;">📊</p>
                <p class="text-sm mt-2" style="color: #6b7280;">Belum ada data bulan ini</p>
            </div>
            @else
            <div class="space-y-5">
                @foreach($topItems as $i => $topItem)
                @php $maxUsed = $topItems->first()->total_used; @endphp
                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold px-3 py-1 rounded-lg" 
                                  style="background: linear-gradient(135deg, #667eea, #764ba2); color: #ffffff;">
                                #{{ $i + 1 }}
                            </span>
                            <span class="text-sm font-semibold truncate" style="color: #1f2937;">
                                {{ $topItem->item->name ?? 'Barang dihapus' }}
                            </span>
                        </div>
                        <span class="text-sm font-bold" style="color: #667eea;">{{ $topItem->total_used }} unit</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full overflow-hidden" style="background: #e5e7eb;">
                        <div style="width: {{ ($topItem->total_used / $maxUsed) * 100 }}%; height: 2.5px; background: linear-gradient(135deg, #667eea, #764ba2);"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
        <a href="{{ route('manager.items.index') }}"
           class="group p-6 flex items-center gap-4 rounded-xl transition-all hover:shadow-lg"
           style="text-decoration: none; background: #ffffff; border: 1px solid #e5e7eb;">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl"
                 style="background: #eff6ff; border: 2px solid #bfdbfe;">📦</div>
            <div class="flex-1">
                <p class="text-sm font-bold" style="color: #1e40af;">Kelola Barang</p>
                <p class="text-xs" style="color: #6b7280;">Edit data & harga</p>
            </div>
            <span class="text-lg transition-transform group-hover:translate-x-1" style="color: #1e40af;">→</span>
        </a>

        <a href="{{ route('manager.stock.in') }}"
           class="group p-6 flex items-center gap-4 rounded-xl transition-all hover:shadow-lg"
           style="text-decoration: none; background: #ffffff; border: 1px solid #e5e7eb;">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl"
                 style="background: #ecfdf5; border: 2px solid #a7f3d0;">↓</div>
            <div class="flex-1">
                <p class="text-sm font-bold" style="color: #059669;">Barang Masuk</p>
                <p class="text-xs" style="color: #6b7280;">Catat penerimaan</p>
            </div>
            <span class="text-lg transition-transform group-hover:translate-x-1" style="color: #059669;">→</span>
        </a>

        <a href="{{ route('manager.stock.low') }}"
           class="group p-6 flex items-center gap-4 rounded-xl transition-all hover:shadow-lg"
           style="text-decoration: none; background: #ffffff; border: 1px solid #e5e7eb;">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl"
                 style="background: #fef2f2; border: 2px solid #fecaca;">⚠️</div>
            <div class="flex-1">
                <p class="text-sm font-bold" style="color: #dc2626;">Stok Menipis</p>
                <p class="text-xs" style="color: #6b7280;">
                    {{ $outOfStock + $lowStockItems > 0 ? $outOfStock + $lowStockItems . ' item' : 'Semua aman' }}
                </p>
            </div>
            <span class="text-lg transition-transform group-hover:translate-x-1" style="color: #dc2626;">→</span>
        </a>
    </div>

    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        body {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%) !important;
        }
        
        @media (prefers-reduced-motion: no-preference) {
            .group {
                transition: all 0.3s ease;
            }
        }
    </style>
</div>