
<div>
    <div class="flex items-center justify-between mb-6">
        <flux:heading size="xl">Kelola Admin</flux:heading>
        <flux:button wire:click="openCreate" variant="primary" icon="plus">
            Tambah Admin
        </flux:button>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('success') }}</flux:callout>
    @endif
    @if(session('error'))
        <flux:callout variant="danger" icon="x-circle" class="mb-4">{{ session('error') }}</flux:callout>
    @endif

    {{-- Search --}}
    <flux:input wire:model.live.debounce="search" placeholder="Cari nama atau email..." icon="magnifying-glass" class="mb-4 max-w-xs" />

    {{-- Tabel --}}
    <flux:table>
        <flux:columns>
            <flux:column>Nama</flux:column>
            <flux:column>Email</flux:column>
            <flux:column>Role</flux:column>
            <flux:column>Status</flux:column>
            <flux:column>Aksi</flux:column>
        </flux:columns>
        <flux:rows>
            @forelse($admins as $admin)
            <flux:row>
                <flux:cell>{{ $admin->name }}</flux:cell>
                <flux:cell>{{ $admin->email }}</flux:cell>
                <flux:cell>
                    <flux:badge variant="{{ $admin->role === 'manager' ? 'blue' : 'green' }}" size="sm">
                        {{ ucfirst($admin->role) }}
                    </flux:badge>
                </flux:cell>
                <flux:cell>
                    <flux:badge variant="{{ $admin->is_active ? 'green' : 'zinc' }}" size="sm">
                        {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                    </flux:badge>
                </flux:cell>
                <flux:cell>
                    <div class="flex gap-2">
                        <flux:button wire:click="openEdit({{ $admin->id }})" size="sm" icon="pencil" />
                        <flux:button wire:click="toggleActive({{ $admin->id }})" size="sm" variant="ghost" icon="{{ $admin->is_active ? 'eye-slash' : 'eye' }}" />
                        <flux:button wire:click="delete({{ $admin->id }})" size="sm" variant="danger" icon="trash"
                            wire:confirm="Yakin ingin menghapus admin ini?" />
                    </div>
                </flux:cell>
            </flux:row>
            @empty
            <flux:row>
                <flux:cell colspan="5" class="text-center text-zinc-400 py-8">Belum ada admin.</flux:cell>
            </flux:row>
            @endforelse
        </flux:rows>
    </flux:table>
    <div class="mt-4">{{ $admins->links() }}</div>

    {{-- Modal Tambah/Edit --}}
    <flux:modal wire:model="showModal" class="max-w-md w-full">
        <flux:heading>{{ $editingId ? 'Edit Admin' : 'Tambah Admin Baru' }}</flux:heading>
        <div class="mt-4 space-y-4">
            <flux:input wire:model="name" label="Nama Lengkap" placeholder="Nama admin" />
            <flux:input wire:model="email" label="Email" type="email" placeholder="email@myuos.com" />
            <flux:input wire:model="password" label="{{ $editingId ? 'Password Baru (kosongkan jika tidak diubah)' : 'Password' }}" type="password" />
            <flux:select wire:model="role" label="Role">
                <option value="kasir">Kasir</option>
                <option value="manager">Manager</option>
            </flux:select>
        </div>
        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

        <div class="flex justify-end gap-3 mt-6">
            <flux:button wire:click="$set('showModal', false)" variant="ghost">Batal</flux:button>
            <flux:button wire:click="save" variant="primary">Simpan</flux:button>
        </div>
    </flux:modal>
</div>
