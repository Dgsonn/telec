<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT id, title, image FROM news WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();
if ($row) {
    if ($row['image'] && file_exists('../' . $row['image'])) @unlink('../' . $row['image']);
    $db->prepare('DELETE FROM news WHERE id = ?')->execute([$id]);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đã xóa bài viết "' . $row['title'] . '".'];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Bài viết không tồn tại.'];
}
header('Location: index.php'); exit;
