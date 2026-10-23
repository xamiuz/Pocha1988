<?php

namespace App\Models;

use Database\Factories\PromoFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promo extends Model
{
    /** @use HasFactory<PromoFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'tagline',
        'badge_text',
        'description',
        'image_url',
        'promo_code',
        'discount_percentage',
        'original_price',
        'promo_price',
        'menu_item_id',
        'button_text',
        'button_link',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'discount_percentage' => 'integer',
            'original_price' => 'decimal:2',
            'promo_price' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    protected function formattedPromoPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->promo_price ? 'Rp '.number_format($this->promo_price, 0, ',', '.') : null
        );
    }

    protected function formattedOriginalPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->original_price ? 'Rp '.number_format($this->original_price, 0, ',', '.') : null
        );
    }
}
