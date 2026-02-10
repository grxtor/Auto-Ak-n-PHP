<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesajlar - Auto Akın</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>.admin-nav{background:#0f172a;border-bottom:none;padding:0}.admin-nav .container{display:flex;justify-content:space-between;align-items:center;height:56px}.admin-nav .nav-right{display:flex;align-items:center;gap:1.5rem}.admin-nav .nav-link{color:#94a3b8;font-size:0.82rem;transition:color 0.2s;font-weight:500}.admin-nav .nav-link:hover{color:white}.admin-nav .nav-link.active{color:white}</style>
</head>
<body style="background:#f8fafc">
    <nav class="navbar admin-nav"><div class="container"><a href="/admin/dashboard" class="logo" style="color:white;font-size:1.1rem">AUTO <span style="color:#ef4444">AKIN</span> <span style="font-size:0.6rem;color:#475569;background:#1e293b;padding:3px 8px;border-radius:4px;margin-left:6px">PANEL</span></a><div class="nav-right"><a href="/admin/dashboard" class="nav-link">Dashboard</a><a href="/admin/products" class="nav-link">Urunler</a><a href="/admin/vehicles" class="nav-link">Araclar</a><a href="/admin/orders" class="nav-link">Siparisler</a><a href="/admin/messages" class="nav-link active">Mesajlar</a><div style="width:1px;height:24px;background:#334155"></div><a href="/" target="_blank" class="nav-link">Siteyi Gor</a></div></div></nav>

    <div class="container" style="padding-top:2rem;padding-bottom:3rem">
        <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:1.5rem">Canlı <span class="text-red">Destek</span></h1>

        <div style="display:grid;grid-template-columns:280px 1fr;gap:1rem;height:500px">
            <!-- Müşteri Listesi -->
            <div class="card" style="overflow-y:auto">
                <div style="padding:12px 16px;border-bottom:1px solid var(--gray-200);font-size:0.85rem;font-weight:700">Sohbetler <span id="custCount"></span></div>
                <div id="custList"></div>
            </div>

            <!-- Sohbet -->
            <div class="card" style="display:flex;flex-direction:column">
                <div id="chatHeader" style="padding:12px 16px;border-bottom:1px solid var(--gray-200);font-weight:600;font-size:0.85rem;display:none"></div>
                <div id="chatArea" style="flex:1;padding:16px;overflow-y:auto;display:flex;flex-direction:column;gap:8px"></div>
                <div id="chatEmpty" style="flex:1;display:flex;align-items:center;justify-content:center;color:var(--gray-500);font-size:0.9rem">Bir sohbet seçin</div>
                <form id="replyForm" onsubmit="sendReply(event)" style="padding:12px 16px;border-top:1px solid var(--gray-200);display:none;gap:8px">
                    <input class="form-input" id="replyInput" placeholder="Cevabınızı yazın..." style="flex:1">
                    <button type="submit" class="btn-primary">Gönder</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    if(!localStorage.getItem('admin_auth'))window.location='/admin/login';

    let selectedCust = null;

    function loadCustomers() {
        fetch('/api/admin/messages').then(r=>r.json()).then(custs => {
            document.getElementById('custCount').textContent = `(${custs.length})`;
            document.getElementById('custList').innerHTML = custs.map(c => `
                <div onclick="selectCustomer('${c.customer_identifier}')" style="padding:10px 16px;cursor:pointer;font-size:0.85rem;border-bottom:1px solid var(--gray-100);background:${selectedCust===c.customer_identifier?'#fef2f2':'transparent'};border-left:3px solid ${selectedCust===c.customer_identifier?'var(--primary)':'transparent'}">
                    <div style="font-weight:600;display:flex;justify-content:space-between">
                        <span>${c.customer_identifier}</span>
                        ${parseInt(c.unread_count)>0?`<span style="background:var(--primary);color:white;border-radius:50%;width:18px;height:18px;display:flex;align-items:center;justify-content:center;font-size:0.65rem">${c.unread_count}</span>`:''}
                    </div>
                    <div style="font-size:0.7rem;color:var(--gray-500)">${new Date(c.last_msg).toLocaleString('tr-TR')}</div>
                </div>
            `).join('');
        });
    }

    function selectCustomer(id) {
        selectedCust = id;
        document.getElementById('chatHeader').style.display='block';
        document.getElementById('chatHeader').textContent=id;
        document.getElementById('chatEmpty').style.display='none';
        document.getElementById('chatArea').style.display='flex';
        document.getElementById('replyForm').style.display='flex';
        loadChat(id);
        loadCustomers();
    }

    function loadChat(id) {
        fetch('/api/messages.php?customerId='+id).then(r=>r.json()).then(msgs => {
            const area = document.getElementById('chatArea');
            area.innerHTML = msgs.map(m => `
                <div style="align-self:${m.sender==='admin'?'flex-end':'flex-start'};background:${m.sender==='admin'?'var(--secondary)':'var(--gray-100)'};color:${m.sender==='admin'?'white':'var(--foreground)'};padding:8px 12px;border-radius:10px;max-width:70%;font-size:0.85rem">
                    ${m.message}
                </div>
            `).join('');
            area.scrollTop = area.scrollHeight;
        });
    }

    function sendReply(e) {
        e.preventDefault();
        const input = document.getElementById('replyInput');
        if(!input.value.trim()||!selectedCust) return;
        fetch('/api/admin/messages',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({customerId:selectedCust,message:input.value})})
        .then(()=>{input.value='';loadChat(selectedCust);loadCustomers();});
    }

    loadCustomers();
    setInterval(loadCustomers, 10000);
    setInterval(()=>{if(selectedCust)loadChat(selectedCust);}, 5000);
    </script>
</body>
</html>
