<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Auto Akın Yönetimi</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body{background:#f8fafc}
        .admin-nav{background:#0f172a;border-bottom:none;padding:0}
        .admin-nav .container{display:flex;justify-content:space-between;align-items:center;height:56px}
        .admin-nav .nav-right{display:flex;align-items:center;gap:1.5rem}
        .admin-nav .nav-link{color:#94a3b8;font-size:0.82rem;transition:color 0.2s;font-weight:500}
        .admin-nav .nav-link:hover{color:white}
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
</head>
<body>
<nav class="navbar admin-nav">
    <div class="container">
        <a href="/admin/dashboard" class="logo" style="color:white;font-size:1.1rem">AUTO <span style="color:#ef4444">AKIN</span> <span style="font-size:0.6rem;color:#475569;background:#1e293b;padding:3px 8px;border-radius:4px;margin-left:6px">PANEL</span></a>
        <div class="nav-right">
            <a href="/admin/dashboard" class="nav-link" style="color:white">Dashboard</a>
            <a href="/admin/products" class="nav-link">Urunler</a>
            <a href="/admin/vehicles" class="nav-link">Araclar</a>
            <a href="/admin/orders" class="nav-link">Siparisler</a>
            <a href="/admin/messages" class="nav-link">Mesajlar</a>
            <div style="width:1px;height:24px;background:#334155"></div>
            <a href="/" target="_blank" class="nav-link">Siteyi Gor</a>
            <button onclick="localStorage.removeItem('admin_auth');window.location='/'" style="border:1px solid #334155;background:transparent;color:#94a3b8;padding:5px 12px;border-radius:4px;font-size:0.78rem;cursor:pointer">Cikis</button>
        </div>
    </div>
</nav>

<div style="background:linear-gradient(135deg,#0f172a,#1e293b);padding:2rem 0 3.5rem;color:white">
    <div class="container">
        <h1 style="font-size:1.5rem;font-weight:800">Hos Geldiniz</h1>
        <p style="color:#64748b;font-size:0.88rem" id="currentDate"></p>
    </div>
</div>

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
                <div class="section-header">Hizli Islemler</div>
                <a href="/admin/products" class="qa"><div class="qa-icon" style="background:#fee2e2;color:#dc2626">P</div><div><div style="font-weight:700;font-size:0.88rem">Urun Yonetimi</div><div style="font-size:0.78rem;color:var(--gray-500)">Yedek parca ekle, duzenle, stok guncelle</div></div></a>
                <a href="/admin/vehicles" class="qa"><div class="qa-icon" style="background:#dbeafe;color:#2563eb">A</div><div><div style="font-weight:700;font-size:0.88rem">Arac Yonetimi</div><div style="font-size:0.78rem;color:var(--gray-500)">Marka, model ve motor varyantlari yonet</div></div></a>
                <a href="/admin/orders" class="qa"><div class="qa-icon" style="background:#fef3c7;color:#d97706">S</div><div><div style="font-weight:700;font-size:0.88rem">Siparis Takibi</div><div style="font-size:0.78rem;color:var(--gray-500)">IBAN odemelerini kontrol et</div></div></a>
                <a href="/admin/messages" class="qa"><div class="qa-icon" style="background:#d1fae5;color:#059669">D</div><div><div style="font-weight:700;font-size:0.88rem">Canli Destek</div><div style="font-size:0.78rem;color:var(--gray-500)">Musteri mesajlarini yanitla</div></div></a>
            </div>
            <div class="card">
                <div class="section-header">Son Siparisler <a href="/admin/orders" style="font-size:0.75rem;color:#dc2626;font-weight:600">Tumunu Gor</a></div>
                <div id="recentOrders"><div style="padding:3rem;text-align:center;color:var(--gray-500);font-size:0.85rem">Yukleniyor...</div></div>
            </div>
        </div>
        <div>
            <div class="card" style="padding:1.5rem;margin-bottom:1.25rem;background:linear-gradient(135deg,#1e293b,#0f172a);color:white;border:none">
                <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:1rem">Magaza Bilgileri</h3>
                <div style="font-size:0.82rem;color:#94a3b8;line-height:2.2">
                    <div>autoakin.com.tr</div><div>info@autoakin.com</div><div>Odeme: IBAN</div><div>Markalar: Hyundai, Kia</div>
                </div>
            </div>
            <div class="card" style="padding:1.5rem;margin-bottom:1.25rem">
                <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:1rem">Hizli Urun Ekle</h3>
                <form onsubmit="quickAdd(event)" class="quick-form">
                    <input class="form-input" id="qName" placeholder="Urun adi *" required>
                    <div style="display:flex;gap:8px"><input class="form-input" id="qPrice" type="number" step="0.01" placeholder="Fiyat (TL) *" required><input class="form-input" id="qStock" type="number" placeholder="Stok *" required></div>
                    <input class="form-input" id="qOem" placeholder="OEM No">
                    <input class="form-input" id="qBrand" placeholder="Parca Markasi">
                    <select class="form-select" id="qCategory" style="width:100%;margin-bottom:8px"><option value="">Kategori</option></select>
                    <button type="submit" class="btn-primary" style="width:100%">Ekle</button>
                </form>
                <div id="quickAddResult" style="margin-top:8px;font-size:0.8rem;text-align:center;display:none"></div>
            </div>
            <div class="card">
                <div class="section-header" style="font-size:0.85rem">Son Mesajlar <a href="/admin/messages" style="font-size:0.75rem;color:#dc2626;font-weight:600">Tumu</a></div>
                <div id="recentMessages"><div style="padding:1.5rem;text-align:center;color:var(--gray-500);font-size:0.82rem">Yukleniyor...</div></div>
            </div>
        </div>
    </div>
</div>

<script>
if(!localStorage.getItem('admin_auth'))window.location='/admin/login';
document.getElementById('currentDate').textContent=new Date().toLocaleDateString('tr-TR',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
var sM={pending:'badge-pending',verified:'badge-verified',shipped:'badge-shipped',delivered:'badge-delivered',cancelled:'badge-cancelled'};
var sL={pending:'Beklemede',verified:'Onaylandi',shipped:'Kargoda',delivered:'Teslim',cancelled:'Iptal'};
fetch('/api/admin/products').then(r=>r.json()).then(p=>document.getElementById('statProducts').textContent=Array.isArray(p)?p.length:0).catch(()=>document.getElementById('statProducts').textContent='0');
fetch('/api/admin/orders').then(r=>r.json()).then(o=>{if(!Array.isArray(o)){document.getElementById('statOrders').textContent='0';document.getElementById('statPending').textContent='0';document.getElementById('recentOrders').innerHTML='<div style="padding:3rem;text-align:center;color:var(--gray-500)">Henuz siparis yok</div>';return;}document.getElementById('statOrders').textContent=o.length;document.getElementById('statPending').textContent=o.filter(x=>x.status==='pending').length;var r=o.slice(0,6);if(!r.length){document.getElementById('recentOrders').innerHTML='<div style="padding:3rem;text-align:center;color:var(--gray-500)">Henuz siparis yok</div>';return;}document.getElementById('recentOrders').innerHTML=r.map(x=>'<div class="order-row"><div><strong style="color:#dc2626">#AKN-'+x.id+'</strong> <span style="color:#6b7280">'+x.customer_name+'</span></div><div style="display:flex;align-items:center;gap:10px"><span style="font-weight:600">'+parseFloat(x.total_amount).toLocaleString('tr-TR')+'TL</span><span class="badge '+(sM[x.status]||'badge-pending')+'">'+(sL[x.status]||x.status)+'</span></div></div>').join('');}).catch(()=>{document.getElementById('statOrders').textContent='0';document.getElementById('statPending').textContent='0';});
fetch('/api/admin/messages').then(r=>r.json()).then(m=>{if(!Array.isArray(m)){document.getElementById('statMessages').textContent='0';return;}var u=m.reduce((s,x)=>s+parseInt(x.unread_count||0),0);document.getElementById('statMessages').textContent=u;if(!m.length){document.getElementById('recentMessages').innerHTML='<div style="padding:1.5rem;text-align:center;color:var(--gray-500);font-size:0.82rem">Henuz mesaj yok</div>';return;}document.getElementById('recentMessages').innerHTML=m.slice(0,5).map(x=>'<div class="msg-row"><span style="font-weight:600">'+x.customer_identifier.substring(0,14)+'</span>'+(parseInt(x.unread_count)>0?'<span style="background:#dc2626;color:white;border-radius:50%;min-width:20px;height:20px;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700">'+x.unread_count+'</span>':'<span style="color:#059669;font-size:0.75rem">OK</span>')+'</div>').join('');}).catch(()=>document.getElementById('statMessages').textContent='0');
fetch('/api/categories.php').then(r=>r.json()).then(c=>{var s=document.getElementById('qCategory');c.forEach(x=>{var o=document.createElement('option');o.value=x.id;o.textContent=x.name;s.appendChild(o);});});
function quickAdd(e){e.preventDefault();fetch('/api/admin/products?action=add',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name:document.getElementById('qName').value,price:document.getElementById('qPrice').value,stock:document.getElementById('qStock').value,oem_no:document.getElementById('qOem').value,part_brand:document.getElementById('qBrand').value,category_id:document.getElementById('qCategory').value||null,description:'',image_url:'',variant_ids:[]})}).then(r=>r.json()).then(r=>{var el=document.getElementById('quickAddResult');if(r.success){el.textContent='Urun eklendi!';el.style.color='#059669';el.style.display='block';document.getElementById('qName').value='';document.getElementById('qPrice').value='';document.getElementById('qStock').value='';document.getElementById('qOem').value='';document.getElementById('qBrand').value='';fetch('/api/admin/products').then(r=>r.json()).then(p=>document.getElementById('statProducts').textContent=Array.isArray(p)?p.length:0);setTimeout(()=>el.style.display='none',3000);}else{el.textContent='Hata!';el.style.color='#dc2626';el.style.display='block';}});}
</script>
</body>
</html>
