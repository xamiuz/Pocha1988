@extends('layouts.app')

@section('title', '1988 Pocha Samarinda | Authentic Korean Street Food Delivery')

@section('content')

<!-- ======================================================== -->
<!-- 1. HERO SECTION (Modern Responsive Edge-to-Edge Banner)   -->
<!-- ======================================================== -->
<section id="hero-banner" class="relative w-full bg-[#0b0d13] overflow-hidden border-b border-gray-900">
    <!-- Outlet Image: Background on Mobile, Split Screen on Desktop -->
    <div class="lg:absolute lg:top-0 lg:bottom-0 lg:right-0 lg:w-[50%] xl:w-[52%] w-full h-56 sm:h-72 lg:h-full relative overflow-hidden">
        <img 
            src="{{ asset('images/pocha-outlet.png') }}" 
            alt="1988 Pocha Samarinda Outlet & Tenda Korea" 
            class="w-full h-full object-cover object-[center_35%] filter contrast-110 saturate-110 brightness-100 hover:scale-105 transition-transform duration-700 ease-out">
        
        <!-- Smooth Dark Gradients -->
        <div class="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-r from-[#0b0d13] via-[#0b0d13]/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/70 pointer-events-none"></div>
    </div>

    <!-- Left Content inside max-w-7xl container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[380px] lg:min-h-[540px] items-center">
            
            <div class="lg:col-span-7 xl:col-span-6 pt-16 pb-8 lg:pt-32 lg:pb-16 pr-0 lg:pr-8 flex flex-col justify-center space-y-4 sm:space-y-6 text-left">
                <!-- Big Headline (Good Food Great Mood style) -->
                <h1 class="text-3xl sm:text-5xl xl:text-6xl font-black text-white leading-[1.08] tracking-tight">
                    Good Food<br>
                    <span class="text-[#e52e2e]">Great</span> <span class="text-[#10b981]">Mood</span>
                </h1>

                <!-- Subtitle Description -->
                <p class="text-xs sm:text-sm text-gray-300 max-w-lg leading-relaxed font-normal">
                    Nikmati cita rasa otentik jajanan jalanan Korea khas Seoul di Samarinda. Diracik dengan bumbu gochugaru asli, porsi mantap, dan diantar hangat langsung ke rumahmu.
                </p>

                <!-- Call to Action Button: PESAN SEKARANG! -->
                <div class="pt-1 flex items-center gap-3">
                    <a href="#menu" id="hero-pesan-btn" class="inline-flex items-center gap-2.5 px-6 sm:px-8 py-3.5 sm:py-4 rounded-2xl bg-gradient-to-r from-[#e52e2e] to-[#c41c1c] hover:from-[#c41c1c] hover:to-[#9e1616] text-white font-black text-xs sm:text-sm uppercase tracking-wider transition-all shadow-xl shadow-red-500/30 hover:shadow-red-500/50 hover:scale-[1.02] active:scale-95 cursor-pointer w-fit border border-white/20">
                        <span>PESAN SEKARANG!</span>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Popular Keywords (Quick Filter Tags - Touch friendly on Mobile) -->
                <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none text-xs pt-1">
                    <span class="text-gray-400 font-bold shrink-0">Populer:</span>
                    <button type="button" onclick="filterByKeyword('Tteokbokki')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-gray-200 border border-white/15 transition text-[11px] font-bold shadow-2xs cursor-pointer shrink-0">
                        <img src="https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=60&q=80" alt="Tteokbokki" class="w-4 h-4 rounded object-cover">
                        <span>Tteokbokki</span>
                    </button>
                    <button type="button" onclick="filterByKeyword('Chicken')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-gray-200 border border-white/15 transition text-[11px] font-bold shadow-2xs cursor-pointer shrink-0">
                        <img src="https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=60&q=80" alt="Korean Chicken" class="w-4 h-4 rounded object-cover">
                        <span>Korean Chicken</span>
                    </button>
                    <button type="button" onclick="filterByKeyword('Ramyeon')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-gray-200 border border-white/15 transition text-[11px] font-bold shadow-2xs cursor-pointer shrink-0">
                        <img src="https://images.unsplash.com/photo-1612927601601-6638404737ce?auto=format&fit=crop&w=60&q=80" alt="Ramyeon" class="w-4 h-4 rounded object-cover">
                        <span>Ramyeon</span>
                    </button>
                    <button type="button" onclick="filterByKeyword('Kimbap')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 hover:bg-white/20 text-gray-200 border border-white/15 transition text-[11px] font-bold shadow-2xs cursor-pointer shrink-0">
                        <img src="https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=60&q=80" alt="Kimbap" class="w-4 h-4 rounded object-cover">
                        <span>Kimbap</span>
                    </button>
                </div>

            </div>

        </div>
    </div>
</section>

<!-- ======================================================== -->
<!-- ======================================================== -->
<!-- 2. COMPACT CATEGORY NAVIGATION & INSTANT SEARCH BAR       -->
<!-- ======================================================== -->
<section class="py-2 sm:py-2.5 bg-white/95 backdrop-blur-md border-b border-gray-200 sticky top-16 z-30 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Desktop (md+) Layout: Single Sleek Row with Category Pills on Left & Search Input on Right -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 md:gap-4">
            
            <!-- Category Buttons Row (Scrollable horizontally) -->
            <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto pb-0.5 scrollbar-none flex-1 min-w-0" id="category-filter-bar">
                <!-- All Filter -->
                <button 
                    type="button" 
                    onclick="filterCategory('all', this)" 
                    class="category-btn active px-3 py-1.5 rounded-xl bg-[#e52e2e] text-white text-[11px] sm:text-xs font-black uppercase tracking-wider shadow-xs flex items-center gap-1.5 shrink-0 transition-all cursor-pointer border border-[#e52e2e]">
                    <img src="https://images.unsplash.com/photo-1553163147-622ab57be1c7?auto=format&fit=crop&w=160&q=80" 
                         alt="Semua Menu" 
                         class="w-5 h-5 rounded-full object-cover ring-1 ring-white/80 shrink-0">
                    <span>Semua Menu</span>
                </button>

                @foreach($categories as $cat)
                    @php
                        $catImg = match(strtolower($cat->slug)) {
                            'korean-street-food', 'tteokbokki', 'rabokki' => 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=160&q=80',
                            'korean-fried-chicken', 'chicken' => 'https://images.unsplash.com/photo-1562967914-608f82629710?auto=format&fit=crop&w=160&q=80',
                            'ramyeon-noodles', 'ramyeon', 'noodle' => 'https://images.unsplash.com/photo-1612927601601-6638404737ce?auto=format&fit=crop&w=160&q=80',
                            'rice-bowl-kimbap', 'kimbap', 'rice' => 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?auto=format&fit=crop&w=160&q=80',
                            'drinks-dessert', 'drinks' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?auto=format&fit=crop&w=160&q=80',
                            default => $cat->menuItems->first()?->image_url ?? 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=160&q=80'
                        };
                    @endphp
                    <button 
                        type="button" 
                        onclick="filterCategory('{{ $cat->slug }}', this)" 
                        class="category-btn px-2.5 sm:px-3 py-1.5 rounded-xl bg-white hover:bg-gray-50 text-gray-800 text-[11px] sm:text-xs font-bold tracking-wide whitespace-nowrap flex items-center gap-1.5 shrink-0 transition-all cursor-pointer border border-gray-200/90 shadow-2xs hover:border-gray-300">
                        <img src="{{ $catImg }}" 
                             alt="{{ $cat->name }}" 
                             class="w-5 h-5 rounded-full object-cover ring-1 ring-gray-200/80 shrink-0">
                        <span>{{ $cat->name }}</span>
                        <span class="cat-count text-[9px] sm:text-[10px] font-black opacity-80 bg-gray-100 text-gray-600 px-1.5 py-0.2 rounded-full border border-gray-200/60">{{ $cat->menuItems->count() }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Compact Instant Search Bar (Right aligned on desktop) -->
            <div class="relative w-full md:w-64 lg:w-72 xl:w-80 shrink-0">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-3.5 h-3.5 text-[#e52e2e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    type="text" 
                    id="hero-search-input" 
                    placeholder="Cari menu Korea..." 
                    class="w-full pl-8 pr-8 py-1.5 sm:py-2 bg-gray-50 hover:bg-gray-100/70 focus:bg-white rounded-xl border border-gray-200 focus:border-[#e52e2e] focus:ring-2 focus:ring-red-500/20 text-xs font-semibold text-gray-900 placeholder-gray-400 outline-none transition shadow-2xs">
                <button 
                    type="button" 
                    id="clear-search-btn" 
                    onclick="clearSearch()" 
                    class="hidden absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-700 text-xs font-bold cursor-pointer">
                    ✕
                </button>
            </div>

        </div>

    </div>
</section>

<!-- ======================================================== -->
<!-- 3. FEATURED DISHES GRID (Compact Modern Desktop Layout)   -->
<!-- ======================================================== -->
<section id="menu" class="pt-3 pb-12 sm:pt-4 sm:pb-16 bg-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="flex items-center justify-between mb-3 sm:mb-5 pb-2 border-b border-gray-100">
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="w-1.5 h-5 bg-[#e52e2e] rounded-xs"></div>
            <h2 class="text-base sm:text-xl font-black uppercase tracking-tight text-gray-900">
                Menu Pilihan 1988 Pocha
            </h2>
            <span class="text-xs text-gray-300 hidden sm:inline">|</span>
            <span class="text-xs text-gray-500 hidden sm:inline">Otentik street food Seoul di Samarinda</span>
        </div>

        <div class="flex items-center gap-1.5 text-xs font-bold">
            <span class="text-gray-400 hidden sm:inline">Kategori:</span>
            <span id="active-category-label" class="text-[#10b981] uppercase tracking-wider font-black bg-emerald-50 px-2 py-0.5 rounded-lg border border-emerald-200/60">Semua Menu</span>
        </div>
    </div>

    <!-- Dish Cards Grid: 2 Kolom Kotak-Kotak di Mobile (grid-cols-2), Multi-Kolom Rapi di Desktop -->
    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-4 lg:gap-5" id="dishes-grid">
        @foreach($categories as $category)
            @foreach($category->menuItems as $item)
                <div class="dish-card bg-white rounded-2xl border border-gray-200/90 hover:border-[#e52e2e]/60 hover:shadow-xl hover:shadow-gray-200/80 transition-all duration-300 flex flex-col justify-between overflow-hidden group shadow-2xs" 
                     data-category="{{ $category->slug }}"
                     data-name="{{ strtolower($item->name . ' ' . $item->korean_name . ' ' . $item->description) }}">
                    
                    <div>
                        <!-- Dish Image Box (Kotak Gambar Bagian Atas) -->
                        <div class="relative h-32 sm:h-36 lg:h-40 overflow-hidden bg-gray-100">
                            <img 
                                src="{{ $item->image_url }}" 
                                alt="{{ $item->name }}" 
                                class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
                            
                            <!-- Badges (Top Left) -->
                            <div class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 flex flex-col gap-1">
                                @if($item->is_popular)
                                    <span class="px-2 py-0.5 bg-[#e52e2e] text-white text-[8px] sm:text-[9px] font-black uppercase tracking-wider rounded-md sm:rounded-lg shadow-sm flex items-center gap-1">
                                        ★ POPULER
                                    </span>
                                @endif
                                @if($item->spicy_level !== 'none')
                                    <span class="px-1.5 py-0.5 bg-white/95 backdrop-blur-xs text-red-600 text-[8px] sm:text-[9px] font-extrabold rounded-md sm:rounded-lg border border-red-200 uppercase shadow-2xs">
                                        🌶 {{ ucfirst($item->spicy_level) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Preparation Time Overlay -->
                            <div class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5">
                                <span class="px-1.5 py-0.5 sm:px-2 sm:py-0.5 bg-black/60 sm:bg-white/90 backdrop-blur-xs text-white sm:text-gray-700 text-[8px] sm:text-[9px] font-bold rounded-md sm:rounded-lg sm:border sm:border-gray-200 shadow-2xs">
                                    ±{{ $item->preparation_time_minutes ?? 15 }}m
                                </span>
                            </div>
                        </div>

                        <!-- Dish Body Info inside the Box -->
                        <div class="p-2.5 sm:p-3 pb-1 sm:pb-1.5">
                            <div class="flex items-start justify-between gap-1">
                                <h4 class="text-xs sm:text-sm font-black text-gray-900 tracking-tight leading-snug line-clamp-2">{{ $item->name }}</h4>
                                <div class="flex items-center text-amber-500 text-[10px] sm:text-xs font-bold shrink-0 pt-0.5">
                                    <span>★</span>
                                    <span class="ml-0.5 sm:ml-1 text-[10px] sm:text-[11px] text-gray-700 font-black">4.9</span>
                                </div>
                            </div>
                            
                            @if($item->korean_name)
                                <p class="text-[9px] sm:text-[11px] text-[#10b981] font-bold korean-font mt-0.5 truncate">{{ $item->korean_name }}</p>
                            @endif

                            <p class="hidden sm:block text-[11px] text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                                {{ $item->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Bottom Price & Add Button inside the Box -->
                    <div class="p-2.5 sm:p-4 pt-1.5 sm:pt-2 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-3 bg-gray-50/50">
                        <div>
                            <span class="text-[8px] sm:text-[10px] text-gray-400 font-bold uppercase block leading-none">Harga</span>
                            <span class="text-xs sm:text-sm font-black text-[#e52e2e]">{{ $item->formatted_price }}</span>
                        </div>

                        <button 
                            type="button" 
                            class="add-to-cart-btn w-full sm:w-auto px-2 sm:px-4 py-1.5 sm:py-2 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-95 text-white text-[10px] sm:text-xs font-black uppercase tracking-wider flex items-center justify-center gap-1 sm:gap-1.5 transition-all shadow-sm shadow-red-500/20 cursor-pointer"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name }}"
                            data-price="{{ $item->price }}"
                            data-image="{{ $item->image_url }}">
                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>Tambah</span>
                        </button>
                    </div>

                </div>
            @endforeach
        @endforeach
    </div>

    <!-- Empty search result state -->
    <div id="no-dishes-found" class="hidden text-center py-16 bg-white rounded-2xl border border-gray-200 mt-6">
        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-400 mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">Menu tidak ditemukan</p>
        <p class="text-xs text-gray-500 mt-1">Coba kata kunci lain atau pilih kategori di atas.</p>
    </div>

</section>

<!-- ======================================================== -->
<!-- 4. SPECIAL OFFERS (Dynamic Admin Menu Promo & Photo Cards) -->
<!-- ======================================================== -->
@if(isset($promos) && $promos->isNotEmpty())
<section id="promo" class="py-12 sm:py-16 bg-white border-y border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-6 sm:mb-8 gap-2">
            <div>
                <div class="flex items-center gap-2 mb-1 sm:mb-1.5">
                    <span class="w-5 sm:w-6 h-[2px] bg-[#e52e2e]"></span>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-[#e52e2e]">SPECIAL OFFERS & PROMO</span>
                    <span class="w-5 sm:w-6 h-[2px] bg-[#10b981]"></span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-black uppercase tracking-tight text-gray-900">
                    Promo Spesial Pocha Hari Ini
                </h2>
                <p class="text-[11px] sm:text-sm text-gray-500 mt-0.5 sm:mt-1 font-medium">Nikmati paket kombo hemat dan penawaran diskon jajanan Korea terbaik di Samarinda.</p>
            </div>
            <span class="text-xs text-gray-400 font-bold hidden sm:block">
                {{ $promos->count() }} Penawaran Spesial Aktif
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 {{ $promos->count() >= 3 ? 'lg:grid-cols-3' : '' }} gap-6">
            @foreach($promos as $promo)
                <div class="relative rounded-3xl overflow-hidden bg-white border border-gray-200 shadow-md hover:shadow-xl hover:border-red-200 transition-all duration-300 flex flex-col justify-between group">
                    
                    <div>
                        <!-- Foto Produk Promo (Unggulan & Tajam) -->
                        <div class="relative h-48 sm:h-56 w-full bg-gray-100 overflow-hidden">
                            <img 
                                src="{{ $promo->image_url }}" 
                                alt="{{ $promo->title }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                            <!-- Badges Overlay -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start z-10">
                                <span class="px-3 py-1 rounded-xl bg-[#e52e2e] text-white text-[10px] font-black uppercase tracking-wider shadow-md">
                                    {{ $promo->badge_text ?? 'PROMO SPESIAL' }}
                                </span>
                            </div>

                            @if($promo->discount_percentage)
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md text-[#e52e2e] px-3 py-1.5 rounded-2xl text-xs font-black shadow-lg border border-red-200 z-10 flex items-center gap-1">
                                    <span>HEMAT</span>
                                    <span class="text-sm font-black">{{ $promo->discount_percentage }}%</span>
                                </div>
                            @endif

                            <!-- Tagline over Image Bottom -->
                            @if($promo->tagline)
                                <div class="absolute bottom-3 left-3 right-3 z-10">
                                    <p class="text-xs sm:text-sm font-bold text-amber-300 drop-shadow-md truncate">
                                        {{ $promo->tagline }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Body Info Promo -->
                        <div class="p-5 sm:p-6 space-y-3">
                            <h3 class="text-xl sm:text-2xl font-black text-gray-900 uppercase tracking-tight leading-tight">
                                {{ $promo->title }}
                            </h3>

                            @if($promo->description)
                                <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">
                                    {{ $promo->description }}
                                </p>
                            @endif

                            <!-- Pricing Section -->
                            @if($promo->promo_price || $promo->original_price)
                                <div class="pt-2 flex items-baseline gap-2.5">
                                    @if($promo->promo_price)
                                        <span class="text-xl sm:text-2xl font-black text-[#e52e2e]">
                                            {{ $promo->formatted_promo_price }}
                                        </span>
                                    @endif
                                    @if($promo->original_price)
                                        <span class="text-xs sm:text-sm text-gray-400 line-through font-bold">
                                            {{ $promo->formatted_original_price }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action Button -->
                    <div class="p-5 sm:p-6 pt-0">
                        <a href="{{ $promo->button_link ?? '#menu' }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-[#e52e2e] to-[#c41c1c] hover:from-[#c41c1c] hover:to-[#9e1616] text-white font-black text-xs uppercase tracking-wider transition-all shadow-md shadow-red-500/20 active:scale-95 cursor-pointer border border-white/20">
                            <span>{{ $promo->button_text ?? 'Pesan Sekarang' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</section>
@endif

<!-- ======================================================== -->
<!-- 5. LOCATION & CONTACT (Alamat & Jam Operasional Samarinda) -->
<!-- ======================================================== -->
<section id="lokasi" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-stretch">
        
        <!-- Kolom Kiri: Info & Kontak -->
        <div class="lg:col-span-5 flex flex-col justify-between space-y-6">
            <div class="space-y-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-[#10b981]">LOKASI & KONTAK</span>
                <h2 class="text-3xl sm:text-4xl font-black uppercase tracking-tight text-gray-900 leading-tight">
                    Dapur 1988 Pocha Samarinda
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 font-medium leading-relaxed">
                    Siap melayani pesanan online untuk pengantaran cepat ke seluruh kecamatan se-Kota Samarinda.
                </p>
            </div>

            <div class="space-y-3.5 flex-1 flex flex-col justify-center">
                <div class="bg-white border border-gray-200 p-5 rounded-2xl flex items-start gap-4 shadow-xs">
                    <div class="w-11 h-11 rounded-xl bg-red-50 text-[#e52e2e] flex items-center justify-center shrink-0 text-xl border border-red-100">
                        📍
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-extrabold text-gray-400 tracking-wider block">ALAMAT DAPUR</span>
                        <p class="text-sm font-black text-gray-900 mt-0.5">1988 Pocha Samarinda</p>
                        <p class="text-xs text-gray-500 mt-0.5">Jl. Untung Suropati, Samarinda, Kalimantan Timur</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 p-5 rounded-2xl flex items-start gap-4 shadow-xs">
                    <div class="w-11 h-11 rounded-xl {{ (isset($paymentSettings) && ! $paymentSettings->is_store_open) ? 'bg-red-50 text-[#e52e2e] border-red-100' : 'bg-emerald-50 text-[#10b981] border-emerald-100' }} flex items-center justify-center shrink-0 text-xl border">
                        ⏰
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-extrabold text-gray-400 tracking-wider block">STATUS & OPERASIONAL RESTO</span>
                        @if(isset($paymentSettings) && ! $paymentSettings->is_store_open)
                            <p class="text-sm font-black text-red-600 mt-0.5">
                                SEDANG TUTUP SEMENTARA
                            </p>
                            <p class="text-xs text-red-600 font-bold mt-0.5">{{ $paymentSettings->closed_message ?: 'Resto sedang tutup, pemesanan dan pembayaran dinonaktifkan.' }}</p>
                        @else
                            <p class="text-sm font-black text-gray-900 mt-0.5">
                                BUKA (11:00 – 23:00 WITA)
                            </p>
                            <p class="text-xs text-[#10b981] font-bold mt-0.5">Pesanan diolah langsung saat diterima</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                @php
                    $waPhone = isset($paymentSettings) && $paymentSettings->whatsapp_number ? $paymentSettings->whatsapp_number : '6281277051988';
                @endphp
                <a href="https://api.whatsapp.com/send?phone={{ $waPhone }}&text=Halo%201988%20Pocha%20Samarinda,%20saya%20mau%20tanya" target="_blank" rel="noopener" class="flex-1 min-w-[180px] px-6 py-3.5 rounded-xl bg-[#25D366] hover:bg-[#1faa53] text-white font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Hubungi WhatsApp</span>
                </a>

                <a href="https://www.instagram.com/1988pocha_smd/" target="_blank" rel="noopener" class="px-5 py-3.5 rounded-xl bg-white hover:bg-gray-50 text-gray-800 border border-gray-300 font-black text-xs uppercase tracking-wider flex items-center gap-2 transition-all active:scale-95 shadow-2xs cursor-pointer">
                    <svg class="w-4 h-4 text-[#e52e2e]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <span>Instagram</span>
                </a>
            </div>
        </div>

        <!-- Kolom Kanan: Peta Kotak Proporsional (Square Box Map) -->
        <div class="lg:col-span-7 flex flex-col">
            <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-md flex flex-col h-full min-h-[460px] lg:min-h-[500px]">
                <!-- Map Header Bar -->
                <div class="px-5 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center p-1.5 shadow-xs shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="1988 Pocha" class="w-full h-full object-contain">
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight flex items-center gap-2 truncate">
                                <span>1988 POCHA SAMARINDA</span>
                                <span class="px-2 py-0.5 rounded-full {{ (isset($paymentSettings) && ! $paymentSettings->is_store_open) ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }} text-[10px] font-black uppercase shrink-0">
                                    {{ (isset($paymentSettings) && ! $paymentSettings->is_store_open) ? 'Tutup' : 'Aktif' }}
                                </span>
                            </h4>
                            <span class="text-xs text-gray-500 font-medium truncate block">Jl. Untung Suropati, Samarinda, Kaltim</span>
                        </div>
                    </div>
                    <a href="https://maps.app.goo.gl/QjWN8SGqBDCFMBXH8" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] text-white text-xs font-black uppercase tracking-wider transition shadow-2xs hover:scale-105 active:scale-95 cursor-pointer shrink-0">
                        <span>Buka di Maps ↗</span>
                    </a>
                </div>

                <!-- Google Maps Embed Iframe (Kotak Tinggi Rapi) -->
                <div class="relative w-full flex-1 min-h-[380px] bg-gray-100">
                    <iframe 
                        src="https://maps.google.com/maps?q=-0.5208104,117.1171918+(1988+POCHA)&t=&z=17&ie=UTF8&iwloc=B&output=embed" 
                        class="absolute inset-0 w-full h-full border-0" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi 1988 Pocha Samarinda">
                    </iframe>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    // Live Search & Category Filter Script (TasteHub Interactive Behavior)
    function filterCategory(categorySlug, clickedBtn) {
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-[#e52e2e]', 'text-white', 'shadow-sm', 'font-black', 'border-[#e52e2e]');
            btn.classList.add('bg-white', 'hover:bg-gray-50', 'text-gray-800', 'font-bold', 'border-gray-200/90');
            const img = btn.querySelector('img');
            if (img) {
                img.classList.remove('ring-white/80');
                img.classList.add('ring-gray-200/80');
            }
            const badge = btn.querySelector('.cat-count');
            if (badge) {
                badge.className = 'cat-count text-[10px] font-black opacity-80 bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full border border-gray-200/60';
            }
        });

        if (clickedBtn) {
            clickedBtn.classList.remove('bg-white', 'hover:bg-gray-50', 'text-gray-800', 'font-bold', 'border-gray-200/90');
            clickedBtn.classList.add('bg-[#e52e2e]', 'text-white', 'shadow-sm', 'font-black', 'border-[#e52e2e]');
            const img = clickedBtn.querySelector('img');
            if (img) {
                img.classList.remove('ring-gray-200/80');
                img.classList.add('ring-white/80');
            }
            const badge = clickedBtn.querySelector('.cat-count');
            if (badge) {
                badge.className = 'cat-count text-[10px] font-black bg-white/20 text-white px-2 py-0.5 rounded-full border border-white/30';
            }
        }

        const label = document.getElementById('active-category-label');
        if (label) {
            label.innerText = categorySlug === 'all' ? 'Semua Menu' : categorySlug.toUpperCase();
        }

        const cards = document.querySelectorAll('.dish-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (categorySlug === 'all' || cardCat === categorySlug) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        const noFound = document.getElementById('no-dishes-found');
        if (noFound) {
            noFound.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function filterByKeyword(keyword) {
        const input = document.getElementById('hero-search-input');
        if (input) {
            input.value = keyword;
            applyKeywordFilter(keyword);
            document.getElementById('menu').scrollIntoView({ behavior: 'smooth' });
        }
    }

    function applyKeywordFilter(kw) {
        const query = kw.toLowerCase().trim();
        const cards = document.querySelectorAll('.dish-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            if (name.includes(query)) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        const noFound = document.getElementById('no-dishes-found');
        if (noFound) {
            noFound.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function clearSearch() {
        const input = document.getElementById('hero-search-input');
        const clearBtn = document.getElementById('clear-search-btn');
        if (input) {
            input.value = '';
            applyKeywordFilter('');
        }
        if (clearBtn) clearBtn.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('hero-search-input');
        const clearBtn = document.getElementById('clear-search-btn');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                applyKeywordFilter(e.target.value);
                if (clearBtn) {
                    clearBtn.classList.toggle('hidden', e.target.value.trim().length === 0);
                }
            });
        }
    });
</script>
@endpush
