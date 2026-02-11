<?php
$pageTitle = 'Müşteri Yönetimi';
$pageDesc = 'Kayıtlı müşterilerin bilgilerini ve sipariş geçmişlerini görüntüleyin.';
include 'includes/header.php';
?>

<div class="container" style="margin-top:-2.5rem;padding-bottom:3rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:800">Müşteri <span class="text-red">Yönetimi</span></h1>
        <button class="btn-primary" onclick="showAddModal()">+ Yeni Müşteri</button>
    </div>

    <div class="card" style="overflow:hidden">
        <table class="customer-table" style="width:100%;border-collapse:collapse">
            <thead>
                <tr>
                    <th>AD SOYAD</th>
                    <th>E-POSTA</th>
                    <th>TELEFON</th>
                    <th>ADRES</th>
                    <th>KAYIT TARİHİ</th>
                    <th>İŞLEM</th>
                </tr>
            </thead>
            <tbody id="customerList">
                <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--gray-500)">Yükleniyor...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Müşteri Ekle Modal -->
<div id="addModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:1100">
    <div class="card" style="width:100%;max-width:480px;padding:2rem;position:relative">
        <h2 style="font-size:1.2rem;font-weight:800;margin-bottom:1.5rem">Yeni Müşteri Ekle</h2>
        <form onsubmit="handleAddCustomer(event)">
            <div style="margin-bottom:12px">
                <label class="form-label">Ad Soyad</label>
                <input class="form-input" id="cName" required>
            </div>
            <div style="margin-bottom:12px">
                <label class="form-label">E-posta</label>
                <input class="form-input" type="email" id="cEmail" required>
            </div>
            <div style="margin-bottom:12px">
                <label class="form-label">Şifre</label>
                <div class="password-wrapper">
                    <input class="form-input" type="password" id="cPass" required minlength="6">
                    <button type="button" class="password-toggle" onclick="togglePassword('cPass', 'eyeIconCust')">
                        <i id="eyeIconCust" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <div style="margin-bottom:12px">
                <label class="form-label">Telefon</label>
                <input class="form-input" id="cPhone">
            </div>
            <div style="margin-bottom:20px">
                <label class="form-label">Adres</label>
                <textarea class="form-input" id="cAddress" rows="3"></textarea>
            </div>
            <div style="display:flex;gap:10px">
                <button type="button" class="btn-secondary" style="flex:1" onclick="closeAddModal()">İptal</button>
                <button type="submit" class="btn-primary" style="flex:1" id="addBtn">Kaydet</button>
            </div>
        </form>
        <div id="addResult" style="margin-top:1rem;font-size:0.85rem;text-align:center;display:none"></div>
    </div>
</div>

<script>

function loadCustomers() {
    fetch(API_BASE + '/admin/customers')
    .then(r => r.json())
    .then(data => {
        const list = document.getElementById('customerList');
        if (!Array.isArray(data) || data.length === 0) {
            list.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--gray-500)">Henüz müşteri yok</td></tr>';
            return;
        }
        list.innerHTML = data.map(c => `
            <tr>
                <td><strong style="color:var(--secondary)">${c.name}</strong></td>
                <td>${c.email}</td>
                <td>${c.phone || '-'}</td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">${c.address || '-'}</td>
                <td style="color:var(--gray-500)">${new Date(c.created_at).toLocaleDateString('tr-TR')}</td>
                <td>
                    <button onclick="deleteCustomer(${c.id})" style="color:#ef4444;background:none;border:none;cursor:pointer;font-weight:700;font-size:0.8rem">Sil</button>
                </td>
            </tr>
        `).join('');
    });
}

function showAddModal() { document.getElementById('addModal').style.display = 'flex'; }
function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

function handleAddCustomer(e) {
    e.preventDefault();
    const btn = document.getElementById('addBtn');
    const res = document.getElementById('addResult');
    btn.disabled = true; btn.textContent = 'Kaydediliyor...';
    
    fetch(API_BASE + '/admin/customers?action=add', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            name: document.getElementById('cName').value,
            email: document.getElementById('cEmail').value,
            password: document.getElementById('cPass').value,
            phone: document.getElementById('cPhone').value,
            address: document.getElementById('cAddress').value
        })
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            res.textContent = 'Müşteri başarıyla eklendi!';
            res.style.color = '#059669'; res.style.display = 'block';
            setTimeout(() => {
                closeAddModal();
                loadCustomers();
                res.style.display = 'none';
                e.target.reset();
                btn.disabled = false; btn.textContent = 'Kaydet';
            }, 1500);
        } else {
            res.textContent = r.error || 'Hata oluştu';
            res.style.color = '#dc2626'; res.style.display = 'block';
            btn.disabled = false; btn.textContent = 'Kaydet';
        }
    })
    .catch(() => {
        btn.disabled = false; btn.textContent = 'Kaydet';
    });
}

function deleteCustomer(id) {
    if (!confirm('Bu müşteriyi silmek istediğinize emin misiniz?')) return;
    fetch(API_BASE + '/admin/customers?action=delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id: id })
    }).then(r => r.json()).then(r => {
        if (r.success) loadCustomers();
        else alert('Silme hatası: ' + r.error);
    });
}

loadCustomers();
</script>
</body>
</html>
