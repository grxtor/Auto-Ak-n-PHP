<?php
header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/config/db.php';

try {
    $db = getDB();
    echo "--- Database Kontrol Paneli ---\n";
    
    // Tabloları listele
    echo "\n[TABLOLAR]\n";
    $stmt = $db->query("SHOW TABLES");
    while($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "- " . $row[0] . "\n";
    }

    // Adminleri kontrol et
    echo "\n[ADMINLER]\n";
    $stmt = $db->query("SELECT id, username FROM admins");
    $admins = $stmt->fetchAll();
    if (empty($admins)) {
        echo "Admin tablosu boş!\n";
    } else {
        foreach($admins as $a) {
            echo "ID: {$a['id']}, Username: '{$a['username']}'\n";
        }
    }

    // Settings kontrol et (özellikle IBAN vs)
    echo "\n[AYARLAR (Son 5)]\n";
    $stmt = $db->query("SELECT setting_key, LEFT(setting_value, 20) as val FROM settings LIMIT 5");
    while($row = $stmt->fetch()) {
        echo "{$row['setting_key']}: {$row['val']}...\n";
    }

} catch (Exception $e) {
    echo "HATA: " . $e->getMessage();
}
