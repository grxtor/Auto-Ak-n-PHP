<?php
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/config/db.php';

try {
    $db = getDB();
    echo "--- Admin Tamir Aracı ---\n";
    
    $u = 'admin';
    $p = 'admin123';
    $h = password_hash($p, PASSWORD_DEFAULT);
    
    // Admins tablosu var mı?
    $stmt = $db->query("SHOW TABLES LIKE 'admins'");
    if ($stmt->rowCount() == 0) {
        echo "Admins tablosu bulunamadı, oluşturuluyor...\n";
        $db->exec("CREATE TABLE admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    // Temizle ve yeniden ekle
    $db->exec("DELETE FROM admins WHERE username = '$u'");
    $stmt = $db->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$u, $h]);
    
    echo "Admin kullanıcısı sıfırlandı: $u / $p\n";
    
    // Test et
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$u]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($p, $admin['password_hash'])) {
        echo "Şifre doğrulama testi: BAŞARILI\n";
    } else {
        echo "Şifre doğrulama testi: BAŞARISIZ!\n";
    }

} catch (Exception $e) {
    echo "HATA: " . $e->getMessage();
}
