<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyUOS - @yield('title', 'Bengkel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @fluxStyles
    @livewireStyles
</head>
<body class="h-full bg-zinc-50 dark:bg-zinc-900">

<flux:sidebar sticky stashable class="bg-white dark:bg-zinc-800 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <div class="flex items-center gap-3 px-4 py-5 border-b border-zinc-100 dark:border-zinc-700">
        <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">🔧</div>
        <div>
            <p class="font-bold text-zinc-900 dark:text-white text-sm">MyUOS</p>
            <p class="text-xs text-zinc-400">{{ ucfirst(auth()->user()->role) }}</p>
        </div>
    </div>

    <flux:navlist class="px-2 py-4">

        {{-- SUPERADMIN --}}
        @if(auth()->user()->isSuperAdmin())
        <flux:navlist.group heading="Superadmin">
            <flux:navlist.item icon="squares-2x2" :href="route('superadmin.dashboard')" :current="request()->routeIs('superadmin.*dashboard')">Dashboard</flux:navlist.item>
            <flux:navlist.item icon="users" :href="route('superadmin.admins.index')" :current="request()->routeIs('superadmin.admins*')">Kelola Admin</flux:navlist.item>
            <flux:navlist.item icon="clipboard-document-list" :href="route('superadmin.service-history')" :current="request()->routeIs('superadmin.service*')">Histori Servis</flux:navlist.item>
            <flux:navlist.item icon="chart-bar" :href="route('superadmin.reports')" :current="request()->routeIs('superadmin.reports*')">Laporan Pendapatan</flux:navlist.item>
        </flux:navlist.group>
        @endif

        {{-- MANAGER --}}
        @if(auth()->user()->isManager())
        <flux:navlist.group heading="Manager">
            <flux:navlist.item icon="squares-2x2" :href="route('manager.dashboard')" :current="request()->routeIs('manager.*dashboard')">Dashboard</flux:navlist.item>
            <flux:navlist.item icon="archive-box" :href="route('manager.items.index')" :current="request()->routeIs('manager.items*')">Kelola Barang</flux:navlist.item>
            <flux:navlist.item icon="arrow-down-tray" :href="route('manager.stock.in')" :current="request()->routeIs('manager.stock*')">Barang Masuk</flux:navlist.item>
            <flux:navlist.item icon="exclamation-triangle" :href="route('manager.stock.low')" :current="request()->routeIs('manager.stock.low')">Stok Menipis</flux:navlist.item>
        </flux:navlist.group>
        @endif

        {{-- KASIR --}}
        @if(auth()->user()->isKasir())
        <flux:navlist.group heading="Kasir">
            <flux:navlist.item icon="squares-2x2" :href="route('kasir.dashboard')" :current="request()->routeIs('kasir.*dashboard')">Dashboard</flux:navlist.item>
            <flux:navlist.item icon="plus-circle" :href="route('kasir.orders.create')" :current="request()->routeIs('kasir.orders.create')">Buat Order Servis</flux:navlist.item>
            <flux:navlist.item icon="list-bullet" :href="route('kasir.orders.index')" :current="request()->routeIs('kasir.orders.index')">Daftar Order</flux:navlist.item>
        </flux:navlist.group>
        @endif

    </flux:navlist>

    <div class="mt-auto px-4 py-4 border-t border-zinc-100 dark:border-zinc-700">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-sm font-semibold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-zinc-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <flux:button type="submit" variant="ghost" size="sm" class="w-full justify-start text-red-500 hover:text-red-600">
                <flux:icon.arrow-right-start-on-rectangle class="size-4 mr-2"/>
                Keluar
            </flux:button>
        </form>
    </div>
</flux:sidebar>

<flux:main class="min-h-screen">
    <div class="flex items-center gap-3 px-4 py-3 border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 lg:hidden">
        <flux:sidebar.toggle icon="bars-3" />
        <span class="font-semibold text-zinc-900 dark:text-white">MyUOS</span>
    </div>

    <div class="p-6">
        {{ $slot }}
    </div>
</flux:main>

@fluxScripts
@livewireScripts
</body>
</html>