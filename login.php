<?php
$pageTitle = 'Giriş Yap / Üye Ol - Auto Akın';
include 'includes/header.php';
?>

<div class="container" style="padding:4rem 1rem;flex:1;display:flex;justify-content:center;align-items:flex-start">
    <div class="card" style="width:100%;max-width:440px;padding:2.5rem">
        <div style="display:flex;gap:1rem;margin-bottom:2rem;border-bottom:1px solid var(--gray-100)">
            <button onclick="switchTab('login')" id="tab-login" style="padding:0.75rem 1rem;background:none;border:none;border-bottom:2px solid var(--primary);font-weight:700;cursor:pointer;flex:1">Giriş Yap</button>
            <button onclick="switchTab('register')" id="tab-register" style="padding:0.75rem 1rem;background:none;border:none;border-bottom:2px solid transparent;font-weight:600;color:var(--gray-500);cursor:pointer;flex:1">Üye Ol</button>
        </div>

        <!-- Login Form -->
        <form id="loginForm" onsubmit="handleAuth(event, 'login')">
            <div style="margin-bottom:1rem">
                <label class="form-label">E-posta Adresiniz</label>
                <input class="form-input" type="email" id="loginEmail" required placeholder="ör: musteri@gmail.com">
            </div>
            <div style="margin-bottom:1.5rem">
                <label class="form-label">Şifre</label>
                <input class="form-input" type="password" id="loginPass" required>
            </div>
            <button type="submit" class="btn-primary" style="width:100%">Giriş Yap</button>
        </form>

        <!-- Register Form -->
        <form id="registerForm" onsubmit="handleAuth(event, 'register')" style="display:none">
            <div style="margin-bottom:1rem">
                <label class="form-label">Ad Soyad</label>
                <input class="form-input" id="regName" required placeholder="Adınız Soyadınız">
            </div>
            <div style="margin-bottom:1rem">
                <label class="form-label">E-posta</label>
                <input class="form-input" type="email" id="regEmail" required placeholder="ör: musteri@gmail.com">
            </div>
            <div style="margin-bottom:1rem">
                <label class="form-label">Telefon (İsteğe bağlı)</label>
                <input class="form-input" id="regPhone" placeholder="05xx xxx xx xx">
            </div>
            <div style="margin-bottom:1.5rem">
                <label class="form-label">Şifre (Min 6 Karakter)</label>
                <input class="form-input" type="password" id="regPass" required minlength="6">
            </div>
            <button type="submit" class="btn-primary" style="width:100%">Hesap Oluştur</button>
        </form>

        <div id="authError" style="display:none;margin-top:1.5rem;padding:1rem;background:#fef2f2;color:#dc2626;border-radius:var(--radius);font-size:0.85rem;text-align:center;border:1px solid #fee2e2"></div>
    </div>
</div>

<script>
function switchTab(tab) {
    const lForm = document.getElementById('loginForm');
    const rForm = document.getElementById('registerForm');
    const lTab = document.getElementById('tab-login');
    const rTab = document.getElementById('tab-register');
    const err = document.getElementById('authError');
    err.style.display = 'none';

    if (tab === 'login') {
        lForm.style.display = 'block';
        rForm.style.display = 'none';
        lTab.style.borderBottomColor = 'var(--primary)';
        lTab.style.fontWeight = '700';
        lTab.style.color = 'var(--foreground)';
        rTab.style.borderBottomColor = 'transparent';
        rTab.style.fontWeight = '600';
        rTab.style.color = 'var(--gray-500)';
    } else {
        lForm.style.display = 'none';
        rForm.style.display = 'block';
        rTab.style.borderBottomColor = 'var(--primary)';
        rTab.style.fontWeight = '700';
        rTab.style.color = 'var(--foreground)';
        lTab.style.borderBottomColor = 'transparent';
        lTab.style.fontWeight = '600';
        lTab.style.color = 'var(--gray-500)';
    }
}

function handleAuth(e, action) {
    e.preventDefault();
    const err = document.getElementById('authError');
    err.style.display = 'none';
    const btn = e.target.querySelector('button[type="submit"]');
    const oldBtnText = btn.textContent;
    btn.disabled = true; btn.textContent = 'İşlem yapılıyor...';

    let body = { action: action };
    if (action === 'login') {
        body.email = document.getElementById('loginEmail').value;
        body.password = document.getElementById('loginPass').value;
    } else {
        body.name = document.getElementById('regName').value;
        body.email = document.getElementById('regEmail').value;
        body.phone = document.getElementById('regPhone').value;
        body.password = document.getElementById('regPass').value;
    }

    fetch('/api/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(r => {
        if (r.success) {
            // Sepet doluysa sepete, değilse ana sayfaya
            const items = Cart.get();
            window.location.href = items.length > 0 ? '/cart' : '/';
        } else {
            err.textContent = r.error || 'Bir hata oluştu.';
            err.style.display = 'block';
            btn.disabled = false; btn.textContent = oldBtnText;
        }
    })
    .catch(() => {
        err.textContent = 'Bağlantı hatası.';
        err.style.display = 'block';
        btn.disabled = false; btn.textContent = oldBtnText;
    });
}

// URL'de ?tab=register varsa direkt oraya git
const urlP = new URLSearchParams(window.location.search);
if(urlP.get('tab') === 'register') switchTab('register');
</script>

<?php include 'includes/footer.php'; ?>
