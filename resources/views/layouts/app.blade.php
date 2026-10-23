<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '1988 Pocha Samarinda | Authentic Korean Street Food')</title>
    <meta name="description" content="Pesan makanan Korean Street Food Otentik langsung secara online di Samarinda. Tteokbokki, Korean Fried Chicken, Ramyeon dengan pengantaran cepat se-Samarinda.">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@500;700;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .korean-font { font-family: 'Noto Sans KR', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-white text-gray-900 min-h-screen flex flex-col selection:bg-[#e52e2e] selection:text-white">

    @php
        $isHome = request()->routeIs('home');
        $isCheckout = request()->routeIs('orders.checkout');
    @endphp

    <!-- Store Closed Announcement Strip (Global) -->
    @if(isset($paymentSettings) && ! $paymentSettings->is_store_open)
        <aside class="bg-gradient-to-r from-red-600 via-rose-600 to-red-600 text-white px-4 py-2.5 text-xs font-semibold shadow-md relative z-50">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-center sm:text-left">
                <div class="flex items-center justify-center sm:justify-start gap-2.5">
                    <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="font-extrabold uppercase tracking-wide bg-black/25 px-2 py-0.5 rounded text-[10px]">Toko Tutup Sementara</span>
                    <span class="text-[11px] sm:text-xs text-white/95">{{ $paymentSettings->closed_message ?: 'Resto 1988 Pocha saat ini sedang tutup. Pemesanan dan pembayaran dinonaktifkan sementara.' }}</span>
                </div>
                <div class="hidden md:flex items-center gap-2 shrink-0 text-[11px] text-white/90">
                    <span class="font-bold">Semua Pembayaran Dikunci</span>
                </div>
            </div>
        </aside>
    @endif

    <!-- Top Navigation Bar (Dark Glassmorphic floating over Hero) -->
    <header id="main-navbar" class="sticky top-0 z-40 transition-all duration-300 {{ $isHome ? 'bg-[#0b0d13]/55 backdrop-blur-md border-b border-white/10 shadow-lg' : 'bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-xs' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand Logo (Logo Resmi 1988 Pocha) -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="1988 Pocha Samarinda" class="w-10 h-10 sm:w-12 sm:h-12 object-contain rounded-xl shadow-md group-hover:scale-105 transition-transform bg-white p-0.5 border border-white/20">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1 sm:gap-1.5">
                            <span class="text-lg sm:text-xl font-black tracking-tight text-[#e52e2e] uppercase group-hover:text-red-500 transition-colors">1988</span>
                            <span class="text-lg sm:text-xl font-black tracking-tight text-[#10b981] uppercase group-hover:text-emerald-500 transition-colors">POCHA</span>
                            <!-- Dynamic Store Status Badge -->
                            @if(isset($paymentSettings) && ! $paymentSettings->is_store_open)
                                <span class="px-2 py-0.5 rounded bg-red-600 text-white text-[9px] font-black uppercase tracking-wider ml-1 shadow-2xs">
                                    TUTUP
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-emerald-600 text-white text-[9px] font-black uppercase tracking-wider ml-1 shadow-2xs">
                                    BUKA
                                </span>
                            @endif
                        </div>
                        <span id="nav-subtitle" class="text-[9px] sm:text-[10px] font-bold tracking-widest uppercase transition-colors {{ $isHome ? 'text-gray-300' : 'text-gray-500' }}">Samarinda Street Food</span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-bold">
                    <a href="{{ route('home') }}#menu" class="nav-item-link transition-colors {{ $isHome ? 'text-gray-200 hover:text-white' : 'text-gray-800 hover:text-[#e52e2e]' }}">Menu Makanan</a>
                    <a href="{{ route('home') }}#promo" class="nav-item-link transition-colors {{ $isHome ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-[#e52e2e]' }}">Promo Spesial</a>
                    <a href="{{ route('home') }}#lokasi" class="nav-item-link transition-colors {{ $isHome ? 'text-gray-300 hover:text-white' : 'text-gray-600 hover:text-[#e52e2e]' }}">Lokasi & Jam Buka</a>
                </nav>

                <!-- Actions: Cart Button -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <button id="cart-drawer-btn" class="relative flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl font-extrabold text-xs transition-all shadow-xs cursor-pointer backdrop-blur-xs {{ $isHome ? 'bg-black/40 hover:bg-black/60 text-white border border-white/15' : 'bg-gray-100 hover:bg-gray-200 text-gray-900 border border-gray-200' }}">
                        <svg class="w-4 h-4 text-[#e52e2e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="hidden sm:inline">Keranjang</span>
                        <span id="cart-badge-count" class="min-w-5 h-5 px-1.5 flex items-center justify-center rounded-md bg-[#e52e2e] text-white text-[10px] sm:text-[11px] font-black shadow-xs">0</span>
                    </button>
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow {{ $isHome ? '-mt-16' : '' }} pb-24 md:pb-0">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 pt-6">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl flex items-center gap-3 text-sm shadow-xs font-semibold">
                    <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Clean White / Light Footer -->
    <footer class="bg-white border-t border-gray-200 py-10 md:py-12 pb-28 md:pb-12 mt-8 md:mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-gray-200">
                <!-- Brand Footer -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="1988 Pocha Samarinda" class="w-10 h-10 object-contain rounded-xl shadow-sm bg-white p-0.5 border border-gray-200">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xl font-black text-[#e52e2e] uppercase tracking-tight">1988</span>
                        <span class="text-xl font-black text-[#10b981] uppercase tracking-tight">POCHA</span>
                        <span class="text-xs text-gray-500 font-bold ml-1 uppercase">SAMARINDA</span>
                    </div>
                </div>

                <!-- Footer Nav Links -->
                <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 text-xs text-gray-600 font-bold">
                    <a href="{{ route('home') }}#menu" class="hover:text-[#e52e2e] transition">Menu</a>
                    <a href="{{ route('home') }}#promo" class="hover:text-[#e52e2e] transition">Promo</a>
                    <a href="{{ route('home') }}#lokasi" class="hover:text-[#e52e2e] transition">Lokasi</a>
                    <a href="https://api.whatsapp.com/send?phone=6281277051988" target="_blank" rel="noopener" class="text-emerald-600 hover:text-emerald-700 transition flex items-center gap-1 font-bold">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>WhatsApp (0812-7705-1988)</span>
                    </a>
                    <a href="https://www.instagram.com/1988pocha_smd/" target="_blank" rel="noopener" class="text-gray-700 hover:text-[#e52e2e] transition flex items-center gap-1">
                        <span>@1988pocha_smd</span>
                    </a>
                </div>
            </div>

            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-3 text-center sm:text-left">
                <p>&copy; {{ date('Y') }} 1988 Pocha Samarinda. Authentic Korean Street Food Delivery.</p>
                <div class="flex items-center gap-1.5 font-semibold text-gray-700">
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Pengantaran Resmi Seluruh Wilayah Kota Samarinda</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Native Android Floating Bottom Cart Pill (Appears on Home when cart has items) -->
    @if(!$isCheckout)
    <div id="mobile-floating-cart-bar" class="hidden fixed bottom-20 inset-x-4 z-40 max-w-md mx-auto md:hidden transition-all duration-300 transform translate-y-4 opacity-0">
        <div class="bg-gray-950 text-white rounded-2xl p-3 px-4 shadow-2xl border border-red-500/50 ring-4 ring-black/15 flex items-center justify-between gap-3 backdrop-blur-md">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="relative w-9 h-9 rounded-xl bg-[#e52e2e] text-white flex items-center justify-center shrink-0 shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span id="mobile-floating-cart-count" class="absolute -top-1.5 -right-1.5 min-w-4 h-4 px-1 rounded-md bg-amber-400 text-gray-950 text-[10px] font-black flex items-center justify-center">0</span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Subtotal Pesanan</p>
                    <p id="mobile-floating-cart-total" class="text-sm font-black text-white truncate">Rp 0</p>
                </div>
            </div>

            <button type="button" onclick="PochaCart.openDrawer()" class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#e52e2e] to-[#c41c1c] hover:from-[#c41c1c] hover:to-[#9e1616] text-white font-black text-xs uppercase tracking-wider shadow-md flex items-center gap-1.5 shrink-0 active:scale-95 transition cursor-pointer">
                <span>Keranjang</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Native Android Bottom Navigation Bar (Mobile Only: 4 Tabs) -->
    <nav id="mobile-bottom-nav" class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200/90 shadow-lg md:hidden">
        <div class="grid grid-cols-4 h-16 items-center px-1">
            <!-- 1. Menu -->
            <a href="{{ route('home') }}#menu" class="mobile-nav-tab flex flex-col items-center justify-center py-1 text-gray-700 hover:text-[#e52e2e] active:scale-95 transition">
                <svg class="w-5 h-5 text-[#e52e2e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="text-[10px] font-black text-[#e52e2e] tracking-tight mt-0.5">Menu</span>
            </a>
            <!-- 2. Promo -->
            <a href="{{ route('home') }}#promo" class="mobile-nav-tab flex flex-col items-center justify-center py-1 text-gray-500 hover:text-[#e52e2e] active:scale-95 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span class="text-[10px] font-bold tracking-tight mt-0.5">Promo</span>
            </a>
            <!-- 3. Lokasi Samarinda -->
            <a href="{{ route('home') }}#lokasi" class="mobile-nav-tab flex flex-col items-center justify-center py-1 text-gray-500 hover:text-[#e52e2e] active:scale-95 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-[10px] font-bold tracking-tight mt-0.5">Lokasi</span>
            </a>
            <!-- 4. Keranjang Pesanan -->
            <button type="button" onclick="PochaCart.openDrawer()" class="mobile-nav-tab relative flex flex-col items-center justify-center py-1 text-gray-700 hover:text-[#e52e2e] active:scale-95 transition cursor-pointer">
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span id="mobile-bottom-nav-cart-badge" class="absolute -top-1.5 -right-2.5 min-w-4 h-4 px-1 rounded-md bg-[#e52e2e] text-white text-[9px] font-black flex items-center justify-center ring-2 ring-white">0</span>
                </div>
                <span class="text-[10px] font-black tracking-tight mt-0.5">Keranjang</span>
            </button>
        </div>
    </nav>

    <!-- Slide-over / Android Bottom Sheet Cart Drawer -->
    <div id="cart-drawer" class="fixed inset-0 z-50 overflow-hidden pointer-events-none transition-opacity duration-300 opacity-0 hidden">
        <div id="cart-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-xs pointer-events-none transition-opacity opacity-0"></div>
        
        <div class="fixed inset-x-0 bottom-0 sm:inset-y-0 sm:right-0 sm:left-auto flex flex-col justify-end sm:justify-start pointer-events-none max-w-full">
            <div id="cart-panel" class="w-full sm:w-[420px] max-h-[85vh] sm:max-h-full bg-white rounded-t-3xl sm:rounded-none border-t sm:border-t-0 sm:border-l border-gray-200 shadow-2xl flex flex-col transform translate-y-full sm:translate-y-0 sm:translate-x-full transition-transform duration-300 pointer-events-auto">
                <!-- Android Drag Handle Indicator -->
                <div class="pt-2.5 pb-0 flex justify-center sm:hidden">
                    <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
                </div>

                <!-- Header -->
                <div class="p-4 sm:p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg overflow-hidden border border-gray-200 bg-white p-0.5 shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="1988 Pocha" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-extrabold text-gray-900 uppercase tracking-tight">Keranjang Pesanan</h3>
                            <p class="text-[11px] sm:text-xs text-gray-500">Pengantaran hangat se-Kota Samarinda</p>
                        </div>
                    </div>
                    <button id="cart-close-btn" class="p-2 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-gray-100 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Items Container -->
                <div id="cart-items-container" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-3 bg-white">
                    <!-- Javascript rendered -->
                </div>

                <!-- Footer Summary & Checkout Button -->
                <div class="p-4 sm:p-6 border-t border-gray-200 bg-gray-50 space-y-3 sm:space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 font-semibold">Subtotal Makanan:</span>
                        <span id="cart-subtotal-text" class="text-lg sm:text-xl font-black text-gray-900">Rp 0</span>
                    </div>

                    @if(isset($paymentSettings) && ! $paymentSettings->is_store_open)
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs font-semibold flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-[#e52e2e] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <p class="font-black uppercase text-[11px] text-red-900">Toko Sedang Tutup</p>
                                <p class="text-[11px] leading-tight text-red-700 mt-0.5">{{ $paymentSettings->closed_message ?: 'Resto saat ini sedang tutup. Pemesanan dan pembayaran dinonaktifkan sementara.' }}</p>
                            </div>
                        </div>
                        <button type="button" disabled class="w-full py-3.5 px-4 rounded-xl bg-gray-200 border border-gray-300 text-gray-500 font-black text-xs uppercase tracking-wider text-center flex items-center justify-center gap-2 cursor-not-allowed">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Toko Tutup - Pembayaran Nonaktif</span>
                        </button>
                    @else
                        <p class="text-[11px] sm:text-xs text-emerald-700 flex items-center gap-1.5 font-bold bg-emerald-50 p-2 rounded-lg border border-emerald-200">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span>Ongkos kirim dihitung di halaman checkout</span>
                        </p>
                        <a href="{{ route('orders.checkout') }}" id="checkout-button" class="w-full py-3.5 px-4 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] text-white font-extrabold text-xs uppercase tracking-wider text-center flex items-center justify-center gap-2 shadow-lg shadow-red-500/20 transition-all active:scale-95 cursor-pointer">
                            <span>Lanjut ke Pengantaran & Bayar</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side Cart Storage Script -->
    <script>
        window.PochaCart = {
            getItems() {
                try {
                    return JSON.parse(localStorage.getItem('pocha_cart') || '[]');
                } catch(e) {
                    return [];
                }
            },
            saveItems(items) {
                localStorage.setItem('pocha_cart', JSON.stringify(items));
                this.updateUI();
            },
            addItem(item) {
                let items = this.getItems();
                const existing = items.find(i => i.id === item.id);
                if (existing) {
                    existing.quantity += 1;
                } else {
                    items.push({
                        id: item.id,
                        name: item.name,
                        price: item.price,
                        image: item.image,
                        quantity: 1,
                        notes: ''
                    });
                }
                this.saveItems(items);
                this.openDrawer();
            },
            updateQty(id, delta) {
                let items = this.getItems();
                const idx = items.findIndex(i => i.id === id);
                if (idx !== -1) {
                    items[idx].quantity += delta;
                    if (items[idx].quantity <= 0) {
                        items.splice(idx, 1);
                    }
                }
                this.saveItems(items);
            },
            removeItem(id) {
                let items = this.getItems().filter(i => i.id !== id);
                this.saveItems(items);
            },
            clear() {
                localStorage.removeItem('pocha_cart');
                this.updateUI();
            },
            openDrawer() {
                const drawer = document.getElementById('cart-drawer');
                const backdrop = document.getElementById('cart-backdrop');
                const panel = document.getElementById('cart-panel');
                if (!drawer || !backdrop || !panel) return;
                
                drawer.classList.remove('hidden');
                void drawer.offsetWidth;

                drawer.classList.remove('opacity-0', 'pointer-events-none');
                drawer.classList.add('pointer-events-auto');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100', 'pointer-events-auto');
                panel.classList.remove('translate-y-full', 'sm:translate-x-full');
            },
            closeDrawer() {
                const drawer = document.getElementById('cart-drawer');
                const backdrop = document.getElementById('cart-backdrop');
                const panel = document.getElementById('cart-panel');
                if (!drawer || !backdrop || !panel) return;

                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                panel.classList.add('translate-y-full', 'sm:translate-x-full');
                drawer.classList.add('pointer-events-none');
                
                setTimeout(() => {
                    drawer.classList.add('opacity-0', 'hidden');
                    drawer.classList.remove('pointer-events-auto');
                }, 300);
            },
            updateUI() {
                const items = this.getItems();
                const badge = document.getElementById('cart-badge-count');
                const mobileNavBadge = document.getElementById('mobile-bottom-nav-cart-badge');
                const floatingBar = document.getElementById('mobile-floating-cart-bar');
                const floatingCount = document.getElementById('mobile-floating-cart-count');
                const floatingTotal = document.getElementById('mobile-floating-cart-total');
                const container = document.getElementById('cart-items-container');
                const subtotalEl = document.getElementById('cart-subtotal-text');
                const checkoutBtn = document.getElementById('checkout-button');

                const totalCount = items.reduce((sum, i) => sum + i.quantity, 0);
                if (badge) badge.innerText = totalCount;
                if (mobileNavBadge) {
                    mobileNavBadge.innerText = totalCount;
                    if (totalCount > 0) {
                        mobileNavBadge.classList.add('scale-125');
                        setTimeout(() => mobileNavBadge.classList.remove('scale-125'), 200);
                    }
                }
                if (floatingCount) floatingCount.innerText = totalCount;

                let subtotal = 0;
                items.forEach(i => { subtotal += i.price * i.quantity; });
                const subtotalFormatted = 'Rp ' + subtotal.toLocaleString('id-ID');

                if (subtotalEl) subtotalEl.innerText = subtotalFormatted;
                if (floatingTotal) floatingTotal.innerText = subtotalFormatted;

                // Sync Floating Quick-Action Cart Pill on mobile
                if (floatingBar) {
                    if (items.length > 0) {
                        floatingBar.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            floatingBar.classList.remove('translate-y-4', 'opacity-0');
                            floatingBar.classList.add('translate-y-0', 'opacity-100');
                        });
                    } else {
                        floatingBar.classList.remove('translate-y-0', 'opacity-100');
                        floatingBar.classList.add('translate-y-4', 'opacity-0');
                        setTimeout(() => {
                            if (this.getItems().length === 0) floatingBar.classList.add('hidden');
                        }, 300);
                    }
                }

                if (!container) return;

                if (items.length === 0) {
                    container.innerHTML = `
                        <div class="h-full flex flex-col items-center justify-center text-center py-12 sm:py-16 text-gray-400">
                            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-3 text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="font-bold text-gray-800 text-sm">Keranjang Belanja Masih Kosong</p>
                            <p class="text-xs text-gray-500 mt-1">Pilih menu favoritmu untuk mulai memesan!</p>
                        </div>
                    `;
                    if (checkoutBtn) checkoutBtn.classList.add('opacity-50', 'pointer-events-none');
                    return;
                }

                if (checkoutBtn) checkoutBtn.classList.remove('opacity-50', 'pointer-events-none');

                let html = '';
                items.forEach(item => {
                    html += `
                        <div class="bg-gray-50 p-3 rounded-2xl border border-gray-200/90 flex items-center gap-3 shadow-2xs hover:bg-white transition">
                            <img src="${item.image}" alt="${item.name}" class="w-14 h-14 rounded-xl object-cover shrink-0 border border-gray-200 bg-white">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-black text-gray-900 truncate">${item.name}</h4>
                                <p class="text-xs text-[#e52e2e] font-black mt-0.5">Rp ${Number(item.price).toLocaleString('id-ID')}</p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <button onclick="PochaCart.updateQty(${item.id}, -1)" class="w-7 h-7 rounded-lg bg-white hover:bg-gray-200 active:scale-95 text-gray-800 border border-gray-300 flex items-center justify-center text-xs font-black cursor-pointer shadow-2xs">-</button>
                                    <span class="text-xs font-black text-gray-900 px-1">${item.quantity}</span>
                                    <button onclick="PochaCart.updateQty(${item.id}, 1)" class="w-7 h-7 rounded-lg bg-white hover:bg-gray-200 active:scale-95 text-gray-800 border border-gray-300 flex items-center justify-center text-xs font-black cursor-pointer shadow-2xs">+</button>
                                </div>
                            </div>
                            <button onclick="PochaCart.removeItem(${item.id})" class="text-gray-400 hover:text-red-600 p-2 cursor-pointer transition active:scale-95" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    `;
                });

                container.innerHTML = html;
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            PochaCart.updateUI();
            const openBtn = document.getElementById('cart-drawer-btn');
            const closeBtn = document.getElementById('cart-close-btn');
            const backdrop = document.getElementById('cart-backdrop');
            if (openBtn) openBtn.addEventListener('click', (e) => {
                e.preventDefault();
                PochaCart.openDrawer();
            });
            if (closeBtn) closeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                PochaCart.closeDrawer();
            });
            if (backdrop) backdrop.addEventListener('click', (e) => {
                e.preventDefault();
                PochaCart.closeDrawer();
            });

            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.add-to-cart-btn');
                if (btn) {
                    e.preventDefault();
                    const id = parseInt(btn.dataset.id, 10);
                    const name = btn.dataset.name;
                    const price = parseFloat(btn.dataset.price);
                    const image = btn.dataset.image;
                    PochaCart.addItem({ id, name, price, image });
                }
            });

            // Dynamic Navbar scroll (Black over hero content, translucent white over white content)
            const mainNav = document.getElementById('main-navbar');
            const heroBanner = document.getElementById('hero-banner');
            
            if (mainNav && heroBanner) {
                const navSub = document.getElementById('nav-subtitle');
                const navLinks = document.querySelectorAll('.nav-item-link');
                const cartBtn = document.getElementById('cart-drawer-btn');

                function handleNavScroll() {
                    const threshold = (heroBanner.offsetHeight || 500) - 80;
                    const isOverWhite = window.scrollY >= threshold;

                    if (isOverWhite) {
                        mainNav.className = 'sticky top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-200/90 shadow-md';
                        if (navSub) {
                            navSub.classList.remove('text-gray-300');
                            navSub.classList.add('text-gray-500');
                        }
                        navLinks.forEach(link => {
                            link.classList.remove('text-gray-300', 'hover:text-white', 'text-gray-200');
                            link.classList.add('text-gray-700', 'hover:text-[#e52e2e]');
                        });
                        if (cartBtn) {
                            cartBtn.className = 'relative flex items-center gap-2 px-4 py-2.5 rounded-xl font-extrabold text-xs transition-all shadow-xs cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-900 border border-gray-200';
                        }
                    } else {
                        mainNav.className = 'sticky top-0 z-50 transition-all duration-300 bg-[#0b0d13]/55 backdrop-blur-md border-b border-white/10 shadow-lg';
                        if (navSub) {
                            navSub.classList.remove('text-gray-500');
                            navSub.classList.add('text-gray-300');
                        }
                        navLinks.forEach(link => {
                            link.classList.remove('text-gray-700', 'hover:text-[#e52e2e]');
                            link.classList.add('text-gray-300', 'hover:text-white');
                        });
                        if (cartBtn) {
                            cartBtn.className = 'relative flex items-center gap-2.5 px-4 py-2 rounded-xl font-extrabold text-xs transition-all shadow-xs cursor-pointer backdrop-blur-xs bg-black/40 hover:bg-black/60 text-white border border-white/15';
                        }
                    }
                }

                window.addEventListener('scroll', handleNavScroll, { passive: true });
                handleNavScroll();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
