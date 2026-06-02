<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyUOS — Bengkel Servis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @livewireStyles
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            --brand-primary: #1e40af;
            --brand-dark: #000000;
            --brand-accent: #eab308;
            --brand-gold: #f59e0b;
            --sidebar-bg: linear-gradient(180deg, #000000 0%, #020c24 40%, #000000 100%);
        }

        /* ===== BASE STYLES ===== */
        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            color: #1f2937;
            font-family: 'Figtree', sans-serif;
        }

        /* ===== SIDEBAR STYLES ===== */
        .sidebar-custom {
            background: #ffffff !important;
            border-right: 1px solid #e5e7eb !important;
            transition: all 0.3s ease;
            min-width: 260px !important;
            width: 100% !important;
        }
        
        .sidebar-custom > div:first-child {
            display: flex;
            flex-wrap: wrap;
            white-space: normal;
        }

        .sidebar-brand {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 12px;
            padding: 8px 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
        }

        .sidebar-brand:hover {
            border-color: rgba(102, 126, 234, 0.6);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.15);
        }

        /* Nav item active / hover */
        .nav-item-active {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.15), rgba(102, 126, 234, 0.08)) !important;
            border-left: 3px solid #667eea !important;
            color: #667eea !important;
            font-weight: 600;
            box-shadow: inset 0 0 8px rgba(102, 126, 234, 0.1);
        }

        .nav-item-hover {
            transition: all 0.2s ease;
            color: #1f2937 !important;
        }

        .nav-item-hover:hover {
            background: rgba(102, 126, 234, 0.08) !important;
            color: #667eea !important;
            transform: translateX(4px);
        }

        /* ===== CARD STYLES ===== */
        .card-dark {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-dark::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), transparent);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card-dark:hover {
            border-color: #d1d5db;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .card-dark:hover::before {
            opacity: 1;
        }

        .orchid-header {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .card-stat {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-stat::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.08), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card-stat:hover {
            transform: translateY(-4px);
            border-color: #d1d5db;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        .card-stat:hover::before {
            opacity: 1;
        }

        /* ===== TEXT STYLES ===== */
        .text-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        h1, h2, .page-title {
            color: #1f2937;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        h1 { font-size: 2rem; }
        h2 { font-size: 1.5rem; }

        .page-subtitle {
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* ===== BUTTON STYLES ===== */
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: 1px solid rgba(102, 126, 234, 0.4) !important;
            color: #fff !important;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            font-weight: 600;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
        }

        .btn-primary-custom:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            border-color: #764ba2 !important;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-custom:hover::before {
            left: 100%;
        }

        .btn-gold {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: 1px solid rgba(102, 126, 234, 0.6) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            transition: all 0.2s ease;
        }

        .btn-gold:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.25);
        }

        /* ===== TABLE STYLES ===== */
        .table-dark-row {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            transition: all 0.15s ease;
        }

        .table-dark-row:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            box-shadow: inset 0 0 8px rgba(102, 126, 234, 0.05);
        }

        /* ===== BADGE STYLES ===== */
        .badge-active {
            background: rgba(16, 185, 129, 0.15);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-warning {
            background: rgba(234, 179, 8, 0.15);
            color: #fde047;
            border: 1px solid rgba(234, 179, 8, 0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.18);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-blue {
            background: rgba(59, 130, 246, 0.18);
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-gold {
            background: rgba(234, 179, 8, 0.18);
            color: #fde047;
            border: 1px solid rgba(234, 179, 8, 0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* ===== INPUT FIELD STYLES ===== */
        .input-dark {
            background: rgba(0, 0, 0, 0.85) !important;
            border: 1px solid rgba(234, 179, 8, 0.25) !important;
            color: #e2e8f0 !important;
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
            padding: 10px 14px !important;
            font-size: 0.95rem !important;
        }

        .input-dark:focus {
            border-color: rgba(234, 179, 8, 0.6) !important;
            box-shadow: 0 0 0 3px rgba(234, 179, 8, 0.15) !important;
            background: rgba(0, 0, 0, 0.95) !important;
        }

        .input-dark::placeholder {
            color: #475569 !important;
            opacity: 0.7 !important;
        }

        .input-dark:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ===== ALERT STYLES ===== */
        .alert-gold {
            background: rgba(234, 179, 8, 0.08);
            border-left: 4px solid #eab308;
            border-radius: 10px;
            padding: 14px 18px;
            color: #fde047;
            backdrop-filter: blur(8px);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.08);
            border-left: 4px solid #ef4444;
            border-radius: 10px;
            padding: 14px 18px;
            color: #f87171;
            backdrop-filter: blur(8px);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.08);
            border-left: 4px solid #10b981;
            border-radius: 10px;
            padding: 14px 18px;
            color: #34d399;
            backdrop-filter: blur(8px);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.08);
            border-left: 4px solid #3b82f6;
            border-radius: 10px;
            padding: 14px 18px;
            color: #93c5fd;
            backdrop-filter: blur(8px);
        }

        /* ===== CONTENT AREA ===== */
        .main-content {
            background: linear-gradient(135deg, #000000 0%, #04112c 50%, #000000 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }

        /* ===== UTILITY CLASSES ===== */
        .sep-gold {
            border-color: rgba(234, 179, 8, 0.25);
        }

        .progress-gold {
            background: linear-gradient(90deg, #000000, #eab308);
            height: 4px;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-gold::after {
            content: '';
            display: block;
            height: 100%;
            background: linear-gradient(90deg, transparent, #fde047, transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Icon containers */
        .icon-blue {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border-radius: 12px;
            padding: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-green {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border-radius: 12px;
            padding: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-yellow {
            background: rgba(234, 179, 8, 0.15);
            color: #fde047;
            border-radius: 12px;
            padding: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-red {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border-radius: 12px;
            padding: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .icon-purple {
            background: rgba(59, 130, 246, 0.15);
            color: #93c5fd;
            border-radius: 12px;
            padding: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== SCROLLBAR STYLES ===== */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.3);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(234, 179, 8, 0.4), rgba(59, 130, 246, 0.3));
            border-radius: 3px;
            border: 1px solid rgba(234, 179, 8, 0.1);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(234, 179, 8, 0.6), rgba(59, 130, 246, 0.5));
        }

        /* ===== COMPONENT OVERRIDES ===== */
        flux-sidebar {
            --flux-sidebar-width: 260px;
            --flux-sidebar-bg: linear-gradient(180deg, #000000 0%, #020c24 40%, #000000 100%);
        }
        
        /* Responsive sidebar behavior */
        @media (max-width: 1023px) {
            flux-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 40;
                width: 260px;
                display: none;
            }
            
            flux-sidebar[open] {
                display: flex;
            }
            
            /* Overlay backdrop */
            flux-sidebar::before {
                content: '';
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: -1;
            }
        }
        
        @media (min-width: 1024px) {
            flux-sidebar {
                display: flex !important;
            }
        }

        /* Tables */
        table {
            border-collapse: collapse;
        }

        thead tr {
            border-bottom: 2px solid rgba(234, 179, 8, 0.2);
        }

        thead th {
            padding: 12px 16px;
            text-align: left;
            font-weight: 700;
            color: #e2e8f0;
            background: rgba(0, 0, 0, 0.4);
        }

        tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(234, 179, 8, 0.1);
        }

        /* Links */
        a {
            color: #60a5fa;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        a:hover {
            color: #93c5fd;
            text-decoration: underline;
        }

        /* Form elements */
        select,
        textarea {
            font-family: 'Figtree', sans-serif;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            h1 { font-size: 1.5rem; }
            h2 { font-size: 1.25rem; }
            .card-stat { min-height: auto; }
        }
    </style>
</head>
<body class="h-full">

<flux:sidebar stashable class="sidebar-custom" x-data="{ open: true }" @stash="open = false" @unstash="open = true">
    <div class="flex items-center justify-between px-4 py-5 gap-2" style="border-bottom: 1px solid #e5e7eb; min-height: 80px;">
        {{-- Logo/Brand --}}
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="sidebar-brand w-10 h-10 flex-shrink-0 flex items-center justify-center text-white text-lg font-black rounded-xl shadow-lg">🔧</div>
            <div class="hidden sm:block min-w-0">
                <p class="font-black text-gray-900 text-base tracking-wide truncate">MyUOS</p>
                <p class="text-xs font-medium truncate" style="color: #667eea;">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
        {{-- Close button for mobile --}}
        <flux:sidebar.toggle class="lg:hidden flex-shrink-0" icon="x-mark" style="color: #94a3b8;" />
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">

        {{-- SUPERADMIN --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="mb-2">
            <p class="px-3 py-1 text-xs font-bold uppercase tracking-widest" style="color: #667eea;">Superadmin</p>
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
               style="{{ $active ? '' : 'color: #6b7280;' }}">
                <span class="text-base w-5 text-center">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
        @endif

        {{-- MANAGER --}}
        @if(auth()->user()->isManager())
        <div class="mb-2 mt-2">
            <p class="px-3 py-1 text-xs font-bold uppercase tracking-widest" style="color: #667eea;">Manager</p>
        </div>
        @php $mgRoutes = [
            ['icon' => '⊞', 'label' => 'Dashboard',    'route' => 'manager.dashboard',  'match' => 'manager.*dashboard'],
            ['icon' => '📦', 'label' => 'Kelola Barang','route' => 'manager.items.index','match' => 'manager.items*'],
            ['icon' => '↓',  'label' => 'Barang Masuk', 'route' => 'manager.stock.in',   'match' => 'manager.stock.in*'],
            ['icon' => '⚠',  'label' => 'Stok Menipis', 'route' => 'manager.stock.low',  'match' => 'manager.stock.low'],
            ['icon' => '💰', 'label' => 'Laporan Pengeluaran', 'route' => 'manager.expenditure-report', 'match' => 'manager.expenditure-report'],
        ]; @endphp
        @foreach($mgRoutes as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 nav-item-hover {{ $active ? 'nav-item-active' : '' }}"
               style="{{ $active ? '' : 'color: #6b7280;' }}">
                <span class="text-base w-5 text-center">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
        @endif

        {{-- KASIR --}}
        @if(auth()->user()->isKasir())
        <div class="mb-2 mt-2">
            <p class="px-3 py-1 text-xs font-bold uppercase tracking-widest" style="color: #667eea;">Kasir</p>
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
               style="{{ $active ? '' : 'color: #6b7280;' }}">
                <span class="text-base w-5 text-center">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
        @endif
    </nav>

    {{-- User footer --}}
    <div class="px-4 py-4" style="border-top: 1px solid #e5e7eb;">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-black text-white"
                 style="background: linear-gradient(135deg, #667eea, #764ba2);">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate" style="color: #1f2937;">{{ auth()->user()->name }}</p>
                <p class="text-xs truncate" style="color: #6b7280;">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all"
                    style="color: #dc2626; background: #fee2e2;"
                    onmouseover="this.style.background='#fecaca'"
                    onmouseout="this.style.background='#fee2e2'">
                <span>⏻</span> Keluar
            </button>
        </form>
    </div>
</flux:sidebar>

<flux:main>
    {{-- Mobile header --}}
    <div class="flex items-center justify-between gap-2 px-4 py-3 lg:hidden" style="border-bottom: 1px solid #e5e7eb; background: #ffffff; min-height: 60px;">
        <flux:sidebar.toggle icon="bars-3" class="flex-shrink-0" style="color: #667eea;" />
        <span class="font-black text-gray-900 text-base flex-1 text-center truncate">🔧 MyUOS</span>
        <div class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center text-sm font-black text-white" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>

    <div class="p-6 relative min-h-screen">
        {{-- Garage decoration background on the right side --}}
        <div class="hidden lg:flex absolute top-0 right-0 pointer-events-none opacity-5 h-full items-center">
            <svg width="600" height="600" viewBox="0 0 600 600" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Garage building -->
                <rect x="100" y="150" width="400" height="300" fill="#667eea" stroke="#1f2937" stroke-width="3"/>
                
                <!-- Roof -->
                <polygon points="100,150 300,50 500,150" fill="#764ba2" stroke="#1f2937" stroke-width="3"/>
                
                <!-- Left door -->
                <rect x="120" y="200" width="150" height="220" fill="#d4a574" stroke="#1f2937" stroke-width="2"/>
                <circle cx="140" cy="310" r="8" fill="#1f2937"/>
                <line x1="140" y1="200" x2="140" y2="420" stroke="#1f2937" stroke-width="1" opacity="0.3"/>
                <line x1="195" y1="200" x2="195" y2="420" stroke="#1f2937" stroke-width="1" opacity="0.3"/>
                
                <!-- Right door -->
                <rect x="330" y="200" width="150" height="220" fill="#d4a574" stroke="#1f2937" stroke-width="2"/>
                <circle cx="350" cy="310" r="8" fill="#1f2937"/>
                <line x1="350" y1="200" x2="350" y2="420" stroke="#1f2937" stroke-width="1" opacity="0.3"/>
                <line x1="405" y1="200" x2="405" y2="420" stroke="#1f2937" stroke-width="1" opacity="0.3"/>
                
                <!-- Windows -->
                <rect x="145" y="100" width="50" height="40" fill="#93c5fd" stroke="#1f2937" stroke-width="2"/>
                <line x1="170" y1="100" x2="170" y2="140" stroke="#1f2937" stroke-width="1"/>
                <line x1="145" y1="120" x2="195" y2="120" stroke="#1f2937" stroke-width="1"/>
                
                <rect x="405" y="100" width="50" height="40" fill="#93c5fd" stroke="#1f2937" stroke-width="2"/>
                <line x1="430" y1="100" x2="430" y2="140" stroke="#1f2937" stroke-width="1"/>
                <line x1="405" y1="120" x2="455" y2="120" stroke="#1f2937" stroke-width="1"/>
                
                <!-- Tools on ground -->
                <circle cx="150" cy="480" r="8" fill="#eab308"/>
                <circle cx="200" cy="490" r="6" fill="#eab308"/>
                <circle cx="400" cy="485" r="7" fill="#eab308"/>
                <circle cx="450" cy="495" r="5" fill="#eab308"/>
                
                <!-- Ground -->
                <rect x="50" y="450" width="500" height="100" fill="#6b7280" opacity="0.3"/>
            </svg>
        </div>

        {{ $slot }}
    </div>
</flux:main>

@livewireScripts
</body>
</html>