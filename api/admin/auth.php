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

    // Login
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (!$username || !$password) {
        echo json_encode(['success' => false, 'error' => 'Kullanıcı adı ve şifre gerekli.']);
        exit;
    }

    // Önce DB'den kontrol et
    try {
        $stmt = $db->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            echo json_encode(['success' => true]);
            exit;
        }
    } catch (Exception $e) {
        // admins tablosu yoksa fallback
    }

    // Fallback: hardcoded (ilk kurulumda)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_user'] = 'admin';
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Kullanıcı adı veya şifre hatalı.']);
}
