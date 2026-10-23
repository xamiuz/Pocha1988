<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'district',
        'subdistrict',
        'delivery_fee',
        'estimated_delivery_minutes',
        'is_active',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'estimated_delivery_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFormattedFeeAttribute(): string
    {
        return 'Rp '.number_format($this->delivery_fee, 0, ',', '.');
    }
}
