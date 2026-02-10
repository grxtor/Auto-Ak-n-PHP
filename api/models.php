<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$brandId = $_GET['brandId'] ?? null;
if (!$brandId) { echo json_encode(['error' => 'brandId gerekli']); exit; }

$stmt = $db->prepare('SELECT * FROM models WHERE brand_id = ? AND is_active = 1 ORDER BY name ASC');
$stmt->execute([$brandId]);
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
