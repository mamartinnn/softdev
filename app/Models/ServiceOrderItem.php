<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrderItem extends Model
{
    protected $fillable = [
        'service_order_id', 'item_id', 'item_name',
        'price_at_time', 'quantity', 'subtotal'
    ];

    protected function casts(): array
    {
        return [
            'price_at_time' => 'decimal:2',
            'subtotal'      => 'decimal:2',
        ];
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}