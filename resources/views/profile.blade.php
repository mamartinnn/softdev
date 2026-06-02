<x-app-layout>
    <div class="max-w-5xl">
        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-4xl font-black mb-2" style="color: #f1f5f9;">
                Pengaturan <span class="text-gradient">Profil</span>
            </h1>
            <p class="text-sm font-medium" style="color: #94a3b8;">Kelola informasi akun dan keamanan Anda</p>
        </div>

        {{-- Profile Information Card --}}
        <div class="card-dark p-8 rounded-2xl mb-8">
            <div class="flex items-center gap-4 pb-6 mb-6 border-b" style="border-color: rgba(234,179,8,0.15);">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl"
                     style="background: linear-gradient(135deg, #1e40af, #000000); border: 1px solid rgba(234,179,8,0.3);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-lg font-bold" style="color: #f1f5f9;">{{ auth()->user()->name }}</p>
                    <p class="text-sm" style="color: #94a3b8;">{{ auth()->user()->email }}</p>
                </div>
            </div>
            <livewire:profile.update-profile-information-form />
        </div>

        {{-- Danger Zone --}}
        <div class="card-dark p-8 rounded-2xl border-red-500 border-opacity-40"
             style="border-color: rgba(239,68,68,0.4);">
            <h3 class="text-lg font-bold mb-6" style="color: #f87171;">⚠️ Zona Berbahaya</h3>
            <livewire:profile.delete-user-form />
        </div>
    </div>
</x-app-layout>
