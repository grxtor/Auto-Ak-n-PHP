<?php
// Dekont yukleme API
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/db.php';
$db = getDB();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'POST gerekli']);
    exit;
}

if (!isset($_FILES['receipt'])) {
    echo json_encode(['success' => false, 'error' => 'Dosya bulunamadı']);
    exit;
}

$orderCode = $_POST['order_code'] ?? '';
if (!$orderCode) {
    echo json_encode(['success' => false, 'error' => 'Sipariş kodu gerekli']);
    exit;
}

$file = $_FILES['receipt'];
$allowed = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
$maxSize = 10 * 1024 * 1024; // 10MB

if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'error' => 'Sadece JPG, PNG, WebP veya PDF yükleyebilirsiniz.']);
    exit;
}

if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'error' => 'Dosya 10MB\'dan büyük olamaz.']);
    exit;
}

// uploads klasoru
$uploadDir = __DIR__ . '/../uploads/receipts/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = $orderCode . '_' . time() . '.' . $ext;
$filepath = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $filepath)) {
    echo json_encode(['success' => false, 'error' => 'Dosya yüklenemedi.']);
    exit;
}

$url = '/uploads/receipts/' . $filename;

// DB guncelle
try {
    $stmt = $db->prepare('UPDATE orders SET receipt_url = ? WHERE order_code = ?');
    $stmt->execute([$url, $orderCode]);
    echo json_encode(['success' => true, 'url' => $url]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Veritabanı hatası']);
}
