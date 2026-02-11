<?php
$pageTitle = 'Canlı Destek';
$pageDesc = 'Müşterilerden gelen mesajları anlık olarak yanıtlayın.';
include 'includes/header.php';
?>

<div class="container" style="margin-top:-2.5rem;padding-bottom:3rem">
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
        fetch(API_BASE + '/admin/messages').then(r=>r.json()).then(custs => {
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
        fetch('/api/messages?customerId='+id).then(r=>r.json()).then(msgs => {
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
        fetch(API_BASE + '/admin/messages',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({customerId:selectedCust,message:input.value})})
        .then(()=>{input.value='';loadChat(selectedCust);loadCustomers();});
    }

    loadCustomers();
    setInterval(loadCustomers, 10000);
    setInterval(()=>{if(selectedCust)loadChat(selectedCust);}, 5000);
    </script>
</body>
</html>
