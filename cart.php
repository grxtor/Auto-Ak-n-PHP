<?php
$pageTitle = 'Sepetim - Auto Akın';
include 'includes/header.php';
?>

<div class="container" style="padding-top:2rem;padding-bottom:3rem;flex:1">
    <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:1.5rem">Sepetim</h1>

    <div id="cartEmpty" style="text-align:center;padding:4rem 2rem;display:none">
        <div style="font-size:3rem;margin-bottom:1rem">&#128722;</div>
        <h2 style="font-size:1.15rem;font-weight:700;margin-bottom:0.5rem">Sepetiniz bo&#351;</h2>
        <p style="color:var(--gray-500);font-size:0.9rem;margin-bottom:1.5rem">Hen&uuml;z sepetinize &uuml;r&uuml;n eklemediniz.</p>
        <a href="/parts" class="btn-primary" style="padding:12px 28px">&#128270; Par&ccedil;alara G&ouml;z At</a>
    </div>

    <div class="cart-grid" id="cartContent" style="display:none">
        <div>
            <div id="cartItems"></div>
        </div>
        <div class="cart-summary">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem">Sipari&#351; &Ouml;zeti</h3>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100)">
                <span style="color:var(--gray-500);font-size:0.9rem">Ara Toplam</span>
                <span id="cartSubtotal" style="font-weight:600">&#8378;0</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100)">
                <span style="color:var(--gray-500);font-size:0.9rem">Kargo</span>
                <span style="font-weight:600;color:#059669">&Uuml;cretsiz</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 0;font-size:1.1rem">
                <span style="font-weight:800">Toplam</span>
                <span id="cartTotal" style="font-weight:800;color:var(--primary)">&#8378;0</span>
            </div>

            <hr style="border:none;border-top:1px solid var(--gray-200);margin:1.5rem 0">
            
            <div id="authCheck" style="padding:1rem;background:var(--gray-50);border-radius:var(--radius);margin-bottom:1rem;text-align:center">
                <p style="font-size:0.8rem;color:var(--gray-500);margin-bottom:8px">Sipari&#351; i&ccedil;in giri&#351; yapman&#305;z &ouml;nerilir.</p>
                <a href="/login" class="text-red" style="font-weight:700;font-size:0.85rem">Hesab&#305;n Var m&#305;? Giri&#351; Yap</a>
            </div>

            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:0.75rem">Teslimat Bilgileri</h4>
            <div style="display:flex;flex-direction:column;gap:8px">
                <input class="form-input" id="custName" placeholder="Ad Soyad" required>
                <input class="form-input" id="custEmail" placeholder="E-posta" type="email" required>
                <input class="form-input" id="custPhone" placeholder="Telefon" required>
                <textarea class="form-input" id="custAddress" placeholder="Adres" rows="2" style="resize:none" required></textarea>
                <div style="display:flex;align-items:center;gap:8px;margin-top:4px" id="saveAddrWrap">
                    <input type="checkbox" id="saveAddress" checked>
                    <label for="saveAddress" style="font-size:0.8rem;color:var(--gray-500)">Bu bilgileri profilime kaydet</label>
                </div>
            </div>

            <div style="margin-top:1.5rem;padding:1.25rem;background:var(--gray-50);border-left:4px solid var(--primary);border-radius:var(--radius)">
                <div style="font-weight:700;font-size:0.85rem;margin-bottom:6px">&#128179; IBAN ile &Ouml;deme</div>
                <div style="font-size:0.8rem;color:var(--gray-500)">
                    Sipari&#351; kodu ile belirtilen IBAN'a &ouml;demenizi yap&#305;p dekont y&uuml;kleyiniz.
                </div>
            </div>

            <button class="btn-primary" style="width:100%;margin-top:1.5rem;padding:14px" id="placeOrderBtn" onclick="placeOrder()">Sipari&#351;i Tamamla</button>
        </div>
    </div>

    <!-- Sipariş Onay -->
    <div id="orderSuccess" style="display:none;text-align:center;padding:3rem">
        <div style="font-size:3rem;margin-bottom:1rem">&#10004;</div>
        <h2 style="font-size:1.5rem;font-weight:800;margin-bottom:0.5rem;color:#059669">Sipari&#351;iniz Al&#305;nd&#305;!</h2>
        <p style="color:var(--gray-500);margin-bottom:2rem">Sipari&#351; Kodunuz: <strong id="orderSuccessCode" style="color:var(--foreground);font-size:1.2rem;background:#f3f4f6;padding:4px 12px;border-radius:6px;margin-left:5px"></strong></p>
        
        <div class="card" style="max-width:480px;margin:0 auto;padding:2rem;text-align:left">
            <h4 style="font-size:1.1rem;font-weight:800;margin-bottom:1rem;border-bottom:1px solid var(--gray-100);padding-bottom:0.5rem">IBAN Bilgileri</h4>
            <p style="font-size:0.9rem;color:var(--gray-500);margin-bottom:1rem">A&#351;a&#287;&#305;daki banka hesab&#305;na <strong>sipari&#351; kodunuzu a&ccedil;&#305;klamaya yazarak</strong> &ouml;deme yap&#305;n&#305;z:</p>
            
            <div style="padding:1.25rem;background:#f8fafc;border:1px solid #eef2f6;border-radius:12px">
                <div style="font-size:0.8rem;color:var(--gray-400);margin-bottom:4px">BANKA</div>
                <div id="ibanBank" style="font-weight:700;margin-bottom:12px">-</div>
                <div style="font-size:0.8rem;color:var(--gray-400);margin-bottom:4px">ALICI</div>
                <div id="ibanHolder" style="font-weight:700;margin-bottom:12px">-</div>
                <div style="font-size:0.8rem;color:var(--gray-400);margin-bottom:4px">IBAN</div>
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <div id="ibanNo" style="font-weight:800;color:var(--primary);font-family:monospace;letter-spacing:1px">-</div>
                    <button onclick="copyIBAN()" style="background:none;border:none;color:var(--primary);cursor:pointer;font-size:0.8rem;font-weight:700">Kopyala</button>
                </div>
            </div>

            <div style="margin-top:2rem">
                <h4 style="font-size:0.9rem;font-weight:800;margin-bottom:0.5rem">&#128206; Dekont Y&uuml;kle</h4>
                <p style="font-size:0.8rem;color:var(--gray-500);margin-bottom:1rem">H&#305;zl&#305; onay i&ccedil;in dekont y&uuml;kleyebilirsiniz.</p>
                <div id="uploadWrap">
                    <input type="file" id="receiptInput" accept="image/*,.pdf" style="display:none" onchange="uploadReceipt()">
                    <button class="btn-outline" style="width:100%" onclick="document.getElementById('receiptInput').click()">Dosya Se&ccedil; ve Y&uuml;kle</button>
                    <div id="uploadStatus" style="margin-top:8px;font-size:0.8rem;text-align:center"></div>
                </div>
            </div>
        </div>
        <div style="margin-top:2rem">
            <a href="/parts" class="btn-primary" style="display:inline-flex">Al&#305;&#351;veri&#351;e Devam Et</a>
        </div>
    </div>
</div>

<script>
let lastOrderCode = null;

function renderCart() {
    try {
        if (typeof Cart === 'undefined') {
            console.error('Cart object is not loaded yet');
            return;
        }
        const items = Cart.get();
        if (!Array.isArray(items) || items.length === 0) {
            document.getElementById('cartEmpty').style.display = 'block';
            document.getElementById('cartContent').style.display = 'none';
            return;
        }
        document.getElementById('cartEmpty').style.display = 'none';
        document.getElementById('cartContent').style.display = 'grid';

        document.getElementById('cartItems').innerHTML = items.map(item => `
            <div class="cart-item">
                <div class="thumb">
                    ${item.image_url ? `<img src="${item.image_url}" alt="${item.name}">` : '<div style="font-size:1.5rem;color:var(--gray-300)">&#128230;</div>'}
                </div>
                <div style="flex:1">
                    <h3 style="font-size:0.95rem;font-weight:700">${item.name || 'İsimsiz Ürün'}</h3>
                    <div style="font-size:1rem;font-weight:800;margin:4px 0">&#8378;${((item.price || 0) * (item.quantity || 1)).toLocaleString('tr-TR',{minimumFractionDigits:2})}</div>
                    <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
                        <button class="btn-outline btn-xs" onclick="changeQty(${item.id},-1)">&minus;</button>
                        <span style="font-weight:600;font-size:0.85rem">${item.quantity}</span>
                        <button class="btn-outline btn-xs" onclick="changeQty(${item.id},1)">+</button>
                        <button style="border:none;background:none;color:var(--primary);cursor:pointer;font-size:0.8rem;margin-left:auto" onclick="removeItem(${item.id})">Kaldır</button>
                    </div>
                </div>
            </div>
        `).join('');

        const total = Cart.total();
        document.getElementById('cartSubtotal').textContent = '₺' + total.toLocaleString('tr-TR',{minimumFractionDigits:2});
        document.getElementById('cartTotal').textContent = '₺' + total.toLocaleString('tr-TR',{minimumFractionDigits:2});
        
        // Auth kontrolü ve form doldurma
        const fillForm = () => {
            if (window.CurrentUser) {
                const ac = document.getElementById('authCheck');
                if(ac) ac.style.display = 'none';
                if(document.getElementById('custName')) document.getElementById('custName').value = window.CurrentUser.name || '';
                if(document.getElementById('custEmail')) document.getElementById('custEmail').value = window.CurrentUser.email || '';
                if(document.getElementById('custPhone')) document.getElementById('custPhone').value = window.CurrentUser.phone || '';
                if(document.getElementById('custAddress')) document.getElementById('custAddress').value = window.CurrentUser.address || '';
                const saw = document.getElementById('saveAddrWrap');
                if(saw) saw.style.display = 'flex';
            } else if (document.getElementById('authCheck')) {
                document.getElementById('authCheck').style.display = 'block';
                const saw = document.getElementById('saveAddrWrap');
                if(saw) saw.style.display = 'none';
            }
        };

        fillForm();
        // Bir de biraz sonra tekrar dene (auth fetch yavaşsa)
        setTimeout(fillForm, 1000);

    } catch (e) {
        console.error('Render error:', e);
        document.getElementById('cartContent').innerHTML = '<div style="padding:2rem;text-align:center;color:red">Sepet yüklenirken bir hata oluştu.</div>';
    }
}

function changeQty(id, delta) {
    const items = Cart.get();
    const item = items.find(i => i.id === id);
    if (item) { Cart.updateQty(id, item.quantity + delta); renderCart(); }
}
function removeItem(id) { Cart.remove(id); renderCart(); }

function placeOrder() {
    const name = document.getElementById('custName').value;
    const email = document.getElementById('custEmail').value;
    const phone = document.getElementById('custPhone').value;
    const address = document.getElementById('custAddress').value;
    const saveAddr = document.getElementById('saveAddress').checked;

    if (!name || !email || !phone || !address) { alert('Lütfen tüm bilgileri doldurun.'); return; }

    const items = Cart.get();
    if (items.length === 0) { alert('Sepetiniz boş.'); return; }

    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true; btn.textContent = 'Siparişiniz alınıyor...';

    fetch(API_BASE + '/orders.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            customer: { name, email, phone, address },
            items: items.map(i => ({ id: i.id, quantity: i.quantity, price: i.price })),
            total: Cart.total(),
            save_address: saveAddr
        })
    }).then(r => r.json()).then(res => {
        if (res.success) {
            Cart.clear();
            lastOrderCode = res.orderCode;
            document.getElementById('cartContent').style.display = 'none';
            document.getElementById('orderSuccess').style.display = 'block';
            document.getElementById('orderSuccessCode').textContent = res.orderCode;
            document.getElementById('ibanBank').textContent = res.iban_bank;
            document.getElementById('ibanHolder').textContent = res.iban_holder;
            document.getElementById('ibanNo').textContent = res.iban;
        } else { 
            alert('Sipariş oluşturulamadı: ' + (res.error || 'Bilinmeyen hata')); 
            btn.disabled = false; btn.textContent = 'Siparişi Tamamla';
        }
    }).catch(() => {
        alert('Bir hata oluştu. Lütfen tekrar deneyin.');
        btn.disabled = false; btn.textContent = 'Siparişi Tamamla';
    });
}

function uploadReceipt() {
    const input = document.getElementById('receiptInput');
    const status = document.getElementById('uploadStatus');
    if (!input.files[0] || !lastOrderCode) return;

    status.textContent = 'Yükleniyor...';
    status.style.color = 'var(--foreground)';

    const formData = new FormData();
    formData.append('receipt', input.files[0]);
    formData.append('order_code', lastOrderCode);

    fetch(API_BASE + '/upload.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            status.textContent = 'Dekont başarıyla yüklendi! Onay bekliyor.';
            status.style.color = '#059669';
            document.getElementById('uploadWrap').innerHTML = `<div style="padding:1rem;background:#d1fae5;color:#059669;border-radius:var(--radius);font-weight:700;text-align:center">&#10004; Dekont Y&uuml;klendi</div>`;
        } else {
            status.textContent = 'Hata: ' + r.error;
            status.style.color = '#dc2626';
        }
    })
    .catch(() => {
        status.textContent = 'Bağlantı hatası.';
        status.style.color = '#dc2626';
    });
}

function copyIBAN() {
    const iban = document.getElementById('ibanNo').textContent;
    navigator.clipboard.writeText(iban).then(() => alert('IBAN kopyalandı!'));
}

document.addEventListener('DOMContentLoaded', () => {
    renderCart();
});
</script>

<?php include 'includes/footer.php'; ?>
