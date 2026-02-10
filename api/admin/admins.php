<?php
// Admin Kullanici Yonetimi (Sistem Yoneticileri) API
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

// GET - Adminleri Listele
if ($method === 'GET') {
    $stmt = $db->query('SELECT id, username, created_at FROM admins ORDER BY created_at DESC');
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $_GET['action'] ?? 'add';

    // Admin Ekle
    if ($action === 'add') {
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if (!$username || !$password) {
            echo json_encode(['success' => false, 'error' => 'Kullanıcı adı ve şifre zorunludur']);
            exit;
        }

        // Username kontrolü
        $check = $db->prepare('SELECT id FROM admins WHERE username = ?');
        $check->execute([$username]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Bu kullanıcı adı zaten alınmış']);
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $db->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
            $stmt->execute([$username, $hash]);
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Veritabanı hatası: ' . $e->getMessage()]);
        }
        exit;
    }

    // Admin Sil
    if ($action === 'delete') {
        $id = $data['id'] ?? null;
        if (!$id) { echo json_encode(['success' => false, 'error' => 'ID gerekli']); exit; }
        
        // Kendi kendini silmeyi engelle
        if ($id == $_SESSION['admin_id']) {
            echo json_encode(['success' => false, 'error' => 'Kendi hesabınızı silemezsiniz']);
            exit;
        }

        $db->prepare('DELETE FROM admins WHERE id = ?')->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }
}
