<?php
$pageTitle = 'Siparişlerim - Auto Akın';
include 'includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}
?>

<div class="container" style="padding: 2rem 0; flex: 1;">
    <h1 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 2rem;">Sipariş <span class="text-red">Geçmişim</span></h1>

    <div class="card" style="overflow:hidden">
        <div id="ordLoading" style="padding:4rem; text-align:center; color:var(--gray-500)">Siparişleriniz yükleniyor...</div>
        <div id="ordEmpty" style="padding:4rem; text-align:center; color:var(--gray-500); display:none">
            <div style="font-size:3rem; margin-bottom:1rem">📦</div>
            <p>Henüz bir siparişiniz bulunmuyor.</p>
            <a href="<?= BASE_URL ?>/parts" class="btn-primary btn-sm" style="margin-top:1rem">Alışverişe Başla</a>
        </div>
        
        <table class="table" id="ordTable" style="display:none">
            <thead>
                <tr>
                    <th>SİPARİŞ NO</th>
                    <th>TUTAR</th>
                    <th>DURUM</th>
                    <th>TARİH</th>
                </tr>
            </thead>
            <tbody id="ordBody"></tbody>
        </table>
    </div>
</div>

<script>
const statusMap = {
    pending: { label: 'BEKLEMEDE', cls: 'badge-pending' },
    verified: { label: 'ONAYLANDI', cls: 'badge-verified' },
    shipped: { label: 'KARGODA', cls: 'badge-shipped' },
    delivered: { label: 'TESLİM EDİLDİ', cls: 'badge-delivered' },
    cancelled: { label: 'İPTAL EDİLDİ', cls: 'badge-cancelled' }
};

function loadOrders() {
    fetch(API_BASE + '/orders')
        .then(r => r.json())
        .then(data => {
            document.getElementById('ordLoading').style.display = 'none';
            if (!data || data.length === 0) {
                document.getElementById('ordEmpty').style.display = 'block';
                return;
            }
            
            document.getElementById('ordTable').style.display = 'table';
            document.getElementById('ordBody').innerHTML = data.map(o => {
                const s = statusMap[o.status] || statusMap.pending;
                return `
                    <tr>
                        <td style="font-weight:700">#${o.order_code}</td>
                        <td style="font-weight:700">₺${parseFloat(o.total_amount).toLocaleString('tr-TR', {minimumFractionDigits:2})}</td>
                        <td><span class="badge ${s.cls}">${s.label}</span></td>
                        <td style="color:var(--gray-500); font-size:0.85rem">${new Date(o.created_at).toLocaleDateString('tr-TR')}</td>
                    </tr>
                `;
            }).join('');
        })
        .catch(() => {
            document.getElementById('ordLoading').textContent = 'Siparişler yüklenirken bir hata oluştu.';
        });
}

loadOrders();
</script>

<?php include 'includes/footer.php'; ?>
