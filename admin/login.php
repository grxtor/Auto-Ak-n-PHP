<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - Auto Akın Yönetimi</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--gray-50)">
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
                    <input class="form-input" type="password" id="password" required>
                </div>
                <button type="submit" class="btn-primary" style="width:100%">Giriş Yap</button>
            </form>
            <div id="loginError" style="color:var(--primary);font-size:0.8rem;text-align:center;margin-top:1rem;display:none"></div>
        </div>
    </div>

    <script>
    function handleLogin(e) {
        e.preventDefault();
        const user = document.getElementById('username').value;
        const pass = document.getElementById('password').value;
        // Basit admin login (üretimde hash+db kontrolü yapılmalı)
        if (user === 'admin' && pass === 'admin123') {
            localStorage.setItem('admin_auth', 'true');
            window.location.href = '/admin/dashboard.php';
        } else {
            const el = document.getElementById('loginError');
            el.textContent = 'Kullanıcı adı veya şifre hatalı.';
            el.style.display = 'block';
        }
    }
    </script>
</body>
</html>
