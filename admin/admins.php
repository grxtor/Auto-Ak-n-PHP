<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Yöneticileri - Auto Akın Yönetimi</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body{background:#f8fafc}
        .admin-nav{background:#0f172a;border-bottom:none;padding:0}
        .admin-nav .container{display:flex;justify-content:space-between;align-items:center;height:56px}
        .admin-nav .nav-right{display:flex;align-items:center;gap:1.5rem}
        .admin-nav .nav-link{color:#94a3b8;font-size:0.82rem;transition:color 0.2s;font-weight:500}
        .admin-nav .nav-link:hover{color:white}
        .admin-nav .nav-link.active{color:white}
        .admin-table th { text-align: left; padding: 12px 20px; font-size: 0.8rem; color: var(--gray-500); text-transform: uppercase; border-bottom: 1px solid var(--gray-200); }
        .admin-table td { padding: 14px 20px; font-size: 0.85rem; border-bottom: 1px solid #f3f4f6; }
    </style>
</head>
<body>
<nav class="navbar admin-nav">
    <div class="container">
        <a href="/admin/dashboard" class="logo" style="color:white;font-size:1.1rem">AUTO <span style="color:#ef4444">AKIN</span> <span style="font-size:0.6rem;color:#475569;background:#1e293b;padding:3px 8px;border-radius:4px;margin-left:6px">PANEL</span></a>
        <div class="nav-right">
            <a href="/admin/dashboard" class="nav-link">Dashboard</a>
            <a href="/admin/products" class="nav-link">Urunler</a>
            <a href="/admin/vehicles" class="nav-link">Araclar</a>
            <a href="/admin/orders" class="nav-link">Siparisler</a>
            <a href="/admin/messages" class="nav-link">Mesajlar</a>
            <a href="/admin/customers" class="nav-link">Musteriler</a><a href="/admin/admins" class="nav-link">Adminler</a>
            <a href="/admin/admins" class="nav-link active">Adminler</a>
            <div style="width:1px;height:24px;background:#334155"></div>
            <a href="/" target="_blank" class="nav-link">Siteyi Gor</a>
            <button onclick="adminLogout()" style="border:1px solid #334155;background:transparent;color:#94a3b8;padding:5px 12px;border-radius:4px;font-size:0.78rem;cursor:pointer">Cikis</button>
        </div>
    </div>
</nav>

<div class="container" style="padding:2rem 1.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem">
        <h1 style="font-size:1.5rem;font-weight:800">Sistem <span class="text-red">Yöneticileri</span></h1>
        <button class="btn-primary" onclick="showAddModal()">+ Yeni Admin Ekle</button>
    </div>

    <div class="card" style="overflow:hidden;max-width:800px">
        <table class="admin-table" style="width:100%;border-collapse:collapse">
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
                <input class="form-input" type="password" id="aPass" required minlength="6">
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
// Auth check
let myId = null;
fetch('/api/admin/auth.php').then(r=>r.json()).then(r=>{
    if(!r.loggedIn) window.location='/admin/login';
    // Sunucudan guncel myId bilgisini almak icin bir endpoint lazim olabilir ama id=1 fallback var suan
});

function adminLogout(){fetch('/api/admin/auth.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'logout'})}).then(()=>{localStorage.removeItem('admin_auth');window.location='/admin/login';});}

function loadAdmins() {
    fetch('/api/admin/admins.php')
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
    
    fetch('/api/admin/admins.php?action=add', {
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
    fetch('/api/admin/admins.php?action=delete', {
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
