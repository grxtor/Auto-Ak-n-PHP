<?php
/**
 * Auto Akın - MySQL Veritabanı Kurulum Scripti
 * Tarayıcıdan çalıştır: https://autoakin.com.tr/init-db.php
 * Kurulum sonrası bu dosyayı SİLİN!
 */

$lockFile = __DIR__ . '/.db_installed';
// Kilit dosyasını sil ki tekrar çalışabilsin
if (file_exists($lockFile)) { unlink($lockFile); }

require_once __DIR__ . '/config/db.php';
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Auto Akın - DB Kurulum</title>
    <style>
        body{font-family:'Inter',sans-serif;max-width:700px;margin:40px auto;padding:20px;background:#f9fafb}
        h1{color:#111}.ok{color:#059669}.err{color:#dc2626}
        .log{background:#111;color:#4ade80;padding:20px;border-radius:8px;font-family:monospace;font-size:0.85rem;line-height:1.8;max-height:500px;overflow-y:auto}
        .warn{background:#fef3c7;color:#92400e;padding:12px 16px;border-radius:6px;margin-top:20px;font-size:0.9rem}
    </style>
</head>
<body>
<h1>Auto Akin <span style="color:#dc2626">DB Kurulum</span></h1>
<div class="log">
<?php
function logMsg($msg, $ok = true) {
    $icon = $ok ? '&#10004;' : '&#10008;';
    echo "$icon $msg<br>\n";
    ob_flush(); flush();
}

try {
    $db = getDB();
    logMsg("Veritabani baglantisi basarili");

    // Eski tabloları sil
    echo "<br>Eski tablolar siliniyor...<br>";
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");
    $drops = ['order_items','product_compatibility','messages','admins','orders','products','categories','variants','models','brands'];
    foreach ($drops as $t) {
        $db->exec("DROP TABLE IF EXISTS $t");
    }
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");
    logMsg("Eski tablolar temizlendi");

    // Tablolar
    $tables = [
        'brands' => "CREATE TABLE IF NOT EXISTS brands (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            slug VARCHAR(100) NOT NULL UNIQUE,
            logo_url VARCHAR(512),
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'models' => "CREATE TABLE IF NOT EXISTS models (
            id INT AUTO_INCREMENT PRIMARY KEY,
            brand_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_brand_slug (brand_id, slug),
            FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'variants' => "CREATE TABLE IF NOT EXISTS variants (
            id INT AUTO_INCREMENT PRIMARY KEY,
            model_id INT NOT NULL,
            year_start INT NOT NULL,
            year_end INT NULL,
            engine_type VARCHAR(100) NOT NULL,
            engine_code VARCHAR(50),
            fuel_type VARCHAR(20) DEFAULT 'benzin',
            horsepower INT,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (model_id) REFERENCES models(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'categories' => "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE,
            icon VARCHAR(10),
            parent_id INT NULL,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'products' => "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            stock INT NOT NULL DEFAULT 0,
            image_url VARCHAR(512),
            category_id INT NULL,
            oem_no VARCHAR(100),
            part_brand VARCHAR(100),
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'product_compatibility' => "CREATE TABLE IF NOT EXISTS product_compatibility (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            variant_id INT NOT NULL,
            UNIQUE KEY unique_prod_var (product_id, variant_id),
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
            FOREIGN KEY (variant_id) REFERENCES variants(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'orders' => "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(255) NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            customer_phone VARCHAR(50),
            customer_address TEXT,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            payment_method VARCHAR(50) DEFAULT 'IBAN',
            iban_ref_no VARCHAR(100),
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'order_items' => "CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'admins' => "CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'messages' => "CREATE TABLE IF NOT EXISTS messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            customer_identifier VARCHAR(100),
            sender VARCHAR(20) NOT NULL,
            message TEXT NOT NULL,
            is_read TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    ];

    echo "<br>📋 Tablolar oluşturuluyor...<br>";
    foreach ($tables as $name => $sql) {
        $db->exec($sql);
        logMsg("$name tablosu oluşturuldu");
    }

    // İndexler
    echo "<br>📋 İndexler oluşturuluyor...<br>";
    $indexes = [
        "CREATE INDEX idx_models_brand ON models(brand_id)",
        "CREATE INDEX idx_variants_model ON variants(model_id)",
        "CREATE INDEX idx_products_category ON products(category_id)",
        "CREATE INDEX idx_product_compat_product ON product_compatibility(product_id)",
        "CREATE INDEX idx_product_compat_variant ON product_compatibility(variant_id)",
        "CREATE INDEX idx_orders_status ON orders(status)",
        "CREATE INDEX idx_orders_created ON orders(created_at)",
        "CREATE INDEX idx_messages_customer ON messages(customer_identifier)",
        "CREATE INDEX idx_products_oem ON products(oem_no)",
    ];
    foreach ($indexes as $idx) { try { $db->exec($idx); } catch (Exception $e) {} }
    logMsg("Tüm indexler oluşturuldu");

    // ===== SEED DATA =====
    echo "<br>📋 Seed data ekleniyor...<br>";

    // Sadece Hyundai ve Kia
    $brands = ['Hyundai','Kia'];
    $stmtBrand = $db->prepare('INSERT IGNORE INTO brands (name, slug) VALUES (?, ?)');
    foreach ($brands as $name) {
        $slug = strtolower($name);
        $stmtBrand->execute([$name, $slug]);
    }
    logMsg("2 marka eklendi (Hyundai, Kia)");

    // Hyundai modelleri
    $hyundaiModels = [
        'i10', 'i20', 'i30', 'i40',
        'Accent', 'Accent Era', 'Accent Blue',
        'Elantra', 'Getz', 'Tucson', 'Kona',
        'Bayon', 'ix35', 'Santa Fe',
    ];

    // Kia modelleri
    $kiaModels = [
        'Rio', 'Ceed', 'Cerato', 'Sportage',
        'Stonic', 'Sorento', 'Picanto', 'Venga',
        'Niro', 'Optima', 'Soul', 'Carnival',
        'XCeed', 'ProCeed',
    ];

    $stmtModel = $db->prepare('INSERT IGNORE INTO models (brand_id, name, slug) VALUES (?, ?, ?)');

    // Hyundai
    $res = $db->prepare('SELECT id FROM brands WHERE slug = ?');
    $res->execute(['hyundai']);
    $hyundaiId = $res->fetch()['id'];
    foreach ($hyundaiModels as $name) {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', str_replace(['ı','ş','ğ','ü','ö','ç'],['i','s','g','u','o','c'], mb_strtolower($name, 'UTF-8'))));
        $stmtModel->execute([$hyundaiId, $name, $slug]);
    }
    logMsg(count($hyundaiModels) . " Hyundai modeli eklendi");

    // Kia
    $res->execute(['kia']);
    $kiaId = $res->fetch()['id'];
    foreach ($kiaModels as $name) {
        $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', str_replace(['ı','ş','ğ','ü','ö','ç'],['i','s','g','u','o','c'], mb_strtolower($name, 'UTF-8'))));
        $stmtModel->execute([$kiaId, $name, $slug]);
    }
    logMsg(count($kiaModels) . " Kia modeli eklendi");

    // Motor Varyantları
    $variantData = [
        // Hyundai
        ['hyundai','i10',[
            [2008,2013,'1.1 CRDi','dizel',75],[2008,2013,'1.2','benzin',78],
            [2013,2019,'1.0','benzin',66],[2013,2019,'1.2','benzin',87],
            [2019,2025,'1.0','benzin',67],[2019,2025,'1.0 T-GDi','benzin',100],
        ]],
        ['hyundai','i20',[
            [2008,2014,'1.2','benzin',78],[2008,2014,'1.4','benzin',100],[2008,2014,'1.4 CRDi','dizel',77],[2008,2014,'1.6 CRDi','dizel',116],
            [2014,2020,'1.2 MPI','benzin',84],[2014,2020,'1.4 MPI','benzin',100],[2014,2020,'1.4 CRDi','dizel',90],[2014,2020,'1.0 T-GDi','benzin',120],
            [2020,2025,'1.0 T-GDi','benzin',100],[2020,2025,'1.0 T-GDi','benzin',120],[2020,2025,'1.4 MPI','benzin',100],
        ]],
        ['hyundai','i30',[
            [2007,2012,'1.6 CRDi','dizel',116],[2007,2012,'1.4','benzin',109],[2007,2012,'1.6','benzin',122],[2007,2012,'2.0 CRDi','dizel',140],
            [2012,2017,'1.6 CRDi','dizel',110],[2012,2017,'1.6 CRDi','dizel',136],[2012,2017,'1.4','benzin',100],[2012,2017,'1.6 GDi','benzin',135],
            [2017,2025,'1.0 T-GDi','benzin',120],[2017,2025,'1.4 T-GDi','benzin',140],[2017,2025,'1.6 CRDi','dizel',115],[2017,2025,'1.6 CRDi','dizel',136],
        ]],
        ['hyundai','accent',[
            [2005,2011,'1.5 CRDi','dizel',110],[2005,2011,'1.4','benzin',97],
            [2011,2017,'1.6 CRDi','dizel',128],[2011,2017,'1.4','benzin',100],[2011,2017,'1.6','benzin',124],
        ]],
        ['hyundai','elantra',[
            [2011,2015,'1.6','benzin',132],[2011,2015,'1.6 CRDi','dizel',128],
            [2015,2019,'1.6','benzin',128],[2015,2019,'1.6 CRDi','dizel',136],
            [2019,2025,'1.6 MPI','benzin',123],[2019,2025,'1.6 CRDi','dizel',136],
        ]],
        ['hyundai','tucson',[
            [2004,2010,'2.0 CRDi','dizel',140],[2004,2010,'2.0','benzin',141],
            [2015,2020,'1.6 T-GDi','benzin',177],[2015,2020,'1.7 CRDi','dizel',116],[2015,2020,'2.0 CRDi','dizel',185],
            [2020,2025,'1.6 T-GDi','benzin',150],[2020,2025,'1.6 CRDi','dizel',136],[2020,2025,'1.6 T-GDi Hybrid','hibrit',230],
        ]],
        ['hyundai','kona',[
            [2017,2023,'1.0 T-GDi','benzin',120],[2017,2023,'1.6 CRDi','dizel',115],[2017,2023,'1.6 CRDi','dizel',136],
            [2023,2025,'1.0 T-GDi','benzin',120],[2023,2025,'1.6 T-GDi Hybrid','hibrit',141],
        ]],
        ['hyundai','bayon',[
            [2021,2025,'1.2 MPI','benzin',84],[2021,2025,'1.0 T-GDi','benzin',100],[2021,2025,'1.0 T-GDi 48V','benzin',100],
        ]],
        ['hyundai','getz',[
            [2002,2009,'1.1','benzin',63],[2002,2009,'1.3','benzin',82],[2002,2009,'1.4','benzin',97],[2002,2009,'1.5 CRDi','dizel',82],
        ]],
        ['hyundai','ix35',[
            [2010,2015,'2.0 CRDi','dizel',136],[2010,2015,'2.0 CRDi','dizel',184],[2010,2015,'1.6 GDi','benzin',135],[2010,2015,'2.0','benzin',163],
        ]],
        ['hyundai','santa-fe',[
            [2006,2012,'2.2 CRDi','dizel',155],[2006,2012,'2.7 V6','benzin',189],
            [2012,2018,'2.0 CRDi','dizel',150],[2012,2018,'2.2 CRDi','dizel',200],
            [2018,2025,'2.2 CRDi','dizel',200],[2018,2025,'2.0 T-GDi','benzin',185],[2018,2025,'1.6 T-GDi Hybrid','hibrit',230],
        ]],

        // Kia
        ['kia','rio',[
            [2005,2011,'1.4','benzin',97],[2005,2011,'1.5 CRDi','dizel',110],
            [2011,2017,'1.25','benzin',84],[2011,2017,'1.4','benzin',109],[2011,2017,'1.4 CRDi','dizel',90],
            [2017,2025,'1.0 T-GDi','benzin',100],[2017,2025,'1.0 T-GDi','benzin',120],[2017,2025,'1.2 MPI','benzin',84],[2017,2025,'1.4 MPI','benzin',100],
        ]],
        ['kia','ceed',[
            [2006,2012,'1.6 CRDi','dizel',115],[2006,2012,'1.4','benzin',109],[2006,2012,'2.0 CRDi','dizel',140],
            [2012,2018,'1.6 CRDi','dizel',110],[2012,2018,'1.6 CRDi','dizel',136],[2012,2018,'1.6 GDi','benzin',135],
            [2018,2025,'1.0 T-GDi','benzin',120],[2018,2025,'1.4 T-GDi','benzin',140],[2018,2025,'1.6 CRDi','dizel',115],[2018,2025,'1.6 CRDi','dizel',136],
        ]],
        ['kia','cerato',[
            [2004,2008,'1.6','benzin',105],[2004,2008,'1.5 CRDi','dizel',102],[2004,2008,'2.0 CRDi','dizel',113],
            [2008,2013,'1.6','benzin',126],[2008,2013,'1.6 CRDi','dizel',115],[2008,2013,'2.0','benzin',156],
        ]],
        ['kia','sportage',[
            [2004,2010,'2.0 CRDi','dizel',140],[2004,2010,'2.0','benzin',141],
            [2010,2015,'2.0 CRDi','dizel',136],[2010,2015,'1.6 GDi','benzin',135],
            [2015,2021,'1.6 T-GDi','benzin',177],[2015,2021,'1.7 CRDi','dizel',116],[2015,2021,'2.0 CRDi','dizel',185],
            [2021,2025,'1.6 T-GDi','benzin',150],[2021,2025,'1.6 CRDi','dizel',136],[2021,2025,'1.6 T-GDi Hybrid','hibrit',230],
        ]],
        ['kia','stonic',[
            [2017,2025,'1.0 T-GDi','benzin',100],[2017,2025,'1.0 T-GDi','benzin',120],[2017,2025,'1.4 MPI','benzin',100],
        ]],
        ['kia','sorento',[
            [2002,2009,'2.5 CRDi','dizel',140],[2002,2009,'2.5 CRDi','dizel',170],
            [2009,2014,'2.2 CRDi','dizel',197],[2009,2014,'2.0 CRDi','dizel',150],
            [2014,2020,'2.2 CRDi','dizel',200],[2014,2020,'2.0 CRDi','dizel',185],
            [2020,2025,'2.2 CRDi','dizel',202],[2020,2025,'1.6 T-GDi Hybrid','hibrit',230],
        ]],
        ['kia','picanto',[
            [2004,2011,'1.0','benzin',62],[2004,2011,'1.1','benzin',65],[2004,2011,'1.1 CRDi','dizel',75],
            [2011,2017,'1.0','benzin',69],[2011,2017,'1.25','benzin',85],
            [2017,2025,'1.0','benzin',67],[2017,2025,'1.0 T-GDi','benzin',100],[2017,2025,'1.2 MPI','benzin',84],
        ]],
        ['kia','niro',[
            [2016,2022,'1.6 GDi Hybrid','hibrit',141],[2016,2022,'1.6 GDi PHEV','hibrit',141],
            [2022,2025,'1.6 GDi Hybrid','hibrit',141],[2022,2025,'1.6 T-GDi PHEV','hibrit',183],
        ]],
        ['kia','xceed',[
            [2019,2025,'1.0 T-GDi','benzin',120],[2019,2025,'1.4 T-GDi','benzin',140],[2019,2025,'1.6 CRDi','dizel',136],[2019,2025,'1.6 GDi PHEV','hibrit',141],
        ]],
    ];

    $stmtVar = $db->prepare("INSERT INTO variants (model_id, year_start, year_end, engine_type, fuel_type, horsepower) VALUES (?, ?, ?, ?, ?, ?)");
    $varCount = 0;
    foreach ($variantData as [$brandSlug, $modelSlug, $variants]) {
        $res2 = $db->prepare("SELECT m.id FROM models m JOIN brands b ON m.brand_id=b.id WHERE m.slug=? AND b.slug=?");
        $res2->execute([$modelSlug, $brandSlug]);
        $model = $res2->fetch();
        if (!$model) continue;
        foreach ($variants as [$ys,$ye,$engine,$fuel,$hp]) {
            try { $stmtVar->execute([$model['id'],$ys,$ye,$engine,$fuel,$hp]); $varCount++; } catch(Exception $e) {}
        }
    }
    logMsg("$varCount motor varyantı eklendi");

    // Kategoriler
    $categories = [
        ['Motor Parçaları','motor-parcalari','🔧',1],
        ['Fren Sistemleri','fren-sistemleri','🛑',2],
        ['Süspansiyon','suspansiyon','🔩',3],
        ['Aydınlatma','aydinlatma','💡',4],
        ['Kaporta Parçaları','kaporta-parcalari','🚗',5],
        ['Elektrik & Elektronik','elektrik-elektronik','⚡',6],
        ['Filtreler','filtreler','🔍',7],
        ['Kayış & Zincir','kayis-zincir','⛓',8],
        ['Egzoz Sistemi','egzoz-sistemi','💨',9],
        ['Soğutma Sistemi','sogutma-sistemi','❄️',10],
        ['Yağlar & Sıvılar','yaglar-sivilar','🛢',11],
        ['İç Aksesuar','ic-aksesuar','🪑',12],
    ];
    $stmtCat = $db->prepare('INSERT IGNORE INTO categories (name, slug, icon, sort_order) VALUES (?, ?, ?, ?)');
    foreach ($categories as [$name,$slug,$icon,$order]) { $stmtCat->execute([$name,$slug,$icon,$order]); }
    logMsg("12 kategori eklendi");

    echo "<br>🎉 <strong>VERİTABANI BAŞARIYLA KURULDU!</strong><br>";
    file_put_contents($lockFile, date('Y-m-d H:i:s'));

} catch (Exception $e) {
    logMsg("HATA: " . $e->getMessage(), false);
}
?>
</div>

<div class="warn">
    ⚠️ <strong>Güvenlik Uyarısı:</strong> Kurulum tamamlandı. <code>init-db.php</code> dosyasını Plesk dosya yöneticisinden silin!
</div>

<p style="margin-top:20px;font-size:0.9rem">
    <a href="/" style="color:#dc2626;font-weight:600">→ Siteye Git</a> &nbsp;|&nbsp;
    <a href="/admin/login.php" style="color:#dc2626;font-weight:600">→ Admin Panele Git</a>
</p>
</body>
</html>
