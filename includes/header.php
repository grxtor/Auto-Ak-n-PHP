<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Auto Akın - Yedek Parça' ?></title>
    <meta name="description" content="<?= $pageDesc ?? 'Auto Akın - Otomotiv yedek parça dünyasında güvenilir adresiniz.' ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo">AUTO <span class="text-red">AKIN</span></a>
            <div class="nav-links">
                <a href="/parts">Yedek Parça</a>
                <a href="/cart" style="position:relative" id="nav-cart-link">
                    Sepet
                    <span class="cart-badge" id="nav-cart-count" style="display:none">0</span>
                </a>
                <a href="/admin/login" class="btn-secondary btn-sm">Panel</a>
            </div>
        </div>
    </nav>
