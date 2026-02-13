<?php
$pageTitle = 'Profilim - Auto Akın';
include 'includes/header.php';

// Auth check
if (!isset($_SESSION['customer_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$db = getDB();
$stmt = $db->prepare('SELECT * FROM customers WHERE id = ?');
$stmt->execute([$_SESSION['customer_id']]);
$c = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$c) {
    session_destroy();
    header('Location: ' . BASE_URL . '/login');
    exit;
}
?>

<div class="container" style="padding: 2rem 0; flex: 1;">
    <h1 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 2rem;">Hesap <span class="text-red">Bilgilerim</span></h1>

    <div class="profile-grid">
        <div>
            <div class="card" style="padding: 2rem; text-align: center;">
                <div style="width: 80px; height: 80px; background: var(--gray-50); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--primary); margin: 0 auto 1rem;">
                    <i class="fas fa-user"></i>
                </div>
                <h2 style="font-size: 1.1rem; font-weight: 800;"><?= htmlspecialchars($c['name']) ?></h2>
                <p style="color: var(--gray-500); font-size: 0.85rem; margin-top: 5px;"><?= htmlspecialchars($c['email']) ?></p>
            </div>
        </div>

        <div>
            <div class="card" style="padding: 2rem;">
                <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 1.5rem;">İletişim & Teslimat</h3>
                <form id="profileForm" onsubmit="updateProfile(event)">
                    <div style="display: grid; gap: 1.25rem;">
                        <div>
                            <label class="form-label">Ad Soyad</label>
                            <input class="form-input" name="name" value="<?= htmlspecialchars($c['name']) ?>" required>
                        </div>
                        <div>
                            <label class="form-label">E-posta</label>
                            <input class="form-input" name="email" value="<?= htmlspecialchars($c['email']) ?>" required readonly style="background:var(--gray-50)">
                        </div>
                        <div>
                            <label class="form-label">Telefon</label>
                            <input class="form-input" name="phone" value="<?= htmlspecialchars($c['phone'] ?? '') ?>">
                        </div>
                        <div>
                            <label class="form-label">Sabit Adres</label>
                            <textarea class="form-input" name="address" rows="3" style="resize:none"><?= htmlspecialchars($c['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                    
                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary" id="saveBtn">Güncelle</button>
                    </div>
                </form>
                <div id="updateMsg" style="margin-top: 1rem; text-align: center; display: none; font-size: 0.85rem; font-weight: 600;"></div>
            </div>
        </div>
    </div>
</div>

<script>
function updateProfile(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    const msg = document.getElementById('updateMsg');
    btn.disabled = true; btn.textContent = 'Güncelleniyor...';
    
    const formData = new FormData(e.target);
    const data = { action: 'update_profile' };
    formData.forEach((v, k) => data[k] = v);

    fetch(API_BASE + '/auth', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(r => {
        btn.disabled = false; btn.textContent = 'Güncelle';
        msg.style.display = 'block';
        if (r.success) {
            msg.textContent = 'Bilgileriniz başarıyla güncellendi!';
            msg.style.color = '#059669';
        } else {
            msg.textContent = 'Hata: ' + (r.error || 'Güncellenemedi');
            msg.style.color = '#dc2626';
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>
