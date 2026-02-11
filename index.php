<?php
$pageTitle = 'Auto Akın - Hyundai & Kia Yedek Parça';
$pageDesc = 'Hyundai ve Kia araçlarınız için orijinal ve muadil yedek parçalar. OEM garantili, hızlı kargo.';
include 'includes/header.php';
?>

<!-- Hero Section - Premium Overhaul -->
<section style="background: white; padding: 2rem 0 4rem; position: relative; overflow: hidden;">
    <!-- Abstract Background Elements -->
    <div style="position: absolute; top: -10%; right: -5%; width: 40%; height: 60%; background: radial-gradient(circle, rgba(239, 68, 68, 0.05) 0%, transparent 70%); z-index: 0;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div style="display: grid; grid-template-columns: 380px 1fr; gap: 2rem; align-items: start;">
            
            <!-- Left: Advanced Vehicle Selector -->
            <div class="card" style="padding: 2rem; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.08); border-radius: 20px; background: white; border: 1px solid var(--gray-100);">
                <div style="margin-bottom: 2rem;">
                    <span style="background: var(--primary); color: white; padding: 4px 12px; border-radius: 50px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Akıllı Arama</span>
                    <h2 style="font-size: 1.5rem; font-weight: 950; color: var(--secondary); margin: 0.5rem 0 0.25rem;">HIZLI PARÇA BUL</h2>
                    <p style="font-size: 0.8rem; color: var(--gray-500); margin: 0;">Aracınıza özel uyumlu parçaları saniyeler içinde süzün.</p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <div class="select-group">
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--gray-400); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Marka Seçimi</label>
                        <select class="form-select custom-select" id="homeBrand" onchange="loadModels(this.value, 'homeModel')">
                            <option value="">Marka Seçin</option>
                        </select>
                    </div>
                    <div class="select-group">
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--gray-400); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Model</label>
                        <select class="form-select custom-select" id="homeModel" onchange="loadVariants(this.value, 'homeVariant')" disabled>
                            <option value="">Model Seçin</option>
                        </select>
                    </div>
                    <div class="select-group">
                        <label style="display: block; font-size: 0.7rem; font-weight: 800; color: var(--gray-400); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Motor / Yıl</label>
                        <select class="form-select custom-select" id="homeVariant" disabled>
                            <option value="">Motor / Yıl Seçin</option>
                        </select>
                    </div>
                    
                    <button class="btn-primary" onclick="searchParts()" style="width: 100%; padding: 16px; border-radius: 12px; font-weight: 800; font-size: 0.9rem; margin-top: 0.5rem; box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2);">
                         UYUMLU PARÇALARI LİSTELE <i class="fas fa-chevron-right" style="margin-left: 8px; font-size: 0.7rem;"></i>
                    </button>
                    
                    <div style="text-align: center; padding-top: 1.5rem; border-top: 1px dotted var(--gray-200); margin-top: 0.5rem;">
                        <p style="font-size: 0.7rem; color: var(--gray-400); font-weight: 700; margin-bottom: 10px;">Veya OEM Numarası ile Arayın</p>
                        <form onsubmit="oemSearch(event)" style="position: relative;">
                            <input class="form-input" id="oemInput" placeholder="Örn: 58101-1RA00" style="padding: 12px 45px 12px 15px; background: var(--gray-50); border: 1px solid var(--gray-100); border-radius: 10px; font-size: 0.85rem; font-family: monospace;">
                            <button type="submit" style="position: absolute; right: 5px; top: 5px; bottom: 5px; width: 35px; background: white; border: none; border-radius: 8px; color: var(--primary); cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.05);"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right: Premium Branding -->
            <div style="height: 100%; min-height: 500px; display: flex; flex-direction: column; justify-content: center; padding-left: 2rem;">
                <div style="display: inline-flex; align-items: center; gap: 8px; background: #fef2f2; color: #dc2626; padding: 6px 16px; border-radius: 100px; font-size: 0.8rem; font-weight: 800; margin-bottom: 1.5rem;">
                    <span style="display: block; width: 8px; height: 8px; background: #dc2626; border-radius: 50%; animation: pulse 2s infinite;"></span>
                    GÜNCEL STOK: 20.000+ ÜRÜN
                </div>
                
                <h1 style="font-size: 4.5rem; font-weight: 1000; line-height: 0.95; color: var(--secondary); margin-bottom: 1.5rem; letter-spacing: -3px;">
                    Hyundai & Kia<br>
                    <span style="color: var(--primary); text-shadow: 0 10px 30px rgba(220, 38, 38, 0.1);">Yedek Parça</span><br>
                    Uzmanlığı.
                </h1>
                
                <p style="font-size: 1.15rem; color: var(--gray-500); max-width: 580px; margin-bottom: 3rem; line-height: 1.6; font-weight: 500;">
                    Auto Akın, 20 yılı aşkın tecrübesiyle Hyundai ve Kia araçlarınız için %100 uyumlu, garantili yedek parçaları en uygun fiyatlarla kapınıza getiriyor.
                </p>

                <div style="display: flex; gap: 20px;">
                    <a href="<?= BASE_URL ?>/parts" class="btn-primary" style="padding: 18px 40px; border-radius: 14px; font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 12px; box-shadow: 0 15px 30px rgba(220, 38, 38, 0.25);">
                        KATALOGU İNCELE <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="https://wa.me/905000000000" class="btn-outline" style="padding: 18px 30px; border-radius: 14px; font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 10px; border: 2.5px solid #25d366; color: #25d366;">
                        <i class="fab fa-whatsapp" style="font-size: 1.4rem;"></i> ŞASİ NO İLE SOR
                    </a>
                </div>

                <div style="margin-top: 4rem; display: flex; gap: 40px; align-items: center;">
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 1.5rem; font-weight: 900; color: var(--secondary); line-height: 1;">%100</span>
                        <span style="font-size: 0.75rem; color: var(--gray-400); font-weight: 700; text-transform: uppercase;">Uyum Garantisi</span>
                    </div>
                    <div style="width: 1px; height: 30px; background: var(--gray-200);"></div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 1.5rem; font-weight: 900; color: var(--secondary); line-height: 1;">Aynı Gün</span>
                        <span style="font-size: 0.75rem; color: var(--gray-400); font-weight: 700; text-transform: uppercase;">Hızlı Kargo</span>
                    </div>
                    <div style="width: 1px; height: 30px; background: var(--gray-200);"></div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 1.5rem; font-weight: 900; color: var(--secondary); line-height: 1;">OEM</span>
                        <span style="font-size: 0.75rem; color: var(--gray-400); font-weight: 700; text-transform: uppercase;">Parça Sorgulama</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
.custom-select {
    background-color: var(--gray-50);
    border: 1.5px solid var(--gray-100);
    border-radius: 10px;
    padding: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.custom-select:focus { border-color: var(--primary); outline: none; background: white; }
.custom-select:disabled { opacity: 0.5; cursor: not-allowed; }
</style>

<!-- Brand Quick Access -->
<section style="padding: 4rem 0; background: #ffffff;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
            <div onclick="selectHomeBrand('hyundai')" class="brand-card hyundai">
                <div class="brand-visual">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Hyundai_Motor_Company_logo.svg/2560px-Hyundai_Motor_Company_logo.svg.png" alt="Hyundai">
                </div>
                <div class="brand-content">
                    <h3>HYUNDAI</h3>
                    <p>Orijinal ve Garantili Parçalar</p>
                    <span class="explore-btn">Hemen İncele <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
            
            <div onclick="selectHomeBrand('kia')" class="brand-card kia">
                <div class="brand-visual">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/47/KIA_logo_2021.svg/2560px-KIA_logo_2021.svg.png" alt="Kia">
                </div>
                <div class="brand-content">
                    <h3>KIA</h3>
                    <p>Hyundai Grubu Kalite ve Güvencesiyle</p>
                    <span class="explore-btn">Hemen İncele <i class="fas fa-arrow-right"></i></span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.brand-card {
    position: relative;
    background: #f8fafc;
    border-radius: 32px;
    padding: 3rem;
    display: flex;
    align-items: center;
    gap: 2rem;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(0,0,0,0.03);
    overflow: hidden;
    text-decoration: none;
}
.brand-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.8), transparent);
    opacity: 0;
    transition: opacity 0.4s;
}
.brand-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.1);
    background: white;
}
.brand-card:hover::before { opacity: 1; }

.brand-visual {
    width: 100px;
    height: 100px;
    background: white;
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    transition: all 0.4s;
    z-index: 1;
}
.brand-card:hover .brand-visual {
    transform: scale(1.1) rotate(-5deg);
}
.brand-visual img {
    width: 70%;
    height: auto;
    filter: grayscale(1);
    opacity: 0.7;
    transition: all 0.4s;
}
.brand-card:hover .brand-visual img {
    filter: none;
    opacity: 1;
}

.brand-content { position: relative; z-index: 1; }
.brand-content h3 {
    font-size: 1.75rem;
    font-weight: 1000;
    margin: 0;
    letter-spacing: -0.5px;
    color: var(--secondary);
}
.brand-content p {
    color: var(--gray-500);
    margin: 8px 0 20px;
    font-size: 0.9rem;
    font-weight: 500;
}
.explore-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: var(--primary);
    font-weight: 800;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.brand-card:hover .explore-btn i {
    transform: translateX(5px);
    transition: transform 0.3s;
}
.brand-card.hyundai:hover { border-bottom: 6px solid #003478; }
.brand-card.kia:hover { border-bottom: 6px solid #bb162b; }
</style>

<!-- Categories Grid -->
<section class="container" style="padding: 5rem 0;">
    <div style="text-align: center; margin-bottom: 3.5rem;">
        <h2 style="font-size: 2.25rem; font-weight: 1000; color: var(--secondary); letter-spacing: -1px;">POPÜLER KATEGORİLER</h2>
        <div style="width: 60px; height: 4px; background: var(--primary); margin: 1rem auto; border-radius: 10px;"></div>
    </div>
    <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 1.25rem;" id="categoryGrid"></div>
</section>

<!-- Ürünler -->
<section style="background: #f8fafc; padding: 5rem 0;">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:2.5rem">
            <div>
                <h2 style="font-size: 2rem; font-weight: 1000; color: var(--secondary); letter-spacing: -1px;">YENİ GELENLER</h2>
                <p style="font-size: 0.9rem; color: var(--gray-500); margin-top: 5px;">Depomuza en son eklenen orijinal yedek parçalar.</p>
            </div>
            <a href="<?= BASE_URL ?>/parts" style="font-size: 0.9rem; color: var(--primary); font-weight: 800; text-decoration: none; border-bottom: 2px solid; padding-bottom: 4px;">Tümünü Gör <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></a>
        </div>
        <div class="product-grid" id="latestProducts"></div>
    </div>
</section>

<script>
function loadBrands(selectId) {
    fetch(API_BASE + '/brands').then(r=>r.json()).then(brands => {
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
    fetch(API_BASE + '/models?brandId='+brandId).then(r=>r.json()).then(models => {
        models.forEach(m=>{const o=document.createElement('option');o.value=m.id;o.textContent=m.name;sel.appendChild(o);});
        sel.disabled=false;
    });
}
function loadVariants(modelId, selectId) {
    const sel = document.getElementById(selectId);
    sel.innerHTML = '<option value="">Motor / Yıl Seçin</option>'; sel.disabled = !modelId;
    if(!modelId) return;
    fetch(API_BASE + '/variants?modelId='+modelId).then(r=>r.json()).then(variants => {
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
    if(variant) window.location.href='<?= BASE_URL ?>/parts?variant='+variant;
    else window.location.href='<?= BASE_URL ?>/parts';
}
function oemSearch(e) {
    e.preventDefault();
    const oem = document.getElementById('oemInput').value;
    if(oem) window.location.href='<?= BASE_URL ?>/parts?search='+encodeURIComponent(oem);
}
function selectHomeBrand(slug) {
    fetch(API_BASE + '/brands.php').then(r=>r.json()).then(brands => {
        const b = brands.find(x => x.slug === slug);
        if(b) { 
            document.getElementById('homeBrand').value = b.id; 
            loadModels(b.id, 'homeModel');
            // Scroll to top to see results
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
}

// Kategori icons
const catIcons = {
    'motor-parcalari':'fas fa-cogs','fren-sistemleri':'fas fa-circle-notch','suspansiyon-parcalari':'fas fa-tools',
    'aydinlatma-grubu':'fas fa-lightbulb','kaporta-parcalari':'fas fa-car-side','elektrik-elektronik':'fas fa-bolt',
    'filtreler':'fas fa-filter','kayis-zincir':'fas fa-link','egzoz-sistemi':'fas fa-smog',
    'sogutma-sistemi':'fas fa-snowflake','yaglar-sivilar':'fas fa-oil-can','ic-aksesuar':'fas fa-couch'
};

fetch(API_BASE + '/categories').then(r=>r.json()).then(cats => {
    document.getElementById('categoryGrid').innerHTML = cats.map(c => `
        <a href="<?= BASE_URL ?>/parts?category=${c.slug}" style="text-decoration:none; color:inherit;">
            <div class="card" style="padding:2.5rem 1rem;text-align:center;transition:all 0.4s;cursor:pointer;border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.03); border-radius: 20px;" onmouseover="this.style.transform='translateY(-10px)';this.style.boxShadow='0 20px 40px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 5px 15px rgba(0,0,0,0.03)';" >
                <div style="font-size:2.2rem;margin-bottom:1rem; color:var(--primary);"><i class="${catIcons[c.slug]||'fas fa-box'}"></i></div>
                <div style="font-size:0.85rem;font-weight:900; color: var(--secondary); text-transform: uppercase;">${c.name}</div>
            </div>
        </a>
    `).join('');
});

// Son urunler
fetch(API_BASE + '/products').then(r=>r.json()).then(products => {
    const grid = document.getElementById('latestProducts');
    if(!Array.isArray(products)||products.length===0){
        grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:4rem;background:white;border-radius:20px;border:1px dashed var(--gray-300);color:var(--gray-400)">Henüz ürün eklenmemiş.</div>`;
        return;
    }
    grid.innerHTML = products.slice(0,8).map(p => `
        <div class="card product-card" style="border:none;box-shadow:0 10px 25px rgba(0,0,0,0.05);border-radius:20px;overflow:hidden">
            <div class="image" style="height:200px;background:#fff;display:flex;align-items:center;justify-content:center;padding:1.5rem">
                ${p.image_url?`<img src="<?= BASE_URL ?>${p.image_url}" style="max-height:100%;max-width:100%;object-fit:contain">`:'<i class="fas fa-image" style="font-size:3rem;color:var(--gray-200)"></i>'}
            </div>
            <div class="info" style="padding:1.5rem">
                <div style="font-size:0.7rem;font-weight:800;color:var(--primary);text-transform:uppercase;margin-bottom:8px">${p.category_name||'Parça'}</div>
                <h3 class="name" style="font-size:1rem;font-weight:800;margin-bottom:4px;height:2.6rem;overflow:hidden;line-height:1.3">${p.name}</h3>
                <div style="font-size:0.75rem;color:var(--gray-400);margin-bottom:1.5rem;font-weight:600">OEM: ${p.oem_no||'-'}</div>
                <div class="price-row" style="display:flex;justify-content:space-between;align-items:center">
                    <span class="price" style="font-size:1.25rem;font-weight:950;color:var(--secondary)">₺${parseFloat(p.price).toLocaleString('tr-TR',{minimumFractionDigits:2})}</span>
                    <button class="btn-primary btn-xs" style="padding:8px 15px;border-radius:10px" onclick='Cart.add(${JSON.stringify({id:p.id,name:p.name,price:parseFloat(p.price),image_url:p.image_url||""})})'><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
    `).join('');
});

loadBrands('homeBrand');
</script>

<?php include 'includes/footer.php'; ?>
