<?php
$pageTitle = 'Auto Akın - Hyundai & Kia Yedek Parça';
$pageDesc = 'Hyundai ve Kia araçlarınız için orijinal ve muadil yedek parçalar. OEM garantili, hızlı kargo.';
include 'includes/header.php';
?>

<!-- Hero Banner -->
<section style="background:linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);padding:4rem 0;color:white;text-align:center">
    <div class="container">
        <div style="display:inline-block;background:var(--primary);color:white;padding:5px 14px;border-radius:20px;font-size:0.75rem;font-weight:700;letter-spacing:1px;margin-bottom:1.25rem">HYUNDAI & KIA YETKILI YEDEK PARCA</div>
        <h1 style="font-size:2.2rem;font-weight:800;margin-bottom:0.75rem;line-height:1.3">Aracınıza Uygun<br><span style="color:#ef4444">Yedek Parça</span> Bulun</h1>
        <p style="color:#999;margin-bottom:2rem;font-size:0.95rem">Marka, model ve motor tipine göre doğru parçayı hızlıca bulun.</p>

        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;max-width:820px;margin:0 auto">
            <select class="form-select" id="homeBrand" onchange="loadModels(this.value, 'homeModel')" style="background:#1e293b;color:white;border-color:#334155;padding:12px 16px;font-size:0.9rem;min-width:160px">
                <option value="">Marka Seçin</option>
            </select>
            <select class="form-select" id="homeModel" onchange="loadVariants(this.value, 'homeVariant')" disabled style="background:#1e293b;color:white;border-color:#334155;padding:12px 16px;font-size:0.9rem;min-width:160px">
                <option value="">Model Seçin</option>
            </select>
            <select class="form-select" id="homeVariant" disabled style="min-width:220px;background:#1e293b;color:white;border-color:#334155;padding:12px 16px;font-size:0.9rem">
                <option value="">Motor / Yıl Seçin</option>
            </select>
            <button class="btn-primary" onclick="searchParts()" style="padding:12px 28px;font-size:0.9rem">Parça Ara</button>
        </div>
        <div style="margin-top:1rem;color:#555;font-size:0.8rem">veya <a href="/parts.php" style="color:#ef4444;font-weight:600">tüm parçalara göz atın</a></div>
    </div>
</section>

<!-- OEM Arama Bandı -->
<section style="background:#dc2626;padding:0.9rem 0">
    <div class="container">
        <form onsubmit="oemSearch(event)" style="display:flex;align-items:center;gap:12px;justify-content:center;flex-wrap:wrap">
            <span style="color:white;font-weight:700;font-size:0.85rem">OEM Numarası ile Hızlı Arama:</span>
            <input class="form-input" id="oemInput" placeholder="OEM numarası girin (ör: 31110-2S000)" style="min-width:280px;padding:9px 14px;font-size:0.85rem">
            <button type="submit" class="btn-secondary btn-sm">Ara</button>
        </form>
    </div>
</section>

<!-- Markalar -->
<section class="container" style="padding-top:2.5rem">
    <h2 style="font-size:1.3rem;font-weight:800;margin-bottom:1.5rem">Hizmet Verdiğimiz <span style="color:#dc2626">Markalar</span></h2>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem">
        <div class="card" style="padding:2rem;text-align:center;cursor:pointer;transition:all 0.2s;border:2px solid transparent" onclick="selectHomeBrand('hyundai')" id="brandHyundai" onmouseover="this.style.borderColor='#dc2626'" onmouseout="this.style.borderColor='transparent'">
            <div style="width:80px;height:80px;margin:0 auto 1rem;background:#f0f4f8;border-radius:50%;display:flex;align-items:center;justify-content:center">
                <svg width="40" height="40" viewBox="0 0 40 40"><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-size="18" font-weight="800" fill="#1a56db">H</text></svg>
            </div>
            <h3 style="font-size:1.25rem;font-weight:800">Hyundai</h3>
            <p style="font-size:0.8rem;color:var(--gray-500);margin-top:6px">i20, i30, Accent, Tucson, Elantra, Kona ve daha fazlası</p>
            <div style="display:flex;gap:6px;justify-content:center;margin-top:12px;flex-wrap:wrap" id="hyundaiModels"></div>
        </div>
        <div class="card" style="padding:2rem;text-align:center;cursor:pointer;transition:all 0.2s;border:2px solid transparent" onclick="selectHomeBrand('kia')" id="brandKia" onmouseover="this.style.borderColor='#dc2626'" onmouseout="this.style.borderColor='transparent'">
            <div style="width:80px;height:80px;margin:0 auto 1rem;background:#f0f4f8;border-radius:50%;display:flex;align-items:center;justify-content:center">
                <svg width="40" height="40" viewBox="0 0 40 40"><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" font-size="14" font-weight="800" fill="#bb162b">KIA</text></svg>
            </div>
            <h3 style="font-size:1.25rem;font-weight:800">Kia</h3>
            <p style="font-size:0.8rem;color:var(--gray-500);margin-top:6px">Sportage, Ceed, Rio, Stonic, Sorento ve daha fazlası</p>
            <div style="display:flex;gap:6px;justify-content:center;margin-top:12px;flex-wrap:wrap" id="kiaModels"></div>
        </div>
    </div>
</section>

<!-- Kategoriler -->
<section class="container" style="margin-top:2.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem">
        <h2 style="font-size:1.3rem;font-weight:800">Kategoriler</h2>
        <a href="/parts.php" style="font-size:0.85rem;color:#dc2626;font-weight:600">Tümünü Gör →</a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:10px" id="categoryGrid"></div>
</section>

<!-- Neden Biz -->
<section style="background:#f8f9fa;padding:3.5rem 0;margin-top:2.5rem">
    <div class="container">
        <h2 style="text-align:center;font-size:1.3rem;font-weight:800;margin-bottom:2.5rem">Neden <span style="color:#dc2626">Auto Akın</span>?</h2>
        <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:1.5rem">
            <div class="card" style="text-align:center;padding:2rem 1.5rem">
                <div style="width:52px;height:52px;margin:0 auto 1rem;background:#fee2e2;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;font-weight:800;color:#dc2626">OEM</div>
                <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0.5rem">OEM Garantili</h3>
                <p style="font-size:0.8rem;color:var(--gray-500);line-height:1.6">Tüm parçalar orijinal OEM numarasıyla eşleştirilir.</p>
            </div>
            <div class="card" style="text-align:center;padding:2rem 1.5rem">
                <div style="width:52px;height:52px;margin:0 auto 1rem;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem">&#128666;</div>
                <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0.5rem">Aynı Gün Kargo</h3>
                <p style="font-size:0.8rem;color:var(--gray-500);line-height:1.6">14:00'a kadar verilen siparişler aynı gün kargoya verilir.</p>
            </div>
            <div class="card" style="text-align:center;padding:2rem 1.5rem">
                <div style="width:52px;height:52px;margin:0 auto 1rem;background:#d1fae5;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:800;color:#059669">TL</div>
                <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0.5rem">Uygun Fiyat</h3>
                <p style="font-size:0.8rem;color:var(--gray-500);line-height:1.6">Bayilerin altında fiyatlarla orijinal ve muadil parçalar.</p>
            </div>
            <div class="card" style="text-align:center;padding:2rem 1.5rem">
                <div style="width:52px;height:52px;margin:0 auto 1rem;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem">&#9742;</div>
                <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:0.5rem">7/24 Destek</h3>
                <p style="font-size:0.8rem;color:var(--gray-500);line-height:1.6">Canlı destek hattımızdan parça sorgulaması yapabilirsiniz.</p>
            </div>
        </div>
    </div>
</section>

<!-- Ürünler -->
<section class="container" style="margin-top:2.5rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem">
        <h2 style="font-size:1.3rem;font-weight:800">Son Eklenen <span style="color:#dc2626">Ürünler</span></h2>
        <a href="/parts.php" style="font-size:0.85rem;color:#dc2626;font-weight:600">Tümünü Gör →</a>
    </div>
    <div class="product-grid" id="latestProducts"></div>
</section>

<!-- Güven Bandı -->
<section style="background:#111;color:white;padding:2.5rem 0;margin-top:2.5rem">
    <div class="container">
        <div style="display:flex;justify-content:center;gap:4rem;flex-wrap:wrap;text-align:center">
            <div><div style="font-size:1.5rem;font-weight:800;color:#ef4444">2.500+</div><div style="font-size:0.8rem;color:#888;margin-top:2px">Ürün Çeşidi</div></div>
            <div><div style="font-size:1.5rem;font-weight:800;color:#ef4444">%100</div><div style="font-size:0.8rem;color:#888;margin-top:2px">OEM Uyumlu</div></div>
            <div><div style="font-size:1.5rem;font-weight:800;color:#ef4444">7/24</div><div style="font-size:0.8rem;color:#888;margin-top:2px">Canlı Destek</div></div>
            <div><div style="font-size:1.5rem;font-weight:800;color:#ef4444">Aynı Gün</div><div style="font-size:0.8rem;color:#888;margin-top:2px">Kargo</div></div>
            <div><div style="font-size:1.5rem;font-weight:800;color:#ef4444">IBAN</div><div style="font-size:0.8rem;color:#888;margin-top:2px">Güvenli Ödeme</div></div>
        </div>
    </div>
</section>

<!-- Nasıl Çalışır -->
<section class="container" style="margin-top:2.5rem;margin-bottom:3rem">
    <h2 style="text-align:center;font-size:1.3rem;font-weight:800;margin-bottom:2rem">Nasıl <span style="color:#dc2626">Sipariş</span> Verilir?</h2>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.5rem">
        <div class="card" style="padding:2rem 1.5rem;text-align:center;position:relative">
            <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#dc2626;color:white;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800">1</div>
            <div style="width:48px;height:48px;margin:0.5rem auto 1rem;background:#fee2e2;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem">&#128663;</div>
            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:6px">Aracınızı Seçin</h4>
            <p style="font-size:0.78rem;color:var(--gray-500)">Marka, model ve motor tipini belirtin</p>
        </div>
        <div class="card" style="padding:2rem 1.5rem;text-align:center;position:relative">
            <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#dc2626;color:white;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800">2</div>
            <div style="width:48px;height:48px;margin:0.5rem auto 1rem;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem">&#128269;</div>
            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:6px">Parça Bulun</h4>
            <p style="font-size:0.78rem;color:var(--gray-500)">OEM no veya kategori ile arayın</p>
        </div>
        <div class="card" style="padding:2rem 1.5rem;text-align:center;position:relative">
            <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#dc2626;color:white;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800">3</div>
            <div style="width:48px;height:48px;margin:0.5rem auto 1rem;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem">&#128722;</div>
            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:6px">Sepete Ekleyin</h4>
            <p style="font-size:0.78rem;color:var(--gray-500)">Birden fazla parça ekleyebilirsiniz</p>
        </div>
        <div class="card" style="padding:2rem 1.5rem;text-align:center;position:relative">
            <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#dc2626;color:white;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:800">4</div>
            <div style="width:48px;height:48px;margin:0.5rem auto 1rem;background:#d1fae5;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem">&#128179;</div>
            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:6px">Ödeme Yapın</h4>
            <p style="font-size:0.78rem;color:var(--gray-500)">IBAN ile havale/EFT yapın</p>
        </div>
    </div>
</section>

<script>
function loadBrands(selectId) {
    fetch('/api/brands.php').then(r=>r.json()).then(brands => {
        const sel = document.getElementById(selectId);
        brands.forEach(b => { const o = document.createElement('option'); o.value=b.id; o.textContent=b.name; sel.appendChild(o); });
    });
}
function loadModels(brandId, selectId) {
    const sel = document.getElementById(selectId);
    sel.innerHTML = '<option value="">Model Seçin</option>'; sel.disabled = !brandId;
    const varSel = document.getElementById(selectId.replace('Model','Variant'));
    if(varSel){varSel.innerHTML='<option value="">Motor / Yıl Seçin</option>';varSel.disabled=true;}
    if(!brandId) return;
    fetch('/api/models.php?brandId='+brandId).then(r=>r.json()).then(models => {
        models.forEach(m=>{const o=document.createElement('option');o.value=m.id;o.textContent=m.name;sel.appendChild(o);});
        sel.disabled=false;
    });
}
function loadVariants(modelId, selectId) {
    const sel = document.getElementById(selectId);
    sel.innerHTML = '<option value="">Motor / Yıl Seçin</option>'; sel.disabled = !modelId;
    if(!modelId) return;
    fetch('/api/variants.php?modelId='+modelId).then(r=>r.json()).then(variants => {
        variants.forEach(v=>{
            const o = document.createElement('option'); o.value=v.id;
            o.textContent=`${v.year_start}-${v.year_end||'...'} ${v.engine_type} ${v.fuel_type} ${v.horsepower?v.horsepower+'HP':''}`;
            sel.appendChild(o);
        });
        sel.disabled=false;
    });
}
function searchParts() {
    const variant = document.getElementById('homeVariant').value;
    if(variant) window.location.href='/parts.php?variant='+variant;
    else window.location.href='/parts.php';
}
function oemSearch(e) {
    e.preventDefault();
    const oem = document.getElementById('oemInput').value;
    if(oem) window.location.href='/parts.php?search='+encodeURIComponent(oem);
}
function selectHomeBrand(slug) {
    fetch('/api/brands.php').then(r=>r.json()).then(brands => {
        const b = brands.find(x => x.slug === slug);
        if(b) { document.getElementById('homeBrand').value = b.id; loadModels(b.id, 'homeModel'); }
    });
}

// Kategori iconları (emoji yerine HTML entity kullanıyoruz)
const catIcons = {
    'motor-parcalari':'&#9881;','fren-sistemleri':'&#9898;','suspansiyon':'&#128295;',
    'aydinlatma':'&#128161;','kaporta-parcalari':'&#128663;','elektrik-elektronik':'&#9889;',
    'filtreler':'&#128270;','kayis-zincir':'&#9939;','egzoz-sistemi':'&#9729;',
    'sogutma-sistemi':'&#10052;','yaglar-sivilar':'&#128738;','ic-aksesuar':'&#128186;'
};

fetch('/api/categories.php').then(r=>r.json()).then(cats => {
    document.getElementById('categoryGrid').innerHTML = cats.map(c => `
        <a href="/parts.php?category=${c.slug}">
            <div class="card" style="padding:1.25rem 0.75rem;text-align:center;transition:all 0.2s;cursor:pointer" onmouseover="this.style.borderColor='#dc2626'" onmouseout="this.style.borderColor='#e5e7eb'">
                <div style="font-size:1.5rem;margin-bottom:0.5rem">${catIcons[c.slug]||'&#128230;'}</div>
                <div style="font-size:0.78rem;font-weight:600">${c.name}</div>
            </div>
        </a>
    `).join('');
});

// Marka model etiketleri
fetch('/api/brands.php').then(r=>r.json()).then(brands => {
    brands.forEach(b => {
        fetch('/api/models.php?brandId='+b.id).then(r=>r.json()).then(models => {
            const el = document.getElementById(b.slug==='hyundai'?'hyundaiModels':b.slug==='kia'?'kiaModels':null);
            if(el) el.innerHTML = models.slice(0,7).map(m => `<span style="background:#f3f4f6;padding:4px 12px;border-radius:20px;font-size:0.72rem;font-weight:600;color:#374151">${m.name}</span>`).join('');
        });
    });
});

// Son ürünler
fetch('/api/products.php').then(r=>r.json()).then(products => {
    const grid = document.getElementById('latestProducts');
    if(!Array.isArray(products)||products.length===0){
        grid.innerHTML = `
            <div style="grid-column:1/-1;">
                <div class="card" style="padding:3rem;text-align:center">
                    <div style="font-size:3rem;margin-bottom:1rem">&#128230;</div>
                    <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.5rem">Henüz Ürün Eklenmemiş</h3>
                    <p style="font-size:0.85rem;color:var(--gray-500);margin-bottom:1rem">Admin panelden hızlıca ürün ekleyebilirsiniz.</p>
                    <a href="/admin/login.php" class="btn-primary btn-sm">Admin Panele Git</a>
                </div>
            </div>`;
        return;
    }
    grid.innerHTML = products.slice(0,8).map(p => `
        <div class="card product-card">
            <div class="image">${p.image_url?`<img src="${p.image_url}" alt="${p.name}">`:'<span style="color:#d1d5db;font-size:2rem">&#128230;</span>'}</div>
            <div class="info">
                ${p.category_name?`<div class="category">${p.category_name}</div>`:''}
                <h3 class="name">${p.name}</h3>
                ${p.oem_no?`<div class="oem">OEM: ${p.oem_no}</div>`:''}
                ${p.part_brand?`<div class="oem">Marka: ${p.part_brand}</div>`:''}
                <div class="price-row">
                    <span class="price">&#8378;${parseFloat(p.price).toLocaleString('tr-TR',{minimumFractionDigits:2})}</span>
                    <button class="btn-primary btn-xs" onclick='Cart.add(${JSON.stringify({id:p.id,name:p.name,price:parseFloat(p.price),image_url:p.image_url||""})})'>Sepete Ekle</button>
                </div>
            </div>
        </div>
    `).join('');
});

loadBrands('homeBrand');
</script>

<?php include 'includes/footer.php'; ?>
