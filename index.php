<?php
$pageTitle = 'Auto Akın - Hyundai & Kia Yedek Parça';
$pageDesc = 'Hyundai ve Kia araçlarınız için orijinal ve muadil yedek parçalar. OEM garantili, hızlı kargo.';
include 'includes/header.php';
?>

<!-- Hero Section - Professional Layout -->
<section style="background: var(--gray-50); padding: 1.5rem 0 3rem;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 340px 1fr; gap: 1.5rem;">
            
            <!-- Left: Vehicle Selection Widget -->
            <div class="card" style="padding: 1.5rem; border: 1px solid var(--gray-200); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1.25rem;">
                    <div style="width: 32px; height: 32px; background: var(--secondary); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fas fa-car"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 0.95rem; font-weight: 800; color: var(--secondary); margin: 0;">Araç Seçin</h3>
                        <p style="font-size: 0.72rem; color: var(--gray-500); margin: 0;">Aracınıza %100 uyumlu parçaları listeleyin</p>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <label class="form-label" style="font-size: 0.75rem;">MARKA</label>
                        <select class="form-select" id="homeBrand" onchange="loadModels(this.value, 'homeModel')">
                            <option value="">Marka Seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.75rem;">MODEL</label>
                        <select class="form-select" id="homeModel" onchange="loadVariants(this.value, 'homeVariant')" disabled>
                            <option value="">Model Seçin</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.75rem;">MOTOR / YIL</label>
                        <select class="form-select" id="homeVariant" disabled>
                            <option value="">Seçin</option>
                        </select>
                    </div>
                    <button class="btn-primary" onclick="searchParts()" style="width: 100%; padding: 12px; margin-top: 5px;">
                        <i class="fas fa-search" style="font-size: 0.8rem;"></i> PARÇALARI BUL
                    </button>
                    
                    <div style="text-align: center; padding-top: 1rem; border-top: 1px solid var(--gray-100); margin-top: 0.5rem;">
                        <span style="font-size: 0.7rem; color: var(--gray-400); text-transform: uppercase; font-weight: 700;">HIZLI OEM ARAMA</span>
                        <form onsubmit="oemSearch(event)" style="margin-top: 8px; position: relative;">
                            <input class="form-input" id="oemInput" placeholder="OEM Numarası..." style="padding-right: 40px; font-size: 0.8rem;">
                            <button type="submit" style="position: absolute; right: 0; top: 0; bottom: 0; background: none; border: none; padding: 0 12px; color: var(--primary); cursor: pointer;"><i class="fas fa-arrow-right"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Large Banner / Slider Area -->
            <div style="position: relative; border-radius: var(--radius); overflow: hidden; background: #0f172a;">
                <!-- Main Banner -->
                <div style="height: 100%; width: 100%; min-height: 400px; padding: 4rem; display: flex; flex-direction: column; justify-content: center; color: white;">
                    <div style="background: var(--primary); color: white; padding: 5px 15px; border-radius: 4px; font-size: 0.75rem; font-weight: 800; width: fit-content; margin-bottom: 1rem;">
                        HYUNDAI & KIA UZMANI
                    </div>
                    <h1 style="font-size: 3rem; font-weight: 950; margin-bottom: 1.5rem; line-height: 1.1; letter-spacing: -1.5px;">
                        TÜRKİYE'NİN EN BÜYÜK<br>
                        <span style="color: var(--primary);">HYUNDAI & KIA</span><br>
                        YEDEK PARÇA DEPOSU
                    </h1>
                    <p style="font-size: 1rem; color: #94a3b8; max-width: 500px; margin-bottom: 2.5rem; line-height: 1.6;">
                        Orijinal ve muadil 20.000+ ürün çeşidiyle Hyundai ve Kia araçlarınız için en doğru adres.
                    </p>
                    <div style="display: flex; gap: 15px;">
                        <a href="/parts" class="btn-primary" style="padding: 14px 30px;">
                            Hemen Alışverişe Başla <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i>
                        </a>
                        <a href="https://wa.me/905000000000" class="btn-outline" style="background: #25d366; border-color: #25d366; color: white; padding: 14px 25px;">
                            <i class="fab fa-whatsapp"></i> Whatsapp Sor-Al
                        </a>
                    </div>
                </div>
                <!-- Banner Image Placeholder / Decoration -->
                <div style="position: absolute; right: -50px; bottom: -20px; opacity: 0.4;">
                    <i class="fas fa-gear" style="font-size: 15rem; color: rgba(255,255,255,0.05); animation: spin 20s linear infinite;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<!-- Featured Sections -->
<section class="container" style="padding: 2rem 0;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <!-- Left: Quick Select Brands -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 15px; cursor: pointer;" onclick="selectHomeBrand('hyundai')">
                <div style="width: 50px; height: 50px; background: #eef2ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #1e40af; font-weight: 900; font-size: 1.5rem;">H</div>
                <div>
                    <h4 style="font-size: 1rem; font-weight: 800; margin: 0;">Hyundai</h4>
                    <p style="font-size: 0.7rem; color: var(--gray-500); margin: 0;">Tüm modeller için parçalar</p>
                </div>
            </div>
            <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 15px; cursor: pointer;" onclick="selectHomeBrand('kia')">
                <div style="width: 50px; height: 50px; background: #fff1f2; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #be123c; font-weight: 900; font-size: 1rem;">KIA</div>
                <div>
                    <h4 style="font-size: 1rem; font-weight: 800; margin: 0;">Kia</h4>
                    <p style="font-size: 0.7rem; color: var(--gray-500); margin: 0;">Tüm modeller için parçalar</p>
                </div>
            </div>
        </div>
        <!-- Right: Promo Text -->
        <div class="card" style="background: var(--primary); color: white; padding: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h4 style="font-weight: 800; margin: 0; font-size: 1.1rem;">Bize Sorun!</h4>
                <p style="font-size: 0.8rem; margin: 0; opacity: 0.9;">Doğru parçayı bulamadınız mı? Şasi numaranız ile sorgulayalım.</p>
            </div>
            <a href="/contact" class="btn-secondary btn-sm" style="background: white; color: var(--primary); border: none;">Canlı Destek <i class="fas fa-headset"></i></a>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="container" style="padding: 2rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; border-bottom: 2px solid var(--gray-100); padding-bottom: 10px;">
        <h2 style="font-size: 1.5rem; font-weight: 900; color: var(--secondary); margin: 0;">Popüler <span style="color: var(--primary);">Kategoriler</span></h2>
        <a href="/parts" style="font-size: 0.85rem; font-weight: 700; color: var(--primary);">Tümünü İncele <i class="fas fa-chevron-right" style="font-size: 0.7rem;"></i></a>
    </div>
    <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem;" id="categoryGrid"></div>
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
        <a href="/parts" style="font-size:0.85rem;color:#dc2626;font-weight:600">Tümünü Gör →</a>
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
    if(variant) window.location.href='/parts?variant='+variant;
    else window.location.href='/parts';
}
function oemSearch(e) {
    e.preventDefault();
    const oem = document.getElementById('oemInput').value;
    if(oem) window.location.href='/parts?search='+encodeURIComponent(oem);
}
function selectHomeBrand(slug) {
    fetch('/api/brands.php').then(r=>r.json()).then(brands => {
        const b = brands.find(x => x.slug === slug);
        if(b) { document.getElementById('homeBrand').value = b.id; loadModels(b.id, 'homeModel'); }
    });
}

// Kategori iconları (FontAwesome kullanıyoruz)
const catIcons = {
    'motor-parcalari':'fas fa-cogs','fren-sistemleri':'fas fa-circle-notch','suspansiyon':'fas fa-tools',
    'aydinlatma':'fas fa-lightbulb','kaporta-parcalari':'fas fa-car-side','elektrik-elektronik':'fas fa-bolt',
    'filtreler':'fas fa-filter','kayis-zincir':'fas fa-link','egzoz-sistemi':'fas fa-smog',
    'sogutma-sistemi':'fas fa-snowflake','yaglar-sivilar':'fas fa-oil-can','ic-aksesuar':'fas fa-couch'
};

fetch('/api/categories.php').then(r=>r.json()).then(cats => {
    document.getElementById('categoryGrid').innerHTML = cats.map(c => `
        <a href="/parts?category=${c.slug}" style="text-decoration:none; color:inherit;">
            <div class="card" style="padding:1.5rem 1rem;text-align:center;transition:all 0.3s;cursor:pointer;border: 1px solid var(--gray-100);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-5px)';" onmouseout="this.style.borderColor='var(--gray-100)'; this.style.transform='translateY(0)';" >
                <div style="font-size:1.8rem;margin-bottom:0.75rem; color:var(--primary);"><i class="${catIcons[c.slug]||'fas fa-box'}"></i></div>
                <div style="font-size:0.8rem;font-weight:700; color:var(--secondary);">${c.name}</div>
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
                    <a href="/admin/login" class="btn-primary btn-sm">Admin Panele Git</a>
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
