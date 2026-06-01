<?php

namespace App\Models;


use Illuminate\Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
   use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Helper methods untuk cek role
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isKasir(): bool
    {
        return $this->role === 'kasir';
    }

    // Relationships
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}