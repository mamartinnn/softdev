<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
        'item_id', 'service_order_id', 'type', 'quantity', 'price_per_unit', 'note', 'user_id'
    ];

    protected function casts(): array
    {
        return ['price_per_unit' => 'decimal:2'];
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}