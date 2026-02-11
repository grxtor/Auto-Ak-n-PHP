#!/bin/bash

# Auto Akın Local Kurulum Scripti (XAMPP for Mac)
# ==============================================

TARGET_DIR="/Applications/XAMPP/xamppfiles/htdocs/autoakin"
PROJECT_DIR="$(pwd)"

echo "🚀 Local kurulum başlatılıyor..."

# 1. Htdocs içine klasör oluştur (Sudo gerekebilir)
echo "📂 Klasör oluşturuluyor: $TARGET_DIR"
sudo mkdir -p "$TARGET_DIR"
sudo chown -R $(whoami) "$TARGET_DIR"

# 2. Dosyaları kopyala
echo "🚚 Dosyalar kopyalanıyor..."
cp -R . "$TARGET_DIR/"

# 3. Local veritabanı ayarlarını yapılandır
echo "⚙️ Veritabanı ayarları düzenleniyor..."
cat <<EOF > "$TARGET_DIR/config/db.php"
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'autoakin_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB() {
    static \$pdo = null;
    if (\$pdo === null) {
        try {
            \$pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException \$e) {
            http_response_code(500);
            die(json_encode(['error' => 'Veritabanı bağlantı hatası: ' . \$e->getMessage()]));
        }
    }
    return \$pdo;
}
EOF

echo "✅ Kopyalama ve ayarlar tamamlandı!"
echo "------------------------------------------------"
echo "Şimdi şunları yap:"
echo "1. XAMPP Panelden Apache ve MySQL'i başlat."
echo "2. Tarayıcıdan http://localhost/phpmyadmin adresine git."
echo "3. 'autoakin_db' adında bir veritabanı oluştur."
echo "4. Şu adresi çalıştırarak tabloları kur: http://localhost/autoakin/init-db.php"
echo "------------------------------------------------"
