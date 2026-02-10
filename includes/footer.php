    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h3 class="text-red">Auto Akın</h3>
                    <p style="opacity:0.7;font-size:0.85rem;line-height:1.6">Hyundai ve Kia araçlarınız için güvenilir yedek parça adresi.</p>
                </div>
                <div>
                    <h4>Hızlı Linkler</h4>
                    <ul>
                        <li><a href="/parts">Tüm Parçalar</a></li>
                        <li><a href="/cart">Sepetim</a></li>
                    </ul>
                </div>
                <div>
                    <h4>İletişim</h4>
                    <ul>
                        <li>E-posta: info@autoakin.com</li>
                        <li>WhatsApp: +90 5xx xxx xx xx</li>
                        <li>Ödeme: IBAN (Havale/EFT)</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">&copy; <?= date('Y') ?> Auto Akın. Tüm hakları saklıdır.</div>
        </div>
    </footer>

    <!-- Canlı Destek Widget -->
    <button class="chat-btn" id="chatToggle" onclick="toggleChat()">&#128172;</button>
    <div class="chat-box" id="chatBox">
        <div class="chat-header">
            <span style="font-weight:700" id="chatTitle">Canlı Destek</span>
            <button onclick="toggleChat()" style="background:none;border:none;color:white;cursor:pointer;font-size:1rem">&times;</button>
        </div>
        <!-- Admin: müşteri listesi -->
        <div id="adminCustomerList" style="display:none;flex:1;overflow-y:auto"></div>
        <!-- Normal chat alanı -->
        <div class="chat-messages" id="chatMessages">
            <p style="text-align:center;color:var(--gray-500);font-size:0.8rem;margin-top:1rem">Size nasıl yardımcı olabiliriz?</p>
        </div>
        <form class="chat-form" onsubmit="sendMessage(event)" id="chatForm">
            <input type="text" id="chatInput" class="form-input" placeholder="Mesajınızı yazın..." style="flex:1;font-size:0.8rem">
            <button type="submit" class="btn-primary btn-sm">Gönder</button>
        </form>
    </div>

    <script>
    // Cart helper
    const Cart = {
        get() { return JSON.parse(localStorage.getItem('autoakin_cart') || '[]'); },
        set(items) { localStorage.setItem('autoakin_cart', JSON.stringify(items)); this.updateBadge(); },
        add(product) {
            let items = this.get();
            const idx = items.findIndex(i => i.id === product.id);
            if (idx > -1) items[idx].quantity++;
            else items.push({ ...product, quantity: 1 });
            this.set(items);
        },
        remove(id) { this.set(this.get().filter(i => i.id !== id)); },
        updateQty(id, qty) {
            let items = this.get();
            const idx = items.findIndex(i => i.id === id);
            if (idx > -1) { items[idx].quantity = Math.max(1, qty); this.set(items); }
        },
        count() { return this.get().reduce((sum, i) => sum + i.quantity, 0); },
        total() { return this.get().reduce((sum, i) => sum + (i.price * i.quantity), 0); },
        clear() { this.set([]); },
        updateBadge() {
            const badge = document.getElementById('nav-cart-count');
            if (!badge) return;
            const c = this.count();
            badge.textContent = c;
            badge.style.display = c > 0 ? 'flex' : 'none';
        }
    };
    document.addEventListener('DOMContentLoaded', () => Cart.updateBadge());

    // Chat - Admin mod kontrolü
    const isAdmin = !!localStorage.getItem('admin_auth');
    let activeCustomer = null;

    function getChatId() {
        let id = localStorage.getItem('autoakin_chat_id');
        if (!id) { id = 'cust_' + Math.random().toString(36).substr(2, 9); localStorage.setItem('autoakin_chat_id', id); }
        return id;
    }

    function toggleChat() {
        const box = document.getElementById('chatBox');
        const btn = document.getElementById('chatToggle');
        if (box.classList.contains('open')) {
            box.classList.remove('open'); btn.style.display = 'flex';
        } else {
            box.classList.add('open'); btn.style.display = 'none';
            if (isAdmin) { loadAdminCustomers(); } else { loadMessages(); }
        }
    }

    // Normal müşteri mesajları
    function loadMessages() {
        fetch('/api/messages.php?customerId=' + getChatId())
        .then(r => r.json()).then(msgs => {
            const el = document.getElementById('chatMessages');
            if (!Array.isArray(msgs) || msgs.length === 0) {
                el.innerHTML = '<p style="text-align:center;color:var(--gray-500);font-size:0.8rem;margin-top:1rem">Size nasıl yardımcı olabiliriz?</p>';
                return;
            }
            el.innerHTML = msgs.map(m => `<div class="chat-msg ${m.sender}">${m.message}</div>`).join('');
            el.scrollTop = el.scrollHeight;
        });
    }

    // Admin: müşteri listesi
    function loadAdminCustomers() {
        document.getElementById('chatTitle').textContent = 'Destek Paneli';
        document.getElementById('adminCustomerList').style.display = 'block';
        document.getElementById('chatMessages').style.display = 'none';
        document.getElementById('chatForm').style.display = 'none';

        fetch('/api/admin/messages.php').then(r => r.json()).then(customers => {
            const el = document.getElementById('adminCustomerList');
            if (!Array.isArray(customers) || customers.length === 0) {
                el.innerHTML = '<div style="padding:2rem;text-align:center;color:var(--gray-500);font-size:0.8rem">Henüz mesaj yok</div>';
                return;
            }
            el.innerHTML = customers.map(c => `
                <div onclick="openAdminChat('${c.customer_identifier}')" style="padding:12px 16px;border-bottom:1px solid #f3f4f6;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-size:0.82rem;transition:background 0.15s" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <span style="font-weight:600">${c.customer_identifier.substring(0,16)}</span>
                    ${parseInt(c.unread_count) > 0 ? `<span style="background:#dc2626;color:white;border-radius:50%;min-width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:700">${c.unread_count}</span>` : '<span style="color:#059669;font-size:0.7rem">OK</span>'}
                </div>
            `).join('');
        });
    }

    // Admin: müşteri sohbetini aç
    function openAdminChat(customerId) {
        activeCustomer = customerId;
        document.getElementById('chatTitle').textContent = customerId.substring(0, 12) + '...';
        document.getElementById('adminCustomerList').style.display = 'none';
        document.getElementById('chatMessages').style.display = 'flex';
        document.getElementById('chatForm').style.display = 'flex';
        document.getElementById('chatInput').placeholder = 'Admin olarak yanıtla...';

        // Geri butonu ekle
        const header = document.querySelector('.chat-header');
        if (!document.getElementById('chatBack')) {
            const backBtn = document.createElement('button');
            backBtn.id = 'chatBack';
            backBtn.innerHTML = '&larr;';
            backBtn.style.cssText = 'background:none;border:none;color:white;cursor:pointer;font-size:1.1rem;margin-right:8px';
            backBtn.onclick = function() { activeCustomer = null; loadAdminCustomers(); this.remove(); };
            header.insertBefore(backBtn, header.firstChild);
        }

        loadAdminMessages(customerId);
    }

    function loadAdminMessages(customerId) {
        fetch('/api/messages.php?customerId=' + customerId)
        .then(r => r.json()).then(msgs => {
            const el = document.getElementById('chatMessages');
            if (!Array.isArray(msgs) || msgs.length === 0) {
                el.innerHTML = '<p style="text-align:center;color:var(--gray-500);font-size:0.8rem;margin-top:1rem">Bu müşteriden henüz mesaj yok</p>';
                return;
            }
            el.innerHTML = msgs.map(m => `<div class="chat-msg ${m.sender}">${m.message}</div>`).join('');
            el.scrollTop = el.scrollHeight;
        });
    }

    function sendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        if (!input.value.trim()) return;

        if (isAdmin && activeCustomer) {
            // Admin olarak gönder
            fetch('/api/admin/messages.php?action=reply', {
                method: 'POST', headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ message: input.value, customerId: activeCustomer })
            }).then(() => { input.value = ''; loadAdminMessages(activeCustomer); });
        } else {
            // Müşteri olarak gönder
            fetch('/api/messages.php', {
                method: 'POST', headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ message: input.value, customerId: getChatId() })
            }).then(() => { input.value = ''; loadMessages(); });
        }
    }

    // Polling
    setInterval(() => {
        if (!document.getElementById('chatBox').classList.contains('open')) return;
        if (isAdmin && activeCustomer) loadAdminMessages(activeCustomer);
        else if (isAdmin) loadAdminCustomers();
        else loadMessages();
    }, 4000);
    </script>
</body>
</html>
