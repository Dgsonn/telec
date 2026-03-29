<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$row = $db->prepare('SELECT id, name, image FROM products WHERE id = ?');
$row->execute([$id]);
$product = $row->fetch();

if ($product) {
    // Xóa file ảnh nếu có
    if ($product['image'] && file_exists('../' . $product['image'])) {
        @unlink('../' . $product['image']);
    }
    $db->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đã xóa sản phẩm "' . $product['name'] . '".'];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Sản phẩm không tồn tại.'];
}

header('Location: index.php');
exit;
