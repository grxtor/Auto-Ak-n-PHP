<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];

try {
    $db = getDB();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB baglanti hatasi: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    if ($method === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            echo json_encode(['error' => 'Gecersiz JSON verisi']);
            exit;
        }

        $categoryId = (!empty($data['category_id']) && $data['category_id'] !== 'null') ? $data['category_id'] : null;

        $stmt = $db->prepare(
            "INSERT INTO products (name, description, price, stock, category_id, image_url, oem_no, part_brand) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['name'] ?? '',
            $data['description'] ?? '',
            $data['price'] ?? 0,
            $data['stock'] ?? 0,
            $categoryId,
            $data['image_url'] ?? '',
            $data['oem_no'] ?? '',
            $data['part_brand'] ?? ''
        ]);
        $productId = $db->lastInsertId();

        // Araç uyumluluğu
        if (!empty($data['variant_ids']) && is_array($data['variant_ids'])) {
            $stmtC = $db->prepare('INSERT IGNORE INTO product_compatibility (product_id, variant_id) VALUES (?, ?)');
            foreach ($data['variant_ids'] as $vid) {
                if ($vid) $stmtC->execute([$productId, $vid]);
            }
        }
        echo json_encode(['success' => true, 'id' => $productId]);
    }
    elseif ($method === 'DELETE') {
        $id = $_GET['id'] ?? null;
        if (!$id) { echo json_encode(['error' => 'ID gerekli']); exit; }
        $db->prepare('DELETE FROM product_compatibility WHERE product_id = ?')->execute([$id]);
        $db->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
        echo json_encode(['success' => true]);
    }
    elseif ($method === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $categoryId = (!empty($data['category_id']) && $data['category_id'] !== 'null') ? $data['category_id'] : null;
        $stmt = $db->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, category_id=?, image_url=?, oem_no=?, part_brand=? WHERE id=?");
        $stmt->execute([
            $data['name'] ?? '', $data['description'] ?? '', $data['price'] ?? 0, $data['stock'] ?? 0,
            $categoryId, $data['image_url'] ?? '', $data['oem_no'] ?? '', $data['part_brand'] ?? '', $data['id']
        ]);
        if (isset($data['variant_ids'])) {
            $db->prepare('DELETE FROM product_compatibility WHERE product_id = ?')->execute([$data['id']]);
            if (is_array($data['variant_ids'])) {
                $stmtC = $db->prepare('INSERT IGNORE INTO product_compatibility (product_id, variant_id) VALUES (?, ?)');
                foreach ($data['variant_ids'] as $vid) { if ($vid) $stmtC->execute([$data['id'], $vid]); }
            }
        }
        echo json_encode(['success' => true]);
    }
    else {
        // GET - tüm ürünler
        $stmt = $db->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB hatasi: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Sunucu hatasi: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
