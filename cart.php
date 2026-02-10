<?php
$pageTitle = 'Sepetim - Auto Akın';
include 'includes/header.php';
?>

<div class="container" style="padding-top:2rem;padding-bottom:3rem">
    <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:1.5rem">Sepetim</h1>

    <div id="cartEmpty" style="text-align:center;padding:3rem;color:var(--gray-500);display:none">
        Sepetiniz boş. <a href="/parts.php" class="text-red" style="font-weight:600">Alışverişe başlayın →</a>
    </div>

    <div class="cart-grid" id="cartContent" style="display:none">
        <div>
            <div id="cartItems"></div>
        </div>
        <div class="cart-summary">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem">Sipariş Özeti</h3>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100)">
                <span style="color:var(--gray-500);font-size:0.9rem">Ara Toplam</span>
                <span id="cartSubtotal" style="font-weight:600">₺0</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100)">
                <span style="color:var(--gray-500);font-size:0.9rem">Kargo</span>
                <span style="font-weight:600;color:#059669">Ücretsiz</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:12px 0;font-size:1.1rem">
                <span style="font-weight:800">Toplam</span>
                <span id="cartTotal" style="font-weight:800;color:var(--primary)">₺0</span>
            </div>

            <hr style="border:none;border-top:1px solid var(--gray-200);margin:1rem 0">
            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:0.75rem">Bilgileriniz</h4>
            <div style="display:flex;flex-direction:column;gap:8px">
                <input class="form-input" id="custName" placeholder="Ad Soyad" required>
                <input class="form-input" id="custEmail" placeholder="E-posta" type="email" required>
                <input class="form-input" id="custPhone" placeholder="Telefon" required>
                <textarea class="form-input" id="custAddress" placeholder="Adres" rows="2" style="resize:none" required></textarea>
            </div>

            <div style="margin-top:1rem;padding:1rem;background:var(--gray-50);border-radius:var(--radius)">
                <div style="font-weight:700;font-size:0.85rem;margin-bottom:4px">💳 IBAN ile Ödeme</div>
                <div style="font-size:0.8rem;color:var(--gray-500)">
                    Sipariş oluşturduktan sonra IBAN bilgileri görüntülenecektir.
                </div>
            </div>

            <button class="btn-primary" style="width:100%;margin-top:1rem" onclick="placeOrder()">Siparişi Tamamla</button>
        </div>
    </div>

    <!-- Sipariş Onay -->
    <div id="orderSuccess" style="display:none;text-align:center;padding:3rem">
        <div style="font-size:2.5rem;margin-bottom:1rem">✅</div>
        <h2 style="font-size:1.25rem;font-weight:800;margin-bottom:0.5rem">Siparişiniz Alındı!</h2>
        <p style="color:var(--gray-500);margin-bottom:1.5rem">Sipariş numaranız: <strong id="orderNo" class="text-red"></strong></p>
        <div class="card" style="max-width:400px;margin:0 auto;padding:1.5rem;text-align:left">
            <h4 style="font-size:0.9rem;font-weight:700;margin-bottom:0.5rem">IBAN Bilgileri</h4>
            <p style="font-size:0.85rem;color:var(--gray-500);margin-bottom:0.5rem">Aşağıdaki hesaba havale/EFT yapınız:</p>
            <div style="padding:0.75rem;background:var(--gray-50);border-radius:var(--radius);font-family:monospace;font-size:0.85rem">
                TR00 0000 0000 0000 0000 0000 00
            </div>
            <p style="font-size:0.8rem;color:var(--gray-500);margin-top:0.5rem">Açıklama: <strong id="orderRef"></strong></p>
        </div>
    </div>
</div>

<script>
function renderCart() {
    const items = Cart.get();
    if (items.length === 0) {
        document.getElementById('cartEmpty').style.display = 'block';
        document.getElementById('cartContent').style.display = 'none';
        return;
    }
    document.getElementById('cartEmpty').style.display = 'none';
    document.getElementById('cartContent').style.display = 'grid';

    document.getElementById('cartItems').innerHTML = items.map(item => `
        <div class="cart-item">
            <div class="thumb">
                ${item.image_url ? `<img src="${item.image_url}" alt="${item.name}">` : '📦'}
            </div>
            <div style="flex:1">
                <h3 style="font-size:0.95rem;font-weight:700">${item.name}</h3>
                <div style="font-size:1rem;font-weight:800;margin:4px 0">₺${(item.price*item.quantity).toLocaleString('tr-TR',{minimumFractionDigits:2})}</div>
                <div style="display:flex;align-items:center;gap:8px;margin-top:6px">
                    <button class="btn-outline btn-xs" onclick="changeQty(${item.id},-1)">−</button>
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
    if (!name || !email || !phone || !address) { alert('Lütfen tüm bilgileri doldurun.'); return; }

    const items = Cart.get();
    if (items.length === 0) { alert('Sepetiniz boş.'); return; }

    fetch('/api/orders.php', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({
            customer: { name, email, phone, address },
            items: items.map(i => ({ id: i.id, quantity: i.quantity, price: i.price })),
            total: Cart.total()
        })
    }).then(r => r.json()).then(res => {
        if (res.success) {
            Cart.clear();
            document.getElementById('cartContent').style.display = 'none';
            document.getElementById('orderSuccess').style.display = 'block';
            document.getElementById('orderNo').textContent = '#AKN-' + res.orderId;
            document.getElementById('orderRef').textContent = 'AKN-' + res.orderId;
        } else { alert('Sipariş oluşturulamadı: ' + (res.error || 'Bilinmeyen hata')); }
    }).catch(() => alert('Bir hata oluştu. Lütfen tekrar deneyin.'));
}

renderCart();
</script>

<?php include 'includes/footer.php'; ?>
