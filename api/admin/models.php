<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $brandId = $_GET['brandId'] ?? null;
    $query = "SELECT m.*, b.name as brand_name FROM models m JOIN brands b ON m.brand_id = b.id";
    $params = [];
    if ($brandId) { $query .= " WHERE m.brand_id = ?"; $params[] = $brandId; }
    $query .= " ORDER BY b.name ASC, m.name ASC";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $brandId = $data['brand_id'] ?? null;
    $name = $data['name'] ?? '';
    if (!$brandId || !$name) { echo json_encode(['error' => 'Eksik veri']); exit; }
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', str_replace(['ı','ş','ğ','ü','ö','ç'], ['i','s','g','u','o','c'], mb_strtolower($name, 'UTF-8'))));
    $stmt = $db->prepare('INSERT INTO models (brand_id, name, slug) VALUES (?, ?, ?)');
    $stmt->execute([$brandId, $name, $slug]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
}
elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) { echo json_encode(['error' => 'ID gerekli']); exit; }
    $stmt = $db->prepare('DELETE FROM models WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}
