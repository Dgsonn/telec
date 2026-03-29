<?php
// API public: lấy danh sách sản phẩm cho frontend
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../admin/config/db.php';

try {
    $db = getDB();
    $category = $_GET['cat'] ?? '';
    $brand    = $_GET['brand'] ?? '';
    $limit    = min((int)($_GET['limit'] ?? 100), 200);

    $sql    = 'SELECT id, name, category, brand, description, image, price_label, link_detail FROM products WHERE is_active = 1';
    $params = [];
    if ($category) { $sql .= ' AND category = ?'; $params[] = $category; }
    if ($brand)    { $sql .= ' AND LOWER(brand) = ?'; $params[] = strtolower($brand); }
    $sql .= ' ORDER BY sort_order ASC, created_at DESC LIMIT ' . $limit;

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $products, 'count' => count($products)]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
