<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$stmt = $db->query('SELECT * FROM brands WHERE is_active = 1 ORDER BY name ASC');
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
