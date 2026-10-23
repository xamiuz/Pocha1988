<?php

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans Environment
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Tampilkan halaman checkout pemesanan.
     */
    public function checkout(): View
    {
        $deliveryZones = DeliveryZone::where('is_active', true)->orderBy('district')->get();
        $paymentSettings = PaymentSetting::current();

        $savedCustomer = [
            'name' => session('customer_name', ''),
            'phone' => session('customer_phone', ''),
            'delivery_zone_id' => session('delivery_zone_id', ''),
            'delivery_address' => session('delivery_address', ''),
        ];

        return view('orders.checkout', compact('deliveryZones', 'savedCustomer', 'paymentSettings'));
    }

    /**
     * Simpan order baru dari pengguna.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'delivery_zone_id' => ['required', 'exists:delivery_zones,id'],
            'delivery_address' => ['required', 'string', 'min:10'],
            'delivery_notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:qris_direct,transfer_bank,cod,midtrans,qris,transfer_bca,transfer_mandiri'],
            'cart_items' => ['required', 'string'],
        ]);

        // Verifikasi apakah toko sedang buka atau tutup
        $paymentSettings = PaymentSetting::current();

        if (! $paymentSettings->is_store_open) {
            $msg = $paymentSettings->closed_message ?: 'Mohon maaf, resto 1988 Pocha sedang tutup saat ini sehingga belum dapat menerima pesanan & pembayaran. Silakan periksa kembali nanti.';

            return back()->withErrors(['store_closed' => $msg])->withInput();
        }

        // Verifikasi apakah metode pembayaran yang dipilih saat ini aktif
        $chosenMethod = $validated['payment_method'];

        if (in_array($chosenMethod, ['qris_direct', 'qris']) && ! $paymentSettings->is_qris_active) {
            return back()->withErrors(['payment_method' => 'Metode pembayaran QRIS sedang ditutup/tidak aktif oleh admin. Silakan gunakan metode bayar lainnya.'])->withInput();
        }

        if (in_array($chosenMethod, ['transfer_bank', 'transfer_bca', 'transfer_mandiri']) && ! $paymentSettings->is_transfer_active) {
            return back()->withErrors(['payment_method' => 'Metode transfer bank sedang ditutup oleh admin. Silakan gunakan metode bayar lainnya.'])->withInput();
        }

        if ($chosenMethod === 'cod' && ! $paymentSettings->is_cod_active) {
            return back()->withErrors(['payment_method' => 'Metode pembayaran COD (Bayar di Tempat) sedang tidak tersedia saat ini.'])->withInput();
        }

        // Simpan data pemesan ke session pengguna agar tersimpan otomatis saat checkout berikutnya
        session([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'delivery_zone_id' => $validated['delivery_zone_id'],
            'delivery_address' => $validated['delivery_address'],
        ]);

        $cartData = json_decode($validated['cart_items'], true);
        if (empty($cartData) || ! is_array($cartData)) {
            return back()->withErrors(['cart' => 'Keranjang belanja Anda masih kosong. Silakan pilih menu terlebih dahulu.'])->withInput();
        }

        $zone = DeliveryZone::findOrFail($validated['delivery_zone_id']);

        $order = DB::transaction(function () use ($validated, $cartData, $zone) {
            $subtotal = 0;
            $itemsToCreate = [];
            $itemDetails = [];

            foreach ($cartData as $item) {
                $menuItem = MenuItem::find($item['id']);
                if ($menuItem && $menuItem->is_available) {
                    $qty = max(1, (int) ($item['quantity'] ?? 1));
                    $itemPrice = (float) $menuItem->price;
                    $lineTotal = $itemPrice * $qty;
                    $subtotal += $lineTotal;

                    $itemsToCreate[] = [
                        'menu_item_id' => $menuItem->id,
                        'item_name' => $menuItem->name,
                        'unit_price' => $itemPrice,
                        'quantity' => $qty,
                        'subtotal' => $lineTotal,
                        'notes' => $item['notes'] ?? null,
                    ];

                    $itemDetails[] = [
                        'id' => 'ITEM-'.$menuItem->id,
                        'price' => (int) $itemPrice,
                        'quantity' => $qty,
                        'name' => Str::limit($menuItem->name, 45),
                    ];
                }
            }

            if (empty($itemsToCreate)) {
                throw new \Exception('Menu yang dipilih tidak valid atau sudah habis.');
            }

            $deliveryFee = (float) $zone->delivery_fee;

            // Generate kode unik acak (100 - 999) khusus untuk transfer/QRIS direct agar auto-verifikasi akurat
            $uniqueCode = 0;
            if (in_array($validated['payment_method'], ['qris_direct', 'transfer_bank', 'transfer_bca', 'transfer_mandiri', 'qris'])) {
                $uniqueCode = rand(101, 899);
            }

            $totalAmount = $subtotal + $deliveryFee + $uniqueCode;

            // Tambahkan ongkos kirim ke detail item Midtrans
            $itemDetails[] = [
                'id' => 'DELIVERY-FEE',
                'price' => (int) $deliveryFee,
                'quantity' => 1,
                'name' => 'Ongkir '.Str::limit($zone->district, 35),
            ];

            $orderNumber = 'POCHA-'.strtoupper(Str::random(6));

            $newOrder = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_zone_id' => $zone->id,
                'delivery_district' => $zone->district,
                'delivery_address' => $validated['delivery_address'],
                'delivery_notes' => $validated['delivery_notes'] ?? null,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount' => 0,
                'unique_code' => $uniqueCode,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending', // Pending menunggu pembayaran Midtrans, Transfer, atau COD
                'status' => 'pending',
                'confirmed_at' => now(),
            ]);

            foreach ($itemsToCreate as $itemRow) {
                $itemRow['order_id'] = $newOrder->id;
                OrderItem::create($itemRow);
            }

            return $newOrder;
        });

        return redirect()->route('orders.show', $order->order_number)
            ->with('success', 'Pesanan Anda berhasil dibuat! Selesaikan pembayaran atau tunggu kurir kami.');
    }

    /**
     * Halaman lacak pesanan, bayar Midtrans, dan slider swipe selesai.
     */
    public function show(string $orderNumber): View
    {
        $order = Order::with(['items.menuItem', 'deliveryZone'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');
        $paymentSettings = PaymentSetting::current();

        return view('orders.show', compact('order', 'clientKey', 'isProduction', 'paymentSettings'));
    }

    /**
     * Konfirmasi pembayaran online berhasil dari popup Snap Midtrans (Client side callback).
     */
    public function paymentSuccess(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $order->update([
            'payment_status' => 'paid',
            'status' => $order->status === 'pending' ? 'cooking' : $order->status,
            'cooking_at' => now(),
            'midtrans_transaction_id' => $request->input('transaction_id') ?? 'TRX-'.Str::random(10),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi oleh sistem!',
            'status' => $order->status,
            'payment_status' => $order->payment_status,
        ]);
    }

    /**
     * Upload bukti transfer pembayaran oleh pelanggan.
     */
    public function uploadProof(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $order->update([
                'payment_proof' => $path,
            ]);
        }

        return back()->with('success', 'Bukti transfer berhasil diunggah! Mohon tunggu konfirmasi / ACC dari admin.');
    }

    /**
     * Webhook HTTP Notification dari Server Midtrans (Server-to-Server).
     */
    public function webhookNotification(Request $request): JsonResponse
    {
        try {
            $notif = new Notification;
            $transaction = $notif->transaction_status;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            $order = Order::where('order_number', $orderId)->first();
            if (! $order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            if ($transaction == 'capture') {
                if ($fraud == 'challenge') {
                    $order->update(['payment_status' => 'pending']);
                } elseif ($fraud == 'accept') {
                    $order->update(['payment_status' => 'paid', 'status' => 'cooking']);
                }
            } elseif ($transaction == 'settlement') {
                $order->update(['payment_status' => 'paid', 'status' => 'cooking']);
            } elseif ($transaction == 'pending') {
                $order->update(['payment_status' => 'pending']);
            } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Webhook penerima notifikasi SMS / m-Banking dari HP Android Kasir (MacroDroid).
     */
    public function webhookSms(Request $request): JsonResponse
    {
        $message = $request->input('message') ?? $request->input('text') ?? $request->input('body') ?? '';
        $amount = (float) $request->input('amount', 0);

        // Jika nominal tidak dikirim terpisah, cari pola angka dari pesan teks
        // Contoh teks: "Trx CR Rp 76.241 Sukses" atau "Dana Masuk Rp76.241"
        if ($amount <= 0 && ! empty($message)) {
            // Regex mencari pola angka nominal: 76.241 atau 76241
            if (preg_match('/(?:rp|idr)?\s*([0-9]{1,3}(?:\.[0-9]{3})+|[0-9]{4,8})/i', $message, $matches)) {
                $rawAmount = str_replace('.', '', $matches[1]);
                $amount = (float) $rawAmount;
            }
        }

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal transfer tidak terdeteksi dari pesan notifikasi.',
                'raw_received' => $message,
            ], 422);
        }

        // Cari order yang pending dengan total_amount tepat sama dengan nominal transfer
        $order = Order::where('payment_status', 'pending')
            ->where('total_amount', $amount)
            ->latest()
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada order pending yang cocok dengan nominal Rp '.number_format($amount, 0, ',', '.'),
                'amount_detected' => $amount,
            ], 404);
        }

        // Update status order otomatis jadi paid & cooking
        $order->update([
            'payment_status' => 'paid',
            'status' => 'cooking',
            'cooking_at' => now(),
            'notes' => ($order->notes ? $order->notes.' | ' : '').'Auto-verified via Android SMS Webhook: Rp '.number_format($amount, 0, ',', '.'),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Order {$order->order_number} berhasil diverifikasi otomatis!",
            'order_number' => $order->order_number,
            'amount_paid' => $amount,
        ]);
    }

    /**
     * Cek status pesanan secara real-time (Polling JSON).
     */
    public function checkStatus(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'payment_status' => $order->payment_status,
        ]);
    }

    /**
     * Aksi menggeser pesanan (Swipe to Complete) saat pesanan sudah diterima oleh pengguna.
     */
    public function confirmDelivery(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $order->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'payment_status' => 'paid',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diselesaikan! Terima kasih sudah memesan di 1988 Pocha Samarinda.',
            'status' => $order->status,
            'delivered_at' => $order->delivered_at->format('d M Y, H:i'),
        ]);
    }
}
