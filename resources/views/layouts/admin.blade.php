<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Kitchen & Delivery | 1988 Pocha Samarinda')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@500;700;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .korean-font { font-family: 'Noto Sans KR', sans-serif; }
        
        @media (min-width: 768px) {
            #admin-sidebar.collapsed {
                margin-left: -16rem !important;
                visibility: hidden !important;
                pointer-events: none !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F8F9FA] text-gray-900 min-h-screen flex flex-col md:flex-row antialiased selection:bg-[#e52e2e] selection:text-white overflow-x-hidden">
    @php
        $globalStoreSetting = \App\Models\PaymentSetting::current();
    @endphp

    <!-- Mobile Topbar with Hamburger Toggle (Glassmorphic) -->
    <header class="md:hidden bg-white/70 backdrop-blur-xl border-b border-gray-200/60 shadow-xs px-4 py-3 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg overflow-hidden shrink-0 shadow-xs border border-gray-200">
                <img src="{{ asset('images/logo.png') }}" alt="1988 Pocha" class="w-full h-full object-contain">
            </div>
            <div>
                <span class="text-xs font-black text-gray-900 uppercase tracking-tight block">1988 POCHA ADMIN</span>
                <span class="text-[9px] text-[#10b981] font-bold block">
                    Samarinda Delivery
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Quick Store Status Toggle (Mobile) -->
            <form action="{{ route('admin.store.toggle') }}" method="POST" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('{{ $globalStoreSetting->is_store_open ? 'Tutup toko sekarang? Pembayaran & checkout pelanggan akan dinonaktifkan.' : 'Buka toko sekarang? Pelanggan dapat kembali memesan dan membayar.' }}')" class="px-2.5 py-1.5 rounded-xl border text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-2xs cursor-pointer {{ $globalStoreSetting->is_store_open ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}" title="{{ $globalStoreSetting->is_store_open ? 'Toko Buka (Klik untuk Tutup)' : 'Toko Tutup (Klik untuk Buka)' }}">
                    <span>{{ $globalStoreSetting->is_store_open ? 'Toko: Buka' : 'Toko: Tutup' }}</span>
                </button>
            </form>

            <button type="button" 
                    onclick="testOrToggleNotificationSound()" 
                    class="px-2 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 flex items-center gap-1 shadow-2xs cursor-pointer" 
                    title="Tes Bel Pesanan">
                <span>🔔</span>
            </button>

            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" onclick="return confirm('Keluar dari panel admin?')" class="p-2 rounded-xl bg-white hover:bg-red-50 border border-gray-200 text-gray-500 hover:text-red-600 transition shadow-2xs cursor-pointer" title="Logout Admin">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>

            <button type="button" id="mobile-sidebar-toggle" class="p-2 rounded-xl bg-gray-100 text-gray-700 hover:text-gray-900 border border-gray-200 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </header>

    <!-- Overlay for mobile sidebar -->
    <div id="mobile-sidebar-overlay" class="hidden fixed inset-0 z-40 bg-black/50 backdrop-blur-xs md:hidden"></div>

    <!-- SIDEBAR ADMIN (Sleek Dark Slate Navy Theme) -->
    <aside id="admin-sidebar" class="fixed md:sticky top-0 left-0 h-screen w-64 bg-[#0d1117] border-r border-[#21262d] flex flex-col justify-between z-50 transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out shrink-0 shadow-2xl md:shadow-none">
        
        <div class="flex flex-col flex-1 overflow-y-auto">
            <!-- Sidebar Header & Brand -->
            <div class="p-5 border-b border-[#21262d] flex items-center justify-between bg-[#161b22]/50">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl overflow-hidden shrink-0 shadow-md group-hover:scale-105 transition-transform bg-white p-0.5 border border-white/20">
                        <img src="{{ asset('images/logo.png') }}" alt="1988 Pocha" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-sm font-black text-white tracking-tight uppercase group-hover:text-[#e52e2e] transition-colors">1988 POCHA</span>
                        </div>
                        <span class="text-[10px] text-gray-400 font-bold block uppercase tracking-wider">Kitchen & Dispatch</span>
                    </div>
                </a>

                <!-- Mobile close button -->
                <button type="button" id="mobile-sidebar-close" class="md:hidden p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>


            <!-- Navigation Links -->
            <div class="p-4 space-y-6">
                <div>
                    <span class="px-3 text-[10px] font-black uppercase tracking-widest text-gray-500 block mb-2.5">Menu Utama</span>
                    <nav class="space-y-1.5">
                        <!-- Dashboard & Pesanan Hari Ini -->
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#e52e2e] text-white shadow-lg shadow-red-500/30 font-black' : 'text-gray-400 hover:text-white hover:bg-[#161b22]' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span>Pesanan Hari Ini</span>
                        </a>

                        <!-- Arsip & Riwayat Pesanan -->
                        <a href="{{ route('admin.archive') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ request()->routeIs('admin.archive') ? 'bg-[#e52e2e] text-white shadow-lg shadow-red-500/30 font-black' : 'text-gray-400 hover:text-white hover:bg-[#161b22]' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.archive') ? 'text-white' : 'text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Arsip Pesanan</span>
                        </a>

                        <!-- Manajemen Menu & Kategori -->
                        <a href="{{ route('admin.menu.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ request()->routeIs('admin.menu.*') ? 'bg-[#e52e2e] text-white shadow-lg shadow-red-500/30 font-black' : 'text-gray-400 hover:text-white hover:bg-[#161b22]' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.menu.*') ? 'text-white' : 'text-emerald-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>Manajemen Menu</span>
                        </a>

                        <!-- Menu Promo & Penawaran -->
                        <a href="{{ route('admin.promos.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ request()->routeIs('admin.promos.*') ? 'bg-[#e52e2e] text-white shadow-lg shadow-red-500/30 font-black' : 'text-gray-400 hover:text-white hover:bg-[#161b22]' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.promos.*') ? 'text-white' : 'text-rose-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Menu Promo</span>
                        </a>

                        <!-- Manajemen Metode Pembayaran (QRIS, Rekening, COD) -->
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-[#e52e2e] text-white shadow-lg shadow-red-500/30 font-black' : 'text-gray-400 hover:text-white hover:bg-[#161b22]' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.payments.*') ? 'text-white' : 'text-cyan-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span>Metode Pembayaran</span>
                        </a>
                    </nav>
                </div>

                <div>
                    <span class="px-3 text-[10px] font-black uppercase tracking-widest text-gray-500 block mb-2.5">Akses Cepat</span>
                    <nav class="space-y-1.5">
                        <!-- Lihat Website Pelanggan -->
                        <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-[#161b22] border border-[#21262d] font-bold text-xs transition-all group">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Lihat Web User</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-gray-600 group-hover:text-gray-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>

                        <!-- Checkout & Test Order -->
                        <a href="{{ route('orders.checkout') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-[#161b22] border border-[#21262d] font-bold text-xs transition-all group">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-[#e52e2e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Halaman Checkout</span>
                            </div>
                            <svg class="w-3.5 h-3.5 text-gray-600 group-hover:text-gray-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Sidebar Footer Info & Logout -->
        <div class="p-4 border-t border-[#21262d] bg-[#161b22]/70 space-y-3">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-[#10b981] flex items-center justify-center font-black text-xs">
                    SMD
                </div>
                <div class="min-w-0 flex-1">
                    <span class="text-xs font-bold text-gray-200 block truncate">Kota Samarinda</span>
                    <span class="text-[10px] text-[#10b981] font-medium block truncate">Pengantaran Aktif</span>
                </div>
            </div>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Yakin ingin logout dari panel admin?')" class="w-full py-2 px-3 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 border border-red-500/20 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Logout Admin</span>
                </button>
            </form>

            <p class="text-[10px] text-gray-500 leading-tight pt-1">
                © 1988 Pocha System | Samarinda
            </p>
        </div>
    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-[#F8F9FA]">
        <!-- Top App Bar in Main Content (Desktop & Mobile) -->
        <header class="hidden md:flex items-center justify-between h-16 px-6 lg:px-8 bg-white border-b border-gray-200/80 sticky top-0 z-30 shadow-[0_1px_3px_rgba(0,0,0,0.02)]">
            <div class="flex items-center gap-4">
                <!-- Desktop Sidebar Toggle Button (Hide / Expand) -->
                <button type="button" id="desktop-sidebar-toggle" class="p-2 rounded-xl text-gray-500 hover:text-gray-900 hover:bg-gray-100 border border-gray-200 transition shadow-2xs cursor-pointer" title="Sembunyikan / Buka Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                </button>

                <div class="flex items-center gap-2.5">
                    <div class="w-1.5 h-4 bg-[#e52e2e] rounded-xs"></div>
                    <span class="text-xs font-black text-gray-800 uppercase tracking-widest">
                        @if(request()->routeIs('admin.dashboard'))
                            Pesanan Hari Ini & Dapur
                        @elseif(request()->routeIs('admin.archive'))
                            Arsip & Riwayat Pesanan
                        @elseif(request()->routeIs('admin.payments.*'))
                            Manajemen Metode Pembayaran
                        @elseif(request()->routeIs('admin.promos.*'))
                            Manajemen Menu Promo
                        @else
                            Manajemen Menu & Kategori
                        @endif
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs">
                    <svg class="w-3.5 h-3.5 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span id="live-clock" class="font-extrabold text-gray-800 tracking-wider">--:--:-- WITA</span>
                </div>

                <!-- Tombol Uji Suara Notifikasi Pesanan Baru -->
                <button type="button" 
                        onclick="testOrToggleNotificationSound()" 
                        class="px-3 py-1.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 flex items-center gap-1.5 transition shadow-2xs hover:border-gray-300 cursor-pointer group" 
                        title="Klik untuk mengetes suara bel pesanan baru">
                    <span class="text-xs group-hover:scale-125 transition-transform">🔔</span>
                    <span class="hidden sm:inline text-[11px] font-black text-gray-800">Tes Bel</span>
                </button>

                <!-- Quick Store Status Toggle (Desktop) -->
                <form action="{{ route('admin.store.toggle') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('{{ $globalStoreSetting->is_store_open ? 'Tutup toko sekarang? Seluruh metode pembayaran & pemesanan checkout akan dinonaktifkan.' : 'Buka toko sekarang? Pelanggan dapat kembali memesan dan memilih metode pembayaran.' }}')" class="px-3.5 py-1.5 rounded-xl border text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shadow-2xs transition cursor-pointer {{ $globalStoreSetting->is_store_open ? 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-red-50 hover:bg-red-100 text-red-800 border-red-300' }}" title="{{ $globalStoreSetting->is_store_open ? 'Restoran Buka (Klik untuk Tutup Toko)' : 'Restoran Tutup (Klik untuk Buka Toko)' }}">
                        <span>{{ $globalStoreSetting->is_store_open ? 'Toko Buka' : 'Toko Tutup' }}</span>
                    </button>
                </form>

                <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 rounded-xl bg-white hover:bg-gray-50 text-gray-700 hover:text-gray-900 border border-gray-200 text-xs font-bold transition flex items-center gap-2 shadow-2xs hover:border-gray-300">
                    <span>Lihat Web Pelanggan</span>
                    <svg class="w-3.5 h-3.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>

                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Keluar dari panel admin?')" class="px-3.5 py-2 rounded-xl bg-white hover:bg-red-50 text-gray-600 hover:text-[#e52e2e] border border-gray-200 hover:border-red-200 text-xs font-bold transition flex items-center gap-1.5 shadow-2xs cursor-pointer" title="Keluar dari sesi admin">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Dynamic Main Content (Expanded Width) -->
        <main class="flex-grow p-3.5 sm:p-6 lg:p-8 bg-[#F8F9FA] w-full max-w-[1700px] mx-auto pb-24 md:pb-8">
            @if(session('success'))
                <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-black flex items-center gap-2.5 shadow-xs">
                    <svg class="w-5 h-5 shrink-0 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-200 py-3 sm:py-4 px-4 sm:px-6 text-center text-[11px] sm:text-xs text-gray-400 mb-16 md:mb-0">
            1988 Pocha Samarinda Kitchen Dispatch & POS System
        </footer>

        <!-- Mobile Bottom Navigation Bar (Glassmorphic, Touch-Friendly for Android & Tablets) -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200/90 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] px-2 py-1.5 flex items-center justify-around safe-area-pb">
            <!-- 1. Pesanan Hari Ini -->
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 px-1 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'text-[#e52e2e] font-black' : 'text-gray-500 hover:text-gray-800 font-semibold' }}">
                <div class="relative p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('admin.dashboard') ? '2.5' : '2' }}" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-5 h-0.5 bg-[#e52e2e]"></span>
                    @endif
                </div>
                <span class="text-[10px] uppercase tracking-tight mt-0.5">Pesanan</span>
            </a>

            <!-- 2. Arsip Pesanan -->
            <a href="{{ route('admin.archive') }}" class="flex flex-col items-center justify-center flex-1 py-1 px-1 rounded-xl transition-all {{ request()->routeIs('admin.archive') ? 'text-[#e52e2e] font-black' : 'text-gray-500 hover:text-gray-800 font-semibold' }}">
                <div class="relative p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('admin.archive') ? '2.5' : '2' }}" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @if(request()->routeIs('admin.archive'))
                        <span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-5 h-0.5 bg-[#e52e2e]"></span>
                    @endif
                </div>
                <span class="text-[10px] uppercase tracking-tight mt-0.5">Arsip</span>
            </a>

            <!-- 3. Kelola Menu -->
            <a href="{{ route('admin.menu.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 px-1 rounded-xl transition-all {{ request()->routeIs('admin.menu.*') ? 'text-[#e52e2e] font-black' : 'text-gray-500 hover:text-gray-800 font-semibold' }}">
                <div class="relative p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('admin.menu.*') ? '2.5' : '2' }}" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    @if(request()->routeIs('admin.menu.*'))
                        <span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-5 h-0.5 bg-[#e52e2e]"></span>
                    @endif
                </div>
                <span class="text-[10px] uppercase tracking-tight mt-0.5">Menu</span>
            </a>

            <!-- 4. Promo -->
            <a href="{{ route('admin.promos.index') }}" class="flex flex-col items-center justify-center flex-1 py-1 px-1 rounded-xl transition-all {{ request()->routeIs('admin.promos.*') ? 'text-[#e52e2e] font-black' : 'text-gray-500 hover:text-gray-800 font-semibold' }}">
                <div class="relative p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('admin.promos.*') ? '2.5' : '2' }}" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    @if(request()->routeIs('admin.promos.*'))
                        <span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-5 h-0.5 bg-[#e52e2e]"></span>
                    @endif
                </div>
                <span class="text-[10px] uppercase tracking-tight mt-0.5">Promo</span>
            </a>

            <!-- 5. Web Pelanggan -->
            <a href="{{ route('home') }}" target="_blank" class="flex flex-col items-center justify-center flex-1 py-1 px-1 rounded-xl transition-all text-gray-500 hover:text-emerald-600 font-semibold">
                <div class="p-1">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-tight mt-0.5">Web</span>
            </a>
        </nav>
    </div>

    <!-- Scripts for Mobile Drawer Toggle and Real-time Clock -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            const openBtn = document.getElementById('mobile-sidebar-toggle');
            const closeBtn = document.getElementById('mobile-sidebar-close');

            const desktopToggleBtn = document.getElementById('desktop-sidebar-toggle');

            // Desktop Toggle Sidebar (Hide / Show)
            if (desktopToggleBtn) {
                // Restore state dari localStorage
                const isSidebarCollapsed = localStorage.getItem('pocha_admin_sidebar_collapsed') === 'true';
                if (isSidebarCollapsed) {
                    sidebar.classList.add('collapsed');
                }

                desktopToggleBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const isCollapsed = sidebar.classList.toggle('collapsed');
                    localStorage.setItem('pocha_admin_sidebar_collapsed', isCollapsed);
                });
            }

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            // Real-time Clock (WITA / Central Indonesia Time)
            function updateClock() {
                const clockEl = document.getElementById('live-clock');
                if (clockEl) {
                    const now = new Date();
                    clockEl.innerText = now.toLocaleTimeString('id-ID', { hour12: false }) + ' WITA';
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
        });
    </script>
    @include('admin.partials.chat_modal')
    @include('admin.partials.order_notification')
    @stack('scripts')
</body>
</html>
