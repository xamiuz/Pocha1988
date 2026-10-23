<!-- Real-time New Order Pop-out Toast Notification & Auto-Refresh -->
<div id="new-order-toast" class="hidden fixed top-3 sm:top-5 inset-x-3 sm:inset-x-auto sm:right-6 z-[9999] sm:w-[420px] transition-all duration-300 ease-out" style="transform: translateY(-25px); opacity: 0;">
    <div class="relative bg-white/95 backdrop-blur-xl border border-red-200/90 rounded-2xl sm:rounded-3xl shadow-[0_20px_50px_rgba(229,46,46,0.18),0_4px_16px_rgba(0,0,0,0.06)] p-4 sm:p-5 overflow-hidden">
        <!-- Top Gradient Glow Accent Bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#e52e2e] via-amber-400 to-[#e52e2e]"></div>

        <!-- Header: Live Badge & Close Button -->
        <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-[#e52e2e] text-white tracking-wider">LIVE</span>
                <span class="text-[11px] font-black uppercase tracking-wider text-[#e52e2e] flex items-center gap-1">
                    <span id="toast-title">Pesanan Baru Masuk!</span>
                </span>
            </div>

            <button type="button" onclick="dismissOrderToast()" class="w-6 h-6 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-900 flex items-center justify-center font-bold text-xs transition cursor-pointer" title="Tutup Notifikasi">
                ✕
            </button>
        </div>

        <!-- Body: Order Card Details -->
        <div class="flex items-start gap-3.5 pt-3">
            <!-- Icon Bell with Glow (Clean Gradient Tile) -->
            <div class="relative w-11 h-11 rounded-2xl bg-gradient-to-br from-[#e52e2e] to-rose-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-red-500/25">
                <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>

            <!-- Text Details -->
            <div class="min-w-0 flex-1 space-y-1">
                <div class="flex items-center justify-between gap-2">
                    <span id="toast-order-num" class="font-black text-gray-900 text-sm tracking-wide truncate">#POCHA-XXXXXX</span>
                    <span id="toast-total-badge" class="text-xs font-black text-[#e52e2e] bg-red-50 px-2 py-0.5 rounded-lg border border-red-200/80 shrink-0">Rp 0</span>
                </div>

                <p id="toast-customer-info" class="text-xs text-gray-700 font-bold truncate">Pelanggan</p>

                <div class="flex items-center gap-2 text-[10px] text-gray-500 font-semibold pt-0.5 flex-wrap">
                    <span id="toast-district" class="inline-flex items-center gap-1 text-emerald-800 bg-emerald-50 px-1.5 py-0.5 rounded font-bold border border-emerald-200/60">
                        <svg class="w-2.5 h-2.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <span>Kec. Samarinda</span>
                    </span>
                    <span class="text-gray-300">|</span>
                    <span id="toast-time" class="text-gray-600 font-medium">Baru saja</span>
                    <span id="toast-method" class="text-[9px] uppercase px-1.5 py-0.2 bg-gray-100 text-gray-600 rounded font-black hidden">QRIS</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-3.5 pt-3 border-t border-gray-100 flex items-center gap-2">
            @if(request()->routeIs('admin.dashboard'))
                <button type="button" id="toast-refresh-btn" onclick="window.location.reload()" class="flex-1 py-2 px-3 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-98 text-white font-black text-xs uppercase tracking-wider shadow-sm shadow-red-500/20 transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg id="toast-spin-icon" class="w-3.5 h-3.5 animate-spin hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span id="toast-refresh-text">Muat Sekarang</span>
                </button>
            @else
                <a href="{{ route('admin.dashboard') }}" class="flex-1 py-2 px-3 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-98 text-white font-black text-xs uppercase tracking-wider shadow-sm shadow-red-500/20 transition flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>Buka Dapur Pesanan ↗</span>
                </a>
            @endif
            <button type="button" onclick="dismissOrderToast()" class="py-2 px-3.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs uppercase tracking-wider transition cursor-pointer">
                Tutup
            </button>
        </div>

        <!-- Subtle Auto-Dismiss Progress Bar -->
        <div id="toast-progress-bar" class="absolute bottom-0 left-0 h-1 bg-[#e52e2e]/40 w-full transition-all duration-[7000ms] ease-linear"></div>
    </div>
</div>

<script>
    (function () {
        let globalAudioCtx = null;
        let toastTimeout = null;
        let isPollingActive = true;

        // Inisialisasi Audio Context
        function getAudioContext() {
            if (!globalAudioCtx) {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (AudioContextClass) {
                    globalAudioCtx = new AudioContextClass();
                }
            }
            if (globalAudioCtx && globalAudioCtx.state === 'suspended') {
                globalAudioCtx.resume();
            }
            return globalAudioCtx;
        }

        // Web Audio Chime (Melodi Bel Pesanan Pocha: C6 -> E6 -> G6)
        window.playOrderChime = function () {
            try {
                const ctx = getAudioContext();
                if (!ctx) return;
                const now = ctx.currentTime;

                const notes = [
                    { freq: 1046.50, start: 0, duration: 0.22, gain: 0.35 },    // C6
                    { freq: 1318.51, start: 0.12, duration: 0.22, gain: 0.38 },   // E6
                    { freq: 1567.98, start: 0.24, duration: 0.65, gain: 0.45 },   // G6
                ];

                notes.forEach(note => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(note.freq, now + note.start);
                    gain.gain.setValueAtTime(note.gain, now + note.start);
                    gain.gain.exponentialRampToValueAtTime(0.0001, now + note.start + note.duration);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now + note.start);
                    osc.stop(now + note.start + note.duration);
                });
            } catch (err) {
                console.warn('Audio chime notice:', err);
            }
        };

        // Tombol Tes Notifikasi Suara di Header Admin
        window.testOrToggleNotificationSound = function () {
            playOrderChime();
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WITA';
            showOrderToast({
                order_number: 'POCHA-TEST',
                customer_name: 'Tes Suara Bel Notifikasi',
                total_formatted: 'Rp 50.000',
                district: 'Samarinda Ulu',
                time: timeStr,
                payment_method: 'QRIS'
            }, false);
        };

        // Unlock audio saat klik pertama pada halaman
        document.addEventListener('click', () => {
            getAudioContext();
        }, { once: true });

        // Tampilkan Toast Pop-out
        window.showOrderToast = function (orderData, isAutoRefreshing = false) {
            const toast = document.getElementById('new-order-toast');
            if (!toast) return;

            const orderNumEl = document.getElementById('toast-order-num');
            const totalBadgeEl = document.getElementById('toast-total-badge');
            const custInfoEl = document.getElementById('toast-customer-info');
            const districtEl = document.getElementById('toast-district');
            const timeEl = document.getElementById('toast-time');
            const methodEl = document.getElementById('toast-method');

            if (orderNumEl) orderNumEl.innerText = orderData.order_number ? `#${orderData.order_number}` : '#POCHA';
            if (totalBadgeEl) totalBadgeEl.innerText = orderData.total_formatted || 'Rp 0';
            if (custInfoEl) custInfoEl.innerText = orderData.customer_name || 'Pelanggan Baru';
            if (districtEl) {
                const distSpan = districtEl.querySelector('span');
                if (distSpan) {
                    distSpan.innerText = `Kec. ${orderData.district || 'Samarinda'}`;
                } else {
                    districtEl.innerText = `Kec. ${orderData.district || 'Samarinda'}`;
                }
            }
            if (timeEl) timeEl.innerText = orderData.time || 'Baru saja';

            if (methodEl) {
                if (orderData.payment_method) {
                    methodEl.innerText = orderData.payment_method;
                    methodEl.classList.remove('hidden');
                } else {
                    methodEl.classList.add('hidden');
                }
            }

            const refreshBtnText = document.getElementById('toast-refresh-text');
            const spinIcon = document.getElementById('toast-spin-icon');
            if (isAutoRefreshing && refreshBtnText && spinIcon) {
                refreshBtnText.innerText = 'Memperbarui Dapur...';
                spinIcon.classList.remove('hidden');
            }

            // Reset dan jalankan progress bar animasi countdown
            const bar = document.getElementById('toast-progress-bar');
            if (bar) {
                bar.style.transition = 'none';
                bar.style.width = '100%';
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        bar.style.transition = 'width 7000ms linear';
                        bar.style.width = '0%';
                    });
                });
            }

            // Tampilkan animasi pop out halus
            toast.classList.remove('hidden');
            requestAnimationFrame(() => {
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
            });

            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                dismissOrderToast();
            }, 7000);
        };

        // Sembunyikan Toast Pop-out
        window.dismissOrderToast = function () {
            const toast = document.getElementById('new-order-toast');
            if (!toast) return;
            toast.style.transform = 'translateY(-25px)';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        };

        // Cek apakah ada notifikasi tertunda dari reload sebelumnya
        try {
            const savedNotice = sessionStorage.getItem('pocha_just_received_order');
            if (savedNotice) {
                const parsed = JSON.parse(savedNotice);
                sessionStorage.removeItem('pocha_just_received_order');
                setTimeout(() => {
                    const titleEl = document.getElementById('toast-title');
                    if (titleEl) titleEl.innerText = 'Antrean Dapur Diperbarui!';
                    showOrderToast(parsed, false);
                }, 400);
            }
        } catch (e) {}

        // Logika Polling Real-time Pesanan Baru
        let currentLastOrderId = 0;
        const metaEl = document.getElementById('admin-dashboard-meta');
        if (metaEl && metaEl.dataset.lastOrderId) {
            currentLastOrderId = parseInt(metaEl.dataset.lastOrderId) || 0;
        }

        async function pollNewOrders() {
            if (!isPollingActive) return;

            try {
                const url = `{{ route('admin.check_new_orders') }}?last_order_id=${currentLastOrderId}`;
                const res = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!res.ok) return;
                const data = await res.json();

                if (currentLastOrderId === 0 && data.latest_order_id) {
                    currentLastOrderId = data.latest_order_id;
                    return;
                }

                if (data.has_new && data.latest_order) {
                    currentLastOrderId = data.latest_order_id;

                    // 1. Bunyikan Bel Pesanan Masuk
                    playOrderChime();

                    // 2. Simpan order ke sessionStorage agar banner tetap tampil setelah refresh
                    try {
                        sessionStorage.setItem('pocha_just_received_order', JSON.stringify(data.latest_order));
                    } catch (e) {}

                    // 3. Tampilkan Pop-out Notifikasi
                    const isDashboard = {{ request()->routeIs('admin.dashboard') ? 'true' : 'false' }};
                    const isProofModalOpen = !document.getElementById('proof-modal')?.classList.contains('hidden');
                    const isChatModalOpen = !document.getElementById('admin-chat-modal')?.classList.contains('hidden');

                    if (isDashboard && !isProofModalOpen && !isChatModalOpen) {
                        showOrderToast(data.latest_order, true);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                    } else {
                        showOrderToast(data.latest_order, false);
                    }
                }
            } catch (err) {
                // Ignore transient network errors
            }
        }

        // Jalankan polling setiap 4 detik
        setInterval(pollNewOrders, 4000);
    })();
</script>
