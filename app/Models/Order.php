<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'delivery_zone_id',
        'delivery_district',
        'delivery_address',
        'delivery_notes',
        'subtotal',
        'delivery_fee',
        'discount',
        'unique_code',
        'total_amount',
        'payment_method',
        'payment_status',
        'payment_proof',
        'snap_token',
        'midtrans_transaction_id',
        'status',
        'confirmed_at',
        'cooking_at',
        'delivering_at',
        'delivered_at',
        'driver_name',
        'driver_phone',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cooking_at' => 'datetime',
        'delivering_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function deliveryZone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(OrderMessage::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'cooking' => 'Sedang Dimasak di Dapur',
            'delivering' => 'Driver Sedang Mengantar',
            'delivered' => 'Pesanan Selesai / Terantar',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
            'cooking' => 'bg-orange-500/20 text-orange-400 border-orange-500/30',
            'delivering' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
            'delivered' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
            'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
            default => 'bg-zinc-500/20 text-zinc-400 border-zinc-500/30',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format($this->total_amount, 0, ',', '.');
    }
}
