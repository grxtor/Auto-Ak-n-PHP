<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Yönetimi - Auto Akın</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>.admin-nav{background:#0f172a;border-bottom:none;padding:0}.admin-nav .container{display:flex;justify-content:space-between;align-items:center;height:56px}.admin-nav .nav-right{display:flex;align-items:center;gap:1.5rem}.admin-nav .nav-link{color:#94a3b8;font-size:0.82rem;transition:color 0.2s;font-weight:500}.admin-nav .nav-link:hover{color:white}.admin-nav .nav-link.active{color:white}</style>
</head>
<body style="background:#f8fafc">
    <nav class="navbar admin-nav"><div class="container"><a href="/admin/dashboard" class="logo" style="color:white;font-size:1.1rem">AUTO <span style="color:#ef4444">AKIN</span> <span style="font-size:0.6rem;color:#475569;background:#1e293b;padding:3px 8px;border-radius:4px;margin-left:6px">PANEL</span></a><div class="nav-right"><a href="/admin/dashboard" class="nav-link">Dashboard</a><a href="/admin/products" class="nav-link active">Urunler</a><a href="/admin/vehicles" class="nav-link">Araclar</a><a href="/admin/orders" class="nav-link">Siparisler</a><a href="/admin/messages" class="nav-link">Mesajlar</a><a href="/admin/customers" class="nav-link">Musteriler</a><a href="/admin/admins" class="nav-link">Adminler</a><div style="width:1px;height:24px;background:#334155"></div><a href="/" target="_blank" class="nav-link">Siteyi Gor</a></div></div></nav>

    <div class="container" style="padding-top:2rem;padding-bottom:3rem">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h1 style="font-size:1.5rem;font-weight:800">Ürün <span class="text-red">Yönetimi</span></h1>
            <div style="display:flex;gap:8px">
                <button class="btn-primary" id="toggleFormBtn" onclick="toggleForm()">+ Yeni Ürün</button>
                <a href="/admin/dashboard" class="btn-outline btn-sm">← Dashboard</a>
            </div>
        </div>

        <!-- Yeni Ürün Formu -->
        <div class="card" id="addForm" style="padding:1.5rem;margin-bottom:1.5rem;display:none">
            <h2 style="font-size:1rem;font-weight:700;margin-bottom:1rem">Yeni Ürün Ekle</h2>
            <form onsubmit="addProduct(event)">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                    <div><label class="form-label">Ürün Adı</label><input class="form-input" id="pName" required></div>
                    <div><label class="form-label">Parça Markası</label><input class="form-input" id="pBrand" placeholder="Bosch, SKF..."></div>
                    <div><label class="form-label">Fiyat (₺)</label><input class="form-input" type="number" step="0.01" id="pPrice" required></div>
                    <div><label class="form-label">Stok</label><input class="form-input" type="number" id="pStock" required></div>
                    <div><label class="form-label">OEM No</label><input class="form-input" id="pOem" placeholder="OEM parça numarası"></div>
                    <div><label class="form-label">Kategori</label><select class="form-select" id="pCategory" style="width:100%"><option value="">Seçin</option></select></div>
                </div>
                <div style="margin-bottom:12px"><label class="form-label">Görsel URL</label><input class="form-input" id="pImage"></div>
                <div style="margin-bottom:12px"><label class="form-label">Açıklama</label><textarea class="form-input" id="pDesc" rows="3" style="resize:none"></textarea></div>

                <!-- Araç Uyumluluğu -->
                <div style="margin-bottom:12px;padding:1rem;background:var(--gray-50);border-radius:var(--radius)">
                    <label class="form-label">Araç Uyumluluğu</label>
                    <div style="display:flex;gap:8px;margin-bottom:8px">
                        <select class="form-select" id="pBrandSel" onchange="loadFormModels()"><option value="">Marka</option></select>
                        <select class="form-select" id="pModelSel" onchange="loadFormVariants()" disabled><option value="">Model</option></select>
                    </div>
                    <div id="variantCheckboxes" style="display:flex;flex-wrap:wrap;gap:6px"></div>
                    <div id="variantCount" style="font-size:0.75rem;color:var(--gray-500);margin-top:6px"></div>
                </div>

                <button type="submit" class="btn-primary" style="width:100%">Ürünü Kaydet</button>
            </form>
        </div>

        <!-- Ürün Listesi -->
        <div class="card">
            <div id="prodLoading" style="padding:2rem;text-align:center;color:var(--gray-500)">Yükleniyor...</div>
            <table class="table" id="prodTable" style="display:none">
                <thead><tr><th>Ürün</th><th>OEM</th><th>Stok</th><th>Fiyat</th><th>İşlem</th></tr></thead>
                <tbody id="prodBody"></tbody>
            </table>
        </div>
    </div>

    <script>
    if(!localStorage.getItem('admin_auth'))window.location='/admin/login';

    let selectedVariants = [];

    function toggleForm() {
        const f = document.getElementById('addForm');
        f.style.display = f.style.display === 'none' ? 'block' : 'none';
    }

    function loadProducts() {
        fetch('/api/admin/products').then(r=>r.json()).then(products => {
            document.getElementById('prodLoading').style.display='none';
            document.getElementById('prodTable').style.display='table';
            document.getElementById('prodBody').innerHTML = products.map(p => `
                <tr>
                    <td><strong>${p.name}</strong>${p.part_brand ? `<span style="color:var(--gray-500);font-size:0.75rem;margin-left:6px">${p.part_brand}</span>` : ''}<br><small style="color:var(--gray-500)">${p.category_name||'-'}</small></td>
                    <td style="font-size:0.8rem">${p.oem_no||'-'}</td>
                    <td>${p.stock}</td>
                    <td>₺${parseFloat(p.price).toLocaleString('tr-TR')}</td>
                    <td><button onclick="deleteProduct(${p.id})" style="color:var(--primary);border:none;background:none;cursor:pointer;font-size:0.8rem">Sil</button></td>
                </tr>
            `).join('');
        });
    }

    function addProduct(e) {
        e.preventDefault();
        fetch('/api/admin/products?action=add', {
            method:'POST', headers:{'Content-Type':'application/json'},
            body: JSON.stringify({
                name: document.getElementById('pName').value,
                description: document.getElementById('pDesc').value,
                price: document.getElementById('pPrice').value,
                stock: document.getElementById('pStock').value,
                category_id: document.getElementById('pCategory').value || null,
                image_url: document.getElementById('pImage').value,
                oem_no: document.getElementById('pOem').value,
                part_brand: document.getElementById('pBrand').value,
                variant_ids: selectedVariants
            })
        }).then(r=>r.json()).then(res => {
            if(res.success){toggleForm();loadProducts();selectedVariants=[];}
        });
    }

    function deleteProduct(id) {
        if(!confirm('Bu ürünü silmek istediğinize emin misiniz?')) return;
        fetch('/api/admin/products?id='+id,{method:'DELETE'}).then(()=>loadProducts());
    }

    function loadFormModels() {
        const brandId = document.getElementById('pBrandSel').value;
        const sel = document.getElementById('pModelSel');
        sel.innerHTML='<option value="">Model</option>'; sel.disabled=!brandId;
        document.getElementById('variantCheckboxes').innerHTML='';
        if(!brandId) return;
        fetch('/api/models.php?brandId='+brandId).then(r=>r.json()).then(models => {
            models.forEach(m=>{const o=document.createElement('option');o.value=m.id;o.textContent=m.name;sel.appendChild(o);});
            sel.disabled=false;
        });
    }
    function loadFormVariants() {
        const modelId = document.getElementById('pModelSel').value;
        const box = document.getElementById('variantCheckboxes');
        box.innerHTML='';
        if(!modelId) return;
        fetch('/api/variants.php?modelId='+modelId).then(r=>r.json()).then(variants => {
            box.innerHTML = variants.map(v => {
                const checked = selectedVariants.includes(v.id);
                return `<label style="display:flex;align-items:center;gap:4px;padding:4px 10px;border-radius:4px;font-size:0.8rem;cursor:pointer;background:${checked?'#fee2e2':'white'};border:1px solid ${checked?'var(--primary)':'var(--gray-300)'}">
                    <input type="checkbox" ${checked?'checked':''} onchange="toggleVariant(${v.id})" style="display:none">
                    ${v.year_start}-${v.year_end||'...'} ${v.engine_type} ${v.fuel_type}
                </label>`;
            }).join('');
        });
    }
    function toggleVariant(id) {
        const idx = selectedVariants.indexOf(id);
        if(idx>-1) selectedVariants.splice(idx,1); else selectedVariants.push(id);
        loadFormVariants();
        document.getElementById('variantCount').textContent = selectedVariants.length > 0 ? selectedVariants.length+' varyant seçildi' : '';
    }

    // Init
    fetch('/api/categories.php').then(r=>r.json()).then(cats => {
        const s = document.getElementById('pCategory');
        cats.forEach(c=>{const o=document.createElement('option');o.value=c.id;o.textContent=c.name;s.appendChild(o);});
    });
    fetch('/api/brands.php').then(r=>r.json()).then(brands => {
        const s = document.getElementById('pBrandSel');
        brands.forEach(b=>{const o=document.createElement('option');o.value=b.id;o.textContent=b.name;s.appendChild(o);});
    });
    loadProducts();
    </script>
</body>
</html>
