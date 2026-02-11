<?php
$pageTitle = 'Yedek Parçalar - Auto Akın';
include 'includes/header.php';
?>

<div class="container" style="padding-top:2rem;padding-bottom:3rem;flex:1">
    <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:1.5rem">Yedek <span class="text-red">Parçalar</span></h1>

    <!-- Filtreler -->
    <div class="filter-bar">
        <select class="form-select" id="filterBrand" onchange="loadModels(this.value,'filterModel')" style="min-width:140px">
            <option value="">Marka</option>
        </select>
        <select class="form-select" id="filterModel" onchange="loadVariants(this.value,'filterVariant')" disabled style="min-width:140px">
            <option value="">Model</option>
        </select>
        <select class="form-select" id="filterVariant" disabled style="min-width:180px">
            <option value="">Motor / Yıl</option>
        </select>
        <select class="form-select" id="filterCategory" style="min-width:140px">
            <option value="">Kategori</option>
        </select>
        <input type="text" class="form-input" id="filterSearch" placeholder="Parça adı veya OEM no..." style="flex:1;min-width:180px">
        <button class="btn-primary" onclick="fetchProducts()">Ara</button>
    </div>

    <!-- Sonuçlar -->
    <div id="productsLoading" style="text-align:center;padding:3rem;color:var(--gray-500)">Yükleniyor...</div>
    <div class="product-grid" id="productGrid" style="display:none"></div>
    <div id="productsEmpty" style="text-align:center;padding:3rem;color:var(--gray-500);display:none">
        Aradığınız kriterlere uygun ürün bulunamadı.
    </div>
</div>

<script>
// Araç seçici (header'dan kopyalanmış, filter prefix ile)
function loadBrands(selectId) {
    fetch('/api/brands.php').then(r=>r.json()).then(brands => {
        const sel = document.getElementById(selectId);
        brands.forEach(b => { const o = document.createElement('option'); o.value=b.id; o.textContent=b.name; sel.appendChild(o); });
    });
}
function loadModels(brandId, selectId) {
    const sel = document.getElementById(selectId);
    sel.innerHTML = '<option value="">Model</option>'; sel.disabled = !brandId;
    const vId = selectId.replace('Model','Variant');
    const varSel = document.getElementById(vId);
    if(varSel){varSel.innerHTML='<option value="">Motor / Yıl</option>'; varSel.disabled=true;}
    if(!brandId) return;
    fetch('/api/models.php?brandId='+brandId).then(r=>r.json()).then(models => {
        models.forEach(m=>{const o=document.createElement('option');o.value=m.id;o.textContent=m.name;sel.appendChild(o);});
        sel.disabled=false;
    });
}
function loadVariants(modelId, selectId) {
    const sel = document.getElementById(selectId);
    sel.innerHTML = '<option value="">Motor / Yıl</option>'; sel.disabled = !modelId;
    if(!modelId) return;
    fetch('/api/variants.php?modelId='+modelId).then(r=>r.json()).then(variants => {
        variants.forEach(v=>{
            const o=document.createElement('option'); o.value=v.id;
            o.textContent=`${v.year_start}-${v.year_end||'...'} ${v.engine_type} ${v.fuel_type}`;
            sel.appendChild(o);
        });
        sel.disabled=false;
    });
}

function fetchProducts() {
    const params = new URLSearchParams();
    const cat = document.getElementById('filterCategory').value;
    const variant = document.getElementById('filterVariant').value;
    const search = document.getElementById('filterSearch').value;
    if (cat) params.set('category', cat);
    if (variant) params.set('variant', variant);
    if (search) params.set('search', search);

    document.getElementById('productsLoading').style.display = 'block';
    document.getElementById('productGrid').style.display = 'none';
    document.getElementById('productsEmpty').style.display = 'none';

    fetch('/api/products.php?' + params.toString()).then(r => r.json()).then(products => {
        document.getElementById('productsLoading').style.display = 'none';
        if (!Array.isArray(products) || products.length === 0) {
            document.getElementById('productsEmpty').style.display = 'block';
            return;
        }
        const grid = document.getElementById('productGrid');
        grid.style.display = 'grid';
        grid.innerHTML = products.map(p => `
            <div class="card product-card">
                <div class="image">
                    ${p.image_url ? `<img src="${p.image_url}" alt="${p.name}">` : '<span style="color:var(--gray-300);font-size:0.8rem">Görsel Yok</span>'}
                </div>
                <div class="info">
                    ${p.category_name ? `<div class="category">${p.category_name}</div>` : ''}
                    <h3 class="name">${p.name}</h3>
                    ${p.oem_no ? `<div class="oem">OEM: ${p.oem_no}</div>` : ''}
                    ${p.part_brand ? `<div class="oem">Marka: ${p.part_brand}</div>` : ''}
                    <div class="price-row">
                        <span class="price">₺${parseFloat(p.price).toLocaleString('tr-TR',{minimumFractionDigits:2})}</span>
                        <button class="btn-primary btn-xs" onclick='Cart.add(${JSON.stringify({id:p.id,name:p.name,price:parseFloat(p.price),image_url:p.image_url})})'>Sepete Ekle</button>
                    </div>
                    ${parseInt(p.stock) <= 0 ? '<div style="font-size:0.75rem;color:var(--primary);font-weight:600;margin-top:6px">Stokta Yok</div>' : ''}
                </div>
            </div>
        `).join('');
    });
}

// Init
loadBrands('filterBrand');
fetch('/api/categories.php').then(r=>r.json()).then(cats => {
    const sel = document.getElementById('filterCategory');
    cats.forEach(c => { const o = document.createElement('option'); o.value=c.slug; o.textContent=c.name; sel.appendChild(o); });
});

// URL params
const urlP = new URLSearchParams(window.location.search);
if (urlP.get('category')) document.getElementById('filterCategory').value = urlP.get('category');
if (urlP.get('search')) document.getElementById('filterSearch').value = urlP.get('search');

if (urlP.get('variant')) {
    // Variant set edilince ürünleri çek
    setTimeout(() => { document.getElementById('filterVariant').value = urlP.get('variant'); fetchProducts(); }, 500);
} else {
    fetchProducts();
}
</script>

<?php include 'includes/footer.php'; ?>
