<?php
$pageTitle = 'Site Ayarları';
$pageDesc = 'İletişim bilgilerini, banka hesaplarını ve genel site ayarlarını buradan güncelleyin.';
include 'includes/header.php';
?>

<div class="container" style="margin-top:-2.5rem; margin-bottom: 4rem;">
    <div id="settingsAlert" style="display:none; margin-bottom: 2rem; padding: 1rem 1.5rem; border-radius: 12px; font-weight: 600; text-align: center;"></div>

    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 2rem;">
        <!-- Categories Sidebar -->
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <button onclick="showSection('contact')" class="section-tab active" id="tab-contact">
                <i class="fas fa-address-book"></i> İletişim Bilgileri
            </button>
            <button onclick="showSection('payment')" class="section-tab" id="tab-payment">
                <i class="fas fa-university"></i> Banka & Ödeme
            </button>
            <button onclick="showSection('general')" class="section-tab" id="tab-general">
                <i class="fas fa-sliders-h"></i> Genel Ayarlar
            </button>
        </div>

        <!-- content areas -->
        <div class="card" style="padding: 2.5rem; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-radius: 20px;">
            <form id="settingsForm" onsubmit="saveSettings(event)">
                
                <!-- Contact Section -->
                <div id="section-contact" class="settings-section">
                    <h2 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--secondary);">İletişim Bilgileri</h2>
                    <div style="display: grid; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">E-posta Adresi</label>
                            <input class="form-input" name="site_email" placeholder="info@autoakin.com">
                            <small style="color:var(--gray-400)">Müşterilerin size ulaşacağı ana e-posta adresi.</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Telefon Numarası</label>
                            <input class="form-input" name="site_phone" placeholder="+90 5xx xxx xx xx">
                        </div>
                        <div class="form-group">
                            <label class="form-label">WhatsApp Numarası</label>
                            <input class="form-input" name="site_whatsapp" placeholder="+90 5xx xxx xx xx">
                            <small style="color:var(--gray-400)">Sitedeki WhatsApp butonları bu numaraya yönlenir.</small>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div id="section-payment" class="settings-section" style="display:none">
                    <h2 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--secondary);">Banka & Ödeme Bilgileri</h2>
                    <div style="display: grid; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Banka Adı</label>
                            <input class="form-input" name="iban_bank" placeholder="Örn: Akbank">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Hesap Sahibi</label>
                            <input class="form-input" name="iban_holder" placeholder="Ad Soyad veya Şirket Ünvanı">
                        </div>
                        <div class="form-group">
                            <label class="form-label">IBAN Numarası</label>
                            <input class="form-input" name="iban" placeholder="TR00 0000...">
                            <small style="color:var(--gray-400)">Boşluklu veya birleşik yazabilirsiniz.</small>
                        </div>
                    </div>
                </div>

                <!-- General Section -->
                <div id="section-general" class="settings-section" style="display:none">
                    <h2 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--secondary);">Genel Site Ayarları</h2>
                    <div style="display: grid; gap: 1.5rem;">
                        <div class="form-group">
                            <label class="form-label">Site Başlığı</label>
                            <input class="form-input" name="site_name" placeholder="Auto Akın">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kargo Notu</label>
                            <input class="form-input" name="shipping_note" placeholder="14:00 öncesi siparişler aynı gün kargolanır.">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Minimum Sipariş Tutarı (₺)</label>
                            <input class="form-input" type="number" name="min_order" value="0">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--gray-100); display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-primary" style="padding: 12px 40px; border-radius: 12px; font-weight: 800;" id="saveBtn">
                        <i class="fas fa-save" style="margin-right:8px"></i> AYARLARI KAYDET
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.section-tab {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 1rem 1.5rem;
    background: white;
    border: 1px solid var(--gray-100);
    border-radius: 12px;
    color: var(--gray-500);
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
    text-align: left;
}
.section-tab i { width: 20px; font-size: 1.1rem; opacity: 0.6; }
.section-tab:hover { background: #f8fafc; color: var(--secondary); }
.section-tab.active {
    background: var(--admin-bg, #0f172a);
    color: white;
    border-color: var(--admin-bg, #0f172a);
    box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.2);
}
.section-tab.active i { opacity: 1; color: var(--primary); }

.form-group label { margin-bottom: 6px; display: block; }
</style>

<script>
function showSection(id) {
    document.querySelectorAll('.settings-section').forEach(s => s.style.display = 'none');
    document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
    
    document.getElementById('section-' + id).style.display = 'block';
    document.getElementById('tab-' + id).classList.add('active');
}

function loadSettings() {
    fetch('<?= BASE_URL ?>/api/admin/settings.php')
        .then(r => r.json())
        .then(data => {
            const form = document.getElementById('settingsForm');
            data.forEach(item => {
                const input = form.querySelector(`[name="${item.setting_key}"]`);
                if (input) input.value = item.setting_value;
            });
        });
}

function saveSettings(e) {
    e.preventDefault();
    const btn = document.getElementById('saveBtn');
    const alert = document.getElementById('settingsAlert');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...';

    const formData = new FormData(e.target);
    const data = {};
    formData.forEach((value, key) => data[key] = value);

    fetch('<?= BASE_URL ?>/api/admin/settings.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(r => {
        btn.disabled = false; btn.innerHTML = '<i class="fas fa-save" style="margin-right:8px"></i> AYARLARI KAYDET';
        
        alert.style.display = 'block';
        if (r.success) {
            alert.style.background = '#0596691a';
            alert.style.color = '#059669';
            alert.style.border = '1px solid #05966933';
            alert.textContent = 'Ayarlar başarıyla güncellendi!';
            setTimeout(() => alert.style.display = 'none', 3000);
        } else {
            alert.style.background = '#dc26261a';
            alert.style.color = '#dc2626';
            alert.style.border = '1px solid #dc262633';
            alert.textContent = 'Hata: ' + (r.error || 'Bilinmeyen bir hata oluştu');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

loadSettings();
</script>

<?php include 'includes/footer.php'; ?>
