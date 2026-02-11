<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Auto Akın - Yedek Parça' ?></title>
    <meta name="description" content="<?= $pageDesc ?? 'Auto Akın - Otomotiv yedek parça dünyasında güvenilir adresiniz.' ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- FontAwesome for professional icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        #userMenu { position: relative; }
        .user-dropdown {
            position: absolute; right: 0; top: 100%; 
            background: white; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            min-width: 180px; display: none; z-index: 1000; padding: 0.5rem 0;
            margin-top: 0.5rem; border: 1px solid var(--gray-100);
        }
        .user-dropdown a, .user-dropdown button {
            display: block; width: 100%; text-align: left; padding: 0.6rem 1rem;
            font-size: 0.85rem; color: var(--foreground); transition: background 0.2s;
            border: none; background: none; cursor: pointer; text-decoration: none;
        }
        .user-dropdown a:hover, .user-dropdown button:hover { background: var(--gray-50); color: var(--primary); }
        #userMenu:hover .user-dropdown { display: block; }
    </style>
</head>
<body style="display:flex;flex-direction:column;min-height:100vh">
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo" id="siteLogo">AUTO <span class="text-red">AKIN</span></a>
            
            <form action="/parts" method="GET" class="header-search">
                <input type="text" name="search" placeholder="Yedek parça ara (Ürün adı veya OEM no)..." required>
                <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
            </form>

            <div class="nav-links">
                <div id="authLinks">
                    <a href="/login"><i class="far fa-user"></i> Giriş Yap</a>
                </div>

                <div id="userMenu" style="display:none">
                    <span id="userName" style="font-weight:600;font-size:0.85rem"><i class="fas fa-user-circle"></i> Hesabım</span>
                    <div class="user-dropdown">
                        <a href="/profile"><i class="fas fa-user-edit"></i> Profilim</a>
                        <a href="/orders"><i class="fas fa-box"></i> Siparişlerim</a>
                        <hr style="border:none;border-top:1px solid var(--gray-100);margin:4px 0">
                        <button onclick="logout()"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</button>
                    </div>
                </div>

                <a href="/cart" style="position:relative" id="nav-cart-link">
                    <i class="fas fa-shopping-cart"></i>
                    Sepetim
                    <span class="cart-badge" id="nav-cart-count" style="display:none">0</span>
                </a>

                <a href="/admin/login" class="btn-secondary btn-sm" style="padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.75rem; background: #f8fafc;">Panel</a>
            </div>
        </div>
    </nav>

    <div class="cat-nav">
        <div class="container">
            <a href="/parts?marka=hyundai">Hyundai Parçaları</a>
            <a href="/parts?marka=kia">Kia Parçaları</a>
            <a href="/parts?category=motor-parcalari">Motor</a>
            <a href="/parts?category=fren-sistemleri">Fren</a>
            <a href="/parts?category=aydınlatma">Aydınlatma</a>
            <a href="/parts?category=filtreler">Filtre</a>
            <a href="/parts?category=kaporta">Kaporta</a>
            <a href="/parts?category=elektrik-elektronik">Elektrik</a>
            <a href="/contact" style="color:var(--primary)">Canlı Destek</a>
        </div>
    </div>

    <script>
    // Cart helper
    const Cart = {
        get() { 
            try { return JSON.parse(localStorage.getItem('autoakin_cart') || '[]'); } 
            catch(e) { return []; } 
        },
        set(items) { localStorage.setItem('autoakin_cart', JSON.stringify(items)); this.updateBadge(); },
        add(product) {
            let items = this.get();
            const idx = items.findIndex(i => i.id === product.id);
            if (idx > -1) items[idx].quantity++;
            else items.push({ ...product, quantity: 1 });
            this.set(items);
        },
        remove(id) { this.set(this.get().filter(i => i.id !== id)); },
        updateQty(id, qty) {
            let items = this.get();
            const idx = items.findIndex(i => i.id === id);
            if (idx > -1) { items[idx].quantity = Math.max(1, qty); this.set(items); }
        },
        count() { return this.get().reduce((sum, i) => sum + i.quantity, 0); },
        total() { return this.get().reduce((sum, i) => sum + (i.price * i.quantity), 0); },
        clear() { this.set([]); },
        updateBadge() {
            const badge = document.getElementById('nav-cart-count');
            if (!badge) return;
            const c = this.count();
            badge.textContent = c;
            badge.style.display = c > 0 ? 'flex' : 'none';
        }
    };
    document.addEventListener('DOMContentLoaded', () => Cart.updateBadge());

    // Site ayarlarını yükle
    let SiteSettings = {};
    fetch('/api/settings.php').then(r=>r.json()).then(s => {
        SiteSettings = s;
        if(s.site_name) {
            const logo = document.getElementById('siteLogo');
            const parts = s.site_name.split(' ');
            if(parts.length > 1) {
                logo.innerHTML = `${parts[0]} <span class="text-red">${parts.slice(1).join(' ')}</span>`;
            } else {
                logo.textContent = s.site_name;
            }
        }
    });

    // Oturum kontrolü
    function checkAuth() {
        fetch('/api/auth.php').then(r=>r.json()).then(r => {
            const authLinks = document.getElementById('authLinks');
            const userMenu = document.getElementById('userMenu');
            const userName = document.getElementById('userName');

            if (r.loggedIn) {
                authLinks.style.display = 'none';
                userMenu.style.display = 'flex';
                userName.textContent = r.customer.name.split(' ')[0];
                window.CurrentUser = r.customer;
            } else {
                authLinks.style.display = 'flex';
                userMenu.style.display = 'none';
                window.CurrentUser = null;
            }
        });
    }

    function logout() {
        fetch('/api/auth.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({action:'logout'})
        }).then(()=>window.location.reload());
    }

    checkAuth();
    </script>
