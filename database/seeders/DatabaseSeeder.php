<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DeliveryZone;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@1988pocha.com'],
            [
                'name' => 'Pocha Admin Samarinda',
                'password' => Hash::make('password123'),
            ]
        );

        // 1. Zona Pengantaran Area Samarinda
        $zones = [
            ['district' => 'Samarinda Kota', 'subdistrict' => 'Pelabuhan, Bugis, Karang Mumus, Pasar Pagi', 'delivery_fee' => 8000, 'estimated_delivery_minutes' => 25],
            ['district' => 'Samarinda Ulu', 'subdistrict' => 'Air Putih, Teluk Lerong Ilir, Gunung Kelua, Dadi Mulya', 'delivery_fee' => 10000, 'estimated_delivery_minutes' => 30],
            ['district' => 'Samarinda Ilir', 'subdistrict' => 'Selili, Sungai Dama, Sidomulyo, Sidodamai', 'delivery_fee' => 10000, 'estimated_delivery_minutes' => 30],
            ['district' => 'Sungai Pinang', 'subdistrict' => 'Bandara, Gunung Lingai, Sungai Pinang Dalam, Mugirejo', 'delivery_fee' => 12000, 'estimated_delivery_minutes' => 35],
            ['district' => 'Samarinda Seberang', 'subdistrict' => 'Baqa, Mesjid, Sungai Keledang, Gunung Panjang', 'delivery_fee' => 14000, 'estimated_delivery_minutes' => 40],
            ['district' => 'Palaran', 'subdistrict' => 'Rawa Makmur, Bukuan, Handil Bakti', 'delivery_fee' => 18000, 'estimated_delivery_minutes' => 50],
            ['district' => 'Loa Janan Ilir', 'subdistrict' => 'Simpang Tiga, Harapan Baru, Tani Aman', 'delivery_fee' => 15000, 'estimated_delivery_minutes' => 45],
            ['district' => 'Sambutan', 'subdistrict' => 'Sungai Kapih, Sambutan, Pulau Atas', 'delivery_fee' => 15000, 'estimated_delivery_minutes' => 45],
            ['district' => 'Samarinda Utara', 'subdistrict' => 'Lempake, Sempaja Selatan, Sempaja Timur, Tanah Merah', 'delivery_fee' => 16000, 'estimated_delivery_minutes' => 45],
            ['district' => 'Sungai Kunjang', 'subdistrict' => 'Loa Bakung, Karang Anyar, Teluk Lerong Ulu', 'delivery_fee' => 12000, 'estimated_delivery_minutes' => 35],
        ];

        $createdZones = [];
        foreach ($zones as $zone) {
            $createdZones[] = DeliveryZone::create($zone);
        }

        // 2. Kategori Menu Khas 1988 Pocha
        $catStreetFood = Category::create([
            'name' => 'Korean Street Food',
            'slug' => 'korean-street-food',
            'icon' => '🍢',
            'description' => 'Jajanan khas tenda jalanan Korea yang gurih & autentik.',
            'sort_order' => 1,
        ]);

        $catChicken = Category::create([
            'name' => 'Korean Fried Chicken',
            'slug' => 'korean-fried-chicken',
            'icon' => '🍗',
            'description' => 'Ayam krispi berbalut bumbu khas Yangnyeom dan Honey Butter.',
            'sort_order' => 2,
        ]);

        $catNoodles = Category::create([
            'name' => 'Ramyeon & Noodles',
            'slug' => 'ramyeon-noodles',
            'icon' => '🍜',
            'description' => 'Mie kuah pedas hangat ala drama Korea cocok untuk malam hari.',
            'sort_order' => 3,
        ]);

        $catRice = Category::create([
            'name' => 'Rice Bowl & Kimbap',
            'slug' => 'rice-bowl-kimbap',
            'icon' => '🍱',
            'description' => 'Menu nasi kenyang dan gulungan rumput laut nikmat.',
            'sort_order' => 4,
        ]);

        $catDrinks = Category::create([
            'name' => 'Pocha Drinks & Dessert',
            'slug' => 'drinks-dessert',
            'icon' => '🍹',
            'description' => 'Minuman segar, Mocktail Pocha, dan Bingsoo dingin.',
            'sort_order' => 5,
        ]);

        // 3. Menu Items
        $items = [
            // Street Food
            [
                'category_id' => $catStreetFood->id,
                'name' => 'Tteokbokki Spicy Mozzarella',
                'korean_name' => '치즈 떡볶이',
                'description' => 'Kue beras kenyal saus gochujang pedas manis dengan lelehan keju mozzarella melimpah dan odeng.',
                'price' => 32000,
                'image_url' => 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => true,
                'spicy_level' => 'medium',
                'preparation_time_minutes' => 15,
            ],
            [
                'category_id' => $catStreetFood->id,
                'name' => 'Eomuk Tang / Odeng Broth (3 Pcs)',
                'korean_name' => '어묵탕',
                'description' => 'Sate fishcake Korea disajikan dengan kuah kaldu ikan teri & lobak gurih hangat.',
                'price' => 24000,
                'image_url' => 'https://images.unsplash.com/photo-1541544741938-0af808871cc0?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => true,
                'spicy_level' => 'none',
                'preparation_time_minutes' => 10,
            ],
            [
                'category_id' => $catStreetFood->id,
                'name' => 'Gimmari Deep Fried (4 Pcs)',
                'korean_name' => '김말이 튀김',
                'description' => 'Bihun Korea berbalut rumput laut digoreng super renyah, cocolan saus tteokbokki terbaik.',
                'price' => 20000,
                'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => false,
                'spicy_level' => 'mild',
                'preparation_time_minutes' => 12,
            ],

            // Chicken
            [
                'category_id' => $catChicken->id,
                'name' => 'Yangnyeom Spicy Glazed Chicken',
                'korean_name' => '양념 치킨',
                'description' => 'Boneless fried chicken krispi diselimuti saus yangnyeom manis pedas khas pocha Seoul.',
                'price' => 38000,
                'image_url' => 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => true,
                'spicy_level' => 'medium',
                'preparation_time_minutes' => 20,
            ],
            [
                'category_id' => $catChicken->id,
                'name' => 'Honey Garlic Butter Chicken',
                'korean_name' => '허니버터 치킨',
                'description' => 'Ayam renyah beraroma bawang putih butter panggang dengan sentuhan madu manis gurih.',
                'price' => 38000,
                'image_url' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => true,
                'spicy_level' => 'none',
                'preparation_time_minutes' => 20,
            ],

            // Noodles
            [
                'category_id' => $catNoodles->id,
                'name' => '1988 Pocha Special Ramyeon',
                'korean_name' => '1988 스페셜 라면',
                'description' => 'Mie ramen Korea dengan kuah gurih pedas, telur setengah matang, sosis, keju slice, dan daun bawang.',
                'price' => 29000,
                'image_url' => 'https://images.unsplash.com/photo-1612927601601-6638404737ce?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => true,
                'spicy_level' => 'medium',
                'preparation_time_minutes' => 15,
            ],
            [
                'category_id' => $catNoodles->id,
                'name' => 'Japchae Glass Noodles',
                'korean_name' => '잡채',
                'description' => 'Dangmyeon tumis minyak wijen dengan irisan daging sapi, jamur kuping, wortel dan bayam.',
                'price' => 35000,
                'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => false,
                'spicy_level' => 'none',
                'preparation_time_minutes' => 15,
            ],

            // Rice & Kimbap
            [
                'category_id' => $catRice->id,
                'name' => 'Classic Beef Bulgogi Kimbap',
                'korean_name' => '소고기 김밥',
                'description' => 'Roll sushi Korea isi daging bulgogi manis gurih, acar lobak kuning dan telur dadar.',
                'price' => 30000,
                'image_url' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => true,
                'spicy_level' => 'none',
                'preparation_time_minutes' => 15,
            ],
            [
                'category_id' => $catRice->id,
                'name' => 'Kimchi Bokkeumbap with Egg',
                'korean_name' => '김치 볶음밥',
                'description' => 'Nasi goreng kimchi fermentasi pedas wangi minyak wijen dengan topping sunny side egg & nori.',
                'price' => 28000,
                'image_url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => false,
                'spicy_level' => 'medium',
                'preparation_time_minutes' => 15,
            ],

            // Drinks
            [
                'category_id' => $catDrinks->id,
                'name' => 'Korean Strawberry Milky Ade',
                'korean_name' => '딸기 라떼',
                'description' => 'Susu segar berpadu dengan selai stroberi chunks asli yang asam manis menyegarkan.',
                'price' => 19000,
                'image_url' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => true,
                'spicy_level' => 'none',
                'preparation_time_minutes' => 8,
            ],
            [
                'category_id' => $catDrinks->id,
                'name' => 'Yuzu Honey Sparkling Soda',
                'korean_name' => '유자 에이드',
                'description' => 'Sari jeruk yuzu khas Korea dengan soda dingin bersoda dan madu alami.',
                'price' => 18000,
                'image_url' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => false,
                'spicy_level' => 'none',
                'preparation_time_minutes' => 5,
            ],
            [
                'category_id' => $catDrinks->id,
                'name' => 'Oksusu Cha (Corn Silk Tea Iced)',
                'korean_name' => '옥수수차',
                'description' => 'Teh jagung panggang dingin otentik Korea yang harum dan bebas kafein.',
                'price' => 12000,
                'image_url' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=700&q=80',
                'is_available' => true,
                'is_popular' => false,
                'spicy_level' => 'none',
                'preparation_time_minutes' => 5,
            ],
        ];

        $createdMenuItems = [];
        foreach ($items as $item) {
            $createdMenuItems[] = MenuItem::create($item);
        }

        // 4. Sample Dummy Order untuk demonstrasi alur live
        $sampleOrder = Order::create([
            'order_number' => 'POCHA-'.strtoupper(Str::random(6)),
            'customer_name' => 'Rizky Pratama',
            'customer_phone' => '081255554321',
            'delivery_zone_id' => $createdZones[0]->id,
            'delivery_district' => 'Samarinda Kota',
            'delivery_address' => 'Jl. Awang Long No. 14, Samarinda Kota, Kalimantan Timur',
            'delivery_notes' => 'Tolong antar ke pagar hitam samping kantor pos, sambal dipisah.',
            'subtotal' => 61000,
            'delivery_fee' => 8000,
            'discount' => 0,
            'total_amount' => 69000,
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'status' => 'delivering', // status pas sedang diantar sehingga bisa coba fitur geser
            'confirmed_at' => now()->subMinutes(25),
            'cooking_at' => now()->subMinutes(20),
            'delivering_at' => now()->subMinutes(8),
            'driver_name' => 'Ahmad Kurir (Pocha Express Samarinda)',
            'driver_phone' => '082199881988',
        ]);

        OrderItem::create([
            'order_id' => $sampleOrder->id,
            'menu_item_id' => $createdMenuItems[0]->id,
            'item_name' => 'Tteokbokki Spicy Mozzarella',
            'unit_price' => 32000,
            'quantity' => 1,
            'subtotal' => 32000,
            'notes' => 'Mozzarella banyak ya',
        ]);

        OrderItem::create([
            'order_id' => $sampleOrder->id,
            'menu_item_id' => $createdMenuItems[5]->id,
            'item_name' => '1988 Pocha Special Ramyeon',
            'unit_price' => 29000,
            'quantity' => 1,
            'subtotal' => 29000,
            'notes' => 'Level pedas sedang',
        ]);
    }
}
