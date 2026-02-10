<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST gerekli']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$customer = $data['customer'] ?? [];
$items = $data['items'] ?? [];
$total = $data['total'] ?? 0;

if (empty($customer['name']) || empty($items)) {
    echo json_encode(['error' => 'Eksik veri']);
    exit;
}

$db = getDB();

try {
    $db->beginTransaction();

    $stmt = $db->prepare(
        "INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, total_amount, status, payment_method) 
         VALUES (?, ?, ?, ?, ?, 'pending', 'IBAN')"
    );
    $stmt->execute([$customer['name'], $customer['email'], $customer['phone'], $customer['address'], $total]);
    $orderId = $db->lastInsertId();

    $stmtItem = $db->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    $stmtStock = $db->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');

    foreach ($items as $item) {
        $stmtItem->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
        $stmtStock->execute([$item['quantity'], $item['id']]);
    }

    $db->commit();
    echo json_encode(['success' => true, 'orderId' => $orderId]);
} catch (Exception $e) {
    $db->rollBack();
    echo json_encode(['error' => 'Sipariş oluşturulamadı']);
}
