<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->query('SELECT * FROM brands ORDER BY name ASC');
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
}
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'] ?? '';
    if (!$name) { echo json_encode(['error' => 'İsim gerekli']); exit; }
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', str_replace(['ı','ş','ğ','ü','ö','ç'], ['i','s','g','u','o','c'], mb_strtolower($name, 'UTF-8'))));
    $stmt = $db->prepare('INSERT IGNORE INTO brands (name, slug) VALUES (?, ?)');
    $stmt->execute([$name, $slug]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
}
elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if (!$id) { echo json_encode(['error' => 'ID gerekli']); exit; }
    $stmt = $db->prepare('DELETE FROM brands WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}
