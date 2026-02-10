<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();
$stmt = $db->query('SELECT * FROM categories ORDER BY sort_order ASC, name ASC');
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
