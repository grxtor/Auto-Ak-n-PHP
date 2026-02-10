<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$db = getDB();

$category = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;
$variantId = $_GET['variant'] ?? null;

$query = "SELECT p.*, c.name as category_name, c.slug as category_slug FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_active = 1";
$params = [];

if ($category) {
    $query .= " AND c.slug = ?";
    $params[] = $category;
}

if ($search) {
    $query .= " AND (p.name LIKE ? OR p.description LIKE ? OR p.oem_no LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($variantId) {
    $query .= " AND p.id IN (SELECT product_id FROM product_compatibility WHERE variant_id = ?)";
    $params[] = $variantId;
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);
echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
