<?php
$pageTitle = 'Dashboard';
include 'includes/header.php';
?>
<style>
    .stat-card{padding:1.5rem;display:flex;align-items:center;gap:1rem}
    .stat-icon{width:50px;height:50px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem}
    .stat-value{font-size:1.75rem;font-weight:800;line-height:1}
    .stat-label{font-size:0.8rem;color:var(--gray-500);margin-top:3px}
    .section-header{padding:16px 20px;border-bottom:1px solid var(--gray-200);font-weight:700;font-size:0.9rem;display:flex;justify-content:space-between;align-items:center}
    .qa{display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid #f3f4f6;transition:background 0.15s;cursor:pointer;text-decoration:none;color:inherit}
    .qa:hover{background:#f9fafb}
    .qa-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
    .order-row{display:flex;justify-content:space-between;align-items:center;padding:12px 20px;border-bottom:1px solid #f3f4f6;font-size:0.85rem}
    .msg-row{padding:10px 16px;border-bottom:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;font-size:0.82rem}
    .quick-form input,.quick-form select{margin-bottom:8px;width:100%}
</style>

<div class="container" style="margin-top:-2.5rem;padding-bottom:3rem">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem">
        <div class="card stat-card"><div class="stat-icon" style="background:#fee2e2;color:#dc2626">P</div><div><div class="stat-value" id="statProducts">-</div><div class="stat-label">Toplam Urun</div></div></div>
        <div class="card stat-card"><div class="stat-icon" style="background:#dbeafe;color:#2563eb">S</div><div><div class="stat-value" id="statOrders">-</div><div class="stat-label">Toplam Siparis</div></div></div>
        <div class="card stat-card"><div class="stat-icon" style="background:#fef3c7;color:#d97706">B</div><div><div class="stat-value" id="statPending">-</div><div class="stat-label">Bekleyen Siparis</div></div></div>
        <div class="card stat-card"><div class="stat-icon" style="background:#d1fae5;color:#059669">M</div><div><div class="stat-value" id="statMessages">-</div><div class="stat-label">Okunmamis Mesaj</div></div></div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem">
        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="section-header">Son Siparisler <a href="/admin/orders" style="font-size:0.75rem;color:var(--primary)">Hepsini Gor</a></div>
                <div id="latestOrders"></div>
            </div>
            <div class="card">
                <div class="section-header">Hizli Urun Ekle</div>
                <div style="padding:20px">
                    <form class="quick-form" onsubmit="quickProduct(event)">
                        <input class="form-input" placeholder="Urun Adi" id="qName" required>
                        <input class="form-input" type="number" placeholder="Fiyat" id="qPrice" required>
                        <button class="btn-primary" style="width:100%">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div class="card" style="margin-bottom:1.5rem">
                <div class="section-header">Son Mesajlar <a href="/admin/messages" style="font-size:0.75rem;color:var(--primary)">Hepsini Gor</a></div>
                <div id="latestMessages"></div>
            </div>
            <div class="card" style="padding:20px">
                 <div style="font-weight:700;font-size:0.9rem;margin-bottom:10px">Sistem Durumu</div>
                 <div style="display:flex;justify-content:space-between;font-size:0.8rem;margin-bottom:6px;color:var(--gray-500)"><span>Veritabani</span><span style="color:#059669">Bagli</span></div>
                 <div style="display:flex;justify-content:space-between;font-size:0.8rem;margin-bottom:6px;color:var(--gray-500)"><span>Versiyon</span><span>v1.0.4</span></div>
                 <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:var(--gray-500)"><span>Sunucu</span><span>PHP 8.x</span></div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('headerDesc').textContent = new Date().toLocaleDateString('tr-TR', {weekday:'long', year:'numeric', month:'long', day:'numeric'});

function loadStats() {
    fetch('/api/admin/settings.php?action=stats').then(r=>r.json()).then(data => {
        document.getElementById('statProducts').textContent = data.products || 0;
        document.getElementById('statOrders').textContent = data.orders || 0;
        document.getElementById('statPending').textContent = data.pending || 0;
        document.getElementById('statMessages').textContent = data.messages || 0;
    });
}

function loadRecent() {
    // Son siparisler
    fetch('/api/orders.php?limit=5').then(r=>r.json()).then(data => {
        const wrap = document.getElementById('latestOrders');
        if(!data.length) { wrap.innerHTML = '<div style="padding:20px;color:var(--gray-400);font-size:0.8rem">Siparis yok</div>'; return; }
        wrap.innerHTML = data.map(o => `
            <div class="order-row">
                <span style="font-weight:600">#${o.order_code}</span>
                <span style="color:var(--gray-500)">${o.customer_name}</span>
                <span style="font-weight:700">TL ${parseFloat(o.total_amount).toLocaleString()}</span>
                <span class="badge ${o.status==='completed'?'badge-success':'badge-pending'}">${o.status}</span>
            </div>
        `).join('');
    });
}

function quickProduct(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button');
    btn.disabled = true; btn.textContent = 'Kaydediliyor...';
    // Buraya API call gelecek
    setTimeout(() => {
        alert('Hizli urun ekleme henuz aktif degil.');
        btn.disabled = false; btn.textContent = 'Kaydet';
        e.target.reset();
    }, 500);
}

loadStats();
loadRecent();
</script>
</body>
</html>
