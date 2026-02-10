<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişler - Auto Akın</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>.admin-nav{background:#0f172a;border-bottom:none;padding:0}.admin-nav .container{display:flex;justify-content:space-between;align-items:center;height:56px}.admin-nav .nav-right{display:flex;align-items:center;gap:1.5rem}.admin-nav .nav-link{color:#94a3b8;font-size:0.82rem;transition:color 0.2s;font-weight:500}.admin-nav .nav-link:hover{color:white}.admin-nav .nav-link.active{color:white}</style>
</head>
<body style="background:#f8fafc">
    <nav class="navbar admin-nav"><div class="container"><a href="/admin/dashboard.php" class="logo" style="color:white;font-size:1.1rem">AUTO <span style="color:#ef4444">AKIN</span> <span style="font-size:0.6rem;color:#475569;background:#1e293b;padding:3px 8px;border-radius:4px;margin-left:6px">PANEL</span></a><div class="nav-right"><a href="/admin/dashboard.php" class="nav-link">Dashboard</a><a href="/admin/products.php" class="nav-link">Urunler</a><a href="/admin/vehicles.php" class="nav-link">Araclar</a><a href="/admin/orders.php" class="nav-link active">Siparisler</a><a href="/admin/messages.php" class="nav-link">Mesajlar</a><div style="width:1px;height:24px;background:#334155"></div><a href="/" target="_blank" class="nav-link">Siteyi Gor</a></div></div></nav>

    <div class="container" style="padding-top:2rem;padding-bottom:3rem">
        <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:1.5rem">Sipariş <span class="text-red">Takibi</span></h1>
        </div>

        <div class="card">
            <div id="ordLoading" style="padding:2rem;text-align:center;color:var(--gray-500)">Yükleniyor...</div>
            <div id="ordEmpty" style="padding:2rem;text-align:center;color:var(--gray-500);display:none">Henüz sipariş yok</div>
            <table class="table" id="ordTable" style="display:none">
                <thead><tr><th>Sipariş No</th><th>Müşteri</th><th>Tutar</th><th>Durum</th><th>Tarih</th><th>İşlem</th></tr></thead>
                <tbody id="ordBody"></tbody>
            </table>
        </div>
    </div>

    <script>
    if(!localStorage.getItem('admin_auth'))window.location='/admin/login.php';

    const statusMap = {
        pending:{label:'BEKLEMEDE',cls:'badge-pending'},
        verified:{label:'ONAYLANDI',cls:'badge-verified'},
        shipped:{label:'KARGODA',cls:'badge-shipped'},
        delivered:{label:'TESLİM',cls:'badge-delivered'},
        cancelled:{label:'İPTAL',cls:'badge-cancelled'}
    };

    function loadOrders() {
        fetch('/api/admin/orders.php').then(r=>r.json()).then(orders => {
            document.getElementById('ordLoading').style.display='none';
            if(!Array.isArray(orders)||orders.length===0){document.getElementById('ordEmpty').style.display='block';return;}
            document.getElementById('ordTable').style.display='table';
            document.getElementById('ordBody').innerHTML = orders.map(o => {
                const s = statusMap[o.status]||statusMap.pending;
                return `<tr>
                    <td style="font-weight:700">#AKN-${o.id}</td>
                    <td>${o.customer_name}<br><small style="color:var(--gray-500)">${o.customer_phone||''}</small></td>
                    <td>₺${parseFloat(o.total_amount).toLocaleString('tr-TR',{minimumFractionDigits:2})}</td>
                    <td><span class="badge ${s.cls}">${s.label}</span></td>
                    <td style="font-size:0.8rem;color:var(--gray-500)">${new Date(o.created_at).toLocaleDateString('tr-TR')}</td>
                    <td>
                        <select class="form-select" style="font-size:0.8rem;padding:4px 8px" onchange="updateStatus(${o.id},this.value)">
                            <option value="pending" ${o.status==='pending'?'selected':''}>Beklemede</option>
                            <option value="verified" ${o.status==='verified'?'selected':''}>Ödeme Onaylandı</option>
                            <option value="shipped" ${o.status==='shipped'?'selected':''}>Kargoda</option>
                            <option value="delivered" ${o.status==='delivered'?'selected':''}>Teslim Edildi</option>
                            <option value="cancelled" ${o.status==='cancelled'?'selected':''}>İptal</option>
                        </select>
                    </td>
                </tr>`;
            }).join('');
        });
    }

    function updateStatus(id, status) {
        fetch('/api/admin/orders.php',{method:'PUT',headers:{'Content-Type':'application/json'},body:JSON.stringify({id,status})})
        .then(()=>loadOrders());
    }

    loadOrders();
    </script>
</body>
</html>
