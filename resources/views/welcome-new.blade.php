<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyUOS — Sistem Manajemen Bengkel Servis</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .hero-gradient {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: #667eea;
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(102, 126, 234, 0.15);
        }

        .text-gradient-brand {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-brand-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: 1px solid #667eea;
            color: #fff;
            transition: all 0.3s ease;
        }

        .btn-brand-primary:hover {
            border-color: #764ba2;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        .btn-brand-secondary {
            background: #ffffff;
            border: 1px solid #667eea;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .btn-brand-secondary:hover {
            background: rgba(102, 126, 234, 0.05);
            border-color: #764ba2;
            color: #764ba2;
        }
    </style>
</head>
<body class="font-sans antialiased">
    {{-- Navigation --}}
    <nav class="fixed top-0 w-full z-50" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #e5e7eb;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-purple-600 to-purple-800 flex items-center justify-center text-white font-black">🔧</div>
                <span class="text-xl font-black text-gray-900">MyUOS</span>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('manager.dashboard') }}" class="px-4 py-2 rounded-lg font-semibold text-sm" style="color: #667eea;">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-sm" style="color: #dc2626;">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg font-semibold text-sm" style="color: #667eea;">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg font-semibold text-sm btn-brand-primary">Daftar</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="min-h-screen flex items-center justify-center pt-20 px-4">
        <div class="text-center max-w-4xl">
            <h1 class="text-5xl md:text-6xl font-black mb-6" style="color: #1f2937;">
                Kelola Bengkel Anda dengan
                <span class="text-gradient-brand">Mudah & Efisien</span>
            </h1>
            <p class="text-lg mb-8" style="color: #6b7280;">
                MyUOS adalah sistem manajemen bengkel servis yang komprehensif. Kelola inventory, tracking servis, dan laporan dengan dashboard intuitif kami.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('manager.dashboard') }}" class="px-8 py-3 rounded-lg font-bold text-base btn-brand-primary inline-block">
                        Buka Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-3 rounded-lg font-bold text-base btn-brand-primary inline-block">
                        Masuk Sekarang
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3 rounded-lg font-bold text-base btn-brand-secondary inline-block">
                        Coba Gratis
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-4xl font-black text-center mb-16" style="color: #1f2937;">
                Fitur <span class="text-gradient-brand">Unggulan</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Feature 1 --}}
                <div class="feature-card p-8 rounded-2xl">
                    <div class="w-14 h-14 rounded-xl mb-4" style="background: rgba(102, 126, 234, 0.15); display: flex; align-items: center; justify-content: center; font-size: 2rem;">📦</div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1f2937;">Manajemen Inventory</h3>
                    <p style="color: #6b7280;">Kelola stok barang dengan efisien. Pantau stok real-time dan dapatkan notifikasi untuk barang yang menipis.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="feature-card p-8 rounded-2xl">
                    <div class="w-14 h-14 rounded-xl mb-4" style="background: rgba(118, 75, 162, 0.15); display: flex; align-items: center; justify-center: center; font-size: 2rem;">📋</div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1f2937;">Tracking Servis</h3>
                    <p style="color: #6b7280;">Kelola order servis dari awal hingga selesai. Pantau status pekerjaan dan berikan update kepada pelanggan.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="feature-card p-8 rounded-2xl">
                    <div class="w-14 h-14 rounded-xl mb-4" style="background: rgba(52, 211, 153, 0.15); display: flex; align-items: center; justify-content: center; font-size: 2rem;">📊</div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1f2937;">Laporan & Analitik</h3>
                    <p style="color: #6b7280;">Dapatkan insights mendalam tentang performa bisnis Anda. Dashboard dengan visualisasi data yang jelas.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="feature-card p-8 rounded-2xl">
                    <div class="w-14 h-14 rounded-xl mb-4" style="background: rgba(59, 130, 246, 0.15); display: flex; align-items: center; justify-content: center; font-size: 2rem;">👥</div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1f2937;">Multi-Role Access</h3>
                    <p style="color: #6b7280;">Atur akses berbeda untuk Admin, Manager, dan Kasir. Kontrol penuh atas siapa yang bisa apa.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="feature-card p-8 rounded-2xl">
                    <div class="w-14 h-14 rounded-xl mb-4" style="background: rgba(168, 85, 247, 0.15); display: flex; align-items: center; justify-content: center; font-size: 2rem;">🔐</div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1f2937;">Keamanan Data</h3>
                    <p style="color: #6b7280;">Semua data Anda aman dan terenkripsi. Backup otomatis dan recovery system yang handal.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="feature-card p-8 rounded-2xl">
                    <div class="w-14 h-14 rounded-xl mb-4" style="background: rgba(239, 68, 68, 0.15); display: flex; align-items: center; justify-content: center; font-size: 2rem;">⚡</div>
                    <h3 class="text-xl font-bold mb-3" style="color: #1f2937;">User-Friendly</h3>
                    <p style="color: #6b7280;">Interface yang intuitif dan mudah digunakan. Tidak perlu training khusus untuk team Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 px-4">
        <div class="max-w-4xl mx-auto hero-gradient rounded-2xl p-12 text-center">
            <h2 class="text-3xl md:text-4xl font-black mb-6" style="color: #1f2937;">
                Siap untuk Meningkatkan Efisiensi?
            </h2>
            <p class="text-lg mb-8" style="color: #6b7280;">
                Mulai gunakan MyUOS hari ini dan rasakan perbedaannya.
            </p>
            @auth
                <a href="{{ route('manager.dashboard') }}" class="px-8 py-3 rounded-lg font-bold text-base btn-brand-primary inline-block">
                    Buka Dashboard →
                </a>
            @else
                <a href="{{ route('register') }}" class="px-8 py-3 rounded-lg font-bold text-base btn-brand-primary inline-block">
                    Mulai Gratis Sekarang
                </a>
            @endauth
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-12 px-4 border-t" style="border-color: #e5e7eb;">
        <div class="max-w-6xl mx-auto text-center" style="color: #6b7280;">
            <p class="mb-2">&copy; 2026 MyUOS - Sistem Manajemen Bengkel Servis</p>
            <p class="text-sm">Developed with <span style="color: #dc2626;">❤️</span> for workshop management</p>
        </div>
    </footer>
</body>
</html>
