@extends('layouts.app')

@section('title', 'Status & Lacak Pesanan ' . $order->order_number . ' | 1988 Pocha')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Top Status Banner (Clean White Card) -->
    <div class="bg-white border border-gray-200/90 rounded-2xl p-6 sm:p-8 shadow-sm relative overflow-hidden mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-gray-100 gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-[#e52e2e]">STATUS PESANAN</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-md font-black uppercase tracking-wider {{ $order->status === 'delivered' ? 'bg-emerald-100 text-[#10b981] border border-emerald-200' : 'bg-red-100 text-[#e52e2e] border border-red-200' }}" id="badge-status-display">
                        {{ $order->status_label }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 mt-1 tracking-tight">{{ $order->order_number }}</h1>
                <p class="text-xs text-gray-500 mt-1 font-medium">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }} WITA</p>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-xs text-gray-400 block font-bold uppercase tracking-wider">Total Pembayaran</span>
                <span class="text-2xl font-black text-gray-900">{{ $order->formatted_total }}</span>
                <span class="block text-[11px] text-[#10b981] mt-0.5 uppercase tracking-wider font-extrabold">
                    {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }} | {{ strtoupper($order->payment_status) }}
                </span>
            </div>
        </div>

        <!-- 4-Step Stepper Progress Bar (Merah & Hijau) -->
        <div class="py-8">
            <div class="grid grid-cols-4 relative">
                <div class="absolute top-1/2 left-0 right-0 h-1.5 bg-gray-100 -translate-y-1/2 z-0 rounded-full"></div>
                
                @php
                    $stepIndex = match($order->status) {
                        'pending' => 1,
                        'cooking' => 2,
                        'delivering' => 3,
                        'delivered' => 4,
                        'cancelled' => 0,
                        default => 1,
                    };
                @endphp

                <div class="absolute top-1/2 left-0 h-1.5 bg-gradient-to-r from-[#e52e2e] to-[#10b981] -translate-y-1/2 z-0 transition-all duration-500 rounded-full" 
                     id="stepper-progress-bar"
                     style="width: {{ $stepIndex >= 4 ? '100%' : ($stepIndex == 3 ? '75%' : ($stepIndex == 2 ? '50%' : '25%')) }};">
                </div>

                <!-- Step 1: Diterima -->
                <div class="flex flex-col items-center relative z-10 text-center">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs shadow-xs transition-all {{ $stepIndex >= 1 ? 'bg-[#e52e2e] text-white' : 'bg-gray-100 text-gray-400 border border-gray-200' }}" id="step-1-circle">
                        1
                    </div>
                    <span class="text-xs font-black text-gray-800 mt-2 uppercase tracking-wide">Diterima</span>
                </div>

                <!-- Step 2: Dimasak -->
                <div class="flex flex-col items-center relative z-10 text-center">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs shadow-xs transition-all {{ $stepIndex >= 2 ? 'bg-[#e52e2e] text-white' : 'bg-gray-100 text-gray-400 border border-gray-200' }}" id="step-2-circle">
                        2
                    </div>
                    <span class="text-xs font-black text-gray-800 mt-2 uppercase tracking-wide">Dimasak</span>
                </div>

                <!-- Step 3: Diantar Kurir -->
                <div class="flex flex-col items-center relative z-10 text-center">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs shadow-xs transition-all {{ $stepIndex >= 3 ? 'bg-[#e52e2e] text-white' : 'bg-gray-100 text-gray-400 border border-gray-200' }}" id="step-3-circle">
                        3
                    </div>
                    <span class="text-xs font-black text-gray-800 mt-2 uppercase tracking-wide">Diantar</span>
                </div>

                <!-- Step 4: Selesai -->
                <div class="flex flex-col items-center relative z-10 text-center">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs shadow-xs transition-all {{ $stepIndex >= 4 ? 'bg-[#10b981] text-white' : 'bg-gray-100 text-gray-400 border border-gray-200' }}" id="step-4-circle">
                        4
                    </div>
                    <span class="text-xs font-black text-gray-800 mt-2 uppercase tracking-wide">Selesai</span>
                </div>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- KARTU PEMBAYARAN LANGSUNG (QRIS / TRANSFER / MIDTRANS) -->
        <!-- ======================================================== -->
        @if($order->payment_status !== 'paid' && $order->payment_method !== 'cod')
            <div id="payment-box" class="my-6 p-6 rounded-2xl bg-white border border-red-200 shadow-sm space-y-4">
                
                @if($order->payment_method === 'qris_direct' || $order->payment_method === 'qris')
                    <!-- QRIS Toko Langsung -->
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="w-48 h-48 bg-white p-2.5 rounded-2xl flex items-center justify-center shrink-0 border border-gray-200 shadow-sm overflow-hidden">
                            <!-- Barcode QRIS Resmi Toko dari Pengaturan Admin -->
                            <img src="{{ $paymentSettings->qris_image_url }}" alt="QRIS {{ $paymentSettings->qris_merchant_name }}" class="w-full h-full object-contain">
                        </div>
                        <div class="flex-1 space-y-3 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start gap-2">
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#10b981] bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 inline-block">Scan QRIS Toko</span>
                                <span class="text-[11px] font-bold text-gray-500 uppercase">{{ $paymentSettings->qris_merchant_name }}</span>
                            </div>
                            <h3 class="text-lg font-black text-gray-900">Scan Barcode untuk Bayar</h3>
                            
                            <div class="p-3.5 bg-amber-50/70 rounded-xl border border-amber-200">
                                <span class="text-[11px] text-gray-600 font-bold block uppercase tracking-wider">TOTAL TRANSFER:</span>
                                <div class="flex items-center justify-center sm:justify-start gap-2 mt-1">
                                    <span class="text-xl font-black text-[#e52e2e]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ (int)$order->total_amount }}'); alert('Nominal Rp {{ (int)$order->total_amount }} berhasil disalin!');" class="text-[10px] uppercase font-black bg-white hover:bg-gray-50 text-gray-700 px-3 py-1 rounded-lg border border-gray-300 shadow-2xs transition cursor-pointer">
                                        Salin Nominal
                                    </button>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 font-medium">
                                {{ $paymentSettings->qris_notes ?: 'Gunakan GoPay, OVO, Dana, BCA Mobile, Livin Mandiri, ShopeePay dll. Setelah transfer, silakan upload bukti struk di bawah agar segera di-ACC admin.' }}
                            </p>
                        </div>
                    </div>

                @else
                    <!-- Transfer Bank Langsung -->
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-[#10b981] bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 inline-block">Transfer Bank Manual</span>
                                <h3 class="text-base font-black text-gray-900 mt-1">Transfer ke Rekening Toko</h3>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="text-[10px] text-gray-400 uppercase font-bold block">Nominal Ditransfer:</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-black text-[#e52e2e]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ (int)$order->total_amount }}'); alert('Nominal Rp {{ (int)$order->total_amount }} berhasil disalin!');" class="text-[10px] uppercase font-black bg-white hover:bg-gray-50 text-gray-700 px-2.5 py-1 rounded-lg border border-gray-300 shadow-2xs transition cursor-pointer">
                                        Salin
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 {{ $paymentSettings->bank_2_name ? 'sm:grid-cols-2' : '' }} gap-3 text-xs">
                            <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200 space-y-1">
                                <span class="text-gray-500 block font-bold">{{ $paymentSettings->bank_name ?: 'Bank BCA' }}</span>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-900 font-black text-sm tracking-wider">{{ $paymentSettings->bank_account_number }}</span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ preg_replace('/[^0-9]/', '', $paymentSettings->bank_account_number) }}'); alert('No. Rekening {{ $paymentSettings->bank_name }} disalin!');" class="text-[10px] font-bold text-gray-700 hover:text-gray-900 uppercase bg-white px-2 py-0.5 rounded border border-gray-300 shadow-2xs">Salin</button>
                                </div>
                                <span class="text-[11px] text-gray-500 font-medium">a/n {{ $paymentSettings->bank_account_name }}</span>
                            </div>

                            @if($paymentSettings->bank_2_name)
                                <div class="p-3.5 bg-gray-50 rounded-xl border border-gray-200 space-y-1">
                                    <span class="text-gray-500 block font-bold">{{ $paymentSettings->bank_2_name }}</span>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-900 font-black text-sm tracking-wider">{{ $paymentSettings->bank_2_account_number }}</span>
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ preg_replace('/[^0-9]/', '', $paymentSettings->bank_2_account_number) }}'); alert('No. Rekening {{ $paymentSettings->bank_2_name }} disalin!');" class="text-[10px] font-bold text-gray-700 hover:text-gray-900 uppercase bg-white px-2 py-0.5 rounded border border-gray-300 shadow-2xs">Salin</button>
                                    </div>
                                    <span class="text-[11px] text-gray-500 font-medium">a/n {{ $paymentSettings->bank_2_account_name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Form Upload Bukti Transfer & Konfirmasi WhatsApp -->
                <div class="pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black text-gray-900 uppercase flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Kirim Bukti Pembayaran
                        </span>
                        @if($order->payment_proof)
                            <span class="text-[11px] text-[#10b981] bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200 font-bold">
                                ✓ Bukti Terkirim (Menunggu ACC Admin)
                            </span>
                        @else
                            <span class="text-[11px] text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-md border border-amber-200 font-bold">
                                Belum Upload Bukti
                            </span>
                        @endif
                    </div>

                    @if($order->payment_proof)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Transfer" class="w-12 h-12 object-cover rounded-lg border border-gray-200 shadow-2xs">
                                <div>
                                    <span class="text-xs font-black text-gray-900 block">Foto Struk Anda Sudah Diupload</span>
                                    <span class="text-[11px] text-gray-500">Admin sedang memverifikasi pembayaran Anda.</span>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-xs text-[#e52e2e] hover:underline font-black">
                                Lihat Foto
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('orders.upload_proof', $order->order_number) }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-2">
                        @csrf
                        <input type="file" name="payment_proof" accept="image/*" required class="w-full text-xs text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 bg-gray-50 rounded-xl border border-gray-200 p-1.5 focus:outline-none">
                        <button type="submit" class="w-full sm:w-auto shrink-0 px-5 py-2.5 bg-[#10b981] hover:bg-[#0da070] text-white font-black text-xs uppercase tracking-wider rounded-xl transition shadow-xs cursor-pointer">
                            {{ $order->payment_proof ? 'Ganti Bukti' : 'Upload Bukti' }}
                        </button>
                    </form>

                    <!-- Tombol Chat WA Kasir -->
                    <div class="pt-2">
                        @php
                            $waText = urlencode("Halo Admin 1988 Pocha, saya sudah transfer untuk pesanan {$order->order_number} sejumlah Rp " . number_format($order->total_amount, 0, ',', '.') . ". Mohon di-ACC ya. Terima kasih!");
                        @endphp
                        <a href="https://api.whatsapp.com/send?phone=6281277051988&text={{ $waText }}" target="_blank" class="w-full py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 font-black text-xs flex items-center justify-center gap-2 transition">
                            <svg class="w-4 h-4 fill-current text-emerald-600" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Konfirmasi Bukti Transfer via WhatsApp Admin</span>
                        </a>
                    </div>
                </div>

            </div>
        @elseif($order->payment_method === 'cod')
            <div class="my-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between text-xs">
                <div>
                    <span class="font-black text-[#10b981] uppercase block">Metode Bayar Tunai (COD)</span>
                    <span class="text-gray-600 font-medium">Siapkan uang pas sejumlah <strong>{{ $order->formatted_total }}</strong> saat kurir tiba.</span>
                </div>
            </div>
        @endif

        <!-- ======================================================== -->
        <!-- FITUR GESER PESANAN SELESAI (SWIPE TO COMPLETE) -->
        <!-- ======================================================== -->
        <div class="mt-4 pt-6 border-t border-gray-100">
            <div id="swipe-container" class="{{ $order->status === 'delivered' ? 'hidden' : 'block' }}">
                <div class="text-center mb-4">
                    <h3 class="text-sm font-black uppercase tracking-wider text-gray-900">
                        Makanan Sudah Tiba di Tanganmu?
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5 font-medium">
                        Geser tombol di bawah ke arah kanan untuk menandai pesanan telah selesai diterima.
                    </p>
                </div>

                <!-- Interactive Swipe Component -->
                <div class="max-w-md mx-auto">
                    <div id="swipe-track" class="relative h-14 bg-gray-100 rounded-xl border border-gray-300 p-1 flex items-center overflow-hidden select-none touch-none shadow-inner">
                        
                        <div id="swipe-fill" class="absolute top-0 left-0 bottom-0 bg-gradient-to-r from-[#e52e2e] to-[#10b981] rounded-xl w-0 transition-all pointer-events-none opacity-80"></div>
                        
                        <div id="swipe-text" class="absolute inset-0 flex items-center justify-center text-xs font-black text-gray-500 tracking-widest uppercase pointer-events-none transition-opacity">
                            <span>GESER JIKA SUDAH SAMPAI 👉</span>
                        </div>

                        <div id="swipe-handle" class="relative z-10 w-12 h-12 rounded-lg bg-[#e52e2e] text-white shadow-md flex items-center justify-center font-black cursor-grab active:cursor-grabbing hover:bg-[#c41c1c] transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kartu Pesan Sukses jika sudah diselesaikan -->
            <div id="delivered-success-card" class="{{ $order->status === 'delivered' ? 'block' : 'hidden' }} max-w-md mx-auto p-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-center shadow-xs">
                <div class="w-12 h-12 rounded-xl bg-[#10b981] text-white flex items-center justify-center text-xl mx-auto mb-3 font-black shadow-sm">
                    ✓
                </div>
                <h4 class="text-base font-black uppercase tracking-wider text-[#10b981]">Pesanan Selesai & Terantar!</h4>
                <p class="text-xs text-gray-600 mt-1 font-medium">Terima kasih telah memesan di 1988 Pocha Samarinda. Selamat menikmati hidangan Korea favoritmu!</p>
                <div class="mt-4 flex items-center justify-center gap-2">
                    <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] text-white text-xs font-black uppercase tracking-wider transition shadow-xs">
                        Pesan Menu Lain
                    </a>
                    <a href="https://www.instagram.com/1988pocha_smd/" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-xl bg-white hover:bg-gray-50 text-gray-700 text-xs font-bold border border-gray-300 transition shadow-2xs">
                        Instagram
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- Rincian Makanan & Info Penerima (Clean White Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200/90 rounded-2xl p-6 space-y-4 shadow-sm">
            <h3 class="text-xs font-black uppercase tracking-wider text-gray-900 pb-3 border-b border-gray-100 flex items-center justify-between">
                <span>Rincian Makanan</span>
                <span class="text-gray-400 font-bold">{{ $order->items->count() }} Menu</span>
            </h3>

            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 text-xs">
                        <div class="flex items-center gap-2.5">
                            @if($item->menuItem && $item->menuItem->image_url)
                                <img src="{{ $item->menuItem->image_url }}" alt="{{ $item->item_name }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100 shadow-2xs">
                            @endif
                            <div>
                                <h4 class="font-black text-gray-900 text-xs">{{ $item->item_name }}</h4>
                                <span class="text-gray-500 text-[11px] font-semibold">{{ $item->quantity }}x @ Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <span class="font-black text-[#e52e2e]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="space-y-1.5 pt-2 border-t border-gray-100 text-xs font-medium">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal:</span>
                    <span class="font-bold text-gray-700">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Ongkir ({{ $order->delivery_district }}):</span>
                    <span class="text-[#10b981] font-bold">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-900 font-black text-base pt-2 border-t border-gray-100">
                    <span>Total Pembayaran:</span>
                    <span class="text-[#e52e2e]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200/90 rounded-2xl p-6 space-y-4 shadow-sm">
            <h3 class="text-xs font-black uppercase tracking-wider text-gray-900 pb-3 border-b border-gray-100">
                Pengantaran Samarinda
            </h3>

            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-gray-400 block uppercase font-bold text-[10px]">Penerima</span>
                    <span class="text-gray-900 font-black">{{ $order->customer_name }} ({{ $order->customer_phone }})</span>
                </div>
                <div>
                    <span class="text-gray-400 block uppercase font-bold text-[10px]">Kecamatan</span>
                    <span class="text-[#10b981] font-black">{{ $order->delivery_district }}</span>
                </div>
                <div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(str_contains(strtolower($order->delivery_address), 'samarinda') ? $order->delivery_address : $order->delivery_address . ', Samarinda') }}" target="_blank" rel="noopener noreferrer" class="group/map flex items-start gap-2 text-gray-800 hover:text-[#e52e2e] bg-gray-50 hover:bg-red-50/50 p-3.5 rounded-xl border border-gray-200 hover:border-red-200 mt-1 font-medium leading-relaxed transition cursor-pointer" title="Buka di Google Maps ↗">
                        <svg class="w-4 h-4 text-[#e52e2e] shrink-0 mt-0.5 group-hover/map:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="flex-1 underline decoration-dotted decoration-gray-300 group-hover/map:decoration-[#e52e2e]">{{ $order->delivery_address }}</span>
                        <span class="text-xs text-gray-400 group-hover/map:text-[#e52e2e] font-bold">↗</span>
                    </a>
                </div>
                @if($order->delivery_notes)
                    <div>
                        <span class="text-gray-400 block uppercase font-bold text-[10px]">Catatan Khusus</span>
                        <p class="text-amber-600 bg-amber-50 p-2.5 rounded-xl border border-amber-200 mt-1 font-semibold italic">{{ $order->delivery_notes }}</p>
                    </div>
                @endif
                <div class="pt-2 border-t border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-100 text-[#e52e2e] flex items-center justify-center font-black text-xs uppercase">
                        KP
                    </div>
                    <div>
                        <span class="font-black text-gray-900 block">{{ $order->driver_name ?? 'Kurir Pocha Samarinda' }}</span>
                        <span class="text-[11px] text-gray-500 font-medium">{{ $order->driver_phone ?? '0821-9988-1988' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@include('orders.partials.chat_widget')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const track = document.getElementById('swipe-track');
        const handle = document.getElementById('swipe-handle');
        const fill = document.getElementById('swipe-fill');
        const swipeText = document.getElementById('swipe-text');
        const container = document.getElementById('swipe-container');
        const successCard = document.getElementById('delivered-success-card');
        const statusBadge = document.getElementById('badge-status-display');
        const step1 = document.getElementById('step-1-circle');
        const step2 = document.getElementById('step-2-circle');
        const step3 = document.getElementById('step-3-circle');
        const step4 = document.getElementById('step-4-circle');
        const progressBar = document.getElementById('stepper-progress-bar');

        // Polling Real-time Status Pesanan & Pembayaran dari Admin
        const orderNumber = "{{ $order->order_number }}";
        let currentStatus = "{{ $order->status }}";

        const checkInterval = setInterval(() => {
            if (currentStatus === 'delivered' || currentStatus === 'cancelled') {
                return;
            }

            fetch(`/orders/${orderNumber}/status`)
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        currentStatus = data.status;

                        // Update Stepper
                        if (data.status === 'pending') {
                            if (progressBar) progressBar.style.width = '25%';
                        } else if (data.status === 'cooking') {
                            if (progressBar) progressBar.style.width = '50%';
                            if (step2) step2.className = 'w-9 h-9 rounded-md flex items-center justify-center font-black text-xs shadow-lg transition-all bg-[#e52e2e] text-white';
                        } else if (data.status === 'delivering') {
                            if (progressBar) progressBar.style.width = '75%';
                            if (step2) step2.className = 'w-9 h-9 rounded-md flex items-center justify-center font-black text-xs shadow-lg transition-all bg-[#e52e2e] text-white';
                            if (step3) step3.className = 'w-9 h-9 rounded-md flex items-center justify-center font-black text-xs shadow-lg transition-all bg-[#e52e2e] text-white';
                        } else if (data.status === 'delivered') {
                            if (progressBar) progressBar.style.width = '100%';
                            if (step4) step4.className = 'w-9 h-9 rounded-md flex items-center justify-center font-black text-xs shadow-lg transition-all bg-[#10b981] text-white';
                            if (container) container.classList.add('hidden');
                            if (successCard) successCard.classList.remove('hidden');
                            clearInterval(checkInterval);
                        }

                        if (statusBadge && data.status_label) {
                            statusBadge.innerText = data.status_label;
                        }

                        // Jika status pembayaran sudah LUNAS (di-ACC admin)
                        if (data.payment_status === 'paid') {
                            const payBox = document.getElementById('payment-box');
                            if (payBox) {
                                payBox.innerHTML = `
                                    <div class="p-5 bg-[#10b981]/20 border border-[#10b981]/40 rounded-xl text-center space-y-1">
                                        <div class="text-[#10b981] font-black text-sm uppercase">✓ PEMBAYARAN SUDAH DI-ACC ADMIN (LUNAS)!</div>
                                        <p class="text-xs text-gray-200">Pesananmu sudah dikonfirmasi. Dapur sedang menyiapkan makananmu untuk diantar.</p>
                                    </div>
                                `;
                            }
                        }
                    }
                })
                .catch(() => {});
        }, 3000);

        if (track && handle) {
            let isDragging = false;
            let startX = 0;
            let currentX = 0;
            let maxDrag = 0;

            function updateBounds() {
                maxDrag = track.clientWidth - handle.clientWidth - 8;
            }
            updateBounds();
            window.addEventListener('resize', updateBounds);

            handle.addEventListener('pointerdown', (e) => {
                isDragging = true;
                startX = e.clientX || (e.touches && e.touches[0].clientX);
                handle.setPointerCapture(e.pointerId);
                if (swipeText) swipeText.style.opacity = '0.3';
            });

            window.addEventListener('pointermove', (e) => {
                if (!isDragging) return;
                const clientX = e.clientX || (e.touches && e.touches[0].clientX);
                const delta = clientX - startX;
                currentX = Math.max(0, Math.min(delta, maxDrag));

                handle.style.transform = `translateX(${currentX}px)`;
                if (fill) fill.style.width = `${currentX + 24}px`;

                if (currentX >= maxDrag * 0.95) {
                    isDragging = false;
                    triggerComplete();
                }
            });

            window.addEventListener('pointerup', () => {
                if (!isDragging) return;
                isDragging = false;
                handle.style.transition = 'transform 0.25s ease-out';
                if (fill) fill.style.transition = 'width 0.25s ease-out';
                handle.style.transform = 'translateX(0px)';
                if (fill) fill.style.width = '0px';
                if (swipeText) swipeText.style.opacity = '1';

                setTimeout(() => {
                    handle.style.transition = '';
                    if (fill) fill.style.transition = '';
                }, 250);
            });
        }

        function triggerComplete() {
            if (handle) handle.style.transform = `translateX(${track ? track.clientWidth - handle.clientWidth - 8 : 200}px)`;
            if (fill) fill.style.width = '100%';
            if (swipeText) swipeText.innerText = 'Memverifikasi Selesai...';

            fetch("{{ route('orders.confirm_delivery', $order->order_number) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    setTimeout(() => {
                        container.classList.add('hidden');
                        successCard.classList.remove('hidden');

                        if (statusBadge) {
                            statusBadge.className = 'text-xs px-2.5 py-0.5 rounded font-black uppercase tracking-wider bg-[#10b981]/20 text-[#10b981] border border-[#10b981]/30';
                            statusBadge.innerText = 'Pesanan Selesai / Terantar';
                        }
                        if (progressBar) progressBar.style.width = '100%';
                        if (step4) {
                            step4.className = 'w-9 h-9 rounded-md flex items-center justify-center font-black text-xs shadow-lg transition-all bg-[#10b981] text-white';
                        }
                    }, 400);
                }
            })
            .catch(err => {
                alert('Terjadi kendala saat update.');
            });
        }
    });
</script>
@endpush
