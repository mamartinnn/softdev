<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'vehicle_type', 'plate_number',
        'complaint', 'status', 'service_fee', 'total_items_cost',
        'grand_total', 'notes', 'user_id', 'completed_at'
    ];

    protected function casts(): array
    {
        return [
            'service_fee'       => 'decimal:2',
            'total_items_cost'  => 'decimal:2',
            'grand_total'       => 'decimal:2',
            'completed_at'      => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    // Generate nomor order otomatis
    public static function generateOrderNumber(): string
    {
        $date   = now()->format('Ymd');
        $prefix = "SRV-{$date}-";
        $last   = self::where('order_number', 'like', $prefix . '%')
                        ->orderByDesc('order_number')
                        ->first();

        $seq = $last ? (int) substr($last->order_number, -3) + 1 : 1;
        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    // Recalculate grand total
    public function recalculateTotal(): void
    {
        $this->total_items_cost = $this->items->sum('subtotal');
        $this->grand_total      = $this->total_items_cost + $this->service_fee;
        $this->save();
    }
}