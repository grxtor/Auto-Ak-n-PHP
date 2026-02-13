<?php
$pageTitle = 'Sistem Yöneticileri';
$pageDesc = 'Panel erişimi olan yönetici hesaplarını buradan yönetebilirsiniz.';
include 'includes/header.php';
?>

<div class="container" style="margin-top:-3rem;padding-bottom:3rem">
    <div style="display:flex;justify-content:flex-end;align-items:center;margin-bottom:2rem">
        <button class="btn-primary" onclick="showAddModal()">+ Yeni Admin Ekle</button>
    </div>

    <div class="card" style="overflow:hidden;max-width:800px">
        <table class="table" style="width:100%;border-collapse:separate;border-spacing:0">
            <thead>
                <tr>
                    <th>KULLANICI ADI</th>
                    <th>OLUŞTURULMA TARİHİ</th>
                    <th>İŞLEM</th>
                </tr>
            </thead>
            <tbody id="adminList">
                <tr><td colspan="3" style="text-align:center;padding:3rem;color:var(--gray-500)">Yükleniyor...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Admin Ekle Modal -->
<div id="addModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;z-index:1100">
    <div class="card" style="width:100%;max-width:400px;padding:2rem;position:relative">
        <h2 style="font-size:1.2rem;font-weight:800;margin-bottom:1.5rem">Yeni Admin Hesabı</h2>
        <form onsubmit="handleAddAdmin(event)">
            <div style="margin-bottom:12px">
                <label class="form-label">Kullanıcı Adı</label>
                <input class="form-input" id="aUser" required placeholder="ör: ahmet_akin">
            </div>
            <div style="margin-bottom:20px">
                <label class="form-label">Şifre</label>
                <div class="password-wrapper">
                    <input class="form-input" type="password" id="aPass" required minlength="6">
                    <button type="button" class="password-toggle" onclick="togglePassword('aPass', 'eyeIconAdm')">
                        <i id="eyeIconAdm" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <div style="display:flex;gap:10px">
                <button type="button" class="btn-secondary" style="flex:1" onclick="closeAddModal()">İptal</button>
                <button type="submit" class="btn-primary" style="flex:1" id="addBtn">Admin Oluştur</button>
            </div>
        </form>
        <div id="addResult" style="margin-top:1rem;font-size:0.85rem;text-align:center;display:none"></div>
    </div>
</div>

<script>

function loadAdmins() {
    fetch(API_BASE + '/admin/admins.php')
    .then(r => r.json())
    .then(data => {
        const list = document.getElementById('adminList');
        if (!Array.isArray(data) || data.length === 0) {
            list.innerHTML = '<tr><td colspan="3" style="text-align:center;padding:3rem;color:var(--gray-500)">Admin bulunamadı</td></tr>';
            return;
        }
        list.innerHTML = data.map(a => `
            <tr>
                <td><strong style="color:var(--secondary)">${a.username}</strong></td>
                <td style="color:var(--gray-500)">${new Date(a.created_at).toLocaleDateString('tr-TR')} ${new Date(a.created_at).toLocaleTimeString('tr-TR', {hour:'2-digit', minute:'2-digit'})}</td>
                <td>
                    <button onclick="deleteAdmin(${a.id})" style="color:#ef4444;background:none;border:none;cursor:pointer;font-weight:700;font-size:0.8rem">Kaldır</button>
                </td>
            </tr>
        `).join('');
    });
}

function showAddModal() { document.getElementById('addModal').style.display = 'flex'; }
function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

function handleAddAdmin(e) {
    e.preventDefault();
    const btn = document.getElementById('addBtn');
    const res = document.getElementById('addResult');
    btn.disabled = true; btn.textContent = 'Oluşturuluyor...';
    
    fetch(API_BASE + '/admin/admins.php?action=add', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            username: document.getElementById('aUser').value,
            password: document.getElementById('aPass').value
        })
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            res.textContent = 'Admin başarıyla eklendi!';
            res.style.color = '#059669'; res.style.display = 'block';
            setTimeout(() => {
                closeAddModal();
                loadAdmins();
                res.style.display = 'none';
                e.target.reset();
                btn.disabled = false; btn.textContent = 'Admin Oluştur';
            }, 1500);
        } else {
            res.textContent = r.error || 'Hata oluştu';
            res.style.color = '#dc2626'; res.style.display = 'block';
            btn.disabled = false; btn.textContent = 'Admin Oluştur';
        }
    })
    .catch(() => {
        btn.disabled = false; btn.textContent = 'Admin Oluştur';
    });
}

function deleteAdmin(id) {
    if (!confirm('Bu admin hesabını silmek istediğinize emin misiniz?')) return;
    fetch(API_BASE + '/admin/admins.php?action=delete', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id: id })
    }).then(r => r.json()).then(r => {
        if (r.success) loadAdmins();
        else alert('Hata: ' + r.error);
    });
}

loadAdmins();
</script>
</body>
</html>
