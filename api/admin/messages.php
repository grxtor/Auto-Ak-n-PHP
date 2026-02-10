<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->query("SELECT customer_identifier, MAX(created_at) as last_msg, SUM(CASE WHEN is_read = 0 AND sender = 'customer' THEN 1 ELSE 0 END) as unread_count FROM messages GROUP BY customer_identifier ORDER BY last_msg DESC");
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $customerId = $data['customerId'] ?? '';
    $message = $data['message'] ?? '';
    if (!$customerId || !$message) { echo json_encode(['error' => 'Eksik veri']); exit; }
    $db->prepare('INSERT INTO messages (customer_identifier, sender, message) VALUES (?, ?, ?)')->execute([$customerId, 'admin', $message]);
    $db->prepare("UPDATE messages SET is_read = 1 WHERE customer_identifier = ? AND sender = 'customer'")->execute([$customerId]);
    echo json_encode(['success' => true]);
}
