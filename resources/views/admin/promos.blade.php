@extends('layouts.admin')

@section('title', 'Manajemen Menu Promo & Penawaran | Admin 1988 Pocha')

@section('content')
<div class="space-y-8">
    <!-- Header Admin Promo -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200">
                    Marketing & Promosi
                </span>
                <span class="text-xs text-[#10b981] font-bold">Tampil di Beranda Pelanggan</span>
            </div>
            <h1 class="text-2xl font-black uppercase tracking-tight text-gray-900">MANAJEMEN MENU PROMO & DISKON</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola banner promo, paket kombo hemat, kode diskon, dan foto produk promo makanan.</p>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
            <a href="{{ route('admin.menu.index') }}" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-bold text-xs uppercase tracking-wider transition shadow-2xs">
                ← Menu Makanan
            </a>
            <button onclick="openAddPromoModal()" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] text-white font-black text-xs uppercase tracking-wider shadow-xs transition cursor-pointer flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ Promo Baru</span>
            </button>
        </div>
    </div>

    <!-- Ringkasan Statistik Promo -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center justify-between shadow-2xs">
            <div>
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Total Promo Dibuat</span>
                <span class="text-2xl font-black text-gray-900">{{ $promos->count() }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-700 text-lg font-bold">
                🏷️
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center justify-between shadow-2xs">
            <div>
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Promo Sedang Aktif</span>
                <span class="text-2xl font-black text-[#10b981]">{{ $promos->where('is_active', true)->count() }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#10b981] flex items-center justify-center text-lg font-bold border border-emerald-100">
                ✓
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center justify-between shadow-2xs">
            <div>
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block">Promo Non-Aktif</span>
                <span class="text-2xl font-black text-gray-400">{{ $promos->where('is_active', false)->count() }}</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center text-lg font-bold border border-gray-200">
                ⏸
            </div>
        </div>
    </div>

    <!-- Daftar Kartu Promo yang Sudah Ada -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-black uppercase tracking-wider text-gray-900">Daftar Menu Promo Aktif & Arsip</h2>
                <p class="text-xs text-gray-500">Promo yang berstatus 'Aktif' langsung tayang di section Promo halaman utama Pocha.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($promos as $promo)
                <div class="bg-white border {{ $promo->is_active ? 'border-gray-200' : 'border-gray-200 opacity-60 bg-gray-50/70' }} rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between group">
                    <div>
                        <!-- Foto Produk Promo -->
                        <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
                            <img 
                                src="{{ $promo->image_url }}" 
                                alt="{{ $promo->title }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <!-- Overlay Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1.5 items-start">
                                <span class="px-2.5 py-1 rounded-lg bg-[#e52e2e] text-white text-[10px] font-black uppercase tracking-wider shadow-sm">
                                    {{ $promo->badge_text ?? 'PROMO SPESIAL' }}
                                </span>
                            </div>

                            @if($promo->discount_percentage)
                                <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-xs text-[#e52e2e] px-2.5 py-1 rounded-xl text-xs font-black shadow-md border border-red-200">
                                    HEMAT {{ $promo->discount_percentage }}%
                                </div>
                            @endif

                            <div class="absolute bottom-2 right-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider {{ $promo->is_active ? 'bg-emerald-500 text-white' : 'bg-gray-700 text-gray-200' }}">
                                    {{ $promo->is_active ? 'Aktif' : 'Non-aktif' }}
                                </span>
                            </div>
                        </div>

                        <!-- Konten Detail Promo -->
                        <div class="p-4 space-y-2.5">
                            <div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-tight line-clamp-1">
                                    {{ $promo->title }}
                                </h3>
                                @if($promo->tagline)
                                    <p class="text-xs font-bold text-[#10b981] mt-0.5 line-clamp-1">
                                        {{ $promo->tagline }}
                                    </p>
                                @endif
                            </div>

                            @if($promo->description)
                                <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed">
                                    {{ $promo->description }}
                                </p>
                            @endif

                            <!-- Info Harga Promo vs Asli -->
                            @if($promo->promo_price || $promo->original_price)
                                <div class="pt-2 border-t border-gray-100 flex items-baseline gap-2">
                                    @if($promo->promo_price)
                                        <span class="text-sm font-black text-[#e52e2e]">{{ $promo->formatted_promo_price }}</span>
                                    @endif
                                    @if($promo->original_price)
                                        <span class="text-xs text-gray-400 line-through font-bold">{{ $promo->formatted_original_price }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($promo->menuItem)
                                <div class="text-[10px] text-gray-500 bg-gray-50 p-2 rounded-lg border border-gray-200/70 flex items-center gap-1.5">
                                    <span class="font-bold text-gray-700">Menu Terkait:</span>
                                    <span class="truncate font-semibold">{{ $promo->menuItem->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Aksi Status, Edit, Hapus -->
                    <div class="p-4 pt-3 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between gap-2">
                        <!-- Toggle Status -->
                        <form action="{{ route('admin.promos.toggle', $promo->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border {{ $promo->is_active ? 'bg-emerald-50 text-[#10b981] border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-600 border-gray-200 hover:bg-gray-200' }} cursor-pointer">
                                {{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <div class="flex items-center gap-1.5">
                            <!-- Edit Promo -->
                            <button 
                                type="button" 
                                onclick="editPromo({{ json_encode($promo) }})" 
                                class="px-3 py-1 rounded-lg bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 text-xs font-bold shadow-2xs cursor-pointer">
                                Edit
                            </button>

                            <!-- Hapus Promo -->
                            <form action="{{ route('admin.promos.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Hapus promo \'{{ addslashes($promo->title) }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold border border-red-200 cursor-pointer">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-200 p-8 space-y-3">
                    <div class="w-12 h-12 rounded-full bg-red-50 text-[#e52e2e] flex items-center justify-center text-xl mx-auto">
                        🏷️
                    </div>
                    <h3 class="text-sm font-black uppercase text-gray-900">Belum Ada Menu Promo</h3>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">Tambahkan promo pertama sekarang untuk memikat pelanggan dengan diskon menarik dan foto produk promo.</p>
                    <button onclick="openAddPromoModal()" class="px-4 py-2 rounded-xl bg-[#e52e2e] text-white font-black text-xs uppercase shadow-sm">
                        + Tambah Promo Sekarang
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 1: TAMBAH PROMO BARU                 -->
<!-- ========================================== -->
<!-- ========================================== -->
<!-- MODAL 1: TAMBAH PROMO BARU                 -->
<!-- ========================================== -->
<div id="modal-add-promo" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-gray-200 rounded-2xl sm:rounded-3xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
        <form action="{{ route('admin.promos.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full max-h-[90vh]">
            @csrf

            <!-- Header (Fixed) -->
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-gray-900">Tambah Menu Promo Baru</h3>
                    <p class="text-[11px] text-gray-500 font-medium">Banner & foto promo akan langsung tampil di beranda pelanggan.</p>
                </div>
                <button type="button" onclick="document.getElementById('modal-add-promo').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center font-bold text-sm cursor-pointer transition">✕</button>
            </div>

            <!-- Scrollable Body Form -->
            <div class="px-5 sm:px-6 py-4 overflow-y-auto flex-1 space-y-3.5 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Judul Promo *</label>
                    <input type="text" name="title" required placeholder="Contoh: PAKET KOMBO KENYANG DAEBAK" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-semibold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Tagline / Subjudul</label>
                        <input type="text" name="tagline" placeholder="Tteokbokki + Chicken + Drink Hemat" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Label Badge</label>
                        <input type="text" name="badge_text" value="PROMO SPESIAL POCHA" placeholder="PROMO SPESIAL / KOMBO HEMAT" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                    </div>
                </div>

                <!-- Bagian Foto Produk Promo: File Upload ATAU URL -->
                <div class="bg-gray-50/80 p-3 sm:p-3.5 rounded-2xl border border-gray-200 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="block font-bold text-gray-800 uppercase text-[11px]">Foto Produk Promo *</span>
                        <span class="text-[10px] text-gray-500 font-medium">Upload file dari galeri atau tautan URL</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-600 mb-0.5 text-[10px]">1. Upload File Foto Produk</label>
                            <input type="file" name="promo_image" accept="image/*" onchange="previewAddImage(this)" class="w-full text-xs text-gray-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#e52e2e] file:text-white hover:file:bg-[#c41c1c] file:cursor-pointer bg-white rounded-xl border border-gray-300 p-1 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-600 mb-0.5 text-[10px]">2. Atau Masukkan URL Foto</label>
                            <input type="url" name="image_url" id="add-promo-url" oninput="previewAddImageUrl(this.value)" placeholder="https://..." class="w-full bg-white border border-gray-300 rounded-xl px-2.5 py-1.5 text-gray-900 focus:outline-none focus:border-[#e52e2e] text-[11px]">
                        </div>
                    </div>

                    <!-- Live Preview Foto -->
                    <div id="add-preview-container" class="hidden pt-2 border-t border-gray-200 flex items-center gap-3">
                        <img id="add-preview-img" src="" alt="Preview Promo" class="w-18 h-14 object-cover rounded-xl border border-gray-300 shadow-2xs">
                        <span class="text-[11px] text-emerald-600 font-bold">✓ Pratinjau Foto Produk Siap</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Harga Normal (Rp)</label>
                        <input type="number" name="original_price" placeholder="50000" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Harga Promo (Rp)</label>
                        <input type="number" name="promo_price" placeholder="40000" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 font-bold text-[#e52e2e] focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Tautkan Menu (Opsional)</label>
                        <select name="menu_item_id" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                            <option value="">-- Promo Umum / Tanpa Menu Khusus --</option>
                            @foreach($menuItems as $m)
                                <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->formatted_price }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Diskon (%)</label>
                        <input type="number" name="discount_percentage" min="1" max="100" placeholder="Contoh: 20" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Deskripsi Penawaran Promo</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan keuntungan promo ini..." class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Teks Tombol</label>
                        <input type="text" name="button_text" value="Pesan Sekarang" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Link Tombol</label>
                        <input type="text" name="button_link" value="#menu" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Urutan Tampil</label>
                        <input type="number" name="sort_order" value="0" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                </div>
            </div>

            <!-- Sticky Footer Actions -->
            <div class="px-5 sm:px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2.5 shrink-0">
                <button type="button" onclick="document.getElementById('modal-add-promo').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold uppercase text-xs hover:bg-gray-100 transition cursor-pointer">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-[0.99] text-white font-black uppercase text-xs shadow-xs transition cursor-pointer">Simpan Menu Promo</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2: EDIT MENU PROMO                   -->
<!-- ========================================== -->
<div id="modal-edit-promo" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-gray-200 rounded-2xl sm:rounded-3xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
        <form id="form-edit-promo" action="" method="POST" enctype="multipart/form-data" class="flex flex-col h-full max-h-[90vh]">
            @csrf
            @method('PUT')

            <!-- Header (Fixed) -->
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-gray-900">Edit Menu Promo</h3>
                    <p class="text-[11px] text-gray-500 font-medium">Perbarui detail promosi, diskon, dan foto produk promo.</p>
                </div>
                <button type="button" onclick="document.getElementById('modal-edit-promo').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center font-bold text-sm cursor-pointer transition">✕</button>
            </div>

            <!-- Scrollable Body Form -->
            <div class="px-5 sm:px-6 py-4 overflow-y-auto flex-1 space-y-3.5 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Judul Promo *</label>
                    <input type="text" name="title" id="edit-promo-title" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-semibold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Tagline / Subjudul</label>
                        <input type="text" name="tagline" id="edit-promo-tagline" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Label Badge</label>
                        <input type="text" name="badge_text" id="edit-promo-badge" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                    </div>
                </div>

                <!-- Bagian Foto Produk Promo: File Upload ATAU URL -->
                <div class="bg-gray-50/80 p-3 sm:p-3.5 rounded-2xl border border-gray-200 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="block font-bold text-gray-800 uppercase text-[11px]">Foto Produk Promo</span>
                        <span class="text-[10px] text-gray-500 font-medium">Ganti foto dengan upload baru atau ubah URL</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-600 mb-0.5 text-[10px]">Upload File Baru</label>
                            <input type="file" name="promo_image" accept="image/*" onchange="previewEditImage(this)" class="w-full text-xs text-gray-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#e52e2e] file:text-white hover:file:bg-[#c41c1c] file:cursor-pointer bg-white rounded-xl border border-gray-300 p-1 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-600 mb-0.5 text-[10px]">Atau URL Foto</label>
                            <input type="url" name="image_url" id="edit-promo-image" oninput="previewEditImageUrl(this.value)" class="w-full bg-white border border-gray-300 rounded-xl px-2.5 py-1.5 text-gray-900 focus:outline-none focus:border-[#e52e2e] text-[11px]">
                        </div>
                    </div>

                    <!-- Preview Foto Terpasang -->
                    <div class="pt-2 border-t border-gray-200 flex items-center gap-3">
                        <img id="edit-preview-img" src="" alt="Preview Foto Promo" class="w-18 h-14 object-cover rounded-xl border border-gray-300 shadow-2xs">
                        <span class="text-[11px] text-gray-600 font-medium">Foto produk promo yang sedang digunakan</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Harga Normal (Rp)</label>
                        <input type="number" name="original_price" id="edit-promo-original-price" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Harga Promo (Rp)</label>
                        <input type="number" name="promo_price" id="edit-promo-price" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 font-bold text-[#e52e2e] focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Tautkan Menu</label>
                        <select name="menu_item_id" id="edit-promo-menu-item" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                            <option value="">-- Promo Umum / Tanpa Menu Khusus --</option>
                            @foreach($menuItems as $m)
                                <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->formatted_price }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Diskon (%)</label>
                        <input type="number" name="discount_percentage" id="edit-promo-discount" min="1" max="100" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Deskripsi Penawaran Promo</label>
                    <textarea name="description" id="edit-promo-desc" rows="2" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Teks Tombol</label>
                        <input type="text" name="button_text" id="edit-promo-btn-text" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Link Tombol</label>
                        <input type="text" name="button_link" id="edit-promo-btn-link" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Urutan Tampil</label>
                        <input type="number" name="sort_order" id="edit-promo-sort" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e]">
                    </div>
                </div>
            </div>

            <!-- Sticky Footer Actions -->
            <div class="px-5 sm:px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2.5 shrink-0">
                <button type="button" onclick="document.getElementById('modal-edit-promo').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold uppercase text-xs hover:bg-gray-100 transition cursor-pointer">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-[0.99] text-white font-black uppercase text-xs shadow-xs transition cursor-pointer">Perbarui Menu Promo</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddPromoModal() {
        document.getElementById('modal-add-promo').classList.remove('hidden');
    }

    function previewAddImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('add-preview-img').src = e.target.result;
                document.getElementById('add-preview-container').classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewAddImageUrl(url) {
        if (url && url.startsWith('http')) {
            document.getElementById('add-preview-img').src = url;
            document.getElementById('add-preview-container').classList.remove('hidden');
        }
    }

    function previewEditImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('edit-preview-img').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewEditImageUrl(url) {
        if (url && url.startsWith('http')) {
            document.getElementById('edit-preview-img').src = url;
        }
    }

    function editPromo(promo) {
        const form = document.getElementById('form-edit-promo');
        form.action = `/admin/promos/${promo.id}`;

        document.getElementById('edit-promo-title').value = promo.title || '';
        document.getElementById('edit-promo-tagline').value = promo.tagline || '';
        document.getElementById('edit-promo-badge').value = promo.badge_text || '';
        document.getElementById('edit-promo-image').value = promo.image_url || '';
        document.getElementById('edit-preview-img').src = promo.image_url || 'https://images.unsplash.com/photo-1590301157890-4810ed352733?auto=format&fit=crop&w=700&q=80';
        document.getElementById('edit-promo-original-price').value = promo.original_price ? parseInt(promo.original_price) : '';
        document.getElementById('edit-promo-price').value = promo.promo_price ? parseInt(promo.promo_price) : '';
        document.getElementById('edit-promo-menu-item').value = promo.menu_item_id || '';
        document.getElementById('edit-promo-discount').value = promo.discount_percentage || '';
        document.getElementById('edit-promo-desc').value = promo.description || '';
        document.getElementById('edit-promo-btn-text').value = promo.button_text || 'Pesan Sekarang';
        document.getElementById('edit-promo-btn-link').value = promo.button_link || '#menu';
        document.getElementById('edit-promo-sort').value = promo.sort_order || 0;

        document.getElementById('modal-edit-promo').classList.remove('hidden');
    }

    // Tutup modal promo bila klik backdrop atau tekan tombol Escape
    document.addEventListener('DOMContentLoaded', () => {
        const promoModals = ['modal-add-promo', 'modal-edit-promo'];
        promoModals.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', (e) => {
                    if (e.target === el) el.classList.add('hidden');
                });
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                promoModals.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.classList.add('hidden');
                });
            }
        });
    });
</script>
@endsection
