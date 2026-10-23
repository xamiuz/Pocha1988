@extends('layouts.admin')

@section('title', 'Admin Kitchen & Order Dispatch | 1988 Pocha Samarinda')

@section('content')
<div id="admin-dashboard-meta" data-last-order-id="{{ $orders->first()?->id ?? 0 }}" class="hidden"></div>
<div class="space-y-6 sm:space-y-8">
    <!-- Top Bar Stats & Action Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 sm:pb-6 border-b border-gray-200/80">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full border border-gray-200">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-gray-900 uppercase tracking-tight">Pesanan Masuk Hari Ini</h1>
            <p class="text-xs text-gray-500 font-medium">Khusus operasional dapur hari ini. Pesanan hari-hari sebelumnya otomatis berpindah ke <a href="{{ route('admin.archive') }}" class="text-[#e52e2e] font-bold underline hover:text-red-700">Arsip Pesanan</a>.</p>
        </div>
        <div class="flex items-center justify-between sm:justify-end gap-2.5 w-full sm:w-auto">
            <div class="flex-1 sm:flex-initial px-3.5 sm:px-5 py-2.5 sm:py-3 rounded-2xl bg-white border border-gray-200 flex items-center gap-3 shadow-2xs">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-emerald-500/10 text-[#10b981] border border-emerald-500/20 flex items-center justify-center font-black text-xs sm:text-sm shrink-0">
                    Rp
                </div>
                <div>
                    <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider block">Total Omzet Hari Ini</span>
                    <span class="text-sm sm:text-base font-black text-gray-900 tracking-tight">Rp {{ number_format($counts['revenue'], 0, ',', '.') }}</span>
                </div>
            </div>
            <button onclick="window.location.reload()" class="p-2.5 sm:p-3 rounded-2xl bg-white hover:bg-gray-50 border border-gray-200 text-gray-600 hover:text-gray-900 shadow-2xs transition hover:rotate-180 duration-500 cursor-pointer shrink-0" title="Refresh Realtime">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    <!-- Summary Counters Grid (Tactile Mobile & Tablet Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3.5">
        <!-- 1. Total Order -->
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-gray-200/90 shadow-2xs hover:shadow-sm transition-all group">
            <span class="text-[10px] sm:text-[11px] text-gray-400 block font-bold uppercase tracking-wider">Total Order</span>
            <div class="flex items-baseline justify-between mt-1.5 sm:mt-2">
                <span class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">{{ $counts['total'] }}</span>
                <span class="text-[10px] font-bold text-gray-400">Semua</span>
            </div>
        </div>

        <!-- 2. Menunggu ACC -->
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-amber-300/80 shadow-2xs hover:shadow-sm transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-12 h-12 bg-amber-500/5 rounded-full -mr-4 -mt-4"></div>
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] text-amber-700 block font-bold uppercase tracking-wider">Menunggu ACC</span>
            </div>
            <div class="flex items-baseline justify-between mt-1.5 sm:mt-2">
                <span class="text-2xl sm:text-3xl font-black text-amber-600 tracking-tight">{{ $counts['pending'] }}</span>
                <span class="text-[9px] sm:text-[10px] font-bold text-amber-600/70">Verifikasi</span>
            </div>
        </div>

        <!-- 3. Sedang Dimasak -->
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-red-300/80 shadow-2xs hover:shadow-sm transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-12 h-12 bg-red-500/5 rounded-full -mr-4 -mt-4"></div>
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-[11px] text-[#e52e2e] block font-bold uppercase tracking-wider">Sedang Dimasak</span>
            </div>
            <div class="flex items-baseline justify-between mt-1.5 sm:mt-2">
                <span class="text-2xl sm:text-3xl font-black text-[#e52e2e] tracking-tight">{{ $counts['cooking'] }}</span>
                <span class="text-[9px] sm:text-[10px] font-bold text-red-400">Dapur</span>
            </div>
        </div>

        <!-- 4. Diantar Kurir -->
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-blue-300/80 shadow-2xs hover:shadow-sm transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-12 h-12 bg-blue-500/5 rounded-full -mr-4 -mt-4"></div>
            <span class="text-[10px] sm:text-[11px] text-blue-600 block font-bold uppercase tracking-wider">Diantar Kurir</span>
            <div class="flex items-baseline justify-between mt-1.5 sm:mt-2">
                <span class="text-2xl sm:text-3xl font-black text-blue-600 tracking-tight">{{ $counts['delivering'] }}</span>
                <span class="text-[9px] sm:text-[10px] font-bold text-blue-400">OTW</span>
            </div>
        </div>

        <!-- 5. Selesai -->
        <div class="bg-white p-3.5 sm:p-5 rounded-2xl border border-emerald-300/80 shadow-2xs hover:shadow-sm transition-all group relative overflow-hidden col-span-2 sm:col-span-1">
            <div class="absolute top-0 right-0 w-12 h-12 bg-emerald-500/5 rounded-full -mr-4 -mt-4"></div>
            <span class="text-[10px] sm:text-[11px] text-[#10b981] block font-bold uppercase tracking-wider">Selesai / Terantar</span>
            <div class="flex items-baseline justify-between mt-1.5 sm:mt-2">
                <span class="text-2xl sm:text-3xl font-black text-[#10b981] tracking-tight">{{ $counts['delivered'] }}</span>
                <span class="text-[9px] sm:text-[10px] font-bold text-emerald-500">Sukses</span>
            </div>
        </div>
    </div>

    <!-- Live Orders Section -->
    <div class="space-y-4">
        <div class="bg-white border border-gray-200/90 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
            <div>
                <h2 class="text-sm font-black uppercase tracking-wider text-gray-900">Antrean Pesanan Masuk</h2>
                <p class="text-[11px] text-gray-500">Pilih "ACC Bayar" setelah memeriksa bukti transfer atau untuk pesanan COD.</p>
            </div>
            <span class="text-xs px-3.5 py-1.5 rounded-xl bg-gray-50 border border-gray-200 text-gray-700 font-black self-start sm:self-auto shadow-2xs">
                {{ $orders->total() }} Pesanan Tercatat
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
                        <!-- Kiri: Order Number + Waktu + Status -->
                        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                            <span class="font-black text-gray-900 text-xs sm:text-sm tracking-wide bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-200">{{ $ord->order_number }}</span>
                            <span class="text-[10px] text-gray-400 font-medium">{{ $ord->created_at->diffForHumans() }}, {{ $ord->created_at->format('H:i') }} WITA</span>
                            <span class="inline-block px-2.5 py-1 rounded-lg text-[10px] font-black border uppercase tracking-wider {{ $badgeClass }}">
                                {{ $ord->status_label }}
                            </span>
                        </div>

                        <!-- Kanan: Total & Payment Status -->
                        <div class="flex items-center justify-between sm:justify-end gap-2 flex-wrap pt-1 sm:pt-0">
                            <span class="font-black text-[#e52e2e] text-base sm:text-lg tracking-tight">{{ $ord->formatted_total }}</span>
                            @if($ord->payment_status === 'paid')
                                <span class="inline-flex items-center text-[10px] uppercase font-black px-2.5 py-1 rounded-lg bg-emerald-50 text-[#10b981] border border-emerald-200">
                                    LUNAS
                                </span>
                            @else
                                <span class="inline-flex items-center text-[10px] uppercase font-black px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-300">
                                    BELUM BAYAR
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
                                                onclick="openProofModal('{{ asset('storage/' . $ord->payment_proof) }}', '{{ $ord->order_number }}', '{{ $ord->customer_name }}', '{{ $ord->formatted_total }}', {{ $ord->id }}, '{{ $ord->payment_status }}')" 
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

                                <!-- Baris Bawah: ACC Bayar & Form Update Status -->
                                <div class="space-y-2">
                                    @if($ord->payment_status !== 'paid')
                                        <form action="{{ route('admin.orders.verify_payment', $ord->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" onclick="return confirm('ACC pembayaran lunas untuk pesanan {{ $ord->order_number }}?');" class="w-full min-h-[38px] py-2 px-3 rounded-xl bg-[#10b981] hover:bg-[#0da070] text-white font-black text-xs uppercase tracking-wider shadow-xs transition cursor-pointer flex items-center justify-center gap-1.5 active:scale-98">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>ACC Pembayaran Lunas</span>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.orders.update_status', $ord->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <select name="status" class="flex-1 min-h-[38px] bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs text-gray-800 font-bold focus:outline-none focus:border-[#e52e2e] cursor-pointer shadow-2xs">
                                            <option value="pending" {{ $ord->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="cooking" {{ $ord->status === 'cooking' ? 'selected' : '' }}>Dimasak</option>
                                            <option value="delivering" {{ $ord->status === 'delivering' ? 'selected' : '' }}>Diantar</option>
                                            <option value="delivered" {{ $ord->status === 'delivered' ? 'selected' : '' }}>Selesai</option>
                                            <option value="cancelled" {{ $ord->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                        <button type="submit" class="min-h-[38px] px-4 py-2 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] text-white font-black text-xs uppercase tracking-wider shadow-xs transition cursor-pointer shrink-0 active:scale-98">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-200 rounded-2xl p-12 text-center text-gray-500 shadow-2xs">
                    <p class="text-sm font-bold text-gray-700">Belum ada pesanan masuk saat ini.</p>
                    <p class="text-xs text-gray-400 mt-1">Pesanan dari pelanggan akan otomatis muncul di sini secara realtime.</p>
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

<!-- ======================================================== -->
<!-- MODAL POPUP PREVIEW BUKTI TRANSFER & ACC LANGSUNG      -->
<!-- ======================================================== -->
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
            
            <div class="flex items-center gap-2">
                <button type="button" onclick="closeProofModal()" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs uppercase cursor-pointer">
                    Tutup
                </button>
                <form id="modal-acc-form" action="" method="POST" class="inline">
                    @csrf
                    <button type="submit" id="modal-acc-btn" class="px-5 py-2 rounded-xl bg-[#10b981] hover:bg-[#0da070] text-white font-black text-xs uppercase tracking-wider shadow-xs flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>ACC Pembayaran Ini</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openProofModal(imgSrc, orderNumber, customerName, totalFormatted, orderId, paymentStatus) {
        document.getElementById('modal-proof-img').src = imgSrc;
        document.getElementById('modal-proof-fullscreen').href = imgSrc;
        document.getElementById('modal-order-subtitle').innerText = `${orderNumber} | ${customerName} | ${totalFormatted}`;
        
        const accForm = document.getElementById('modal-acc-form');
        const accBtn = document.getElementById('modal-acc-btn');
        accForm.action = `/admin/orders/${orderId}/verify-payment`;

        if (paymentStatus === 'paid') {
            accBtn.disabled = true;
            accBtn.innerText = 'Sudah Di-ACC (Lunas)';
            accBtn.classList.remove('bg-[#10b981]', 'hover:bg-[#0ea371]');
            accBtn.classList.add('bg-gray-700', 'text-gray-400', 'cursor-not-allowed');
        } else {
            accBtn.disabled = false;
            accBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>ACC Pembayaran Ini</span>
            `;
            accBtn.classList.add('bg-[#10b981]', 'hover:bg-[#0ea371]');
            accBtn.classList.remove('bg-gray-700', 'text-gray-400', 'cursor-not-allowed');
        }

        document.getElementById('proof-modal').classList.remove('hidden');
    }

    function closeProofModal() {
        document.getElementById('proof-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection
