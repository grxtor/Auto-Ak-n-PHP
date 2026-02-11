<?php
$pageTitle = 'Araç Yönetimi';
$pageDesc = 'Uyumlu parça eşleştirmesi için marka, model ve motor varyantlarını düzenleyin.';
include 'includes/header.php';
?>

<div class="container" style="margin-top:-2.5rem;padding-bottom:3rem">
        <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:1.5rem">Araç <span class="text-red">Yönetimi</span></h1>

        <div class="vehicle-manager">
            <!-- Markalar -->
            <div class="card" style="padding:1rem">
                <h2 style="font-size:1rem;font-weight:700;margin-bottom:1rem">Markalar</h2>
                <form onsubmit="addBrand(event)" style="display:flex;gap:6px;margin-bottom:1rem">
                    <input class="form-input" id="newBrand" placeholder="Yeni marka" style="flex:1">
                    <button type="submit" class="btn-primary btn-xs">Ekle</button>
                </form>
                <div class="vehicle-list" id="brandList"></div>
            </div>

            <!-- Modeller -->
            <div class="card" style="padding:1rem">
                <h2 style="font-size:1rem;font-weight:700;margin-bottom:1rem">Modeller <span id="modelBrandName" style="font-size:0.75rem;color:var(--gray-500)"></span></h2>
                <div id="modelFormWrap">
                    <p style="color:var(--gray-500);font-size:0.85rem">Önce bir marka seçin</p>
                </div>
                <div class="vehicle-list" id="modelList"></div>
            </div>

            <!-- Varyantlar -->
            <div class="card" style="padding:1rem">
                <h2 style="font-size:1rem;font-weight:700;margin-bottom:1rem">Motor Varyantları <span id="varModelName" style="font-size:0.75rem;color:var(--gray-500)"></span></h2>
                <div id="varFormWrap">
                    <p style="color:var(--gray-500);font-size:0.85rem">Önce bir model seçin</p>
                </div>
                <div class="vehicle-list" id="variantList" style="max-height:300px;overflow-y:auto"></div>
            </div>
        </div>
    </div>

    <script>
    if(!localStorage.getItem('admin_auth'))window.location='/admin/login';

    let selBrand = null, selModel = null, brandsData = [], modelsData = [];

    function fetchBrands() {
        fetch('/api/admin/brands.php').then(r=>r.json()).then(brands => {
            brandsData = brands;
            document.getElementById('brandList').innerHTML = brands.map(b => `
                <div class="vehicle-item ${selBrand==b.id?'active':''}" onclick="selectBrand(${b.id})">
                    <span>${b.name}</span>
                    <button class="delete-btn" onclick="event.stopPropagation();deleteBrand(${b.id})">✕</button>
                </div>
            `).join('');
        });
    }

    function selectBrand(id) {
        selBrand = id; selModel = null;
        fetchBrands();
        const brand = brandsData.find(b=>b.id==id);
        document.getElementById('modelBrandName').textContent = brand ? `(${brand.name})` : '';
        document.getElementById('modelFormWrap').innerHTML = `
            <form onsubmit="addModel(event)" style="display:flex;gap:6px;margin-bottom:1rem">
                <input class="form-input" id="newModel" placeholder="Yeni model" style="flex:1">
                <button type="submit" class="btn-primary btn-xs">Ekle</button>
            </form>
        `;
        document.getElementById('varFormWrap').innerHTML = '<p style="color:var(--gray-500);font-size:0.85rem">Önce bir model seçin</p>';
        document.getElementById('variantList').innerHTML = '';
        document.getElementById('varModelName').textContent = '';
        fetchModels(id);
    }

    function fetchModels(brandId) {
        fetch('/api/admin/models.php?brandId='+brandId).then(r=>r.json()).then(models => {
            modelsData = models;
            document.getElementById('modelList').innerHTML = models.map(m => `
                <div class="vehicle-item ${selModel==m.id?'active':''}" onclick="selectModel(${m.id})">
                    <span>${m.name}</span>
                    <button class="delete-btn" onclick="event.stopPropagation();deleteModel(${m.id})">✕</button>
                </div>
            `).join('');
        });
    }

    function selectModel(id) {
        selModel = id;
        fetchModels(selBrand);
        const model = modelsData.find(m=>m.id==id);
        document.getElementById('varModelName').textContent = model ? `(${model.name})` : '';
        document.getElementById('varFormWrap').innerHTML = `
            <form onsubmit="addVariant(event)" style="display:flex;flex-direction:column;gap:6px;margin-bottom:1rem">
                <div style="display:flex;gap:6px">
                    <input class="form-input" type="number" id="vYearStart" placeholder="Yıl başlangıç" required style="flex:1">
                    <input class="form-input" type="number" id="vYearEnd" placeholder="Yıl bitiş" style="flex:1">
                </div>
                <input class="form-input" id="vEngine" placeholder="Motor tipi (ör: 1.4 TDCi)" required>
                <div style="display:flex;gap:6px">
                    <select class="form-select" id="vFuel" style="flex:1">
                        <option value="benzin">Benzin</option>
                        <option value="dizel">Dizel</option>
                        <option value="lpg">LPG</option>
                        <option value="elektrik">Elektrik</option>
                        <option value="hibrit">Hibrit</option>
                    </select>
                    <input class="form-input" type="number" id="vHP" placeholder="HP" style="flex:1">
                </div>
                <button type="submit" class="btn-primary btn-sm">Varyant Ekle</button>
            </form>
        `;
        fetchVariants(id);
    }

    function fetchVariants(modelId) {
        fetch('/api/admin/variants.php?modelId='+modelId).then(r=>r.json()).then(variants => {
            document.getElementById('variantList').innerHTML = variants.map(v => `
                <div style="display:flex;justify-content:space-between;align-items:center;padding:8px;font-size:0.8rem;border-bottom:1px solid var(--gray-100)">
                    <div><strong>${v.year_start}-${v.year_end||'...'}</strong> ${v.engine_type}<br><small style="color:var(--gray-500)">${v.fuel_type} ${v.horsepower?'| '+v.horsepower+'HP':''}</small></div>
                    <button class="delete-btn" onclick="deleteVariant(${v.id})">✕</button>
                </div>
            `).join('');
        });
    }

    function addBrand(e) {
        e.preventDefault();
        const name = document.getElementById('newBrand').value;
        if(!name) return;
        fetch('/api/admin/brands.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name})})
        .then(()=>{document.getElementById('newBrand').value='';fetchBrands();});
    }
    function addModel(e) {
        e.preventDefault();
        const name = document.getElementById('newModel').value;
        if(!name||!selBrand) return;
        fetch('/api/admin/models.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({brand_id:selBrand,name})})
        .then(()=>{document.getElementById('newModel').value='';fetchModels(selBrand);});
    }
    function addVariant(e) {
        e.preventDefault();
        if(!selModel) return;
        fetch('/api/admin/variants.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({
            model_id:selModel,
            year_start:document.getElementById('vYearStart').value,
            year_end:document.getElementById('vYearEnd').value||null,
            engine_type:document.getElementById('vEngine').value,
            fuel_type:document.getElementById('vFuel').value,
            horsepower:document.getElementById('vHP').value||null
        })}).then(()=>fetchVariants(selModel));
    }
    function deleteBrand(id){if(!confirm('Bu markayı silmek istediğinize emin misiniz?'))return;fetch('/api/admin/brands.php?id='+id,{method:'DELETE'}).then(()=>{selBrand=null;fetchBrands();});}
    function deleteModel(id){if(!confirm('Bu modeli silmek istediğinize emin misiniz?'))return;fetch('/api/admin/models.php?id='+id,{method:'DELETE'}).then(()=>{selModel=null;fetchModels(selBrand);});}
    function deleteVariant(id){if(!confirm('Bu varyantı silmek istediğinize emin misiniz?'))return;fetch('/api/admin/variants.php?id='+id,{method:'DELETE'}).then(()=>fetchVariants(selModel));}

    fetchBrands();
    </script>
</body>
</html>
