<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->query('SELECT * FROM orders ORDER BY created_at DESC');
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
}
elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['id']) || empty($data['status'])) { echo json_encode(['error' => 'Eksik veri']); exit; }
    $stmt = $db->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute([$data['status'], $data['id']]);
    echo json_encode(['success' => true]);
}
