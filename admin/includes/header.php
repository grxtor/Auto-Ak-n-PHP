<?php
// Admin Header - Centralized Navigation and Auth
session_start();
require_once __DIR__ . '/../../config/db.php';

// Auth Check
if (!isset($_SESSION['admin_id'])) {
    header('Location: /admin/login');
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> - Auto Akın Yönetimi</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body{background:#f8fafc; display: flex; flex-direction: column; min-height: 100vh;}
        .admin-nav{background:#0f172a;border-bottom:none;padding:0; position: sticky; top:0; z-index: 1000;}
        .admin-nav .container{display:flex;justify-content:space-between;align-items:center;height:56px}
        .admin-nav .nav-right{display:flex;align-items:center;gap:1.2rem}
        .admin-nav .nav-link{color:#94a3b8;font-size:0.82rem;transition:color 0.2s;font-weight:500; text-decoration: none;}
        .admin-nav .nav-link:hover, .admin-nav .nav-link.active{color:white}
        
        /* Form & Password Toggle Styles */
        .password-wrapper { position: relative; width: 100%; }
        .password-toggle { 
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%); 
            cursor: pointer; color: var(--gray-400); transition: color 0.2s;
            background: none; border: none; padding: 4px;
        }
        .password-toggle:hover { color: var(--primary); }
    </style>
</head>
<body>
<nav class="navbar admin-nav">
    <div class="container">
        <a href="/admin/dashboard" class="logo" style="color:white;font-size:1.1rem">AUTO <span style="color:#ef4444">AKIN</span> <span style="font-size:0.6rem;color:#475569;background:#1e293b;padding:3px 8px;border-radius:4px;margin-left:6px">PANEL</span></a>
        <div class="nav-right">
            <a href="/admin/dashboard" class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
            <a href="/admin/products" class="nav-link <?= $current_page == 'products.php' ? 'active' : '' ?>">Urunler</a>
            <a href="/admin/vehicles" class="nav-link <?= $current_page == 'vehicles.php' ? 'active' : '' ?>">Araclar</a>
            <a href="/admin/orders" class="nav-link <?= $current_page == 'orders.php' ? 'active' : '' ?>">Siparisler</a>
            <a href="/admin/messages" class="nav-link <?= $current_page == 'messages.php' ? 'active' : '' ?>">Mesajlar</a>
            <a href="/admin/customers" class="nav-link <?= $current_page == 'customers.php' ? 'active' : '' ?>">Musteriler</a>
            <a href="/admin/admins" class="nav-link <?= $current_page == 'admins.php' ? 'active' : '' ?>">Adminler</a>
            <div style="width:1px;height:24px;background:#334155"></div>
            <a href="/" target="_blank" class="nav-link" title="Siteyi Gör"><i class="fas fa-external-link-alt"></i></a>
            <button onclick="adminLogout()" style="border:1px solid #334155;background:transparent;color:#94a3b8;padding:5px 12px;border-radius:4px;font-size:0.78rem;cursor:pointer">Cikis</button>
        </div>
    </div>
</nav>

<div style="background:linear-gradient(135deg,#0f172a,#1e293b);padding:2rem 0 3.5rem;color:white">
    <div class="container">
        <h1 style="font-size:1.5rem;font-weight:800"><?= $pageTitle ?? 'Yönetim Paneli' ?></h1>
        <p style="color:#64748b;font-size:0.88rem" id="headerDesc"><?= $pageDesc ?? 'Sisteminizi buradan yönetebilirsiniz.' ?></p>
    </div>
</div>

<script>
function adminLogout(){
    fetch('/api/admin/auth.php',{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({action:'logout'})
    }).then(()=>{
        localStorage.removeItem('admin_auth');
        window.location='/admin/login';
    });
}

function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
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
</script>
