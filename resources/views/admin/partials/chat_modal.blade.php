<!-- Admin Live Chat Modal with Buyer -->
<div id="admin-chat-modal" class="fixed inset-0 z-50 hidden transition-opacity duration-300">
    <!-- Backdrop -->
    <div id="admin-chat-backdrop" class="fixed inset-0 bg-black/60 backdrop-blur-xs cursor-pointer"></div>

    <!-- Modal Box -->
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6 pointer-events-none">
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-200 flex flex-col h-[580px] max-h-[90vh] overflow-hidden pointer-events-auto transition-all transform animate-in fade-in zoom-in-95 duration-200">
            
            <!-- Chat Header -->
            <div class="p-4 bg-gray-900 text-white flex items-center justify-between border-b border-gray-800 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#e52e2e] flex items-center justify-center text-white font-black text-sm shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 id="admin-chat-customer-name" class="font-black text-sm text-white">Nama Pembeli</h3>
                            <span id="admin-chat-order-number" class="text-[10px] bg-red-950 text-red-300 px-2 py-0.5 rounded-full border border-red-800 font-mono font-bold">#POCHA</span>
                        </div>
                        <div class="text-[11px] text-emerald-400 mt-0.5">
                            <span id="admin-chat-status">Live Chat Pembeli | Samarinda</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a id="admin-chat-wa-link" href="#" target="_blank" class="p-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-[#10b981] border border-emerald-500/30 transition text-xs font-bold flex items-center gap-1" title="Buka WhatsApp Pembeli">
                        <span class="text-xs">WA</span>
                        <span>↗</span>
                    </a>
                    <button type="button" id="admin-chat-close-btn" class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Chat Messages Container -->
            <div id="admin-chat-messages" class="flex-1 p-4 space-y-3 overflow-y-auto bg-gray-50/70 text-xs">
                <div class="text-center py-10 text-gray-400">
                    <span class="animate-spin inline-block mb-2">⏳</span>
                    <p class="font-bold">Memuat riwayat obrolan...</p>
                </div>
            </div>

            <!-- Chat Input Footer -->
            <div class="p-3 bg-white border-t border-gray-200 shrink-0">
                <form id="admin-chat-form" class="flex items-center gap-2">
                    <input 
                        type="text" 
                        id="admin-chat-input" 
                        required 
                        autocomplete="off" 
                        placeholder="Ketik pesan untuk pembeli (Enter untuk kirim)..." 
                        class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-[#e52e2e] focus:bg-white font-medium transition"
                    >
                    <button 
                        type="submit" 
                        id="admin-chat-send-btn" 
                        class="px-4 py-2.5 rounded-xl bg-[#e52e2e] hover:bg-[#c41c1c] active:scale-95 text-white font-black text-xs uppercase tracking-wider flex items-center gap-1.5 transition shadow-sm cursor-pointer shrink-0"
                    >
                        <span>Kirim</span>
                        <svg class="w-3.5 h-3.5 rotate-45 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    (function () {
        let currentChatOrderNumber = null;
        let adminPollInterval = null;

        const modal = document.getElementById('admin-chat-modal');
        const backdrop = document.getElementById('admin-chat-backdrop');
        const closeBtn = document.getElementById('admin-chat-close-btn');
        const messagesContainer = document.getElementById('admin-chat-messages');
        const chatForm = document.getElementById('admin-chat-form');
        const chatInput = document.getElementById('admin-chat-input');
        const nameEl = document.getElementById('admin-chat-customer-name');
        const orderEl = document.getElementById('admin-chat-order-number');
        const waLink = document.getElementById('admin-chat-wa-link');

        // Buka Modal Chat Admin
        window.openAdminChatModal = function (orderNumber, customerName, customerPhone) {
            currentChatOrderNumber = orderNumber;
            nameEl.innerText = customerName || 'Pembeli';
            orderEl.innerText = `#${orderNumber}`;
            if (waLink && customerPhone) {
                const cleanPhone = customerPhone.replace(/[^0-9]/g, '');
                waLink.href = `https://wa.me/${cleanPhone}`;
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            chatInput.value = '';
            chatInput.focus();

            loadMessages();

            // Polling setiap 3 detik
            clearInterval(adminPollInterval);
            adminPollInterval = setInterval(loadMessages, 3000);
        };

        // Tutup Modal Chat
        function closeAdminChatModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentChatOrderNumber = null;
            clearInterval(adminPollInterval);
        }

        if (closeBtn) closeBtn.addEventListener('click', closeAdminChatModal);
        if (backdrop) backdrop.addEventListener('click', closeAdminChatModal);

        // Ambil Pesan dari Server
        function loadMessages() {
            if (!currentChatOrderNumber) return;

            fetch(`/orders/${currentChatOrderNumber}/messages?as=admin`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderMessages(data.messages);
                }
            })
            .catch(err => console.error('Error load admin chat:', err));
        }

        function renderMessages(messages) {
            if (!messages || messages.length === 0) {
                messagesContainer.innerHTML = `
                    <div class="text-center py-12 space-y-2">
                        <div class="w-12 h-12 rounded-full bg-red-50 text-[#e52e2e] flex items-center justify-center mx-auto text-lg">
                            💬
                        </div>
                        <p class="font-extrabold text-gray-800 text-xs">Belum ada percakapan</p>
                        <p class="text-[11px] text-gray-500 max-w-xs mx-auto">Ketik pesan di bawah untuk mulai mengobrol langsung dengan pembeli.</p>
                    </div>
                `;
                return;
            }

            const isScrolledToBottom = messagesContainer.scrollHeight - messagesContainer.clientHeight <= messagesContainer.scrollTop + 50;

            let html = '';
            messages.forEach(msg => {
                const isAdmin = msg.sender_type === 'admin';
                if (isAdmin) {
                    html += `
                        <div class="flex flex-col items-end">
                            <div class="max-w-[80%] rounded-2xl rounded-tr-xs bg-[#e52e2e] text-white p-3 shadow-2xs">
                                <span class="text-[10px] font-black uppercase tracking-wider block text-red-200 mb-0.5">Admin Pocha</span>
                                <p class="text-xs leading-relaxed font-medium whitespace-pre-wrap">${escapeHtml(msg.message)}</p>
                            </div>
                            <div class="flex items-center gap-1 mt-1 text-[10px] text-gray-400 font-medium">
                                <span>${msg.time}</span>
                                <span class="text-gray-300">|</span>
                                <span class="${msg.is_read ? 'text-[#10b981] font-bold' : 'text-gray-400'}">${msg.is_read ? '✓✓ Dibaca' : '✓ Terkirim'}</span>
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="flex flex-col items-start">
                            <div class="max-w-[80%] rounded-2xl rounded-tl-xs bg-white border border-gray-200 text-gray-900 p-3 shadow-2xs">
                                <span class="text-[10px] font-black uppercase tracking-wider block text-emerald-600 mb-0.5">${escapeHtml(msg.sender_name || 'Pembeli')}</span>
                                <p class="text-xs leading-relaxed font-medium text-gray-800 whitespace-pre-wrap">${escapeHtml(msg.message)}</p>
                            </div>
                            <span class="mt-1 text-[10px] text-gray-400 font-medium">${msg.time}</span>
                        </div>
                    `;
                }
            });

            messagesContainer.innerHTML = html;

            if (isScrolledToBottom) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        }

        // Kirim Pesan Form
        if (chatForm) {
            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                sendMessage(chatInput.value);
            });
        }

        function sendMessage(text) {
            const trimmed = text.trim();
            if (!trimmed || !currentChatOrderNumber) return;

            chatInput.value = '';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(`/orders/${currentChatOrderNumber}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    message: trimmed,
                    sender_type: 'admin',
                    sender_name: 'Admin Pocha'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadMessages();
                    setTimeout(() => {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }, 100);
                }
            })
            .catch(err => alert('Gagal mengirim pesan chat. Silakan coba lagi.'));
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.innerText = text;
            return div.innerHTML;
        }
    })();
</script>
