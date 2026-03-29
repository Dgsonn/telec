<?php
// API public: lấy danh sách / chi tiết tin tức
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../admin/config/db.php';

try {
    $db = getDB();
    $id    = (int)($_GET['id'] ?? 0);
    $limit = min((int)($_GET['limit'] ?? 6), 50);

    if ($id) {
        // Chi tiết 1 bài
        $stmt = $db->prepare('SELECT * FROM news WHERE id = ? AND is_active = 1');
        $stmt->execute([$id]);
        $article = $stmt->fetch();
        if ($article) {
            $db->prepare('UPDATE news SET views = views + 1 WHERE id = ?')->execute([$id]);
            echo json_encode(['success' => true, 'data' => $article]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Not found']);
        }
    } else {
        // Danh sách
        $stmt = $db->prepare('SELECT id, title, summary, image, views, created_at FROM news WHERE is_active = 1 ORDER BY created_at DESC LIMIT ' . $limit);
        $stmt->execute();
        $news = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $news, 'count' => count($news)]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
