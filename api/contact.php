<?php
// API public: lấy thông tin liên hệ cho frontend
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../admin/config/db.php';

try {
    $db = getDB();
    $rows = $db->query('SELECT key_name, value FROM contact_info')->fetchAll();
    $result = [];
    foreach ($rows as $r) $result[$r['key_name']] = $r['value'];
    echo json_encode(['success' => true, 'data' => $result]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
