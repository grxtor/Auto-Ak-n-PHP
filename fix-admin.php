<?php
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/config/db.php';
$db = getDB();

echo "--- Admin Sifre Sifirlama ---\n";

$u = 'admin';
$p = 'admin123';
$h = password_hash($p, PASSWORD_DEFAULT);

try {
    // Önce var mı bak
    $stmt = $db->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$u]);
    $admin = $stmt->fetch();

    if ($admin) {
        $stmt = $db->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
        $stmt->execute([$h, $admin['id']]);
        echo "Admin hesabı güncellendi: $u / $p\n";
    } else {
        $stmt = $db->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$u, $h]);
        echo "Admin hesabı oluşturuldu: $u / $p\n";
    }
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}

echo "\nIslem tamam. Simdi http://localhost/autoakin/admin/login adresinden giris yapabilirsiniz.\n";
