<?php
// API public: lấy danh sách dự án cho frontend
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../admin/config/db.php';

try {
    $db = getDB();
    $category = $_GET['cat'] ?? '';
    $limit    = min((int)($_GET['limit'] ?? 10), 50);

    $sql    = 'SELECT id, title, client_name, location, category, description, image, capacity, project_date, link_detail FROM projects WHERE is_active = 1';
    $params = [];
    if ($category) { $sql .= ' AND category = ?'; $params[] = $category; }
    $sql .= ' ORDER BY sort_order ASC, created_at DESC LIMIT ' . $limit;

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $projects = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $projects, 'count' => count($projects)]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
