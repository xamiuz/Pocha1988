<!-- Floating Customer Live Chat Widget -->
<div id="customer-chat-root" class="relative z-50">
    <!-- Floating Trigger Button -->
    <div id="customer-chat-btn-wrapper" class="fixed bottom-6 right-6 z-50">
        <button id="btn-toggle-customer-chat" 
                type="button"
                onclick="toggleCustomerChat()" 
                class="group flex items-center gap-2 px-4 py-3 bg-[#e52e2e] hover:bg-red-700 text-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-200 hover:-translate-y-0.5 cursor-pointer border-2 border-white/80">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <span class="font-extrabold text-xs tracking-wide">Chat Resto & Kurir</span>
            <span id="customer-unread-badge" class="hidden bg-emerald-400 text-gray-950 font-black text-[10px] px-1.5 py-0.2 rounded-md shadow-xs">0</span>
        </button>
    </div>

    <!-- Floating Chat Window -->
    <div id="customer-chat-window" 
         class="fixed bottom-6 right-6 sm:bottom-8 sm:right-8 z-50 w-[94vw] sm:w-[380px] h-[530px] max-h-[85vh] bg-white rounded-2xl shadow-2xl border border-gray-200 flex flex-col overflow-hidden transition-all duration-300 transform translate-y-4 opacity-0 pointer-events-none">
        
        <!-- Chat Header -->
        <div class="bg-gray-950 text-white p-3.5 sm:p-4 flex items-center justify-between border-b border-gray-800 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#e52e2e] flex items-center justify-center font-black text-white text-xs shadow-xs shrink-0">
                    POCHA
                </div>
                <div>
                    <h4 class="font-black text-sm text-white tracking-wide">1988 Pocha Resto</h4>
                    <p class="text-[11px] text-gray-400 font-medium">
                        Order #{{ $order->order_number }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-1">
                <button type="button" 
                        onclick="toggleCustomerChat()" 
                        class="p-1.5 text-gray-400 hover:text-white rounded-lg hover:bg-gray-800 transition" 
                        title="Tutup Chat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Order Context Subheader -->
        <div class="bg-amber-50/90 border-b border-amber-200/80 px-3.5 py-1.5 flex items-center justify-between text-[11px] text-amber-900 shrink-0">
            <div class="flex items-center gap-1.5">
                <span class="font-bold">Status:</span>
                <span class="font-black uppercase tracking-wider text-[#e52e2e]">{{ $order->status_label }}</span>
            </div>
            <span class="text-[10px] text-amber-700 font-medium">Samarinda WITA</span>
        </div>

        <!-- Messages Thread Body -->
        <div id="customer-chat-messages" class="flex-1 p-4 overflow-y-auto space-y-3 bg-[#f8fafc]/90 text-xs">
            <div class="text-center my-2">
                <span class="text-[10px] font-semibold text-gray-400 bg-white border border-gray-200 px-2.5 py-1 rounded-lg shadow-2xs">
                    Percakapan langsung dengan kasir & kurir Pocha
                </span>
            </div>
            <div id="customer-chat-loading" class="text-center py-6 text-gray-400">
                <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menghubungkan ke resto...</span>
            </div>
            <div id="customer-chat-list" class="space-y-3"></div>
        </div>


        <!-- Input Footer -->
        <form id="customer-chat-form" onsubmit="handleCustomerSendMessage(event)" class="p-2.5 sm:p-3 bg-white border-t border-gray-150 flex items-center gap-2 shrink-0">
            <input type="text" 
                   id="customer-chat-input" 
                   placeholder="Tulis pesan ke resto/kurir..." 
                   maxlength="1000"
                   autocomplete="off"
                   class="flex-1 bg-gray-100 border border-gray-200 text-gray-900 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-[#e52e2e]/50 focus:bg-white transition" />
            <button type="submit" 
                    id="customer-chat-submit"
                    class="bg-[#e52e2e] hover:bg-red-700 text-white p-2.5 rounded-xl transition shadow-xs hover:shadow cursor-pointer flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 transform rotate-90" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
            </button>
        </form>
    </div>
</div>

<script>
    (function() {
        const orderNumber = "{{ $order->order_number }}";
        const customerName = "{{ addslashes($order->customer_name) }}";
        let isChatOpen = false;
        let customerPollingTimer = null;
        let lastMessageCount = 0;

        window.toggleCustomerChat = function() {
            const chatWindow = document.getElementById('customer-chat-window');
            const btnWrapper = document.getElementById('customer-chat-btn-wrapper');
            const unreadBadge = document.getElementById('customer-unread-badge');

            if (!isChatOpen) {
                // Open chat
                isChatOpen = true;
                chatWindow.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-4');
                chatWindow.classList.add('opacity-100', 'translate-y-0');
                btnWrapper.classList.add('opacity-0', 'pointer-events-none');
                
                // Reset unread
                unreadBadge.innerText = '0';
                unreadBadge.classList.add('hidden');

                // Load messages & focus
                fetchCustomerMessages(true);
                startCustomerPolling();
                setTimeout(() => {
                    document.getElementById('customer-chat-input')?.focus();
                }, 150);
            } else {
                // Close chat
                isChatOpen = false;
                chatWindow.classList.add('opacity-0', 'pointer-events-none', 'translate-y-4');
                chatWindow.classList.remove('opacity-100', 'translate-y-0');
                btnWrapper.classList.remove('opacity-0', 'pointer-events-none');
                stopCustomerPolling();
            }
        };

        function startCustomerPolling() {
            stopCustomerPolling();
            customerPollingTimer = setInterval(() => {
                fetchCustomerMessages(false);
            }, 3000);
        }

        function stopCustomerPolling() {
            if (customerPollingTimer) {
                clearInterval(customerPollingTimer);
                customerPollingTimer = null;
            }
        }

        function fetchCustomerMessages(shouldScrollToBottom = false) {
            fetch(`/orders/${orderNumber}/messages`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('customer-chat-loading')?.classList.add('hidden');
                    if (!data.messages) return;

                    const listContainer = document.getElementById('customer-chat-list');
                    if (!listContainer) return;

                    const messages = data.messages;
                    const hasNew = messages.length > lastMessageCount;
                    lastMessageCount = messages.length;

                    if (messages.length === 0) {
                        listContainer.innerHTML = `
                            <div class="text-center py-6 text-gray-400">
                                <p class="text-xs">Belum ada percakapan.</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">Kirim pesan pertama ke resto sekarang!</p>
                            </div>
                        `;
                        return;
                    }

                    let html = '';
                    messages.forEach(msg => {
                        const isMe = msg.sender_type === 'customer';
                        const timeStr = msg.time || msg.formatted_time || '';
                        if (isMe) {
                            html += `
                                <div class="flex flex-col items-end">
                                    <div class="max-w-[82%] bg-[#e52e2e] text-white px-3.5 py-2.5 rounded-2xl rounded-tr-xs shadow-xs text-xs leading-relaxed break-words font-medium">
                                        ${escapeHtml(msg.message)}
                                    </div>
                                    <span class="text-[10px] text-gray-400 mt-1 font-medium">${timeStr}</span>
                                </div>
                            `;
                        } else {
                            html += `
                                <div class="flex items-start gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-gray-900 text-white flex items-center justify-center font-black text-[9px] shrink-0 mt-0.5 shadow-xs">
                                        P
                                    </div>
                                    <div class="max-w-[82%]">
                                        <span class="text-[10px] font-bold text-gray-600 block mb-0.5">${escapeHtml(msg.sender_name || 'Resto Pocha')}</span>
                                        <div class="bg-white text-gray-900 border border-gray-200/90 px-3.5 py-2.5 rounded-2xl rounded-tl-xs shadow-xs text-xs leading-relaxed break-words font-medium">
                                            ${escapeHtml(msg.message)}
                                        </div>
                                        <span class="text-[10px] text-gray-400 mt-1 block font-medium">${timeStr}</span>
                                    </div>
                                </div>
                            `;
                        }
                    });

                    listContainer.innerHTML = html;

                    if (shouldScrollToBottom || hasNew) {
                        const scrollContainer = document.getElementById('customer-chat-messages');
                        if (scrollContainer) {
                            scrollContainer.scrollTop = scrollContainer.scrollHeight;
                        }
                    }
                })
                .catch(err => {
                    console.error('Error fetching customer chat:', err);
                });
        }

        window.handleCustomerSendMessage = function(e) {
            e.preventDefault();
            const input = document.getElementById('customer-chat-input');
            const text = (input?.value || '').trim();
            if (!text) return;

            sendChatMessagePayload(text);
            input.value = '';
        };


        function sendChatMessagePayload(text) {
            const submitBtn = document.getElementById('customer-chat-submit');
            if (submitBtn) submitBtn.disabled = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch(`/orders/${orderNumber}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    message: text,
                    sender_name: customerName
                })
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) submitBtn.disabled = false;
                if (data.success) {
                    fetchCustomerMessages(true);
                } else {
                    alert(data.message || 'Gagal mengirim pesan.');
                }
            })
            .catch(err => {
                if (submitBtn) submitBtn.disabled = false;
                console.error(err);
                alert('Gagal terhubung ke server.');
            });
        }

        function escapeHtml(string) {
            const div = document.createElement('div');
            div.innerText = string;
            return div.innerHTML;
        }

        // Cek secara berkala apakah ada pesan masuk saat chat tertutup
        setInterval(() => {
            if (!isChatOpen) {
                fetch(`/orders/${orderNumber}/messages?peek=1`)
                    .then(res => res.json())
                    .then(data => {
                        const badge = document.getElementById('customer-unread-badge');
                        if (data.unread_for_customer > 0) {
                            if (badge) {
                                badge.innerText = data.unread_for_customer;
                                badge.classList.remove('hidden');
                            }
                        } else {
                            if (badge) {
                                badge.classList.add('hidden');
                            }
                        }
                    })
                    .catch(() => {});
            }
        }, 4000);
    })();
</script>
