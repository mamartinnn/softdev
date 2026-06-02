<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black" style="color: #000000;">Kelola <span class="text-gradient">Admin</span></h1>
            <p class="text-sm mt-1" style="color: #475569;">Manajemen akun kasir dan manager</p>
        </div>
        <button wire:click="openCreate"
                class="btn-gold px-4 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
            ➕ Tambah Admin
        </button>
    </div>

    @if(session('success'))
    <div class="alert-success mb-4 flex items-center gap-2">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert-danger mb-4 flex items-center gap-2">❌ {{ session('error') }}</div>
    @endif

    <div class="mb-5">
        <input wire:model.live.debounce="search" placeholder="🔍 Cari nama atau email..."
               class="px-4 py-2.5 text-sm rounded-xl w-full max-w-sm"
               style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
    </div>

    <div class="card-dark overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(234,179,8,0.15); background: rgba(15,23,42,0.6);">
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Nama</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Email</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Role</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold uppercase tracking-wide" style="color: #eab308;">Status</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr class="table-dark-row" style="border-bottom: 1px solid rgba(234,179,8,0.06);">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black text-white"
                                     style="background: linear-gradient(135deg, {{ $admin->role === 'manager' ? '#1d4ed8,#7c3aed' : '#059669,#10b981' }});">
                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold" style="color: #000000;">{{ $admin->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5" style="color: #000000;">{{ $admin->email }}</td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold"
                                  style="{{ $admin->role === 'manager' ? 'background:rgba(59,130,246,0.15);color:#93c5fd;' : 'background:rgba(16,185,129,0.15);color:#34d399;' }}">
                                {{ ucfirst($admin->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold"
                                  style="{{ $admin->is_active ? 'background:rgba(16,185,129,0.15);color:#34d399;' : 'background:rgba(148,163,184,0.15);color:#64748b;' }}">
                                {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2 justify-end">
                                <button wire:click="openEdit({{ $admin->id }})"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                        style="background: rgba(59,130,246,0.12); color: #93c5fd; border: 1px solid rgba(59,130,246,0.2);">
                                    ✏ Edit
                                </button>
                                <button wire:click="toggleActive({{ $admin->id }})"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all"
                                        style="background: rgba(234,179,8,0.12); color: #fde047; border: 1px solid rgba(234,179,8,0.2);">
                                    {{ $admin->is_active ? '🔇 Nonaktif' : '✔ Aktifkan' }}
                                </button>
                            
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center" style="color: #334155;">Belum ada admin terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($admins->hasPages())
        <div class="px-5 py-4" style="border-top: 1px solid rgba(234,179,8,0.1);">
            {{ $admins->links() }}
        </div>
        @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(2,6,23,0.85); backdrop-filter: blur(8px);"
         wire:click.self="$set('showModal', false)">
        <div class="w-full max-w-md rounded-2xl p-6"
             style="background: linear-gradient(135deg,#0f172a,#1e1b4b); border: 1px solid rgba(234,179,8,0.3);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-black" style="color: #fde047;">
                    {{ $editingId ? '✏ Edit Admin' : '➕ Tambah Admin Baru' }}
                </h3>
                <button wire:click="$set('showModal', false)" style="color: #475569;">✕</button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Nama Lengkap</label>
                    <input wire:model="name" type="text" placeholder="John Doe"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('name') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Email</label>
                    <input wire:model="email" type="email" placeholder="admin@myuos.com"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('email') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">
                        Password {{ $editingId ? '(Kosongkan jika tidak diubah)' : '' }}
                    </label>
                    <input wire:model="password" type="password" placeholder="Minimal 6 karakter"
                           class="w-full px-3 py-2.5 rounded-xl text-sm"
                           style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0; outline: none;" />
                    @error('password') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold mb-1.5 block" style="color: #94a3b8;">Role</label>
                    <select wire:model="role"
                            class="w-full px-3 py-2.5 rounded-xl text-sm"
                            style="background: rgba(15,23,42,0.8); border: 1px solid rgba(234,179,8,0.2); color: #e2e8f0;">
                        <option value="kasir">Kasir</option>
                        <option value="manager">Manager</option>
                    </select>
                    @error('role') <p class="text-xs mt-1" style="color: #f87171;">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button wire:click="save"
                        class="flex-1 py-2.5 rounded-xl font-bold text-sm btn-gold">
                    {{ $editingId ? 'Simpan Perubahan' : 'Tambah Admin' }}
                </button>
                <button wire:click="$set('showModal', false)"
                        class="px-5 py-2.5 rounded-xl font-semibold text-sm"
                        style="background: rgba(148,163,184,0.1); color: #94a3b8; border: 1px solid rgba(148,163,184,0.15);">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>