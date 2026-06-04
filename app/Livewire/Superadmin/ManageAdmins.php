<?php

namespace App\Livewire\Superadmin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class ManageAdmins extends Component
{
    use WithPagination;

    public string $search   = '';
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $role     = 'kasir';
    public ?int $editingId  = null;
    public bool $showModal  = false;

    protected function rules(): array
    {
        $emailRule = $this->editingId
            ? "required|email|unique:users,email,{$this->editingId}"
            : 'required|email|unique:users,email';

        return [
            'name'     => 'required|string|max:100',
            'email'    => $emailRule,
            'password' => $this->editingId ? 'nullable|min:6' : 'required|min:6',
            'role'     => 'required|in:kasir,manager',
        ];
    }

    public function openCreate(): void
    {
        $this->reset(['name', 'email', 'password', 'role', 'editingId']);
        $this->role      = 'kasir';
        $this->showModal = true;
    }

    public function openEdit(User $user): void
    {
        $this->editingId = $user->id;
        $this->name      = $user->name;
        $this->email     = $user->email;
        $this->role      = $user->role;
        $this->password  = '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingId) {
            User::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Data admin berhasil diperbarui.');
        } else {
            User::create($data);
            $this->dispatch('notify', type: 'success', message: 'Admin baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->reset(['name', 'email', 'password', 'role', 'editingId']);
    }

    public function toggleActive(User $user): void
    {
        $user->update(['is_active' => !$user->is_active]);
    }

    public function delete(User $user): void
    {
        if ($user->role === 'superadmin') {
            $this->dispatch('notify', type: 'error', message: 'Superadmin tidak bisa dihapus.');
            return;
        }
        $user->delete();
        $this->dispatch('notify', type: 'success', message: 'Admin berhasil dihapus.');
    }

    public function render()
    {
        $admins = User::where('role', '!=', 'superadmin')
            ->when($this->search, fn($q) => $q->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(10);

        return view('livewire.superadmin.manage-admins', compact('admins'))
            ->layout('layouts.app');
    }
}