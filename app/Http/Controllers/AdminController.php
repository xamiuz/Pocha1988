<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\PaymentSetting;
use App\Models\Promo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Dashboard ringkasan order & status operasional khusus HARI INI (Live Kitchen).
     */
    public function dashboard(): View
    {
        $today = now()->today();

        $orders = Order::with(['items', 'messages'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $counts = [
            'total' => Order::whereDate('created_at', $today)->count(),
            'pending' => Order::whereDate('created_at', $today)->where('status', 'pending')->count(),
            'cooking' => Order::whereDate('created_at', $today)->where('status', 'cooking')->count(),
            'delivering' => Order::whereDate('created_at', $today)->where('status', 'delivering')->count(),
            'delivered' => Order::whereDate('created_at', $today)->where('status', 'delivered')->count(),
            'revenue' => Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('admin.dashboard', compact('orders', 'counts'));
    }

    /**
     * Real-time Polling API untuk mendeteksi pesanan baru masuk hari ini.
     */
    public function checkNewOrders(Request $request): JsonResponse
    {
        $lastOrderId = (int) $request->input('last_order_id', 0);
        $today = now()->today();

        $newOrdersQuery = Order::whereDate('created_at', $today);

        if ($lastOrderId > 0) {
            $newOrdersQuery->where('id', '>', $lastOrderId);
        }

        $newOrders = $newOrdersQuery->orderBy('id', 'desc')->limit(5)->get();

        $latestOrder = Order::whereDate('created_at', $today)->orderBy('id', 'desc')->first();
        $latestId = $latestOrder ? $latestOrder->id : 0;
        $totalToday = Order::whereDate('created_at', $today)->count();

        if ($lastOrderId > 0 && $newOrders->isNotEmpty()) {
            $top = $newOrders->first();

            return response()->json([
                'has_new' => true,
                'count' => $newOrders->count(),
                'total_today' => $totalToday,
                'latest_order_id' => $latestId,
                'latest_order' => [
                    'id' => $top->id,
                    'order_number' => $top->order_number,
                    'customer_name' => $top->customer_name,
                    'total_formatted' => $top->formatted_total,
                    'payment_method' => str_replace('_', ' ', strtoupper($top->payment_method)),
                    'district' => $top->delivery_district,
                    'time' => $top->created_at->format('H:i').' WITA',
                ],
            ]);
        }

        return response()->json([
            'has_new' => false,
            'count' => 0,
            'total_today' => $totalToday,
            'latest_order_id' => $latestId,
        ]);
    }

    /**
     * Halaman Arsip Pesanan Lampau dengan filter kalender tanggal dan status.
     */
    public function archiveOrders(Request $request): View
    {
        $selectedDate = $request->input('date');
        $selectedStatus = $request->input('status');
        $search = $request->input('search');

        $query = Order::with(['items', 'messages']);

        // Jika filter tanggal diisi, ambil tanggal tersebut. Jika kosong, default tampilkan pesanan sebelum hari ini
        if ($selectedDate) {
            $query->whereDate('created_at', $selectedDate);
        } else {
            // Default arsip: pesanan sebelum hari ini
            $query->whereDate('created_at', '<', now()->today());
        }

        if ($selectedStatus && $selectedStatus !== 'all') {
            $query->where('status', $selectedStatus);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Hitung total dan pendapatan pada hasil filter arsip saat ini
        $baseQuery = clone $query;
        $archiveStats = [
            'total' => (clone $query)->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
            'revenue' => (clone $query)->where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('admin.archive', compact('orders', 'selectedDate', 'selectedStatus', 'search', 'archiveStats'));
    }

    /**
     * Update status pesanan dari dashboard dapur/admin.
     */
    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,cooking,delivering,delivered,cancelled'],
            'driver_name' => ['nullable', 'string', 'max:100'],
            'driver_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'cooking') {
            if (! $order->cooking_at) {
                $updateData['cooking_at'] = now();
            }
            // Jika admin langsung ganti ke masak, otomatis payment_status dianggap paid
            $updateData['payment_status'] = 'paid';
        } elseif ($validated['status'] === 'delivering') {
            if (! $order->delivering_at) {
                $updateData['delivering_at'] = now();
            }
            if (! empty($validated['driver_name'])) {
                $updateData['driver_name'] = $validated['driver_name'];
            }
            if (! empty($validated['driver_phone'])) {
                $updateData['driver_phone'] = $validated['driver_phone'];
            }
        } elseif ($validated['status'] === 'delivered') {
            if (! $order->delivered_at) {
                $updateData['delivered_at'] = now();
            }
            $updateData['payment_status'] = 'paid';
        }

        $order->update($updateData);

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diubah ke {$order->status_label}.");
    }

    /**
     * Tombol cepat ACC / Verifikasi Pembayaran oleh Admin.
     */
    public function verifyPayment(Order $order): RedirectResponse
    {
        $order->update([
            'payment_status' => 'paid',
            'status' => $order->status === 'pending' ? 'cooking' : $order->status,
            'cooking_at' => $order->cooking_at ?? now(),
        ]);

        return back()->with('success', "Pembayaran pesanan {$order->order_number} berhasil di-ACC (LUNAS). Pesanan diteruskan ke dapur untuk dimasak.");
    }

    /**
     * Manajemen Menu Makanan Pocha & Jenis / Kategori Menu.
     */
    public function menuIndex(): View
    {
        $categories = Category::with('menuItems')->orderBy('sort_order')->get();
        $menuItems = MenuItem::with('category')->orderBy('category_id')->get();

        return view('admin.menu', compact('categories', 'menuItems'));
    }

    /**
     * Ubah status ketersediaan menu (Tersedia / Habis).
     */
    public function toggleMenuAvailability(MenuItem $item): RedirectResponse
    {
        $item->update([
            'is_available' => ! $item->is_available,
        ]);

        $statusStr = $item->is_available ? 'Tersedia' : 'Habis / Sold Out';

        return back()->with('success', "Menu {$item->name} sekarang {$statusStr}.");
    }

    /**
     * CRUD MENU: Tambah Menu Baru.
     */
    public function storeMenuItem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'korean_name' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:1000'],
            'image_url' => ['nullable', 'url'],
            'menu_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'spicy_level' => ['required', 'in:none,mild,medium,very_spicy'],
            'preparation_time_minutes' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('menu_image')) {
            $path = $request->file('menu_image')->store('menu_items', 'public');
            $validated['image_url'] = asset('storage/'.$path);
        }

        unset($validated['menu_image']);

        if (empty($validated['image_url'])) {
            $validated['image_url'] = 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=700&q=80';
        }

        MenuItem::create($validated);

        return back()->with('success', "Menu '{$validated['name']}' berhasil ditambahkan ke katalog!");
    }

    /**
     * CRUD MENU: Update Menu.
     */
    public function updateMenuItem(Request $request, MenuItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'korean_name' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:1000'],
            'image_url' => ['nullable', 'url'],
            'menu_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'spicy_level' => ['required', 'in:none,mild,medium,very_spicy'],
            'preparation_time_minutes' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->hasFile('menu_image')) {
            $path = $request->file('menu_image')->store('menu_items', 'public');
            $validated['image_url'] = asset('storage/'.$path);
        }

        unset($validated['menu_image']);

        $item->update($validated);

        return back()->with('success', "Menu '{$item->name}' berhasil diperbarui!");
    }

    /**
     * CRUD MENU: Hapus Menu.
     */
    public function destroyMenuItem(MenuItem $item): RedirectResponse
    {
        $name = $item->name;
        $item->delete();

        return back()->with('success', "Menu '{$name}' telah dihapus.");
    }

    /**
     * CRUD JENIS / KATEGORI MENU: Tambah Kategori Baru.
     */
    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', "Jenis / Kategori menu '{$validated['name']}' berhasil dibuat!");
    }

    /**
     * CRUD JENIS / KATEGORI MENU: Update Kategori.
     */
    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,'.$category->id],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return back()->with('success', "Jenis / Kategori menu '{$category->name}' berhasil diperbarui!");
    }

    /**
     * CRUD JENIS / KATEGORI MENU: Hapus Kategori.
     */
    public function destroyCategory(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();

        return back()->with('success', "Jenis / Kategori menu '{$name}' dan menu di dalamnya telah dihapus.");
    }

    /**
     * Manajemen Menu Promo & Penawaran Spesial.
     */
    public function promoIndex(): View
    {
        $promos = Promo::with('menuItem')->orderBy('sort_order')->orderBy('id', 'desc')->get();
        $menuItems = MenuItem::where('is_available', true)->orderBy('name')->get();

        return view('admin.promos', compact('promos', 'menuItems'));
    }

    /**
     * CRUD PROMO: Tambah Menu Promo Baru.
     */
    public function storePromo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:150'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
            'promo_image' => ['nullable', 'image', 'max:5120'], // max 5MB
            'promo_code' => ['nullable', 'string', 'max:50'],
            'discount_percentage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'menu_item_id' => ['nullable', 'exists:menu_items,id'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('promo_image')) {
            $path = $request->file('promo_image')->store('promos', 'public');
            $validated['image_url'] = asset('storage/'.$path);
        }

        if (empty($validated['image_url'])) {
            $validated['image_url'] = 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=700&q=80';
        }

        if (empty($validated['badge_text'])) {
            $validated['badge_text'] = 'PROMO SPESIAL';
        }

        if (empty($validated['button_text'])) {
            $validated['button_text'] = 'Pesan Sekarang';
        }

        if (empty($validated['button_link'])) {
            $validated['button_link'] = '#menu';
        }

        $validated['is_active'] = true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        unset($validated['promo_image']);

        Promo::create($validated);

        return back()->with('success', "Menu promo '{$validated['title']}' berhasil ditambahkan!");
    }

    /**
     * CRUD PROMO: Update Menu Promo.
     */
    public function updatePromo(Request $request, Promo $promo): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'tagline' => ['nullable', 'string', 'max:150'],
            'badge_text' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
            'promo_image' => ['nullable', 'image', 'max:5120'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'discount_percentage' => ['nullable', 'integer', 'min:1', 'max:100'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'promo_price' => ['nullable', 'numeric', 'min:0'],
            'menu_item_id' => ['nullable', 'exists:menu_items,id'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('promo_image')) {
            $path = $request->file('promo_image')->store('promos', 'public');
            $validated['image_url'] = asset('storage/'.$path);
        }

        unset($validated['promo_image']);

        $promo->update($validated);

        return back()->with('success', "Menu promo '{$promo->title}' berhasil diperbarui!");
    }

    /**
     * CRUD PROMO: Toggle Status Aktif.
     */
    public function togglePromo(Promo $promo): RedirectResponse
    {
        $promo->update([
            'is_active' => ! $promo->is_active,
        ]);

        $statusStr = $promo->is_active ? 'Aktif (Tampil di Website)' : 'Non-aktif (Disembunyikan)';

        return back()->with('success', "Status promo '{$promo->title}' sekarang {$statusStr}.");
    }

    /**
     * CRUD PROMO: Hapus Promo.
     */
    public function destroyPromo(Promo $promo): RedirectResponse
    {
        $title = $promo->title;
        $promo->delete();

        return back()->with('success', "Menu promo '{$title}' berhasil dihapus.");
    }

    /**
     * Tampilkan halaman manajemen metode pembayaran (QRIS Toko, Rekening Bank, COD).
     */
    public function paymentSettings(): View
    {
        $setting = PaymentSetting::current();

        return view('admin.payments', compact('setting'));
    }

    /**
     * Update konfigurasi penerimaan pembayaran (Upload QRIS, No Rekening, Buka/Tutup COD/Transfer/QRIS).
     */
    public function updatePaymentSettings(Request $request): RedirectResponse
    {
        $setting = PaymentSetting::current();

        $validated = $request->validate([
            'is_store_open' => ['nullable', 'boolean'],
            'closed_message' => ['nullable', 'string', 'max:1000'],

            'is_qris_active' => ['nullable', 'boolean'],
            'qris_merchant_name' => ['nullable', 'string', 'max:150'],
            'qris_notes' => ['nullable', 'string', 'max:1000'],
            'qris_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
            'remove_qris_image' => ['nullable', 'boolean'],

            'is_transfer_active' => ['nullable', 'boolean'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'bank_account_name' => ['nullable', 'string', 'max:100'],

            'bank_2_name' => ['nullable', 'string', 'max:100'],
            'bank_2_account_number' => ['nullable', 'string', 'max:100'],
            'bank_2_account_name' => ['nullable', 'string', 'max:100'],

            'is_cod_active' => ['nullable', 'boolean'],
            'cod_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $isStoreOpen = $request->boolean('is_store_open');
        $isQris = $request->boolean('is_qris_active');
        $isTransfer = $request->boolean('is_transfer_active');
        $isCod = $request->boolean('is_cod_active');

        // Jika toko dalam posisi BUKA, pastikan minimal 1 metode pembayaran tetap aktif
        if ($isStoreOpen && ! $isQris && ! $isTransfer && ! $isCod) {
            return back()->withErrors(['payment_methods' => 'Saat toko dibuka, minimal 1 (satu) metode pembayaran harus tetap aktif!'])->withInput();
        }

        if ($request->boolean('remove_qris_image')) {
            if ($setting->qris_image && Storage::disk('public')->exists($setting->qris_image)) {
                Storage::disk('public')->delete($setting->qris_image);
            }
            $setting->qris_image = null;
        } elseif ($request->hasFile('qris_image')) {
            if ($setting->qris_image && Storage::disk('public')->exists($setting->qris_image)) {
                Storage::disk('public')->delete($setting->qris_image);
            }
            $path = $request->file('qris_image')->store('payment_settings', 'public');
            $setting->qris_image = $path;
        }

        $setting->is_store_open = $isStoreOpen;
        $setting->closed_message = $validated['closed_message'] ?? 'Mohon maaf, resto 1988 Pocha sedang tutup / istirahat saat ini. Pemesanan dan pembayaran sementara tidak dapat diproses.';

        $setting->is_qris_active = $isQris;
        $setting->qris_merchant_name = $validated['qris_merchant_name'] ?? '1988 POCHA SAMARINDA';
        $setting->qris_notes = $validated['qris_notes'] ?? null;

        $setting->is_transfer_active = $isTransfer;
        $setting->bank_name = $validated['bank_name'] ?? 'Bank BCA';
        $setting->bank_account_number = $validated['bank_account_number'] ?? '-';
        $setting->bank_account_name = $validated['bank_account_name'] ?? '-';

        $setting->bank_2_name = $validated['bank_2_name'] ?? null;
        $setting->bank_2_account_number = $validated['bank_2_account_number'] ?? null;
        $setting->bank_2_account_name = $validated['bank_2_account_name'] ?? null;

        $setting->is_cod_active = $isCod;
        $setting->cod_notes = $validated['cod_notes'] ?? null;

        $setting->save();

        $statusStr = $setting->is_store_open ? 'Toko DIBUKA (Menerima Pesanan & Pembayaran)' : 'Toko DITUTUP (Semua Pembayaran Ditutup)';

        return back()->with('success', "Pengaturan berhasil disimpan! Status: {$statusStr}.");
    }

    /**
     * Quick toggle buka / tutup toko langsung.
     */
    public function toggleStoreStatus(): RedirectResponse
    {
        $setting = PaymentSetting::current();
        $setting->is_store_open = ! $setting->is_store_open;
        $setting->save();

        $statusStr = $setting->is_store_open ? 'Toko DIBUKA (Menerima Pesanan & Pembayaran)' : 'Toko DITUTUP (Semua Pembayaran Ditutup)';

        return back()->with('success', "Status resto 1988 Pocha berhasil diubah: {$statusStr}.");
    }
}
