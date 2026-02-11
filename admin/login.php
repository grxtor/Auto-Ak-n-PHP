<?php require_once __DIR__ . '/../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - Auto Akın Yönetimi</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --admin-bg: #0f172a;
            --admin-card: #1e293b;
        }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--admin-bg);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            background: var(--admin-card);
            padding: 3rem;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 900;
            margin: 0;
            letter-spacing: -1px;
        }
        .login-header p {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-top: 8px;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .admin-input {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            font-size: 0.9rem;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .admin-input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }
        .btn-admin {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 800;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
            filter: brightness(1.1);
        }
        .btn-admin:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        #loginError {
            background: rgba(220, 38, 38, 0.1);
            color: #ef4444;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            margin-top: 1.5rem;
            display: none;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1>AUTO <span style="color:var(--primary)">AKIN</span></h1>
            <p>Yönetim Paneli Girişi</p>
        </div>
        <form onsubmit="handleLogin(event)">
            <div class="form-group">
                <label>Kullanıcı Adı</label>
                <input class="admin-input" id="username" required placeholder="Admin kullanıcı adınız">
            </div>
            <div class="form-group">
                <label>Şifre</label>
                <div style="position:relative">
                    <input class="admin-input" type="password" id="password" required placeholder="••••••••">
                    <button type="button" onclick="togglePassword()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#64748b;cursor:pointer;padding:4px">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-admin" id="loginBtn">Sisteme Giriş Yap</button>
        </form>
        <div id="loginError"></div>
    </div>

    <script>
    const API_BASE = '<?= BASE_URL ?>/api';
    
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
    fetch(API_BASE + '/admin/auth').then(r=>r.json()).then(r => {
        if (r.loggedIn) window.location.href = '<?= BASE_URL ?>/admin/dashboard';
    });

    function handleLogin(e) {
        e.preventDefault();
        const btn = document.getElementById('loginBtn');
        const errEl = document.getElementById('loginError');
        errEl.style.display = 'none';
        btn.disabled = true; btn.textContent = 'Doğrulanıyor...';
        
        fetch(API_BASE + '/admin/auth', {
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
                localStorage.setItem('admin_auth', 'true');
                window.location.href = '<?= BASE_URL ?>/admin/dashboard';
            } else {
                console.log('Login failed:', r);
                errEl.textContent = r.error || 'Geçersiz kullanıcı adı veya şifre.';
                errEl.style.display = 'block';
                btn.disabled = false; btn.textContent = 'Sisteme Giriş Yap';
            }
        })
        .catch(() => {
            errEl.textContent = 'Sunucu bağlantı hatası!';
            errEl.style.display = 'block';
            btn.disabled = false; btn.textContent = 'Sisteme Giriş Yap';
        });
    }
    </script>
</body>
</html>
