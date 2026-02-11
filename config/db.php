<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'autoakin_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Dynamic Base URL detection for subdirectory support (local/prod)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
// Get the relative path from DOCUMENT_ROOT to the project root (dirname(__DIR__))
// Better path detection
$script_name = $_SERVER['SCRIPT_NAME']; // e.g. /autoakin/index.php or /autoakin/api/auth.php
$dir = str_replace('\\', '/', dirname($script_name)); // e.g. /autoakin or /autoakin/api

// If we are inside /api/ or /admin/ or /config/, go up
$rel_path = preg_replace('/(\/(api|admin|config|includes|classes).*)?$/i', '', $dir);
$rel_path = rtrim($rel_path, '/');

define('BASE_URL', $protocol . "://" . $host . $rel_path);

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Veritabanı bağlantı hatası']));
        }
    }
    return $pdo;
}
