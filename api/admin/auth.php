<?php
// Admin oturum yonetimi - PHP session tabanlı
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/db.php';
$db = getDB();


$method = $_SERVER['REQUEST_METHOD'];

// GET - oturum kontrol
if ($method === 'GET') {
    echo json_encode(['loggedIn' => isset($_SESSION['admin_id'])]);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? 'login';

    if ($action === 'logout') {
        unset($_SESSION['admin_id']);
        session_destroy();
        echo json_encode(['success' => true]);
        exit;
    }

    // Giriş Kontrolü
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');

    if (!$username || !$password) {
        echo json_encode(['success' => false, 'error' => 'Lutfen tum alanlari doldurun. (Empty inputs)']);
        exit;
    }

    // MASTER LOGIN: admin / admin123
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_user'] = 'admin';
        echo json_encode(['success' => true]);
        exit;
    }

    // Normal DB Login
    try {
        $stmt = $db->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            echo json_encode(['success' => true]);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'DB Hatasi: ' . $e->getMessage()]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Giris basarisiz. (Kullanici: ' . $username . ')']);
}
