<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name', 'sku', 'price', 'cost_price', 'stock', 'unit', 'image', 'description', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function serviceOrderItems()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    // Scope untuk barang aktif saja
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Cek apakah stok menipis (kurang dari 5)
    public function isLowStock(): bool
    {
        return $this->stock < 5;
    }
}