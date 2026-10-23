@extends('layouts.admin')

@section('title', 'Manajemen Metode Pembayaran | 1988 Pocha Samarinda')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto pb-24">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                <span>Admin Pocha</span>
                <span>/</span>
                <span class="text-[#e52e2e]">Manajemen Pembayaran</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 uppercase tracking-tight flex items-center gap-2.5">
                <span>Pengaturan Metode Pembayaran</span>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-100 text-[#e52e2e] border border-red-200 uppercase">
                    Live Control
                </span>
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1 font-medium">
                Atur barcode QRIS toko, rekening transfer bank, serta buka atau tutup metode pembayaran (seperti hanya menerima COD).
            </p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <a href="{{ route('orders.checkout') }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-white hover:bg-gray-50 border border-gray-300 text-xs font-black text-gray-700 shadow-2xs transition flex items-center gap-2">
                <svg class="w-4 h-4 text-[#e52e2e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Cek Halaman Checkout</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold flex items-center gap-3 shadow-xs animate-in fade-in">
            <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center font-black shrink-0">✓</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm font-semibold space-y-1 shadow-xs animate-in fade-in">
            <div class="font-black flex items-center gap-2 text-red-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>Mohon periksa kembali input Anda:</span>
            </div>
            <ul class="list-disc pl-5 space-y-0.5 text-xs">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Utama Konfigurasi Pembayaran -->
    <form action="{{ route('admin.payments.update') }}" method="POST" enctype="multipart/form-data" id="payment-settings-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Kolom Pengaturan Pembayaran (8 Kolom) -->
            <div class="lg:col-span-8 space-y-6">

                <!-- 0. KARTU MASTER: STATUS RESTORAN (BUKA / TUTUP TOKO) -->
                <div class="bg-white border rounded-2xl p-5 sm:p-6 shadow-sm transition-all duration-300 {{ $setting->is_store_open ? 'border-emerald-300 ring-2 ring-emerald-500/10' : 'border-red-300 bg-red-50/20 ring-2 ring-red-500/10' }}" id="card-store-status">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 font-black text-xl transition-colors {{ $setting->is_store_open ? 'bg-emerald-50 text-[#10b981] border border-emerald-200' : 'bg-red-50 text-[#e52e2e] border border-red-200' }}" id="icon-store-status">
                                {{ $setting->is_store_open ? '🏪' : '🔒' }}
                            </div>
                            <div>
                                <h3 class="text-base font-black uppercase text-gray-900 tracking-tight flex items-center gap-2">
                                    <span>Status Buka / Tutup Toko</span>
                                    <span id="badge-store-status" class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-md {{ $setting->is_store_open ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                                        {{ $setting->is_store_open ? 'Status: Toko Buka' : 'Status: Toko Tutup' }}
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-500 font-medium">
                                    Kontrol utama resto. Jika toko ditutup, semua pembayaran (QRIS, Transfer, COD) otomatis terkunci dan pembeli tidak bisa checkout.
                                </p>
                            </div>
                        </div>

                        <!-- Sakelar Switch Buka / Tutup Toko -->
                        <div class="flex items-center gap-3 self-end sm:self-auto">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_store_open" id="toggle-store" value="1" class="sr-only peer" {{ old('is_store_open', $setting->is_store_open) ? 'checked' : '' }}>
                                <div class="w-13 h-7 bg-red-500 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2.5px] after:left-[2.5px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#10b981]"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2.5" id="store-closed-msg-container">
                        <label class="block text-xs font-bold text-gray-700 uppercase">
                            Pesan / Alasan Toko Tutup untuk Pelanggan (Tampil di Checkout)
                        </label>
                        <textarea name="closed_message" id="closed-message-input" rows="2" placeholder="Contoh: Mohon maaf, 1988 Pocha sedang istirahat. Buka kembali pukul 16:00 WITA..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition">{{ old('closed_message', $setting->closed_message) }}</textarea>
                        <p class="text-[11px] text-gray-500">
                            💡 Pesan ini akan otomatis tampil saat pembeli membuka halaman checkout ketika resto sedang ditutup.
                        </p>
                    </div>
                </div>

                <!-- 1. KARTU QRIS TOKO LANGSUNG -->
                <div class="bg-white border rounded-2xl p-5 sm:p-6 shadow-sm transition-all duration-300 {{ $setting->is_qris_active ? 'border-gray-200' : 'border-gray-200 opacity-80 bg-gray-50/50' }}" id="card-qris">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-200 flex items-center justify-center text-[#e52e2e] shrink-0 font-black">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black uppercase text-gray-900 tracking-tight flex items-center gap-2">
                                    <span>QRIS Toko Langsung</span>
                                    <span id="badge-qris-status" class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full {{ $setting->is_qris_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                        {{ $setting->is_qris_active ? 'Status: Aktif' : 'Status: Ditutup' }}
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-500 font-medium">Pelanggan scan barcode QRIS menggunakan BCA, GoPay, OVO, ShopeePay, Mandiri, dll.</p>
                            </div>
                        </div>

                        <!-- Sakelar Switch Aktif / Non-aktif -->
                        <div class="flex items-center gap-3 self-end sm:self-auto">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_qris_active" id="toggle-qris" value="1" class="sr-only peer" {{ old('is_qris_active', $setting->is_qris_active) ? 'checked' : '' }}>
                                <div class="w-12 h-6.5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5.5 after:w-5.5 after:transition-all peer-checked:bg-[#10b981]"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-5 space-y-5" id="qris-fields-container">
                        <!-- Upload & Preview Barcode QRIS -->
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80 space-y-4">
                            <label class="block text-xs font-black uppercase tracking-wider text-gray-700">Foto Barcode QRIS Resmi Toko</label>
                            
                            <div class="flex flex-col sm:flex-row items-center gap-5">
                                <!-- Preview Box -->
                                <div class="relative w-36 h-36 bg-white p-2 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center shrink-0 shadow-xs overflow-hidden group">
                                    <img id="qris-preview-img" src="{{ $setting->qris_image_url }}" alt="Preview QRIS" class="w-full h-full object-contain rounded-xl">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold text-center p-2 rounded-xl">
                                        Scan Barcode Preview
                                    </div>
                                </div>

                                <div class="space-y-2.5 flex-1 w-full text-center sm:text-left">
                                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                        <label for="qris_image" class="px-3.5 py-2 bg-gray-900 hover:bg-[#e52e2e] text-white text-xs font-black uppercase tracking-wider rounded-xl cursor-pointer transition shadow-xs inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            <span>Unggah Foto QRIS Baru</span>
                                        </label>
                                        <input type="file" name="qris_image" id="qris_image" accept="image/*" class="hidden" onchange="previewQrisImage(event)">

                                        @if($setting->qris_image)
                                            <label class="px-3 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 text-xs font-bold rounded-xl cursor-pointer transition inline-flex items-center gap-1.5">
                                                <input type="checkbox" name="remove_qris_image" value="1" class="rounded text-[#e52e2e] focus:ring-0 mr-1">
                                                <span>Hapus Foto & Gunakan Default</span>
                                            </label>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-gray-500">
                                        Format file: <strong>JPG, PNG, WEBP, SVG</strong> (Maksimal 5MB). Pastikan barcode jelas terbaca dan kontras.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Merchant Name & Notes -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nama Usaha / Merchant di QRIS *</label>
                                <input type="text" name="qris_merchant_name" value="{{ old('qris_merchant_name', $setting->qris_merchant_name) }}" placeholder="Contoh: 1988 POCHA SAMARINDA" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Petunjuk Pembayaran QRIS untuk Pelanggan</label>
                                <textarea name="qris_notes" rows="2" placeholder="Petunjuk khusus seperti 'Unggah bukti transfer sesaat setelah scan'..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition">{{ old('qris_notes', $setting->qris_notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- 2. KARTU TRANSFER BANK LANGSUNG -->
                <div class="bg-white border rounded-2xl p-5 sm:p-6 shadow-sm transition-all duration-300 {{ $setting->is_transfer_active ? 'border-gray-200' : 'border-gray-200 opacity-80 bg-gray-50/50' }}" id="card-transfer">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-600 shrink-0 font-black">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black uppercase text-gray-900 tracking-tight flex items-center gap-2">
                                    <span>Transfer Bank Langsung</span>
                                    <span id="badge-transfer-status" class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full {{ $setting->is_transfer_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                        {{ $setting->is_transfer_active ? 'Status: Aktif' : 'Status: Ditutup' }}
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-500 font-medium">Pelanggan transfer manual ke rekening bank restoran dan mengunggah bukti struk transfer.</p>
                            </div>
                        </div>

                        <!-- Sakelar Switch Transfer -->
                        <div class="flex items-center gap-3 self-end sm:self-auto">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_transfer_active" id="toggle-transfer" value="1" class="sr-only peer" {{ old('is_transfer_active', $setting->is_transfer_active) ? 'checked' : '' }}>
                                <div class="w-12 h-6.5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5.5 after:w-5.5 after:transition-all peer-checked:bg-[#10b981]"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-5 space-y-6" id="transfer-fields-container">
                        <!-- Rekening Utama (Bank 1) -->
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase text-gray-900 flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-md bg-blue-600 text-white flex items-center justify-center text-[10px] font-bold">1</span>
                                    Rekening Bank Utama (Wajib Aktif)
                                </span>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-md">Utama</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Nama Bank</label>
                                    <input type="text" name="bank_name" value="{{ old('bank_name', $setting->bank_name) }}" placeholder="Contoh: Bank BCA" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] font-semibold transition">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Nomor Rekening</label>
                                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $setting->bank_account_number) }}" placeholder="Contoh: 869-1988-1988" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] font-bold tracking-wider transition">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Atas Nama Pemilik</label>
                                    <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $setting->bank_account_name) }}" placeholder="Contoh: 1988 Pocha Samarinda" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] font-semibold transition">
                                </div>
                            </div>
                        </div>

                        <!-- Rekening Tambahan (Bank 2) -->
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-200/80 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase text-gray-900 flex items-center gap-1.5">
                                    <span class="w-5 h-5 rounded-md bg-amber-600 text-white flex items-center justify-center text-[10px] font-bold">2</span>
                                    Rekening Bank Kedua (Opsional)
                                </span>
                                <span class="text-[10px] font-bold text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded-md">Pilihan Tambahan</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Nama Bank #2</label>
                                    <input type="text" name="bank_2_name" value="{{ old('bank_2_name', $setting->bank_2_name) }}" placeholder="Contoh: Bank Mandiri / BRI" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] font-semibold transition">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Nomor Rekening #2</label>
                                    <input type="text" name="bank_2_account_number" value="{{ old('bank_2_account_number', $setting->bank_2_account_number) }}" placeholder="Contoh: 148-00-1988-1988" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] font-bold tracking-wider transition">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Atas Nama Pemilik #2</label>
                                    <input type="text" name="bank_2_account_name" value="{{ old('bank_2_account_name', $setting->bank_2_account_name) }}" placeholder="Contoh: 1988 Pocha Samarinda" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] font-semibold transition">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- 3. KARTU BAYAR DI TEMPAT (COD) -->
                <div class="bg-white border rounded-2xl p-5 sm:p-6 shadow-sm transition-all duration-300 {{ $setting->is_cod_active ? 'border-gray-200' : 'border-gray-200 opacity-80 bg-gray-50/50' }}" id="card-cod">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-[#10b981] shrink-0 font-black">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black uppercase text-gray-900 tracking-tight flex items-center gap-2">
                                    <span>COD (Bayar Tunai di Tempat)</span>
                                    <span id="badge-cod-status" class="text-[10px] font-black uppercase px-2 py-0.5 rounded-full {{ $setting->is_cod_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                        {{ $setting->is_cod_active ? 'Status: Aktif' : 'Status: Ditutup' }}
                                    </span>
                                </h3>
                                <p class="text-xs text-gray-500 font-medium">Pelanggan membayar dengan uang tunai langsung kepada kurir Pocha saat pesanan tiba.</p>
                            </div>
                        </div>

                        <!-- Sakelar Switch COD -->
                        <div class="flex items-center gap-3 self-end sm:self-auto">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_cod_active" id="toggle-cod" value="1" class="sr-only peer" {{ old('is_cod_active', $setting->is_cod_active) ? 'checked' : '' }}>
                                <div class="w-12 h-6.5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5.5 after:w-5.5 after:transition-all peer-checked:bg-[#10b981]"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4" id="cod-fields-container">
                        <!-- Alert Tips Khusus Mode Hanya COD -->
                        <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200 text-amber-800 text-xs flex items-start gap-2.5">
                            <span class="text-base leading-none">💡</span>
                            <div class="space-y-1">
                                <span class="font-black uppercase tracking-wide block">Fitur Mode Hanya COD:</span>
                                <p class="text-gray-600 font-medium leading-relaxed">
                                    Jika Anda hanya ingin menerima pesanan secara <strong>COD (Cash on Delivery)</strong>, cukup <strong>matikan sakelar sakelar QRIS dan Transfer Bank</strong> di atas. Halaman checkout pembeli secara otomatis hanya akan menampilkan opsi COD saja.
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Catatan / Ketentuan untuk Pembeli COD</label>
                            <textarea name="cod_notes" rows="2" placeholder="Contoh: Mohon siapkan uang pas saat kurir tiba di alamat Anda..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition">{{ old('cod_notes', $setting->cod_notes) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Kolom Preview Interaktif & Simpan (4 Kolom) -->
            <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                
                <!-- Action Save Box -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-900 pb-2 border-b border-gray-100 flex items-center justify-between">
                        <span>Aksi Penyimpanan</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Panel Admin</span>
                    </h3>

                    <p class="text-xs text-gray-500 font-medium leading-relaxed">
                        Perubahan metode pembayaran, barcode QRIS, dan nomor rekening akan langsung diterapkan pada halaman checkout pelanggan secara seketika.
                    </p>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-95 text-white font-black text-xs uppercase tracking-widest shadow-md shadow-red-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>SIMPAN PENGATURAN</span>
                    </button>
                </div>

                <!-- Live Simulator: Apa yang dilihat pelanggan di Checkout? -->
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span class="text-xs">👁️</span>
                            <h3 class="text-xs font-black uppercase tracking-wider text-gray-900">Simulasi Tampilan Checkout</h3>
                        </div>
                        <span class="text-[9px] font-bold text-gray-400 uppercase">Live Preview</span>
                    </div>

                    <p class="text-[11px] text-gray-500 font-medium">
                        Berikut opsi pembayaran yang saat ini akan tampil di hadapan pembeli:
                    </p>

                    <!-- Preview Cards List -->
                    <div class="space-y-2.5" id="preview-checkout-options">
                        
                        <!-- Banner Simulasi Toko Ditutup -->
                        <div id="sim-store-closed-banner" class="hidden p-4 rounded-xl bg-red-100 border border-red-300 text-red-900 space-y-1.5 text-center">
                            <span class="text-xs font-black uppercase tracking-wider block text-red-700">🔒 RESTORAN DITUTUP</span>
                            <p class="text-[11px] font-semibold text-red-800 leading-tight">
                                Semua metode pembayaran & checkout dinonaktifkan. Pelanggan tidak dapat melakukan pemesanan.
                            </p>
                        </div>

                        <!-- Preview QRIS -->
                        <div id="sim-qris-card" class="p-3 rounded-xl border border-gray-200 bg-gray-50 flex items-center gap-3 transition-all duration-200">
                            <div class="w-4 h-4 rounded-md border border-[#e52e2e] bg-[#e52e2e] flex items-center justify-center text-white">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-xs font-black text-gray-900 block truncate">QRIS Toko Langsung</span>
                                <span class="text-[10px] text-gray-500 block truncate">BCA, Mandiri, GoPay, OVO, ShopeePay</span>
                            </div>
                        </div>

                        <!-- Preview Transfer Bank -->
                        <div id="sim-transfer-card" class="p-3 rounded-xl border border-gray-200 bg-gray-50 flex items-center gap-3 transition-all duration-200">
                            <div class="w-4 h-4 rounded-md border border-gray-300"></div>
                            <div class="min-w-0 flex-1">
                                <span class="text-xs font-black text-gray-900 block truncate">Transfer Bank Langsung</span>
                                <span class="text-[10px] text-gray-500 block truncate" id="sim-bank-names">
                                    {{ $setting->bank_name ?: 'Bank BCA' }}
                                    @if($setting->bank_2_name) / {{ $setting->bank_2_name }} @endif
                                </span>
                            </div>
                        </div>

                        <!-- Preview COD -->
                        <div id="sim-cod-card" class="p-3 rounded-xl border border-gray-200 bg-gray-50 flex items-center gap-3 transition-all duration-200">
                            <div class="w-4 h-4 rounded-md border border-gray-300"></div>
                            <div class="min-w-0 flex-1">
                                <span class="text-xs font-black text-gray-900 block truncate">COD (Bayar di Tempat)</span>
                                <span class="text-[10px] text-emerald-700 font-semibold block truncate">Bayar tunai ke kurir saat makanan tiba</span>
                            </div>
                        </div>

                        <!-- Empty State if all disabled in preview -->
                        <div id="sim-empty-warning" class="hidden p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-bold text-center">
                            ⚠️ Perhatian: Anda mematikan semua metode bayar. Minimal 1 harus aktif!
                        </div>

                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                        <span>Status Sistem:</span>
                        <span id="sim-active-count" class="font-black text-[#e52e2e]">3 Metode</span>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

@push('scripts')
<script>
    // Live Image Preview for uploaded QRIS
    function previewQrisImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('qris-preview-img').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    // Dynamic Live Preview Updates based on Toggle Switches
    document.addEventListener('DOMContentLoaded', () => {
        const toggleStore = document.getElementById('toggle-store');
        const badgeStore = document.getElementById('badge-store-status');
        const cardStore = document.getElementById('card-store-status');
        const iconStore = document.getElementById('icon-store-status');
        const simStoreClosed = document.getElementById('sim-store-closed-banner');

        const toggleQris = document.getElementById('toggle-qris');
        const toggleTransfer = document.getElementById('toggle-transfer');
        const toggleCod = document.getElementById('toggle-cod');

        const badgeQris = document.getElementById('badge-qris-status');
        const badgeTransfer = document.getElementById('badge-transfer-status');
        const badgeCod = document.getElementById('badge-cod-status');

        const cardQris = document.getElementById('card-qris');
        const cardTransfer = document.getElementById('card-transfer');
        const cardCod = document.getElementById('card-cod');

        const simQris = document.getElementById('sim-qris-card');
        const simTransfer = document.getElementById('sim-transfer-card');
        const simCod = document.getElementById('sim-cod-card');
        const simWarning = document.getElementById('sim-empty-warning');
        const simActiveCount = document.getElementById('sim-active-count');

        function updateLiveState() {
            const isStoreOpen = toggleStore ? toggleStore.checked : true;
            const isQris = toggleQris.checked;
            const isTransfer = toggleTransfer.checked;
            const isCod = toggleCod.checked;

            // Update Master Store Status
            if (isStoreOpen) {
                badgeStore.className = 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300';
                badgeStore.textContent = 'Status: Toko Buka';
                cardStore.className = 'bg-white border rounded-2xl p-5 sm:p-6 shadow-sm transition-all duration-300 border-emerald-300 ring-2 ring-emerald-500/10';
                iconStore.className = 'w-11 h-11 rounded-xl flex items-center justify-center shrink-0 font-black text-xl transition-colors bg-emerald-50 text-[#10b981] border border-emerald-200';
                iconStore.textContent = '🏪';
                simStoreClosed.classList.add('hidden');
            } else {
                badgeStore.className = 'text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-red-100 text-red-800 border border-red-300';
                badgeStore.textContent = 'Status: Toko Tutup';
                cardStore.className = 'bg-white border rounded-2xl p-5 sm:p-6 shadow-sm transition-all duration-300 border-red-300 bg-red-50/20 ring-2 ring-red-500/10';
                iconStore.className = 'w-11 h-11 rounded-xl flex items-center justify-center shrink-0 font-black text-xl transition-colors bg-red-50 text-[#e52e2e] border border-red-200';
                iconStore.textContent = '🔒';
                simStoreClosed.classList.remove('hidden');
            }

            // Badges & opacity for individual payment methods
            if (isQris) {
                badgeQris.className = 'text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200';
                badgeQris.textContent = 'Status: Aktif';
                cardQris.classList.remove('opacity-80', 'bg-gray-50/50');
                if (isStoreOpen) simQris.classList.remove('hidden');
                else simQris.classList.add('hidden');
            } else {
                badgeQris.className = 'text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200';
                badgeQris.textContent = 'Status: Ditutup';
                cardQris.classList.add('opacity-80', 'bg-gray-50/50');
                simQris.classList.add('hidden');
            }

            if (isTransfer) {
                badgeTransfer.className = 'text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200';
                badgeTransfer.textContent = 'Status: Aktif';
                cardTransfer.classList.remove('opacity-80', 'bg-gray-50/50');
                if (isStoreOpen) simTransfer.classList.remove('hidden');
                else simTransfer.classList.add('hidden');
            } else {
                badgeTransfer.className = 'text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200';
                badgeTransfer.textContent = 'Status: Ditutup';
                cardTransfer.classList.add('opacity-80', 'bg-gray-50/50');
                simTransfer.classList.add('hidden');
            }

            if (isCod) {
                badgeCod.className = 'text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200';
                badgeCod.textContent = 'Status: Aktif';
                cardCod.classList.remove('opacity-80', 'bg-gray-50/50');
                if (isStoreOpen) simCod.classList.remove('hidden');
                else simCod.classList.add('hidden');
            } else {
                badgeCod.className = 'text-[10px] font-black uppercase px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200';
                badgeCod.textContent = 'Status: Ditutup';
                cardCod.classList.add('opacity-80', 'bg-gray-50/50');
                simCod.classList.add('hidden');
            }

            if (!isStoreOpen) {
                simWarning.classList.add('hidden');
                simActiveCount.textContent = 'Toko Tutup (Pembayaran Dikunci)';
                simActiveCount.className = 'font-black text-red-600';
                return;
            }

            let count = (isQris ? 1 : 0) + (isTransfer ? 1 : 0) + (isCod ? 1 : 0);
            if (count === 0) {
                simWarning.classList.remove('hidden');
                simActiveCount.textContent = '0 Metode (Checkout Eror)';
                simActiveCount.className = 'font-black text-red-600';
            } else {
                simWarning.classList.add('hidden');
                if (count === 1 && isCod) {
                    simActiveCount.textContent = 'Hanya COD Aktif';
                    simActiveCount.className = 'font-black text-emerald-600';
                } else {
                    simActiveCount.textContent = `${count} Metode Aktif`;
                    simActiveCount.className = 'font-black text-[#e52e2e]';
                }
            }
        }

        if (toggleStore) toggleStore.addEventListener('change', updateLiveState);
        toggleQris.addEventListener('change', updateLiveState);
        toggleTransfer.addEventListener('change', updateLiveState);
        toggleCod.addEventListener('change', updateLiveState);

        updateLiveState();
    });
</script>
@endpush
@endsection
