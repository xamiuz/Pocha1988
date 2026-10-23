<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promo::create([
            'title' => 'DISKON 20% PESANAN PERTAMA',
            'tagline' => 'Untuk Pesanan Pertama Kamu di Samarinda',
            'badge_text' => 'PROMO SPESIAL POCHA',
            'description' => 'Gunakan kode promo saat pemesanan untuk menikmati jajanan Korea autentik dengan harga hemat se-Kota Samarinda.',
            'image_url' => 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=700&q=80',
            'promo_code' => 'POCHA88',
            'discount_percentage' => 20,
            'original_price' => 50000,
            'promo_price' => 40000,
            'button_text' => 'Pesan Sekarang',
            'button_link' => '#menu',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Promo::create([
            'title' => 'PAKET KOMBO KENYANG',
            'tagline' => 'Tteokbokki + Chicken + Drink Hemat',
            'badge_text' => 'KOMBO HEMAT SAMARINDA',
            'description' => 'Lebih kenyang dan lebih hemat bareng teman atau keluarga dengan paket kombo favorit street food khas Seoul.',
            'image_url' => 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=700&q=80',
            'promo_code' => 'KOMBOPOCHA',
            'discount_percentage' => 15,
            'original_price' => 65000,
            'promo_price' => 52000,
            'button_text' => 'Lihat Menu Kombo',
            'button_link' => '#menu',
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
