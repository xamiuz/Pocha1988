@extends('layouts.app')

@section('title', 'Checkout Pemesanan Samarinda | 1988 Pocha')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    /* Custom Leaflet Map Pin & Popup Styling */
    .leaflet-popup-content-wrapper {
        border-radius: 14px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }
    .leaflet-popup-content {
        margin: 10px 14px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 11px;
        font-weight: 700;
        color: #1f2937;
    }
    .pocha-draggable-pin {
        background: transparent !important;
        border: none !important;
    }
    .leaflet-container {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    #delivery-map {
        height: 340px !important;
        min-height: 340px !important;
        width: 100% !important;
        background-color: #f3f4f6;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <a href="{{ route('home') }}#menu" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#e52e2e] mb-3 uppercase tracking-wider transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>KEMBALI KE MENU</span>
        </a>
        <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-gray-900">CHECKOUT PEMESANAN</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-1 font-medium">Lengkapi alamat pengantaran khusus area Samarinda dan konfirmasi pesananmu.</p>
    </div>

    @if(!$paymentSettings->is_store_open)
        <div class="mb-8 p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-red-50 to-rose-50 border-2 border-red-500 shadow-md">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#e52e2e] text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-md bg-red-600 text-white text-[10px] font-black uppercase tracking-wider">Toko Tutup Sementara</span>
                            <span class="text-xs font-bold text-red-700">Semua Pembayaran Dikunci</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-gray-900 mt-1 uppercase">Resto 1988 Pocha Sedang Tutup</h2>
                        <p class="text-xs text-gray-700 mt-1 leading-relaxed">
                            {{ $paymentSettings->closed_message ?: 'Mohon maaf, saat ini restoran sedang tutup. Pemesanan dan pembayaran tidak dapat diproses hingga toko dibuka kembali oleh admin.' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                    <a href="{{ route('home') }}#menu" class="px-4 py-2.5 rounded-xl bg-white hover:bg-gray-100 text-gray-800 border border-gray-300 text-xs font-black uppercase tracking-wider transition shadow-2xs">
                        Kembali Ke Menu
                    </a>
                    @if($paymentSettings->whatsapp_number)
                        <a href="https://api.whatsapp.com/send?phone={{ $paymentSettings->whatsapp_number }}&text=Halo%201988%20Pocha,%20apakah%20toko%20sudah%20bisa%20menerima%20pesanan%3F" target="_blank" class="px-4 py-2.5 rounded-xl bg-[#25D366] hover:bg-[#1faa53] text-white text-xs font-black uppercase tracking-wider transition shadow-2xs inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Tanya WA</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs">
            <ul class="list-disc pl-5 space-y-1 font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
        @csrf
        <input type="hidden" name="cart_items" id="cart-items-input" value="[]">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Form Pengantaran Samarinda -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Data Penerima -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-4 shadow-sm">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-md bg-[#e52e2e] text-white flex items-center justify-center font-black text-xs">1</span>
                            <h2 class="text-sm font-black uppercase tracking-wide text-gray-900">Data Penerima</h2>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                            Tersimpan Otomatis
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Nama Lengkap *</label>
                            <input type="text" name="customer_name" required value="{{ old('customer_name', $savedCustomer['name'] ?? session('customer_name')) }}" placeholder="Nama kamu" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">No. WhatsApp / HP Aktif *</label>
                            <input type="tel" name="customer_phone" required value="{{ old('customer_phone', $savedCustomer['phone'] ?? session('customer_phone')) }}" placeholder="08xxxxxxxxxx" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition">
                        </div>
                    </div>
                </div>

                <!-- Alamat Pengantaran Samarinda -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-4 shadow-sm">
                    <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                        <span class="w-6 h-6 rounded-md bg-[#e52e2e] text-white flex items-center justify-center font-black text-xs">2</span>
                        <div class="flex items-center justify-between flex-1">
                            <h2 class="text-sm font-black uppercase tracking-wide text-gray-900">Alamat Pengantaran Samarinda</h2>
                            <span class="text-[10px] font-bold text-[#10b981] bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">SAMARINDA ONLY</span>
                        </div>
                    </div>

                    <!-- Interactive Pin Maps Picker -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-gray-700 uppercase flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#e52e2e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Pilih Titik di Peta (Geser Pin / Klik Peta)</span>
                            </label>
                            <button type="button" id="btn-current-location" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-[#e52e2e] text-xs font-extrabold transition cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>Gunakan Lokasi Saya</span>
                            </button>
                        </div>

                        <!-- Leaflet Map Container -->
                        <div class="relative w-full rounded-2xl border border-gray-300 overflow-hidden shadow-2xs bg-gray-100 flex flex-col">
                            <!-- Leaflet Div -->
                            <div id="delivery-map" style="height: 340px; min-height: 340px; width: 100%;" class="relative w-full z-0"></div>

                            <!-- Bar Status & Petunjuk Interaktif -->
                            <div class="p-3 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs">
                                <div class="flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-[#e52e2e] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="text-gray-700 font-bold text-[11px]" id="map-status-text">Geser pin merah atau klik di peta untuk menandai alamat antarmu.</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span id="map-coords-badge" class="px-2 py-0.5 rounded-md bg-white border border-gray-200 text-[10px] font-mono text-gray-500 font-bold">
                                        -0.5021, 117.1537
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Kecamatan di Samarinda *</label>
                        <select name="delivery_zone_id" id="delivery-zone-select" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-semibold transition cursor-pointer">
                            <option value="">-- Pilih Wilayah / Kecamatan Samarinda --</option>
                            @php
                                $selectedZoneId = old('delivery_zone_id', $savedCustomer['delivery_zone_id'] ?? session('delivery_zone_id'));
                            @endphp
                            @foreach($deliveryZones as $zone)
                                <option value="{{ $zone->id }}" data-district="{{ strtolower($zone->district) }}" data-fee="{{ $zone->delivery_fee }}" data-time="{{ $zone->estimated_delivery_minutes }}" {{ $selectedZoneId == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->district }} (Ongkir: {{ $zone->formatted_fee }}, Est: ±{{ $zone->estimated_delivery_minutes }} mnt)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-gray-700 uppercase">Alamat Lengkap (Jalan, RT/RW, No. Rumah) *</label>
                            <button type="button" id="btn-search-address-on-map" class="text-[11px] font-bold text-[#e52e2e] hover:text-[#c41c1c] flex items-center gap-1 cursor-pointer transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Cari Jalan Ini di Peta</span>
                            </button>
                        </div>
                        <textarea name="delivery_address" id="delivery-address-input" rows="3" required placeholder="Jl. P. Antasari No. 45 RT 12, patokan depan ruko..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition">{{ old('delivery_address', $savedCustomer['delivery_address'] ?? session('delivery_address')) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Catatan Pesanan (Opsional)</label>
                        <input type="text" name="delivery_notes" value="{{ old('delivery_notes') }}" placeholder="Contoh: Saus gochujang dipisah" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition">
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-4 shadow-sm">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-md bg-[#e52e2e] text-white flex items-center justify-center font-black text-xs">3</span>
                            <h2 class="text-sm font-black uppercase tracking-wide text-gray-900">Metode Pembayaran</h2>
                        </div>
                        @if(!$paymentSettings->is_store_open)
                            <span class="text-[10px] font-black text-red-800 bg-red-100 px-2.5 py-0.5 rounded-md border border-red-200 uppercase">
                                Toko Tutup (Nonaktif)
                            </span>
                        @elseif(!$paymentSettings->is_qris_active && !$paymentSettings->is_transfer_active && $paymentSettings->is_cod_active)
                            <span class="text-[10px] font-black text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-md border border-emerald-200 uppercase">
                                Hanya Menerima COD
                            </span>
                        @endif
                    </div>

                    @if(!$paymentSettings->is_store_open)
                        <div class="p-4 rounded-xl bg-red-50/80 border border-red-200 text-red-800 space-y-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span class="font-black uppercase text-xs">Semua Metode Pembayaran Dikunci</span>
                            </div>
                            <p class="text-xs text-gray-700 leading-relaxed">
                                Restoran sedang dalam status <strong>TUTUP</strong>. Seluruh sistem pembayaran (QRIS, Transfer Bank, dan COD) dinonaktifkan sementara dan tidak dapat diproses.
                            </p>
                        </div>
                    @else
                        @php
                            $hasQris = $paymentSettings->is_qris_active;
                            $hasTransfer = $paymentSettings->is_transfer_active;
                            $hasCod = $paymentSettings->is_cod_active;

                            // Default selection fallback
                            $selectedMethod = old('payment_method');
                            if (!$selectedMethod) {
                                if ($hasQris) $selectedMethod = 'qris_direct';
                                elseif ($hasTransfer) $selectedMethod = 'transfer_bank';
                                elseif ($hasCod) $selectedMethod = 'cod';
                            }
                        @endphp

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if($hasQris)
                                <label class="relative flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-[#e52e2e] transition has-[:checked]:border-[#e52e2e] has-[:checked]:bg-red-50/50 {{ (!$hasTransfer && !$hasCod) ? 'sm:col-span-2' : '' }}">
                                    <input type="radio" name="payment_method" value="qris_direct" {{ $selectedMethod === 'qris_direct' ? 'checked' : '' }} class="text-[#e52e2e] focus:ring-0">
                                    <div>
                                        <span class="block text-xs font-black text-gray-900 uppercase">QRIS Toko Langsung</span>
                                        <span class="block text-[11px] text-gray-500">Scan QRIS toko (GoPay, OVO, Dana, BCA, Mandiri)</span>
                                    </div>
                                </label>
                            @endif

                            @if($hasTransfer)
                                <label class="relative flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-[#e52e2e] transition has-[:checked]:border-[#e52e2e] has-[:checked]:bg-red-50/50 {{ (!$hasQris && !$hasCod) ? 'sm:col-span-2' : '' }}">
                                    <input type="radio" name="payment_method" value="transfer_bank" {{ $selectedMethod === 'transfer_bank' ? 'checked' : '' }} class="text-[#e52e2e] focus:ring-0">
                                    <div>
                                        <span class="block text-xs font-black text-gray-900 uppercase">Transfer Bank Langsung</span>
                                        <span class="block text-[11px] text-gray-500">Transfer ke Rekening {{ $paymentSettings->bank_name }}{{ $paymentSettings->bank_2_name ? ' / ' . $paymentSettings->bank_2_name : '' }}</span>
                                    </div>
                                </label>
                            @endif

                            @if($hasCod)
                                <label class="relative flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50 cursor-pointer hover:border-[#10b981] transition has-[:checked]:border-[#10b981] has-[:checked]:bg-emerald-50/50 sm:col-span-2">
                                    <input type="radio" name="payment_method" value="cod" {{ $selectedMethod === 'cod' ? 'checked' : '' }} class="text-[#10b981] focus:ring-0">
                                    <div>
                                        <span class="block text-xs font-black text-gray-900 uppercase">COD (Bayar Tunai di Tempat)</span>
                                        <span class="block text-[11px] text-emerald-700 font-semibold">{{ $paymentSettings->cod_notes ?: 'Bayar uang tunai langsung ke kurir saat pesanan sampai di tujuan' }}</span>
                                    </div>
                                </label>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ringkasan Pesanan & Pembayaran -->
            <div class="lg:col-span-5">
                <div class="sticky top-28 bg-white border border-gray-200 rounded-2xl p-6 space-y-5 shadow-lg">
                    <h3 class="text-sm font-black uppercase tracking-wider text-gray-900 pb-3 border-b border-gray-100 flex items-center justify-between">
                        <span>Ringkasan Menu</span>
                        <span id="checkout-total-items-badge" class="text-[10px] px-2.5 py-0.5 rounded-md bg-[#e52e2e] text-white font-black">0 Item</span>
                    </h3>

                    <div id="checkout-items-list" class="space-y-2.5 max-h-56 overflow-y-auto pr-1">
                        <!-- Items rendered by javascript -->
                    </div>

                    <div class="space-y-2 pt-3 border-t border-gray-100 text-xs font-medium">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal Menu:</span>
                            <span id="summary-subtotal" class="font-black text-gray-900">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Ongkir Samarinda:</span>
                            <span id="summary-delivery-fee" class="font-black text-[#10b981]">Pilih Wilayah</span>
                        </div>
                        <div class="flex justify-between text-gray-500 text-[11px]">
                            <span>Estimasi Waktu:</span>
                            <span id="summary-est-time">-</span>
                        </div>
                        <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                            <span class="text-sm font-black uppercase text-gray-900">Total Bayar:</span>
                            <span id="summary-total" class="text-2xl font-black text-[#e52e2e]">Rp 0</span>
                        </div>
                    </div>

                    @if(!$paymentSettings->is_store_open)
                        <button type="button" disabled class="w-full py-4 rounded-xl bg-gray-200 border border-gray-300 text-gray-500 font-black text-xs uppercase tracking-widest cursor-not-allowed flex items-center justify-center gap-2 shadow-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>TOKO TUTUP - PEMBAYARAN DIKUNCI</span>
                        </button>
                    @else
                        <button type="submit" id="submit-order-btn" class="w-full py-3.5 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-95 text-white font-black text-xs uppercase tracking-widest shadow-md shadow-red-500/30 transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <span>KONFIRMASI & PESAN</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    @endif

                    <p class="text-[10px] text-gray-400 text-center leading-relaxed">
                        Pengantaran resmi Kota Samarinda. Kamu dapat menggeser pesanan (Swipe to Complete) setelah makanan diterima.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // ========================================================
        // DOM ELEMENTS REFERENCES
        // ========================================================
        const addressInput = document.querySelector('textarea[name="delivery_address"]');
        const zoneSelect = document.getElementById('delivery-zone-select');
        const btnCurrentLocation = document.getElementById('btn-current-location');
        const mapStatusText = document.getElementById('map-status-text');
        const coordsBadge = document.getElementById('map-coords-badge');
        const mapContainer = document.getElementById('delivery-map');

        const nameInput = document.querySelector('input[name="customer_name"]');
        const phoneInput = document.querySelector('input[name="customer_phone"]');
        const notesInput = document.querySelector('input[name="delivery_notes"]');
        const checkoutForm = document.getElementById('checkout-form');

        const cartItems = typeof PochaCart !== 'undefined' ? PochaCart.getItems() : [];
        const itemsContainer = document.getElementById('checkout-items-list');
        const badgeTotal = document.getElementById('checkout-total-items-badge');
        const subtotalEl = document.getElementById('summary-subtotal');
        const deliveryFeeEl = document.getElementById('summary-delivery-fee');
        const estTimeEl = document.getElementById('summary-est-time');
        const totalEl = document.getElementById('summary-total');
        const submitBtn = document.getElementById('submit-order-btn');
        const hiddenCartInput = document.getElementById('cart-items-input');

        // ========================================================
        // 1. INTERACTIVE DRAGGABLE LEAFLET MAP
        // ========================================================
        // Koordinat default: Samarinda Kota (-0.5021, 117.1537)
        let currentLat = -0.5021;
        let currentLng = 117.1537;
        let isProgrammaticZoneChange = false;

        // Pemetaan koordinat per-kecamatan Samarinda
        const districtCoordinates = {
            'samarinda kota': [-0.4998, 117.1528],
            'samarinda ulu': [-0.4850, 117.1350],
            'samarinda ilir': [-0.4950, 117.1650],
            'sungai pinang': [-0.4700, 117.1550],
            'samarinda seberang': [-0.5250, 117.1400],
            'palaran': [-0.5650, 117.1800],
            'loa janan ilir': [-0.5450, 117.1050],
            'sambutan': [-0.5100, 117.1900],
            'samarinda utara': [-0.4400, 117.1700],
            'sungai kunjang': [-0.5180, 117.1100],
        };

        let map = null;
        let marker = null;

        if (mapContainer && typeof L !== 'undefined') {
            map = L.map('delivery-map', {
                center: [currentLat, currentLng],
                zoom: 14,
                zoomControl: true,
                scrollWheelZoom: true
            });

            // Tile layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Custom Draggable Red Pin Icon
            const redPinIcon = L.divIcon({
                className: 'pocha-draggable-pin',
                html: `
                    <div class="relative flex items-center justify-center -translate-x-1/2 -translate-y-full cursor-grab active:cursor-grabbing hover:scale-110 transition-transform">
                        <div class="w-9 h-9 rounded-full bg-[#e52e2e] shadow-xl flex items-center justify-center text-white border-2 border-white ring-4 ring-red-500/25">
                            <svg class="w-5 h-5 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 w-3 h-1 bg-black/30 rounded-full blur-[1px]"></div>
                    </div>
                `,
                iconSize: [36, 36],
                iconAnchor: [18, 36]
            });

            marker = L.marker([currentLat, currentLng], {
                draggable: true,
                icon: redPinIcon
            }).addTo(map);

            marker.bindPopup("<b>Titik Pengantaran</b><br>Geser pin merah ini ke rumah atau lokasimu.").openPopup();

            function onMarkerMoved(lat, lng, syncTextarea = true) {
                currentLat = lat;
                currentLng = lng;
                if (coordsBadge) {
                    coordsBadge.innerText = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                }
                if (mapStatusText) {
                    mapStatusText.innerText = "Mencari nama jalan titik pin...";
                }

                // Reverse geocode via OpenStreetMap Nominatim
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=id`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.address) {
                            const addr = data.address;
                            const road = addr.road || addr.pedestrian || addr.street || addr.residential || addr.path || '';
                            const houseNo = addr.house_number ? `No. ${addr.house_number}` : '';
                            const neighbourhood = addr.neighbourhood || addr.suburb || addr.hamlet || '';
                            const village = addr.village || addr.subdistrict || '';
                            const district = addr.city_district || addr.county || '';
                            
                            const parts = [
                                [road, houseNo].filter(Boolean).join(' '),
                                neighbourhood,
                                village,
                                district,
                                'Kota Samarinda'
                            ].filter(Boolean);

                            const full = parts.join(', ');

                            if (syncTextarea && addressInput && full) {
                                addressInput.value = full;
                                addressInput.dispatchEvent(new Event('input'));
                            }

                            if (mapStatusText) {
                                mapStatusText.innerText = `Pin disetel: ${road || neighbourhood || district || 'Samarinda'}`;
                            }

                            // Auto-select zone kecamatan jika cocok TANPA reset pin
                            if (zoneSelect) {
                                const searchZone = (district + ' ' + village + ' ' + neighbourhood).toLowerCase();
                                for (let i = 0; i < zoneSelect.options.length; i++) {
                                    const opt = zoneSelect.options[i];
                                    const optDistrict = opt.getAttribute('data-district') || '';
                                    if (optDistrict && searchZone.includes(optDistrict)) {
                                        isProgrammaticZoneChange = true;
                                        zoneSelect.selectedIndex = i;
                                        calculateFinalTotal();
                                        saveProfileLocally();
                                        isProgrammaticZoneChange = false;
                                        break;
                                    }
                                }
                            }
                        } else {
                            if (mapStatusText) {
                                mapStatusText.innerText = `Pin disetel di koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                            }
                        }
                    })
                    .catch(() => {
                        if (mapStatusText) {
                            mapStatusText.innerText = `Pin disetel di: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                        }
                    });
            }

            // Event saat pin selesai digeser
            marker.on('dragend', () => {
                const pos = marker.getLatLng();
                onMarkerMoved(pos.lat, pos.lng, true);
            });

            // Event saat user mengklik peta langsung
            map.on('click', (e) => {
                marker.setLatLng(e.latlng);
                onMarkerMoved(e.latlng.lat, e.latlng.lng, true);
            });

            // Pastikan peta render ukuran penuh
            setTimeout(() => {
                map.invalidateSize();
            }, 300);

            // Bila kecamatan di dropdown diganti manual oleh user -> geser peta & pin ke kecamatan itu
            if (zoneSelect) {
                zoneSelect.addEventListener('change', () => {
                    if (isProgrammaticZoneChange) return; // JANGAN geser pin jika dropdown dipilih otomatis oleh GPS
                    const opt = zoneSelect.options[zoneSelect.selectedIndex];
                    const distName = opt ? (opt.getAttribute('data-district') || '').toLowerCase() : '';
                    if (distName && districtCoordinates[distName]) {
                        const targetCoords = districtCoordinates[distName];
                        map.flyTo(targetCoords, 15);
                        marker.setLatLng(targetCoords);
                        if (coordsBadge) {
                            coordsBadge.innerText = `${targetCoords[0].toFixed(5)}, ${targetCoords[1].toFixed(5)}`;
                        }
                        if (mapStatusText) {
                            mapStatusText.innerText = `Peta diarahkan ke Kecamatan ${opt.text.split('(')[0].trim()}`;
                        }
                    }
                });
            }

            // Tombol "Gunakan Lokasi Saya" (GPS Browser Presisi Tinggi)
            if (btnCurrentLocation) {
                btnCurrentLocation.addEventListener('click', () => {
                    if (navigator.geolocation) {
                        btnCurrentLocation.innerHTML = `<span class="animate-spin inline-block">⏳</span> <span>Mengunci GPS...</span>`;
                        btnCurrentLocation.disabled = true;

                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                const lat = pos.coords.latitude;
                                const lng = pos.coords.longitude;

                                // Geser pin dan zoom langsung ke koordinat GPS asli pengguna
                                marker.setLatLng([lat, lng]);
                                map.setView([lat, lng], 17);
                                marker.bindPopup("<b>📍 Lokasimu Terkunci</b><br>Jika belum pas persis di rumahmu, silakan geser pin merah tepat ke atap rumah Anda.").openPopup();

                                if (coordsBadge) {
                                    coordsBadge.innerText = `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                                }

                                onMarkerMoved(lat, lng, true);

                                btnCurrentLocation.innerHTML = `
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Lokasi Terkunci</span>
                                `;
                                setTimeout(() => {
                                    btnCurrentLocation.innerHTML = `
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><circle cx="12" cy="12" r="3"/></svg>
                                        <span>Gunakan Lokasi Saya</span>
                                    `;
                                    btnCurrentLocation.disabled = false;
                                }, 3000);
                            },
                            (err) => {
                                alert("Izin GPS tidak diberikan atau sinyal GPS lemah. Anda bisa langsung geser pin merah atau ketik nama jalan Anda.");
                                btnCurrentLocation.innerHTML = `
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>Gunakan Lokasi Saya</span>
                                `;
                                btnCurrentLocation.disabled = false;
                            },
                            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                        );
                    }
                });
            }

            // Tombol "Cari Jalan Ini di Peta"
            const btnSearchAddress = document.getElementById('btn-search-address-on-map');
            if (btnSearchAddress && addressInput) {
                btnSearchAddress.addEventListener('click', () => {
                    const query = addressInput.value.trim();
                    if (!query) {
                        alert('Ketik nama jalan atau patokan di kolom alamat terlebih dahulu.');
                        addressInput.focus();
                        return;
                    }

                    btnSearchAddress.innerHTML = `<span class="animate-spin inline-block">⏳</span> <span>Mencari...</span>`;
                    btnSearchAddress.disabled = true;

                    const searchQuery = encodeURIComponent(query.includes('Samarinda') ? query : `${query}, Samarinda`);
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${searchQuery}&limit=1`)
                        .then(res => res.json())
                        .then(results => {
                            if (results && results.length > 0) {
                                const item = results[0];
                                const lat = parseFloat(item.lat);
                                const lng = parseFloat(item.lon);
                                marker.setLatLng([lat, lng]);
                                map.flyTo([lat, lng], 17);
                                marker.bindPopup(`<b>📍 ${item.display_name.split(',')[0]}</b><br>Geser pin jika ingin lebih presisi ke rumahmu.`).openPopup();
                                onMarkerMoved(lat, lng, false);
                                if (mapStatusText) {
                                    mapStatusText.innerText = `Peta diarahkan ke: ${item.display_name.split(',')[0]}`;
                                }
                            } else {
                                alert('Nama jalan tidak spesifik di Samarinda. Silakan geser pin merah langsung ke titik yang diinginkan.');
                            }
                        })
                        .catch(() => {
                            alert('Gagal mencari lokasi. Silakan geser pin merah di peta.');
                        })
                        .finally(() => {
                            btnSearchAddress.innerHTML = `
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Cari Jalan Ini di Peta</span>
                            `;
                            btnSearchAddress.disabled = false;
                        });
                });
            }
        }

        // ========================================================
        // 2. LOGIKA KERANJANG & CHECKOUT ORDER
        // ========================================================
        if (!cartItems || cartItems.length === 0) {
            if (itemsContainer) {
                itemsContainer.innerHTML = `
                    <div class="text-center py-6 space-y-3">
                        <div class="w-10 h-10 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <p class="text-gray-600 text-xs font-bold">Keranjang belanjamu masih kosong.</p>
                        <a href="{{ route('home') }}#menu" class="inline-block px-4 py-2 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] text-white text-xs font-black uppercase tracking-wider transition">
                            Pilih Menu Sekarang
                        </a>
                    </div>
                `;
            }
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            return;
        }

        if (hiddenCartInput) {
            hiddenCartInput.value = JSON.stringify(cartItems);
        }

        let subtotal = 0;
        let totalCount = 0;
        let html = '';

        cartItems.forEach(item => {
            const line = item.price * item.quantity;
            subtotal += line;
            totalCount += item.quantity;
            html += `
                <div class="flex items-center justify-between text-xs py-2 border-b border-gray-100">
                    <div class="flex items-center gap-2.5 max-w-[70%]">
                        <img src="${item.image}" class="w-8 h-8 rounded-lg object-cover shrink-0 border border-gray-200">
                        <div class="truncate">
                            <span class="font-black text-gray-900 block truncate">${item.name}</span>
                            <span class="text-gray-500 text-[11px]">${item.quantity} x Rp ${Number(item.price).toLocaleString('id-ID')}</span>
                        </div>
                    </div>
                    <span class="font-black text-[#e52e2e]">Rp ${line.toLocaleString('id-ID')}</span>
                </div>
            `;
        });

        if (itemsContainer) itemsContainer.innerHTML = html;
        if (badgeTotal) badgeTotal.innerText = `${totalCount} Item`;
        if (subtotalEl) subtotalEl.innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;

        function calculateFinalTotal() {
            if (!zoneSelect) return;
            const selected = zoneSelect.options[zoneSelect.selectedIndex];
            let fee = 0;
            if (selected && selected.value) {
                fee = parseFloat(selected.getAttribute('data-fee') || 0);
                const time = selected.getAttribute('data-time') || '30';
                if (deliveryFeeEl) deliveryFeeEl.innerText = `Rp ${fee.toLocaleString('id-ID')}`;
                if (estTimeEl) estTimeEl.innerText = `± ${time} Menit ke lokasi`;
            } else {
                if (deliveryFeeEl) deliveryFeeEl.innerText = 'Pilih Wilayah';
                if (estTimeEl) estTimeEl.innerText = '-';
            }

            const total = subtotal + fee;
            if (totalEl) totalEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
        }

        if (zoneSelect) {
            zoneSelect.addEventListener('change', calculateFinalTotal);
            calculateFinalTotal();
        }

        // ========================================================
        // 3. AUTO-SAVE & RESTORE PROFIL LOKAL PENGGUNA
        // ========================================================
        const STORAGE_KEY = 'pocha_customer_profile';

        function saveProfileLocally() {
            const profile = {
                name: nameInput ? nameInput.value.trim() : '',
                phone: phoneInput ? phoneInput.value.trim() : '',
                zone_id: zoneSelect ? zoneSelect.value : '',
                address: addressInput ? addressInput.value.trim() : '',
            };
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(profile));
            } catch (e) {}
        }

        function restoreProfileLocally() {
            try {
                const raw = localStorage.getItem(STORAGE_KEY);
                if (!raw) return;
                const profile = JSON.parse(raw);
                if (nameInput && (!nameInput.value || nameInput.value.trim() === '') && profile.name) {
                    nameInput.value = profile.name;
                }
                if (phoneInput && (!phoneInput.value || phoneInput.value.trim() === '') && profile.phone) {
                    phoneInput.value = profile.phone;
                }
                if (zoneSelect && (!zoneSelect.value || zoneSelect.value === '') && profile.zone_id) {
                    zoneSelect.value = profile.zone_id;
                    calculateFinalTotal();
                }
                if (addressInput && (!addressInput.value || addressInput.value.trim() === '') && profile.address) {
                    addressInput.value = profile.address;
                }
            } catch (e) {
                console.warn('Gagal memulihkan profil lokal', e);
            }
        }

        restoreProfileLocally();

        [nameInput, phoneInput, addressInput, notesInput].forEach(el => {
            if (el) el.addEventListener('input', saveProfileLocally);
        });

        if (zoneSelect) {
            zoneSelect.addEventListener('change', () => {
                calculateFinalTotal();
                saveProfileLocally();
            });
        }

        const isStoreOpen = {{ $paymentSettings->is_store_open ? 'true' : 'false' }};

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', (e) => {
                if (!isStoreOpen) {
                    e.preventDefault();
                    alert('Mohon maaf, 1988 Pocha saat ini sedang TUTUP. Seluruh pemesanan dan transaksi pembayaran belum dapat diproses.');
                    return false;
                }

                saveProfileLocally();
                setTimeout(() => {
                    if (typeof PochaCart !== 'undefined') {
                        PochaCart.clear();
                    }
                }, 100);
            });
        }
    });
</script>
@endpush
