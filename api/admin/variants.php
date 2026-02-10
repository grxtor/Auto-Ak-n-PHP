<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $modelId = $_GET['modelId'] ?? null;
    $query = "SELECT v.*, m.name as model_name, b.name as brand_name FROM variants v JOIN models m ON v.model_id = m.id JOIN brands b ON m.brand_id = b.id";
    $params = [];
    if ($modelId) { $query .= " WHERE v.model_id = ?"; $params[] = $modelId; }
    $query .= " ORDER BY b.name, m.name, v.year_start DESC";
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare("INSERT INTO variants (model_id, year_start, year_end, engine_type, engine_code, fuel_type, horsepower) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['model_id'], $data['year_start'], $data['year_end'] ?: null,
        $data['engine_type'], $data['engine_code'] ?: null,
        $data['fuel_type'] ?? 'benzin', $data['horsepower'] ?: null
    ]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
}
elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) { echo json_encode(['error' => 'ID gerekli']); exit; }
    $stmt = $db->prepare('DELETE FROM variants WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}
