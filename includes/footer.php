    <?php
    // Fetch settings for the footer from DB
    $db = getDB();
    $settings = [];
    try {
        $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    } catch (Exception $e) {}

    // Fallbacks
    $fEmail = $settings['site_email'] ?? 'info@autoakin.com';
    $fPhone = $settings['site_phone'] ?? '+90 5xx xxx xx xx';
    $fWhatsapp = $settings['site_whatsapp'] ?? '+90 5xx xxx xx xx';
    ?>
    <footer class="footer" style="background:#0f172a; color: white; padding: 4rem 0 2rem; margin-top: 5rem; border-top: 4px solid var(--primary);">
        <div class="container">
            <div class="footer-grid" style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 4rem;">
                <div>
                    <h3 style="font-size: 1.5rem; font-weight: 1000; margin-bottom: 1rem;">AUTO <span style="color:var(--primary)">AKIN</span></h3>
                    <p style="opacity:0.6;font-size:0.9rem;line-height:1.7; max-width: 400px;">Hyundai ve Kia araçlarınız için 20 yıldır güvenilir yedek parça adresi. Orijinal ve garantili ürünlerle aracınızın yanındayız.</p>
                    <div style="display: flex; gap: 15px; margin-top: 2rem;">
                         <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
                         <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;"><i class="fab fa-instagram"></i></a>
                         <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $fWhatsapp) ?>" target="_blank" style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #25d366; text-decoration: none;"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div>
                    <h4 style="font-size: 1rem; font-weight: 800; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">Hızlı Linkler</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 12px;"><a href="<?= BASE_URL ?>/parts" style="color: #94a3b8; text-decoration: none; font-size: 0.95rem; font-weight: 600;">Tüm Parçalar</a></li>
                        <li style="margin-bottom: 12px;"><a href="<?= BASE_URL ?>/cart" style="color: #94a3b8; text-decoration: none; font-size: 0.95rem; font-weight: 600;">Sepetim</a></li>
                        <li style="margin-bottom: 12px;"><a href="<?= BASE_URL ?>/login" style="color: #94a3b8; text-decoration: none; font-size: 0.95rem; font-weight: 600;">Hesabım</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 1rem; font-weight: 800; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px;">İletişim</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="color: #94a3b8; font-size: 0.95rem; margin-bottom: 10px;"><i class="fas fa-envelope" style="color: var(--primary); margin-right: 10px;"></i> <?= $fEmail ?></li>
                        <li style="color: #94a3b8; font-size: 0.95rem; margin-bottom: 10px;"><i class="fas fa-phone" style="color: var(--primary); margin-right: 10px;"></i> <?= $fPhone ?></li>
                        <li style="color: #94a3b8; font-size: 0.95rem; margin-bottom: 10px;"><i class="fab fa-whatsapp" style="color: #25d366; margin-right: 10px;"></i> <?= $fWhatsapp ?></li>
                        <li style="color: #94a3b8; font-size: 0.95rem; margin-bottom: 10px;"><i class="fas fa-money-bill-transfer" style="color: var(--primary); margin-right: 10px;"></i> IBAN ile Güvenli Ödeme</li>
                    </ul>
                </div>
            </div>
            <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.05); text-align: center; color: #64748b; font-size: 0.85rem; font-weight: 600;">
                &copy; <?= date('Y') ?> Auto Akın. Tüm hakları saklıdır.
            </div>
        </div>
    </footer>

    <!-- Canlı Destek Widget - Premium Redesign -->
    <div id="chatWrapper" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999; font-family: 'Inter', sans-serif;">
        <button class="chat-toggle-btn" id="chatToggle" onclick="toggleChat()" style="width: 65px; height: 65px; background: var(--primary); color: white; border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; box-shadow: 0 10px 40px rgba(220, 38, 38, 0.4); transition: all 0.3s;">
            <i class="fas fa-comments"></i>
        </button>

        <div class="chat-container" id="chatBox" style="position: absolute; bottom: 85px; right: 0; width: 380px; height: 500px; background: white; border-radius: 24px; box-shadow: 0 25px 60px rgba(0,0,0,0.15); display: none; flex-direction: column; overflow: hidden; transform-origin: bottom right; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
            <div class="chat-header" style="background: var(--secondary); color: white; padding: 25px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight:900; font-size: 1.1rem; letter-spacing: -0.5px;" id="chatTitle">Canlı Destek</div>
                    <div style="font-size: 0.75rem; opacity: 0.7; margin-top: 4px;"><span style="display:inline-block; width:6px; height:6px; background:#22c55e; border-radius:50%; margin-right:5px;"></span> Çevrimiçi</div>
                </div>
                <button onclick="toggleChat()" style="background:rgba(255,255,255,0.1); border:none; color:white; cursor:pointer; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center;"><i class="fas fa-times"></i></button>
            </div>
            
            <div id="adminCustomerList" style="display:none; flex:1; overflow-y:auto; background: #f8fafc;"></div>
            
            <div class="chat-body" id="chatMessages" style="flex: 1; padding: 20px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 12px;">
                <div style="text-align:center; padding: 2rem 1rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">👋</div>
                    <h4 style="margin:0; font-weight:800; color:var(--secondary);">Size nasıl yardımcı olabiliriz?</h4>
                    <p style="font-size: 0.8rem; color: var(--gray-500); margin-top: 8px;">Müşteri temsilcilerimiz şuan aktif ve yardıma hazır.</p>
                </div>
            </div>

            <form class="chat-footer" onsubmit="sendMessage(event)" id="chatForm" style="padding: 20px; background: white; border-top: 1px solid var(--gray-100); display: flex; gap: 10px;">
                <input type="text" id="chatInput" style="flex: 1; padding: 12px 18px; border: 1.5px solid var(--gray-100); border-radius: 14px; font-size: 0.9rem; font-weight: 500; outline: none; transition: all 0.2s;" placeholder="Mesajınızı yazın..." onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--gray-100)'">
                <button type="submit" style="background: var(--primary); color: white; border: none; width: 44px; height: 44px; border-radius: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; transition: all 0.2s;"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>
    </div>

    <style>
    .chat-msg { max-width: 80%; padding: 12px 16px; border-radius: 18px; font-size: 0.88rem; font-weight: 500; line-height: 1.4; position: relative; }
    .chat-msg.customer { align-self: flex-end; background: var(--primary); color: white; border-bottom-right-radius: 4px; }
    .chat-msg.admin { align-self: flex-start; background: white; color: var(--secondary); border-bottom-left-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .chat-toggle-btn:hover { transform: scale(1.1) rotate(5deg); }
    </style>

    <script>
    const isAdmin = !!localStorage.getItem('admin_auth');
    let activeCustomer = null;

    function getChatId() {
        let id = localStorage.getItem('autoakin_chat_id');
        if (!id) { id = 'cust_' + Math.random().toString(36).substr(2, 9); localStorage.setItem('autoakin_chat_id', id); }
        return id;
    }

    function toggleChat() {
        const box = document.getElementById('chatBox');
        if (box.style.display === 'none' || box.style.display === '') {
            box.style.display = 'flex';
            if (isAdmin) { loadAdminCustomers(); } else { loadMessages(); }
        } else {
            box.style.display = 'none';
        }
    }

    function loadMessages() {
        fetch(API_BASE + '/messages?customerId=' + getChatId())
        .then(r => r.json()).then(msgs => {
            const el = document.getElementById('chatMessages');
            if (!Array.isArray(msgs) || msgs.length === 0) return;
            el.innerHTML = msgs.map(m => `<div class="chat-msg ${m.sender}">${m.message}</div>`).join('');
            el.scrollTop = el.scrollHeight;
        }).catch(e => console.error('Chat error:', e));
    }

    function loadAdminCustomers() {
        document.getElementById('chatTitle').textContent = 'Admin Destek';
        document.getElementById('adminCustomerList').style.display = 'block';
        document.getElementById('chatMessages').style.display = 'none';
        document.getElementById('chatForm').style.display = 'none';

        fetch(API_BASE + '/admin/messages').then(r => r.json()).then(customers => {
            const el = document.getElementById('adminCustomerList');
            if (!Array.isArray(customers) || customers.length === 0) {
                el.innerHTML = '<div style="padding:4rem;text-align:center;color:var(--gray-400);font-size:0.9rem">Henüz aktif görüşme yok.</div>';
                return;
            }
            el.innerHTML = customers.map(c => `
                <div onclick="openAdminChat('${c.customer_identifier}')" style="padding:18px 25px; border-bottom:1px solid #f1f5f9; cursor:pointer; display:flex; justify-content:space-between; align-items:center; transition:all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                    <div style="display:flex; align-items:center; gap:12px">
                        <div style="width:40px; height:40px; background:#e2e8f0; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#64748b; font-weight:700">${c.customer_identifier.substring(5,7).toUpperCase()}</div>
                        <div>
                            <div style="font-weight:800; font-size:0.85rem; color:var(--secondary)">${c.customer_identifier.substring(0,16)}</div>
                            <div style="font-size:0.7rem; color:var(--gray-400)">Müşteri</div>
                        </div>
                    </div>
                    ${parseInt(c.unread_count) > 0 ? `<span style="background:var(--primary); color:white; border-radius:50%; min-width:22px; height:22px; display:flex; align-items:center; justify-content:center; font-size:0.65rem; font-weight:800">${c.unread_count}</span>` : ''}
                </div>
            `).join('');
        }).catch(e => console.error('Admin chat list error:', e));
    }

    function openAdminChat(customerId) {
        activeCustomer = customerId;
        document.getElementById('chatTitle').textContent = 'Müşteri Sohbeti';
        document.getElementById('adminCustomerList').style.display = 'none';
        document.getElementById('chatMessages').style.display = 'flex';
        document.getElementById('chatForm').style.display = 'flex';
        loadAdminMessages(customerId);
    }

    function loadAdminMessages(customerId) {
        fetch(API_BASE + '/messages?customerId=' + customerId)
        .then(r => r.json()).then(msgs => {
            const el = document.getElementById('chatMessages');
            el.innerHTML = msgs.map(m => `<div class="chat-msg ${m.sender}">${m.message}</div>`).join('');
            el.scrollTop = el.scrollHeight;
        }).catch(e => console.error('Admin message load error:', e));
    }

    function sendMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chatInput');
        const msg = input.value.trim();
        if (!msg) return;

        const body = isAdmin && activeCustomer ? 
            { action: 'reply', message: msg, customerId: activeCustomer } : 
            { message: msg, customerId: getChatId() };

        const url = isAdmin && activeCustomer ? API_BASE + '/admin/messages?action=reply' : API_BASE + '/messages';

        fetch(url, {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify(body)
        }).then(() => {
            input.value = '';
            if (isAdmin && activeCustomer) loadAdminMessages(activeCustomer);
            else loadMessages();
        }).catch(e => console.error('Send message error:', e));
    }

    setInterval(() => {
        if (document.getElementById('chatBox').style.display !== 'flex') return;
        if (isAdmin && activeCustomer) loadAdminMessages(activeCustomer);
        else if (isAdmin) loadAdminCustomers();
        else loadMessages();
    }, 4000);
    </script>
