@extends('layouts.admin')

@section('title', 'Arsip & Riwayat Pesanan | 1988 Pocha Samarinda')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Top Bar Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 sm:pb-6 border-b border-gray-200/80">
        <div class="space-y-1">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-black uppercase tracking-wider">
                    Database Arsip Pesanan
                </span>
                @if($selectedDate)
                    <span class="text-xs font-bold text-gray-700 bg-gray-100 px-2.5 py-0.5 rounded-full border border-gray-200">
                        Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                    </span>
                @else
                    <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full border border-gray-200">
                        Semua Hari Sebelumnya (< {{ now()->translatedFormat('d M Y') }})
                    </span>
                @endif
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-900 uppercase tracking-tight">Arsip & Riwayat Pesanan</h1>
            <p class="text-xs text-gray-500 font-medium">Pesanan hari kemarin dan seterusnya otomatis tersimpan di sini. Gunakan filter kalender untuk mencari tanggal spesifik.</p>
        </div>
        <div class="flex items-center gap-2.5 self-stretch sm:self-auto">
            <div class="w-full sm:w-auto px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white border border-gray-200 flex items-center gap-3 shadow-2xs">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-amber-500/10 text-amber-600 border border-amber-500/20 flex items-center justify-center font-black text-xs sm:text-sm shrink-0">
                    Rp
                </div>
                <div>
                    <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Omzet Terfilter</span>
                    <span class="text-sm sm:text-base font-black text-gray-900 tracking-tight">Rp {{ number_format($archiveStats['revenue'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar & Date Picker (Kalender) -->
    <div class="bg-white border border-gray-200/90 rounded-2xl p-4 sm:p-5 shadow-2xs">
        <form action="{{ route('admin.archive') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4 items-end">
            <!-- Filter Tanggal (Kalender Datepicker) -->
            <div>
                <label for="date-filter" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Filter Kalender (Pilih Hari)</span>
                </label>
                <div class="relative">
                    <input 
                        type="date" 
                        id="date-filter" 
                        name="date" 
                        value="{{ $selectedDate ?? '' }}" 
                        max="{{ now()->format('Y-m-d') }}"
                        class="w-full min-h-[42px] bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 font-bold focus:outline-none focus:ring-2 focus:ring-[#e52e2e]/20 focus:border-[#e52e2e] shadow-2xs cursor-pointer">
                </div>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status-filter" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Status Pesanan
                </label>
                <select 
                    id="status-filter" 
                    name="status" 
                    class="w-full min-h-[42px] bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-[#e52e2e]/20 focus:border-[#e52e2e] shadow-2xs cursor-pointer">
                    <option value="all" {{ empty($selectedStatus) || $selectedStatus === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="delivered" {{ $selectedStatus === 'delivered' ? 'selected' : '' }}>Selesai / Terantar</option>
                    <option value="delivering" {{ $selectedStatus === 'delivering' ? 'selected' : '' }}>Diantar Kurir</option>
                    <option value="cooking" {{ $selectedStatus === 'cooking' ? 'selected' : '' }}>Sedang Dimasak</option>
                    <option value="pending" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Pending / Menunggu ACC</option>
                    <option value="cancelled" {{ $selectedStatus === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Cari No Order / Pelanggan -->
            <div>
                <label for="search-input" class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">
                    Cari No. Order / Nama / HP
                </label>
                <input 
                    type="text" 
                    id="search-input" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    placeholder="Contoh: POCHA-..., Budi, 0812..." 
                    class="w-full min-h-[42px] bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#e52e2e]/20 focus:border-[#e52e2e] shadow-2xs font-medium">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button 
                    type="submit" 
                    class="flex-1 min-h-[42px] bg-[#e52e2e] hover:bg-[#c41c1c] text-white px-4 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition shadow-xs flex items-center justify-center gap-1.5 cursor-pointer active:scale-98">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Terapkan Filter</span>
                </button>
                @if($selectedDate || ($selectedStatus && $selectedStatus !== 'all') || $search)
                    <a 
                        href="{{ route('admin.archive') }}" 
                        class="min-h-[42px] bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider transition shadow-2xs cursor-pointer flex items-center justify-center active:scale-98"
                        title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Summary Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-4">
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-gray-200/90 shadow-2xs">
            <span class="text-[10px] sm:text-[11px] text-gray-400 block font-bold uppercase tracking-wider">Total Pesanan</span>
            <span class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight mt-1.5 sm:mt-2 block">{{ $archiveStats['total'] }}</span>
        </div>
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-emerald-200 shadow-2xs">
            <span class="text-[10px] sm:text-[11px] text-[#10b981] block font-bold uppercase tracking-wider">Pesanan Sukses</span>
            <span class="text-2xl sm:text-3xl font-black text-[#10b981] tracking-tight mt-1.5 sm:mt-2 block">{{ $archiveStats['delivered'] }}</span>
        </div>
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-amber-200 shadow-2xs col-span-2 sm:col-span-1">
            <span class="text-[10px] sm:text-[11px] text-amber-700 block font-bold uppercase tracking-wider">Total Omzet Lunas</span>
            <span class="text-xl sm:text-2xl font-black text-amber-600 tracking-tight mt-1.5 sm:mt-2 block">Rp {{ number_format($archiveStats['revenue'], 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Archive Orders Section -->
    <div class="space-y-4">
        <div class="bg-white border border-gray-200/90 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
            <div>
                <h2 class="text-sm font-black uppercase tracking-wider text-gray-900">
                    @if($selectedDate)
                        Pesanan Pada {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                    @else
                        Daftar Seluruh Pesanan Lampau
                    @endif
                </h2>
                <p class="text-[11px] text-gray-500">Menampilkan data arsip pesanan pelanggan beserta rincian transaksi.</p>
            </div>
            <span class="text-xs px-3.5 py-1.5 rounded-xl bg-gray-50 border border-gray-200 text-gray-700 font-black self-start sm:self-auto shadow-2xs">
                {{ $orders->total() }} Pesanan Ditemukan
            </span>
        </div>

        <div class="space-y-3.5">
            @forelse($orders as $ord)
                @php
                    $unreadCustomerMsgs = $ord->messages ? $ord->messages->where('sender_type', 'customer')->where('is_read', false)->count() : 0;
                    $badgeClass = match($ord->status) {
                        'pending' => 'bg-amber-50 text-amber-800 border-amber-300',
                        'cooking' => 'bg-red-50 text-[#e52e2e] border-red-200',
                        'delivering' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'delivered' => 'bg-emerald-50 text-[#10b981] border-emerald-200',
                        'cancelled' => 'bg-gray-100 text-gray-600 border-gray-200',
                        default => 'bg-gray-100 text-gray-600 border-gray-200',
                    };
                @endphp
                <div class="bg-white border border-gray-200/90 hover:border-gray-300 rounded-2xl p-4 sm:p-5 shadow-2xs transition-all">
                    <!-- Baris 1: Header Order -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 pb-3 border-b border-gray-150 mb-3.5">
                        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                            <span class="font-black text-gray-900 text-xs sm:text-sm tracking-wide bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-200">{{ $ord->order_number }}</span>
                            <span class="text-[10px] text-gray-700 font-bold">{{ $ord->created_at->translatedFormat('d M Y') }}</span>
                            <span class="text-[10px] text-gray-400 font-medium">{{ $ord->created_at->format('H:i') }} WITA</span>
                            <span class="inline-block px-2.5 py-1 rounded-lg text-[10px] font-black border uppercase tracking-wider {{ $badgeClass }}">
                                {{ $ord->status_label }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end gap-2 flex-wrap pt-1 sm:pt-0">
                            <span class="font-black text-[#e52e2e] text-base sm:text-lg tracking-tight">{{ $ord->formatted_total }}</span>
                            @if($ord->payment_status === 'paid')
                                <span class="inline-flex items-center text-[10px] uppercase font-black px-2.5 py-1 rounded-lg bg-emerald-50 text-[#10b981] border border-emerald-200">
                                    LUNAS
                                </span>
                            @else
                                <span class="inline-flex items-center text-[10px] uppercase font-black px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-300">
                                    BELUM LUNAS
                                </span>
                            @endif
                            <span class="text-[10px] font-bold text-gray-400 uppercase bg-gray-50 px-2 py-1 rounded-lg border border-gray-200">{{ str_replace('_', ' ', $ord->payment_method) }}</span>
                        </div>
                    </div>

                    <!-- Baris 2: Detail Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 sm:gap-4 items-start">
                        <!-- Kolom 1: Penerima & Alamat (4 col desktop, 5 col tablet) -->
                        <div class="md:col-span-5 lg:col-span-4 space-y-2.5">
                            <!-- Nama, Telepon & Chat -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-black text-gray-900 text-sm">{{ $ord->customer_name }}</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ord->customer_phone) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold text-xs transition shadow-2xs" title="Chat via WhatsApp">
                                    {{ $ord->customer_phone }}
                                    <span class="text-[9px] bg-emerald-600 text-white px-1 rounded font-black">WA</span>
                                </a>
                                <button type="button" 
                                        onclick="openAdminChatModal('{{ $ord->order_number }}', '{{ addslashes($ord->customer_name) }}', '{{ $ord->customer_phone }}')" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 font-bold text-xs transition cursor-pointer shadow-2xs"
                                        title="Buka Chat Pembeli">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    <span>Chat</span>
                                    @if($unreadCustomerMsgs > 0)
                                        <span class="bg-[#e52e2e] text-white text-[10px] font-black px-1.5 py-0.5 rounded-md leading-none">{{ $unreadCustomerMsgs }}</span>
                                    @endif
                                </button>
                            </div>

                            <!-- Kecamatan Tag -->
                            <div>
                                <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200">
                                    <svg class="w-3 h-3 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    Kec. {{ $ord->delivery_district }}
                                </span>
                            </div>

                            <!-- Google Maps Link -->
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(str_contains(strtolower($ord->delivery_address), 'samarinda') ? $ord->delivery_address : $ord->delivery_address . ', Samarinda') }}" 
                               target="_blank" rel="noopener noreferrer" 
                               class="group/map flex items-start gap-1.5 text-gray-700 hover:text-[#e52e2e] text-xs leading-snug font-medium transition cursor-pointer p-2.5 rounded-xl border border-gray-200 hover:border-red-200 hover:bg-red-50/40 bg-gray-50/70" 
                               title="Klik untuk membuka lokasi di Google Maps">
                                <svg class="w-4 h-4 text-[#e52e2e] shrink-0 mt-0.5 group-hover/map:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="flex-1 underline decoration-dotted decoration-gray-300 group-hover/map:decoration-[#e52e2e] break-words">{{ $ord->delivery_address }}</span>
                                <span class="text-[11px] text-gray-400 group-hover/map:text-[#e52e2e] shrink-0 font-bold flex items-center gap-0.5">
                                    <span>Maps</span> ↗
                                </span>
                            </a>

                            <!-- Catatan Pengantaran -->
                            @if($ord->delivery_notes)
                                <div class="text-[11px] text-amber-900 bg-amber-50/90 px-2.5 py-1.5 rounded-lg border border-amber-200 font-medium flex items-start gap-1.5">
                                    <span class="shrink-0">📝</span>
                                    <span class="break-words font-semibold">{{ $ord->delivery_notes }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Kolom 2: Menu Dipesan (5 col desktop, 7 col tablet) -->
                        <div class="md:col-span-7 lg:col-span-5">
                            <div class="bg-gray-50/90 rounded-xl p-3 sm:p-3.5 border border-gray-200/80 space-y-2">
                                <div class="text-[10px] font-bold uppercase tracking-wider text-gray-400 pb-1.5 border-b border-gray-200/60 flex justify-between">
                                    <span>Menu Dipesan</span>
                                    <span>Subtotal</span>
                                </div>
                                @foreach($ord->items as $item)
                                    <div class="flex items-start justify-between gap-2.5 text-xs">
                                        <div class="flex items-start gap-2 min-w-0 flex-1">
                                            <span class="font-black text-gray-900 bg-white px-1.5 py-0.5 rounded-md border border-gray-200 text-[11px] shrink-0">{{ $item->quantity }}x</span>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-gray-900 font-bold leading-snug break-words">{{ $item->item_name }}</p>
                                                @if($item->notes)
                                                    <p class="text-[10px] text-amber-700 italic">"{{ $item->notes }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="text-gray-700 font-extrabold whitespace-nowrap shrink-0 text-xs">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach

                                @if($ord->delivery_fee > 0)
                                    <div class="pt-1.5 border-t border-gray-200/60 flex items-center justify-between text-[11px] text-gray-500 font-medium">
                                        <span>Ongkir ({{ $ord->delivery_district }})</span>
                                        <span class="font-bold text-gray-700">Rp {{ number_format($ord->delivery_fee, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Kolom 3: Bukti Bayar + Aksi Status (3 col desktop, 12 col tablet) -->
                        <div class="md:col-span-12 lg:col-span-3">
                            <div class="bg-gray-50/70 rounded-xl p-3 sm:p-3.5 border border-gray-200/80 space-y-3">
                                <!-- Baris Atas: Bukti Transfer & Lacak -->
                                <div class="flex items-center justify-between gap-2 flex-wrap pb-2.5 border-b border-gray-200/60">
                                    <div>
                                        @if($ord->payment_proof)
                                            <button 
                                                type="button" 
                                                onclick="openProofModal('{{ asset('storage/' . $ord->payment_proof) }}', '{{ $ord->order_number }}', '{{ $ord->customer_name }}', '{{ $ord->formatted_total }}')" 
                                                class="inline-flex items-center gap-1.5 text-xs text-[#10b981] hover:text-emerald-700 font-bold bg-white hover:bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-200 shadow-2xs transition cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Lihat Struk</span>
                                            </button>
                                        @elseif($ord->payment_method === 'cod')
                                            <span class="inline-flex items-center gap-1 text-[11px] text-blue-700 font-bold bg-blue-50 px-2.5 py-1 rounded-md border border-blue-200">
                                                💵 Bayar COD
                                            </span>
                                        @else
                                            <span class="text-[11px] text-gray-400 italic">Belum kirim bukti</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('orders.show', $ord->order_number) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-[#e52e2e] hover:text-red-700 font-bold hover:underline">
                                        <span>Lacak</span>
                                        <span>↗</span>
                                    </a>
                                </div>

                                <!-- Baris Bawah: Form Update Status -->
                                <form action="{{ route('admin.orders.update_status', $ord->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <select name="status" class="flex-1 min-h-[38px] bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-800 font-bold focus:outline-none focus:border-[#e52e2e] cursor-pointer shadow-2xs">
                                        <option value="pending" {{ $ord->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="cooking" {{ $ord->status === 'cooking' ? 'selected' : '' }}>Dimasak</option>
                                        <option value="delivering" {{ $ord->status === 'delivering' ? 'selected' : '' }}>Diantar</option>
                                        <option value="delivered" {{ $ord->status === 'delivered' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $ord->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                    <button type="submit" class="min-h-[38px] px-4 py-2 rounded-xl bg-gray-800 hover:bg-black text-white font-black text-xs uppercase tracking-wider shadow-xs transition cursor-pointer shrink-0 active:scale-98">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center text-gray-500 shadow-2xs">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-bold text-gray-700">Tidak ada pesanan ditemukan untuk filter ini.</p>
                    <p class="text-xs text-gray-400 mt-1">Coba pilih tanggal lain atau ganti kata kunci pencarian.</p>
                </div>
            @endforelse
        </div>

        @if($orders->hasPages())
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-2xs">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Bukti Struk -->
<div id="proof-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white border border-gray-200 rounded-3xl max-w-lg w-full p-6 space-y-4 shadow-2xl relative">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-gray-900">
                    Bukti Pembayaran / Struk Transfer
                </h3>
                <p id="modal-order-subtitle" class="text-xs text-gray-500 mt-0.5 font-medium">Order # - Total: Rp 0</p>
            </div>
            <button type="button" onclick="closeProofModal()" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center font-bold text-sm cursor-pointer">
                ✕
            </button>
        </div>

        <div class="bg-gray-50 rounded-2xl p-3 border border-gray-200 flex items-center justify-center max-h-96 overflow-hidden">
            <img id="modal-proof-img" src="" alt="Bukti Transfer" class="max-h-80 max-w-full object-contain rounded-xl shadow-xs">
        </div>

        <div class="flex items-center justify-between pt-2">
            <a id="modal-proof-fullscreen" href="" target="_blank" class="text-xs text-gray-500 hover:text-[#e52e2e] font-bold underline">
                Buka Resolusi Penuh ↗
            </a>
            <button type="button" onclick="closeProofModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs uppercase cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openProofModal(imgSrc, orderNumber, customerName, totalFormatted) {
        document.getElementById('modal-proof-img').src = imgSrc;
        document.getElementById('modal-proof-fullscreen').href = imgSrc;
        document.getElementById('modal-order-subtitle').innerText = `${orderNumber} | ${customerName} | ${totalFormatted}`;
        document.getElementById('proof-modal').classList.remove('hidden');
    }

    function closeProofModal() {
        document.getElementById('proof-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection
