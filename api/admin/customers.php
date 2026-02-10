<?php
// Admin Kullanici Yonetimi API
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/db.php';
$db = getDB();

// Oturum kontrolü
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Yetkisiz erişim']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// GET - Müşterileri Listele
if ($method === 'GET') {
    $stmt = $db->query('SELECT id, name, email, phone, address, created_at FROM customers ORDER BY created_at DESC');
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $_GET['action'] ?? 'add';

    // Müşteri Ekle
    if ($action === 'add') {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $phone = trim($data['phone'] ?? '');
        $address = trim($data['address'] ?? '');

        if (!$name || !$email || !$password) {
            echo json_encode(['success' => false, 'error' => 'Ad, email ve şifre zorunludur']);
            exit;
        }

        // Email kontrolü
        $check = $db->prepare('SELECT id FROM customers WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Bu e-posta adresi zaten kayıtlı']);
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $db->prepare('INSERT INTO customers (name, email, password_hash, phone, address) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$name, $email, $hash, $phone, $address]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        exit;
    }

    // Müşteri Sil (Opsiyonel)
    if ($action === 'delete') {
        $id = $data['id'] ?? null;
        if (!$id) { echo json_encode(['success' => false, 'error' => 'ID gerekli']); exit; }
        $db->prepare('DELETE FROM customers WHERE id = ?')->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }
}
