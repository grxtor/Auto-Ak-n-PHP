<?php
// Admin ayarlar API - CRUD
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/db.php';
$db = getDB();

// Oturum kontrolu
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Yetkisiz erisim']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// GET - tum ayarlari getir veya istatistikleri cek
if ($method === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'stats') {
        $stats = [
            'products' => (int)$db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            'orders' => (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'pending' => (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn(),
            'messages' => (int)$db->query("SELECT COUNT(*) FROM messages WHERE is_read = 0 AND sender = 'customer'")->fetchColumn()
        ];
        echo json_encode($stats);
        exit;
    }
    
    $stmt = $db->query('SELECT * FROM settings ORDER BY setting_key');
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

// PUT - ayar guncelle
if ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $key = $data['key'] ?? '';
    $value = $data['value'] ?? '';

    if (!$key) {
        echo json_encode(['success' => false, 'error' => 'Anahtar gerekli']);
        exit;
    }

    $stmt = $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?');
    $stmt->execute([$key, $value, $value]);
    echo json_encode(['success' => true]);
    exit;
}

// POST - toplu ayar guncelle
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?');
    foreach ($data as $key => $value) {
        $stmt->execute([$key, $value, $value]);
    }
    echo json_encode(['success' => true]);
    exit;
}
