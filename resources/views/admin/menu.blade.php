@extends('layouts.admin')

@section('title', 'Manajemen Menu & Kategori | Admin 1988 Pocha')

@section('content')
<div class="space-y-10">
    <!-- Header Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-black uppercase tracking-tight text-gray-900">MANAJEMEN MENU & KATEGORI</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola daftar menu makanan, harga, status ketersediaan, serta jenis/kategori menu.</p>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
            <a href="{{ route('admin.promos.index') }}" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 font-black text-xs uppercase tracking-wider transition shadow-2xs flex items-center gap-1.5">
                <span>🏷️</span>
                <span>Menu Promo</span>
            </a>
            <button onclick="document.getElementById('modal-add-category').classList.remove('hidden')" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-bold text-xs uppercase tracking-wider transition shadow-2xs cursor-pointer">
                + Jenis Menu
            </button>
            <button onclick="openAddMenuModal()" class="px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] text-white font-black text-xs uppercase tracking-wider shadow-xs transition cursor-pointer">
                + Tambah Menu
            </button>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- BAGIAN 1: CRUD JENIS / KATEGORI MENU       -->
    <!-- ========================================== -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-4 shadow-xs">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h2 class="text-sm font-black uppercase tracking-wider text-gray-900">Daftar Jenis / Kategori Menu</h2>
                <p class="text-[11px] text-gray-500">Kelompok kategori yang tampil pada navigasi menu pelanggan.</p>
            </div>
            <span class="text-xs font-bold text-gray-500">{{ $categories->count() }} Kategori Aktif</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($categories as $cat)
                <div class="bg-gray-50/80 border border-gray-200 rounded-xl p-3.5 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-black text-gray-900 block uppercase tracking-wide">{{ $cat->name }}</span>
                        <span class="text-[11px] text-gray-500 font-medium">{{ $cat->menuItems->count() }} Menu Terdaftar</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Edit Kategori -->
                        <button onclick="editCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}')" class="px-3 py-1 rounded-lg bg-white hover:bg-gray-100 text-gray-700 text-[11px] font-bold border border-gray-200 shadow-2xs cursor-pointer">
                            Edit
                        </button>

                        <!-- Hapus Kategori -->
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }} beserta seluruh menu di dalamnya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-[11px] font-bold border border-red-200 cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ========================================== -->
    <!-- BAGIAN 2: DAFTAR MENU & EDIT PER KATEGORI -->
    <!-- ========================================== -->
    <div class="space-y-8">
        @foreach($categories as $cat)
            <div class="bg-white border border-gray-200 rounded-2xl p-6 space-y-5 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-base font-black uppercase tracking-wider text-gray-900">
                        {{ $cat->name }}
                    </h3>
                    <span class="text-xs text-gray-500 font-bold">{{ $cat->menuItems->count() }} Pilihan Menu</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($cat->menuItems as $item)
                        <div class="bg-gray-50/70 border border-gray-200 rounded-2xl p-4 flex flex-col justify-between hover:border-gray-300 transition">
                            <div class="flex items-start gap-3">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-16 h-16 rounded-xl object-cover shrink-0 bg-gray-100 border border-gray-200 shadow-2xs">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <h4 class="text-xs font-black text-gray-900 truncate">{{ $item->name }}</h4>
                                        @if($item->is_popular)
                                            <span class="text-[9px] bg-red-600 text-white px-1.5 py-0.2 rounded font-black uppercase">Populer</span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-[#10b981] font-bold truncate">{{ $item->korean_name }}</p>
                                    <p class="text-xs font-black text-[#e52e2e] mt-1">{{ $item->formatted_price }}</p>
                                </div>
                            </div>

                            <!-- Tombol Aksi Status & CRUD Menu -->
                            <div class="mt-4 pt-3 border-t border-gray-200/80 flex items-center justify-between text-xs">
                                <!-- Status Switch -->
                                <form action="{{ route('admin.menu.toggle', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] uppercase font-black px-2.5 py-0.5 rounded-full {{ $item->is_available ? 'bg-emerald-100 text-[#10b981] border border-emerald-200' : 'bg-red-100 text-[#e52e2e] border border-red-200' }} cursor-pointer">
                                        {{ $item->is_available ? 'Tersedia' : 'Habis' }}
                                    </button>
                                </form>

                                <div class="flex items-center gap-1.5">
                                    <!-- Edit Menu -->
                                    <button 
                                        type="button"
                                        onclick="editMenuItem({{ json_encode($item) }})"
                                        class="px-3 py-1 rounded-lg bg-white hover:bg-gray-100 text-gray-700 border border-gray-200 text-[11px] font-bold shadow-2xs cursor-pointer">
                                        Edit
                                    </button>

                                    <!-- Hapus Menu -->
                                    <form action="{{ route('admin.menu.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $item->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 text-[11px] font-bold border border-red-200 cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-gray-400 text-xs font-medium">
                            Belum ada menu pada kategori ini.
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 1: TAMBAH MENU BARU                  -->
<!-- ========================================== -->
<div id="modal-add-menu" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-gray-200 rounded-2xl sm:rounded-3xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full max-h-[90vh]">
            @csrf
            <!-- Header (Fixed) -->
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-50 text-[#e52e2e] flex items-center justify-center shrink-0 border border-red-100">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-wider text-gray-900">Tambah Menu Makanan</h3>
                        <p class="text-[11px] text-gray-500 font-medium">Lengkapi detail hidangan untuk katalog menu 1988 Pocha.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('modal-add-menu').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center font-bold text-sm cursor-pointer transition">✕</button>
            </div>

            <!-- Scrollable Body Form -->
            <div class="px-5 sm:px-6 py-4 overflow-y-auto flex-1 space-y-3.5 text-xs">
                <!-- Row 1: Kategori & Waktu Siap -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Jenis / Kategori Menu *</label>
                        <select name="category_id" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Estimasi Siap (Menit) *</label>
                        <input type="number" name="preparation_time_minutes" value="15" min="1" max="180" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                    </div>
                </div>

                <!-- Row 2: Nama Menu & Nama Korea -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Nama Menu *</label>
                        <input type="text" name="name" required placeholder="Contoh: Kimbap Tuna Mayo" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Nama Korea (Opsional)</label>
                        <input type="text" name="korean_name" placeholder="참치마요 김밥" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium korean-font">
                    </div>
                </div>

                <!-- Row 3: Harga & Level Pedas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Harga (Rp) *</label>
                        <input type="number" name="price" required placeholder="30000" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Level Pedas *</label>
                        <select name="spicy_level" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                            <option value="none">Tidak Pedas</option>
                            <option value="mild">Mild (Pedas Ringan)</option>
                            <option value="medium">Medium (Pedas Sedang)</option>
                            <option value="very_spicy">Very Spicy (Ekstra Pedas)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4: Foto Menu Makanan (Compact Card) -->
                <div class="bg-gray-50/80 p-3 sm:p-3.5 rounded-2xl border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="block font-bold text-gray-800 uppercase text-[11px]">Foto Menu Makanan</span>
                        <span class="text-[10px] text-gray-500 font-medium">Upload file lokal atau link URL</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Preview Thumbnail Box -->
                        <div class="w-20 h-20 min-w-[80px] max-w-[80px] min-h-[80px] max-h-[80px] rounded-xl bg-white border border-gray-300 shadow-2xs overflow-hidden shrink-0 flex items-center justify-center relative">
                            <img id="add-menu-preview-img" src="" alt="Preview Menu" class="w-full h-full object-cover hidden">
                            <div id="add-menu-placeholder" class="flex flex-col items-center justify-center text-gray-400 p-2 text-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[9px] font-bold text-gray-400 mt-0.5">Pratinjau</span>
                            </div>
                        </div>

                        <!-- Upload & URL Inputs -->
                        <div class="flex-1 w-full space-y-2">
                            <div>
                                <span class="block text-[10px] font-bold text-gray-600 mb-0.5">1. Upload Foto Langsung</span>
                                <input type="file" name="menu_image" id="add-menu-file" accept="image/*" onchange="previewAddMenuFile(event)" class="w-full text-xs text-gray-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#e52e2e] file:text-white hover:file:bg-[#c41c1c] file:cursor-pointer bg-white rounded-xl border border-gray-300 p-1 focus:outline-none">
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-600 mb-0.5">2. Atau Masukkan URL Gambar</span>
                                <input type="url" name="image_url" id="add-menu-url" oninput="previewAddMenuUrl(this.value)" placeholder="https://..." class="w-full bg-white border border-gray-300 rounded-xl px-2.5 py-1.5 text-gray-900 focus:outline-none focus:border-[#e52e2e] text-[11px]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Deskripsi Makanan -->
                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Deskripsi Makanan</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat hidangan & cita rasa..." class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium"></textarea>
                </div>
            </div>

            <!-- Sticky Footer Actions -->
            <div class="px-5 sm:px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2.5 shrink-0">
                <button type="button" onclick="document.getElementById('modal-add-menu').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold uppercase text-xs hover:bg-gray-100 transition cursor-pointer">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-[0.99] text-white font-black uppercase text-xs shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Menu</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2: EDIT MENU                         -->
<!-- ========================================== -->
<div id="modal-edit-menu" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-gray-200 rounded-2xl sm:rounded-3xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden">
        <form id="form-edit-menu" action="" method="POST" enctype="multipart/form-data" class="flex flex-col h-full max-h-[90vh]">
            @csrf
            @method('PUT')

            <!-- Header (Fixed) -->
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-wider text-gray-900">Edit Menu Makanan</h3>
                        <p class="text-[11px] text-gray-500 font-medium">Perbarui harga, ketersediaan, atau deskripsi menu.</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('modal-edit-menu').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center font-bold text-sm cursor-pointer transition">✕</button>
            </div>

            <!-- Scrollable Body Form -->
            <div class="px-5 sm:px-6 py-4 overflow-y-auto flex-1 space-y-3.5 text-xs">
                <!-- Row 1: Kategori & Waktu Siap -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Jenis / Kategori Menu *</label>
                        <select name="category_id" id="edit-menu-category" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Estimasi Siap (Menit) *</label>
                        <input type="number" name="preparation_time_minutes" id="edit-menu-time" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                    </div>
                </div>

                <!-- Row 2: Nama Menu & Nama Korea -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Nama Menu *</label>
                        <input type="text" name="name" id="edit-menu-name" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Nama Korea</label>
                        <input type="text" name="korean_name" id="edit-menu-korean" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium korean-font">
                    </div>
                </div>

                <!-- Row 3: Harga & Level Pedas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Harga (Rp) *</label>
                        <input type="number" name="price" id="edit-menu-price" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-bold">
                    </div>
                    <div>
                        <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Level Pedas *</label>
                        <select name="spicy_level" id="edit-menu-spicy" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                            <option value="none">Tidak Pedas</option>
                            <option value="mild">Mild (Pedas Ringan)</option>
                            <option value="medium">Medium (Pedas Sedang)</option>
                            <option value="very_spicy">Very Spicy (Ekstra Pedas)</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4: Foto Menu Makanan (Compact Card) -->
                <div class="bg-gray-50/80 p-3 sm:p-3.5 rounded-2xl border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="block font-bold text-gray-800 uppercase text-[11px]">Foto Menu Makanan</span>
                        <span class="text-[10px] text-gray-500 font-medium">Upload file baru atau ubah URL</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Preview Thumbnail Box -->
                        <div class="w-20 h-20 min-w-[80px] max-w-[80px] min-h-[80px] max-h-[80px] rounded-xl bg-white border border-gray-300 shadow-2xs overflow-hidden shrink-0 flex items-center justify-center relative">
                            <img id="edit-menu-preview-img" src="" alt="Preview Menu" class="w-full h-full object-cover hidden">
                            <div id="edit-menu-placeholder" class="flex flex-col items-center justify-center text-gray-400 p-2 text-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[9px] font-bold text-gray-400 mt-0.5">Pratinjau</span>
                            </div>
                        </div>

                        <!-- Upload & URL Inputs -->
                        <div class="flex-1 w-full space-y-2">
                            <div>
                                <span class="block text-[10px] font-bold text-gray-600 mb-0.5">1. Upload Foto Baru Langsung</span>
                                <input type="file" name="menu_image" id="edit-menu-file" accept="image/*" onchange="previewEditMenuFile(event)" class="w-full text-xs text-gray-700 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#e52e2e] file:text-white hover:file:bg-[#c41c1c] file:cursor-pointer bg-white rounded-xl border border-gray-300 p-1 focus:outline-none">
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-600 mb-0.5">2. Atau Ubah URL Gambar</span>
                                <input type="url" name="image_url" id="edit-menu-image" oninput="previewEditMenuUrl(this.value)" placeholder="https://..." class="w-full bg-white border border-gray-300 rounded-xl px-2.5 py-1.5 text-gray-900 focus:outline-none focus:border-[#e52e2e] text-[11px]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Deskripsi Makanan -->
                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Deskripsi Makanan</label>
                    <textarea name="description" id="edit-menu-desc" rows="2" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium"></textarea>
                </div>
            </div>

            <!-- Sticky Footer Actions -->
            <div class="px-5 sm:px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-2.5 shrink-0">
                <button type="button" onclick="document.getElementById('modal-edit-menu').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold uppercase text-xs hover:bg-gray-100 transition cursor-pointer">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-[0.99] text-white font-black uppercase text-xs shadow-xs transition cursor-pointer flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Perbarui Menu</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 3: TAMBAH JENIS / KATEGORI MENU     -->
<!-- ========================================== -->
<div id="modal-add-category" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-gray-200 rounded-2xl sm:rounded-3xl max-w-md w-full shadow-2xl overflow-hidden">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col">
            @csrf
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <h3 class="text-sm font-black uppercase tracking-wider text-gray-900">Tambah Jenis / Kategori Menu</h3>
                <button type="button" onclick="document.getElementById('modal-add-category').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center font-bold text-sm cursor-pointer transition">✕</button>
            </div>

            <div class="p-5 sm:p-6 space-y-3.5 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Nama Jenis / Kategori *</label>
                    <input type="text" name="name" required placeholder="Contoh: Korean Bento & Combo" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Deskripsi Kategori</label>
                    <textarea name="description" rows="2" placeholder="Penjelasan kategori..." class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium"></textarea>
                </div>
            </div>

            <div class="px-5 sm:px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2 shrink-0">
                <button type="button" onclick="document.getElementById('modal-add-category').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold uppercase text-xs hover:bg-gray-100 cursor-pointer transition">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#10b981] hover:bg-[#0da070] text-white font-black uppercase text-xs shadow-xs cursor-pointer transition">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 4: EDIT JENIS / KATEGORI MENU       -->
<!-- ========================================== -->
<div id="modal-edit-category" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-5">
    <div class="bg-white border border-gray-200 rounded-2xl sm:rounded-3xl max-w-md w-full shadow-2xl overflow-hidden">
        <form id="form-edit-category" action="" method="POST" class="flex flex-col">
            @csrf
            @method('PUT')
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <h3 class="text-sm font-black uppercase tracking-wider text-gray-900">Edit Jenis / Kategori Menu</h3>
                <button type="button" onclick="document.getElementById('modal-edit-category').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center font-bold text-sm cursor-pointer transition">✕</button>
            </div>

            <div class="p-5 sm:p-6 space-y-3.5 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Nama Jenis / Kategori *</label>
                    <input type="text" name="name" id="edit-cat-name" required class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1 uppercase text-[11px]">Deskripsi Kategori</label>
                    <textarea name="description" id="edit-cat-desc" rows="2" class="w-full bg-gray-50 border border-gray-300 rounded-xl px-3 py-2 text-gray-900 focus:bg-white focus:outline-none focus:border-[#e52e2e] font-medium"></textarea>
                </div>
            </div>

            <div class="px-5 sm:px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end gap-2 shrink-0">
                <button type="button" onclick="document.getElementById('modal-edit-category').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold uppercase text-xs hover:bg-gray-100 cursor-pointer transition">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#10b981] hover:bg-[#0da070] text-white font-black uppercase text-xs shadow-xs cursor-pointer transition">Perbarui Kategori</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddMenuModal() {
        const fileInput = document.getElementById('add-menu-file');
        if (fileInput) fileInput.value = '';
        const urlInput = document.getElementById('add-menu-url');
        if (urlInput) urlInput.value = '';
        const previewImg = document.getElementById('add-menu-preview-img');
        const placeholder = document.getElementById('add-menu-placeholder');
        if (previewImg) {
            previewImg.src = '';
            previewImg.classList.add('hidden');
        }
        if (placeholder) placeholder.classList.remove('hidden');
        document.getElementById('modal-add-menu').classList.remove('hidden');
    }

    function previewAddMenuFile(event) {
        const file = event.target.files[0];
        const previewImg = document.getElementById('add-menu-preview-img');
        const placeholder = document.getElementById('add-menu-placeholder');
        if (file && previewImg) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function previewAddMenuUrl(url) {
        const previewImg = document.getElementById('add-menu-preview-img');
        const placeholder = document.getElementById('add-menu-placeholder');
        if (url && url.trim().length > 10 && previewImg) {
            previewImg.src = url;
            previewImg.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        } else if ((!url || url.trim().length === 0) && previewImg) {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
        }
    }

    function previewEditMenuFile(event) {
        const file = event.target.files[0];
        const previewImg = document.getElementById('edit-menu-preview-img');
        const placeholder = document.getElementById('edit-menu-placeholder');
        if (file && previewImg) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function previewEditMenuUrl(url) {
        const previewImg = document.getElementById('edit-menu-preview-img');
        const placeholder = document.getElementById('edit-menu-placeholder');
        if (url && url.trim().length > 10 && previewImg) {
            previewImg.src = url;
            previewImg.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        } else if ((!url || url.trim().length === 0) && previewImg) {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
        }
    }

    function editMenuItem(item) {
        const form = document.getElementById('form-edit-menu');
        form.action = `/admin/menu/${item.id}`;
        document.getElementById('edit-menu-category').value = item.category_id;
        document.getElementById('edit-menu-name').value = item.name;
        document.getElementById('edit-menu-korean').value = item.korean_name || '';
        document.getElementById('edit-menu-price').value = parseInt(item.price);
        document.getElementById('edit-menu-spicy').value = item.spicy_level;
        document.getElementById('edit-menu-image').value = item.image_url || '';
        document.getElementById('edit-menu-desc').value = item.description || '';
        document.getElementById('edit-menu-time').value = item.preparation_time_minutes;

        // Reset file input & perbarui preview
        const fileInput = document.getElementById('edit-menu-file');
        if (fileInput) fileInput.value = '';

        const previewImg = document.getElementById('edit-menu-preview-img');
        const placeholder = document.getElementById('edit-menu-placeholder');
        if (item.image_url && previewImg) {
            previewImg.src = item.image_url;
            previewImg.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        } else if (previewImg) {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            if (placeholder) placeholder.classList.remove('hidden');
        }

        document.getElementById('modal-edit-menu').classList.remove('hidden');
    }

    function editCategory(id, name, desc) {
        const form = document.getElementById('form-edit-category');
        form.action = `/admin/categories/${id}`;
        document.getElementById('edit-cat-name').value = name;
        document.getElementById('edit-cat-desc').value = desc;
        document.getElementById('modal-edit-category').classList.remove('hidden');
    }

    // Tutup modal bila klik backdrop atau tekan tombol Escape
    document.addEventListener('DOMContentLoaded', () => {
        const modalIds = ['modal-add-menu', 'modal-edit-menu', 'modal-add-category', 'modal-edit-category'];
        modalIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', (e) => {
                    if (e.target === el) el.classList.add('hidden');
                });
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                modalIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.classList.add('hidden');
                });
            }
        });
    });
</script>
@endsection
