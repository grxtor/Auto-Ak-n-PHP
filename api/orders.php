<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Siparis sorgula (musteri kendi siparislerini gorur)
    $orderCode = $_GET['code'] ?? '';
    if ($orderCode) {
        $stmt = $db->prepare('SELECT * FROM orders WHERE order_code = ?');
        $stmt->execute([$orderCode]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order) {
            $items = $db->prepare('SELECT oi.*, p.name as product_name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?');
            $items->execute([$order['id']]);
            $order['items'] = $items->fetchAll(PDO::FETCH_ASSOC);
            // IBAN bilgilerini ekle
            $settings = $db->query('SELECT setting_key, setting_value FROM settings WHERE setting_key IN ("iban","iban_holder","iban_bank")')->fetchAll(PDO::FETCH_KEY_PAIR);
            $order['iban'] = $settings['iban'] ?? '';
            $order['iban_holder'] = $settings['iban_holder'] ?? '';
            $order['iban_bank'] = $settings['iban_bank'] ?? '';
            echo json_encode($order);
        } else {
            echo json_encode(['error' => 'Siparis bulunamadi']);
        }
        exit;
    }
    // Musteri kendi siparislerini gorur
    if (isset($_SESSION['customer_id'])) {
        $stmt = $db->prepare('SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC');
        $stmt->execute([$_SESSION['customer_id']]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        echo json_encode([]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST gerekli']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$customer = $data['customer'] ?? [];
$items = $data['items'] ?? [];
$total = $data['total'] ?? 0;
$saveAddress = $data['save_address'] ?? false;

if (empty($customer['name']) || empty($items)) {
    echo json_encode(['error' => 'Eksik veri']);
    exit;
}

try {
    $db->beginTransaction();

    // Benzersiz siparis kodu olustur
    $orderCode = 'AKN-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

    // Musteri oturum acmissa customer_id ekle
    $customerId = $_SESSION['customer_id'] ?? null;

    $stmt = $db->prepare(
        "INSERT INTO orders (order_code, customer_id, customer_name, customer_email, customer_phone, customer_address, total_amount, status, payment_method) 
         VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'IBAN')"
    );
    $stmt->execute([$orderCode, $customerId, $customer['name'], $customer['email'] ?? '', $customer['phone'] ?? '', $customer['address'] ?? '', $total]);
    $orderId = $db->lastInsertId();

    $stmtItem = $db->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    $stmtStock = $db->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');

    foreach ($items as $item) {
        $stmtItem->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
        $stmtStock->execute([$item['quantity'], $item['id']]);
    }

    // Adres kaydetme
    if ($saveAddress && $customerId) {
        $db->prepare('UPDATE customers SET phone = ?, address = ? WHERE id = ?')
           ->execute([$customer['phone'] ?? '', $customer['address'] ?? '', $customerId]);
    }

    $db->commit();

    // IBAN bilgilerini gonder
    $settings = $db->query('SELECT setting_key, setting_value FROM settings WHERE setting_key IN ("iban","iban_holder","iban_bank")')->fetchAll(PDO::FETCH_KEY_PAIR);

    echo json_encode([
        'success' => true,
        'orderId' => $orderId,
        'orderCode' => $orderCode,
        'iban' => $settings['iban'] ?? '',
        'iban_holder' => $settings['iban_holder'] ?? '',
        'iban_bank' => $settings['iban_bank'] ?? ''
    ]);
} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['error' => 'Sipariş oluşturulamadı: ' . $e->getMessage()]);
}
