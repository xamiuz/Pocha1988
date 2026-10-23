<?php

namespace Database\Factories;

use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promo>
 */
class PromoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'tagline' => fake()->sentence(4),
            'badge_text' => 'PROMO SPESIAL',
            'description' => fake()->paragraph(),
            'image_url' => 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=800&q=80',
            'promo_code' => 'POCHA'.fake()->numberBetween(10, 99),
            'discount_percentage' => 20,
            'original_price' => 50000,
            'promo_price' => 40000,
            'button_text' => 'Pesan Sekarang',
            'button_link' => '#menu',
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
