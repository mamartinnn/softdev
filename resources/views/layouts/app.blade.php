<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyUOS — Bengkel Servis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @fluxStyles
    @livewireStyles
    <style>
        :root {
            --brand-primary: #2563eb;
            --brand-dark:    #1e3a8a;
            --brand-accent:  #eab308;
            --brand-gold:    #f59e0b;
            --sidebar-bg:    linear-gradient(180deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }
        body { background: linear-gradient(135deg, #000000 0%, #020617 40%, #0a1628 100%); min-height: 100vh; }

        /* Sidebar */
        .sidebar-custom {
            background: linear-gradient(180deg, #020617 0%, #0f172a 30%, #1e1b4b 70%, #0f172a 100%) !important;
            border-right: 1px solid rgba(234,179,8,0.15) !important;
        }
        .sidebar-brand {
            background: linear-gradient(135deg, #1d4ed8, #7c3aed);
            border-radius: 10px;
            padding: 6px 8px;
        }
        /* Nav item active / hover */
        .nav-item-active {
            background: linear-gradient(90deg, rgba(234,179,8,0.18), rgba(37,99,235,0.18)) !important;
            border-left: 3px solid #eab308 !important;
            color: #fde047 !important;
        }
        .nav-item-hover:hover {
            background: rgba(234,179,8,0.08) !important;
            color: #fde047 !important;
        }

        /* Cards */
        .card-dark {
            background: linear-gradient(135deg, rgba(15,23,42,0.9), rgba(30,27,75,0.8));
            border: 1px solid rgba(234,179,8,0.12);
            border-radius: 12px;
            backdrop-filter: blur(12px);
        }
        .card-stat {
            background: linear-gradient(135deg, rgba(15,23,42,0.95), rgba(23,37,84,0.9));
            border: 1px solid rgba(234,179,8,0.2);
            border-radius: 14px;
            transition: transform .2s, border-color .2s, box-shadow .2s;
        }
        .card-stat:hover {
            transform: translateY(-2px);
            border-color: rgba(234,179,8,0.45);
            box-shadow: 0 8px 32px rgba(234,179,8,0.12);
        }

        /* Gradient text */
        .text-gradient {
            background: linear-gradient(135deg, #60a5fa, #eab308);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, #1d4ed8, #7c3aed) !important;
            border: none !important;
            color: #fff !important;
            transition: opacity .2s, transform .15s;
        }
        .btn-primary-custom:hover { opacity: .92; transform: translateY(-1px); }

        .btn-gold {
            background: linear-gradient(135deg, #d97706, #eab308) !important;
            border: none !important;
            color: #0a0f1e !important;
            font-weight: 700 !important;
        }
        .btn-gold:hover { opacity: .9; }

        /* Tables */
        .table-dark-row {
            background: rgba(15,23,42,0.7);
            border-bottom: 1px solid rgba(234,179,8,0.08);
            transition: background .15s;
        }
        .table-dark-row:hover { background: rgba(30,27,75,0.7); }

        /* Badges */
        .badge-active  { background: rgba(16,185,129,0.18); color: #34d399; border: 1px solid rgba(16,185,129,.3); }
        .badge-warning { background: rgba(234,179,8,0.18);  color: #fde047; border: 1px solid rgba(234,179,8,.3); }
        .badge-danger  { background: rgba(239,68,68,0.18);  color: #f87171; border: 1px solid rgba(239,68,68,.3); }
        .badge-blue    { background: rgba(59,130,246,0.18); color: #93c5fd; border: 1px solid rgba(59,130,246,.3); }
        .badge-gold    { background: rgba(234,179,8,0.18);  color: #fde047; border: 1px solid rgba(234,179,8,.3); }

        /* Input fields */
        .input-dark {
            background: rgba(15,23,42,0.8) !important;
            border: 1px solid rgba(234,179,8,0.2) !important;
            color: #e2e8f0 !important;
            border-radius: 8px !important;
        }
        .input-dark:focus {
            border-color: rgba(234,179,8,0.5) !important;
            box-shadow: 0 0 0 3px rgba(234,179,8,0.1) !important;
        }
        .input-dark::placeholder { color: #475569 !important; }

        /* Alert/callout */
        .alert-gold {
            background: rgba(234,179,8,0.08);
            border-left: 3px solid #eab308;
            border-radius: 8px;
            padding: 12px 16px;
            color: #fde047;
        }
        .alert-danger {
            background: rgba(239,68,68,0.08);
            border-left: 3px solid #ef4444;
            border-radius: 8px;
            padding: 12px 16px;
            color: #f87171;
        }
        .alert-success {
            background: rgba(16,185,129,0.08);
            border-left: 3px solid #10b981;
            border-radius: 8px;
            padding: 12px 16px;
            color: #34d399;
        }

        /* Main content area */
        .main-content {
            background: linear-gradient(135deg, #0a0f1e 0%, #0d1b3e 50%, #0a1628 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        /* Heading styles */
        h1, h2, .page-title { color: #f1f5f9; }
        .page-subtitle { color: #64748b; }

        /* Separator */
        .sep-gold { border-color: rgba(234,179,8,0.2); }

        /* Progress bar */
        .progress-gold { background: linear-gradient(90deg, #d97706, #eab308); height: 4px; border-radius: 2px; }

        /* Icon containers */
        .icon-blue   { background: rgba(59,130,246,0.15); color: #60a5fa; }
        .icon-green  { background: rgba(16,185,129,0.15); color: #34d399; }
        .icon-yellow { background: rgba(234,179,8,0.15);  color: #fde047; }
        .icon-red    { background: rgba(239,68,68,0.15);  color: #f87171; }
        .icon-purple { background: rgba(167,139,250,0.15); color: #c4b5fd; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0a0f1e; }
        ::-webkit-scrollbar-thumb { background: rgba(234,179,8,0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(234,179,8,0.5); }

        /* Flux overrides */
        flux-sidebar { --flux-sidebar-width: 240px; }
    </style>
</head>
<body class="h-full" style="background: linear-gradient(135deg, #0a0f1e 0%, #0d1b3e 40%, #0a1628 100%);">

<flux:sidebar sticky stashable class="sidebar-custom" style="background: linear-gradient(180deg, #020617 0%, #0f172a 30%, #1e1b4b 70%, #0f172a 100%); border-right: 1px solid rgba(234,179,8,0.15);">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" style="color: #94a3b8;" />

    {{-- Logo/Brand --}}
    <div class="flex items-center gap-3 px-4 py-5" style="border-bottom: 1px solid rgba(234,179,8,0.15);">
        <div class="sidebar-brand w-10 h-10 flex items-center justify-center text-white text-lg font-black rounded-xl shadow-lg">🔧</div>
        <div>
            <p class="font-black text-white text-base tracking-wide">MyUOS</p>
            <p class="text-xs font-medium" style="color: #eab308;">{{ ucfirst(auth()->user()->role) }}</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        {{-- SUPERADMIN --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="mb-2">
            <p class="px-3 py-1 text-xs font-bold uppercase tracking-widest" style="color: #eab308;">Superadmin</p>
        </div>
        @php $saRoutes = [
            ['icon' => '⊞', 'label' => 'Dashboard',         'route' => 'superadmin.dashboard',      'match' => 'superadmin.*dashboard'],
            ['icon' => '👥', 'label' => 'Kelola Admin',      'route' => 'superadmin.admins.index',   'match' => 'superadmin.admins*'],
            ['icon' => '📋', 'label' => 'Histori Servis',    'route' => 'superadmin.service-history','match' => 'superadmin.service*'],
            ['icon' => '📊', 'label' => 'Laporan Pendapatan','route' => 'superadmin.reports',        'match' => 'superadmin.reports*'],
        ]; @endphp
        @foreach($saRoutes as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 nav-item-hover {{ $active ? 'nav-item-active' : '' }}"
               style="{{ $active ? 'background: linear-gradient(90deg,rgba(234,179,8,.15),rgba(37,99,235,.12)); border-left: 3px solid #eab308; color: #fde047;' : 'color: #94a3b8;' }}">
                <span class="text-base w-5 text-center">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
        @endif

        {{-- MANAGER --}}
        @if(auth()->user()->isManager())
        <div class="mb-2 mt-2">
            <p class="px-3 py-1 text-xs font-bold uppercase tracking-widest" style="color: #eab308;">Manager</p>
        </div>
        @php $mgRoutes = [
            ['icon' => '⊞', 'label' => 'Dashboard',    'route' => 'manager.dashboard',  'match' => 'manager.*dashboard'],
            ['icon' => '📦', 'label' => 'Kelola Barang','route' => 'manager.items.index','match' => 'manager.items*'],
            ['icon' => '↓',  'label' => 'Barang Masuk', 'route' => 'manager.stock.in',   'match' => 'manager.stock.in*'],
            ['icon' => '⚠',  'label' => 'Stok Menipis', 'route' => 'manager.stock.low',  'match' => 'manager.stock.low'],
        ]; @endphp
        @foreach($mgRoutes as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 nav-item-hover {{ $active ? 'nav-item-active' : '' }}"
               style="{{ $active ? 'background: linear-gradient(90deg,rgba(234,179,8,.15),rgba(37,99,235,.12)); border-left: 3px solid #eab308; color: #fde047;' : 'color: #94a3b8;' }}">
                <span class="text-base w-5 text-center">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
        @endif

        {{-- KASIR --}}
        @if(auth()->user()->isKasir())
        <div class="mb-2 mt-2">
            <p class="px-3 py-1 text-xs font-bold uppercase tracking-widest" style="color: #eab308;">Kasir</p>
        </div>
        @php $krRoutes = [
            ['icon' => '⊞', 'label' => 'Dashboard',       'route' => 'kasir.dashboard',     'match' => 'kasir.*dashboard'],
            ['icon' => '➕', 'label' => 'Buat Order Servis','route' => 'kasir.orders.create', 'match' => 'kasir.orders.create'],
            ['icon' => '📄', 'label' => 'Daftar Order',     'route' => 'kasir.orders.index',  'match' => 'kasir.orders.index'],
        ]; @endphp
        @foreach($krRoutes as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 nav-item-hover {{ $active ? 'nav-item-active' : '' }}"
               style="{{ $active ? 'background: linear-gradient(90deg,rgba(234,179,8,.15),rgba(37,99,235,.12)); border-left: 3px solid #eab308; color: #fde047;' : 'color: #94a3b8;' }}">
                <span class="text-base w-5 text-center">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
        @endif
    </nav>

    {{-- User footer --}}
    <div class="px-4 py-4" style="border-top: 1px solid rgba(234,179,8,0.15);">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-black text-white"
                 style="background: linear-gradient(135deg,#1d4ed8,#7c3aed);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate" style="color: #e2e8f0;">{{ auth()->user()->name }}</p>
                <p class="text-xs truncate" style="color: #475569;">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all"
                    style="color: #f87171; background: rgba(239,68,68,0.08);"
                    onmouseover="this.style.background='rgba(239,68,68,0.15)'"
                    onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                <span>⏻</span> Keluar
            </button>
        </form>
    </div>
</flux:sidebar>

<flux:main style="background: linear-gradient(135deg, #0a0f1e 0%, #0d1b3e 50%, #0a1628 100%); min-height: 100vh;">
    {{-- Mobile header --}}
    <div class="flex items-center gap-3 px-4 py-3 lg:hidden" style="border-bottom: 1px solid rgba(234,179,8,0.15); background: rgba(2,6,23,0.95);">
        <flux:sidebar.toggle icon="bars-3" style="color: #94a3b8;" />
        <span class="font-black text-white text-base">🔧 MyUOS</span>
    </div>

    <div class="p-6">
        {{ $slot }}
    </div>
</flux:main>

@fluxScripts
@livewireScripts
</body>
</html>