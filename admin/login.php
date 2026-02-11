<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - Auto Akın Yönetimi</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0f172a,#1e293b)">
        <div class="card" style="padding:2.5rem;width:100%;max-width:380px">
            <h1 style="text-align:center;font-size:1.25rem;font-weight:800;margin-bottom:1.5rem">
                AUTO <span class="text-red">AKIN</span>
                <div style="font-size:0.8rem;color:var(--gray-500);font-weight:400;margin-top:4px">Yönetim Paneli</div>
            </h1>
            <form onsubmit="handleLogin(event)">
                <div style="margin-bottom:12px">
                    <label class="form-label">Kullanıcı Adı</label>
                    <input class="form-input" id="username" required>
                </div>
                <div style="margin-bottom:1.5rem">
                    <label class="form-label">Şifre</label>
                    <div style="position:relative">
                        <input class="form-input" type="password" id="password" required>
                        <button type="button" onclick="togglePassword()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--gray-400);cursor:pointer;padding:4px">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%" id="loginBtn">Giriş Yap</button>
            </form>
            <div id="loginError" style="color:var(--primary);font-size:0.8rem;text-align:center;margin-top:1rem;display:none"></div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    // Sayfa acildiginda session kontrol et
    fetch('/api/admin/auth.php').then(r=>r.json()).then(r => {
        if (r.loggedIn) window.location.href = '/admin/dashboard';
    });

    function handleLogin(e) {
        e.preventDefault();
        const btn = document.getElementById('loginBtn');
        btn.disabled = true; btn.textContent = 'Giriş yapılıyor...';
        
        fetch('/api/admin/auth.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                username: document.getElementById('username').value,
                password: document.getElementById('password').value
            })
        })
        .then(r => r.json())
        .then(r => {
            if (r.success) {
                // localStorage'ı da set et (uyumluluk)
                localStorage.setItem('admin_auth', 'true');
                window.location.href = '/admin/dashboard';
            } else {
                const el = document.getElementById('loginError');
                el.textContent = r.error || 'Giriş başarısız.';
                el.style.display = 'block';
                btn.disabled = false; btn.textContent = 'Giriş Yap';
            }
        })
        .catch(() => {
            btn.disabled = false; btn.textContent = 'Giriş Yap';
        });
    }
    </script>
</body>
</html>
