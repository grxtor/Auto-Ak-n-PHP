<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message = $data['message'] ?? '';
    $customerId = $data['customerId'] ?? '';

    if (!$message || !$customerId) { echo json_encode(['error' => 'Eksik veri']); exit; }

    $stmt = $db->prepare('INSERT INTO messages (customer_identifier, sender, message) VALUES (?, ?, ?)');
    $stmt->execute([$customerId, 'customer', $message]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
    exit;
}

// GET
$customerId = $_GET['customerId'] ?? null;
if (!$customerId) { echo json_encode(['error' => 'customerId gerekli']); exit; }

$stmt = $db->prepare('SELECT * FROM messages WHERE customer_identifier = ? ORDER BY created_at ASC');
$stmt->execute([$customerId]);
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
