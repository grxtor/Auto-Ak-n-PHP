<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$modelId = $_GET['modelId'] ?? null;
if (!$modelId) { echo json_encode(['error' => 'modelId gerekli']); exit; }

$stmt = $db->prepare('SELECT * FROM variants WHERE model_id = ? AND is_active = 1 ORDER BY year_start DESC, engine_type ASC');
$stmt->execute([$modelId]);
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
