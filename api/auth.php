<?php
// Musteri kayit / giris / profil API
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';
$db = getDB();
session_start();

$method = $_SERVER['REQUEST_METHOD'];

// GET - mevcut oturum bilgisi
if ($method === 'GET') {
    if (isset($_SESSION['customer_id'])) {
        $stmt = $db->prepare('SELECT id, name, email, phone, address, save_address FROM customers WHERE id = ?');
        $stmt->execute([$_SESSION['customer_id']]);
        $cust = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cust) {
            echo json_encode(['loggedIn' => true, 'customer' => $cust]);
        } else {
            session_destroy();
            echo json_encode(['loggedIn' => false]);
        }
    } else {
        echo json_encode(['loggedIn' => false]);
    }
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';

    // KAYIT
    if ($action === 'register') {
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $password = $data['password'] ?? '';

        if (!$name || !$email || !$password) {
            echo json_encode(['success' => false, 'error' => 'Ad, email ve şifre zorunludur.']);
            exit;
        }
        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'error' => 'Şifre en az 6 karakter olmalı.']);
            exit;
        }

        // Email kontrolu
        $check = $db->prepare('SELECT id FROM customers WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'error' => 'Bu email zaten kayıtlı.']);
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO customers (name, email, phone, password_hash) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $phone, $hash]);

        $customerId = $db->lastInsertId();
        $_SESSION['customer_id'] = $customerId;

        echo json_encode([
            'success' => true,
            'customer' => ['id' => $customerId, 'name' => $name, 'email' => $email, 'phone' => $phone, 'address' => null, 'save_address' => 1]
        ]);
        exit;
    }

    // GIRIS
    if ($action === 'login') {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            echo json_encode(['success' => false, 'error' => 'Email ve şifre gerekli.']);
            exit;
        }

        $stmt = $db->prepare('SELECT * FROM customers WHERE email = ?');
        $stmt->execute([$email]);
        $cust = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cust || !password_verify($password, $cust['password_hash'])) {
            echo json_encode(['success' => false, 'error' => 'Email veya şifre hatalı.']);
            exit;
        }

        $_SESSION['customer_id'] = $cust['id'];

        echo json_encode([
            'success' => true,
            'customer' => [
                'id' => $cust['id'], 'name' => $cust['name'], 'email' => $cust['email'],
                'phone' => $cust['phone'], 'address' => $cust['address'], 'save_address' => $cust['save_address']
            ]
        ]);
        exit;
    }

    // CIKIS
    if ($action === 'logout') {
        session_destroy();
        echo json_encode(['success' => true]);
        exit;
    }

    // PROFIL GUNCELLE
    if ($action === 'update') {
        if (!isset($_SESSION['customer_id'])) {
            echo json_encode(['success' => false, 'error' => 'Oturum açmanız gerekli.']);
            exit;
        }
        $fields = [];
        $values = [];
        if (isset($data['name'])) { $fields[] = 'name = ?'; $values[] = trim($data['name']); }
        if (isset($data['phone'])) { $fields[] = 'phone = ?'; $values[] = trim($data['phone']); }
        if (isset($data['address'])) { $fields[] = 'address = ?'; $values[] = trim($data['address']); }
        if (isset($data['save_address'])) { $fields[] = 'save_address = ?'; $values[] = (int)$data['save_address']; }

        if ($fields) {
            $values[] = $_SESSION['customer_id'];
            $db->prepare('UPDATE customers SET ' . implode(', ', $fields) . ' WHERE id = ?')->execute($values);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'error' => 'Geçersiz istek.']);
}
