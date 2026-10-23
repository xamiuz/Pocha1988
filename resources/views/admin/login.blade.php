<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin & Kitchen Dispatch | 1988 Pocha Samarinda</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Noto+Sans+KR:wght@500;700;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .korean-font { font-family: 'Noto Sans KR', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[#F4F5F7] text-gray-900 flex items-center justify-center p-4 sm:p-6 lg:p-10 antialiased selection:bg-[#e52e2e] selection:text-white">

    <div class="w-full max-w-4xl bg-white rounded-3xl border border-gray-200 shadow-xl shadow-gray-300/40 overflow-hidden grid grid-cols-1 md:grid-cols-12 min-h-[540px]">
        
        <!-- Left Column: Restaurant & Kitchen Branding Visual (Desktop only on 5 cols) -->
        <div class="hidden md:flex md:col-span-5 relative flex-col justify-between p-8 bg-gray-950 text-white overflow-hidden">
            <!-- Background Image with Clean Dark Overlay -->
            <img 
                src="{{ asset('images/pocha-outlet.png') }}" 
                alt="1988 Pocha Samarinda" 
                class="absolute inset-0 w-full h-full object-cover object-center filter brightness-60 contrast-110">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/80 to-gray-950/50 pointer-events-none"></div>

            <!-- Top Brand Badge -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white p-1 border border-white/20 shadow-md flex items-center justify-center shrink-0">
                    <img src="{{ asset('images/logo.png') }}" alt="1988 Pocha" class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-1 leading-none">
                        <span class="text-base font-black text-[#e52e2e]">1988</span>
                        <span class="text-base font-black text-[#10b981]">POCHA</span>
                    </div>
                    <span class="text-[10px] text-gray-300 font-bold uppercase tracking-widest mt-0.5 block">Samarinda Street Food</span>
                </div>
            </div>

            <!-- Middle Headline Content -->
            <div class="relative z-10 space-y-3 my-auto py-8">
                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-red-600 text-white inline-block">
                    PORTAL ADMIN
                </span>
                <h2 class="text-2xl lg:text-3xl font-black text-white leading-tight tracking-tight">
                    Kitchen Dispatch & Order Control
                </h2>
                <p class="text-xs text-gray-300 leading-relaxed font-normal">
                    Kelola antrean pesanan masuk real-time, verifikasi bukti pembayaran QRIS dan transfer bank, serta pantau pengantaran kurir se-Samarinda.
                </p>
            </div>

            <!-- Bottom Operational Details -->
            <div class="relative z-10 pt-4 border-t border-white/15 flex items-center justify-between text-[11px] text-gray-300">
                <span class="flex items-center gap-1.5 font-semibold text-gray-200">
                    <svg class="w-3.5 h-3.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Sesi Terenkripsi</span>
                </span>
                <span class="font-bold text-gray-400">Kota Samarinda</span>
            </div>
        </div>

        <!-- Right Column: Authentication Form (7 cols on Desktop, Full on Mobile) -->
        <div class="md:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-between bg-white">
            <div>
                <!-- Mobile Brand Header (Visible on Mobile only) -->
                <div class="md:hidden flex items-center gap-3 pb-5 mb-5 border-b border-gray-100">
                    <div class="w-10 h-10 rounded-xl bg-white p-1 border border-gray-200 shadow-xs flex items-center justify-center shrink-0">
                        <img src="{{ asset('images/logo.png') }}" alt="1988 Pocha" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <div class="flex items-center gap-1 leading-none">
                            <span class="text-base font-black text-[#e52e2e]">1988</span>
                            <span class="text-base font-black text-[#10b981]">POCHA</span>
                        </div>
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block mt-0.5">Kitchen Dispatch Samarinda</span>
                    </div>
                </div>

                <!-- Form Header -->
                <div class="space-y-1.5">
                    <span class="text-[11px] font-black uppercase tracking-wider text-[#e52e2e] block">Autentikasi Internal</span>
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Masuk ke Panel Admin</h1>
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">Masukkan master password admin untuk membuka akses dapur dan antrean transaksi.</p>
                </div>

                <!-- Status Feedback Alerts -->
                <div class="mt-5 space-y-2.5">
                    @if(session('warning'))
                        <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold flex items-start gap-2.5 shadow-2xs">
                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span class="leading-relaxed">{{ session('warning') }}</span>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-start gap-2.5 shadow-2xs">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span class="leading-relaxed">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->has('password'))
                        <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-900 text-xs font-semibold flex items-start gap-2.5 shadow-2xs">
                            <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="leading-relaxed">{{ $errors->first('password') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Form Login -->
                <form action="{{ route('admin.login.submit') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password-input" class="block text-xs font-black uppercase tracking-wider text-gray-700">
                                Password Admin
                            </label>
                            <span class="text-[11px] text-gray-400 font-semibold">Master Key</span>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>

                            <input 
                                type="password" 
                                id="password-input" 
                                name="password" 
                                required 
                                autofocus
                                placeholder="Masukkan password admin..." 
                                class="w-full bg-gray-50/70 border border-gray-300 focus:border-[#e52e2e] focus:bg-white focus:ring-4 focus:ring-red-500/10 text-gray-900 rounded-xl pl-10 pr-11 py-3 text-sm placeholder-gray-400 font-medium transition shadow-xs outline-none">

                            <!-- Toggle Show / Hide Password -->
                            <button 
                                type="button" 
                                id="toggle-password" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 transition cursor-pointer"
                                title="Tampilkan / Sembunyikan Password">
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-5 bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-[0.99] text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-md shadow-red-500/20 transition flex items-center justify-center gap-2 cursor-pointer">
                        <span>Masuk ke Panel Admin</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between text-xs">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#e52e2e] font-bold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Website</span>
                </a>
                <span class="text-gray-400 text-[11px] font-semibold">1988 Pocha System</span>
            </div>
        </div>

    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pwdInput = document.getElementById('password-input');
            const toggleBtn = document.getElementById('toggle-password');
            const eyeIcon = document.getElementById('eye-icon');

            if (toggleBtn && pwdInput && eyeIcon) {
                toggleBtn.addEventListener('click', () => {
                    if (pwdInput.type === 'password') {
                        pwdInput.type = 'text';
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>`;
                    } else {
                        pwdInput.type = 'password';
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
                    }
                });
            }
        });
    </script>
</body>
</html>
