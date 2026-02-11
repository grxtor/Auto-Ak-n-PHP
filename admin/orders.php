<?php
$pageTitle = 'Sipariş Takibi';
$pageDesc = 'Gelen siparişleri onaylayın, kargo bilgilerini güncelleyin veya iptal edin.';
include 'includes/header.php';
?>

<div class="container" style="margin-top:-2.5rem;padding-bottom:3rem">
        <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:1.5rem">Sipariş <span class="text-red">Takibi</span></h1>

        <div class="card">
            <div id="ordLoading" style="padding:2rem;text-align:center;color:var(--gray-500)">Yükleniyor...</div>
            <div id="ordEmpty" style="padding:2rem;text-align:center;color:var(--gray-500);display:none">Henüz sipariş yok</div>
            <table class="table" id="ordTable" style="display:none">
                <thead><tr><th>Sipariş No</th><th>Müşteri</th><th>Tutar</th><th>Durum</th><th>Dekont</th><th>Tarih</th><th>İşlem</th></tr></thead>
                <tbody id="ordBody"></tbody>
            </table>
        </div>
    </div>

    <script>
    if(!localStorage.getItem('admin_auth'))window.location='/admin/login';

    const statusMap = {
        pending:{label:'BEKLEMEDE',cls:'badge-pending'},
        verified:{label:'ONAYLANDI',cls:'badge-verified'},
        shipped:{label:'KARGODA',cls:'badge-shipped'},
        delivered:{label:'TESLİM',cls:'badge-delivered'},
        cancelled:{label:'İPTAL',cls:'badge-cancelled'}
    };

    function loadOrders() {
        fetch('/api/admin/orders').then(r=>r.json()).then(orders => {
            document.getElementById('ordLoading').style.display='none';
            if(!Array.isArray(orders)||orders.length===0){document.getElementById('ordEmpty').style.display='block';return;}
            document.getElementById('ordTable').style.display='table';
            document.getElementById('ordBody').innerHTML = orders.map(o => {
                const s = statusMap[o.status]||statusMap.pending;
                
                return `<tr>
                    <td style="font-weight:700">#${o.order_code}</td>
                    <td>${o.customer_name}<br><small style="color:var(--gray-500)">${o.customer_phone||''}</small></td>
                    <td>₺${parseFloat(o.total_amount).toLocaleString('tr-TR',{minimumFractionDigits:2})}</td>
                    <td><span class="badge ${s.cls}">${s.label}</span></td>
                    <td>
                        ${o.receipt_url ? `
                            <a href="${o.receipt_url}" target="_blank" style="color:var(--primary);font-size:0.8rem;text-decoration:none;display:flex;align-items:center;gap:4px">
                                <i class="fas fa-file-invoice"></i> Gör
                            </a>
                        ` : '<small style="color:var(--gray-400)">Yüklenmedi</small>'}
                    </td>
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
        fetch('/api/admin/orders',{method:'PUT',headers:{'Content-Type':'application/json'},body:JSON.stringify({id,status})})
        .then(()=>loadOrders());
    }

    loadOrders();
    </script>
</body>
</html>
