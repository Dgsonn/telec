<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Quản lý Tin tức';
$db = getDB();
$search = trim($_GET['q'] ?? '');
$where = ['1=1']; $params = [];
if ($search) { $where[] = '(title LIKE ? OR summary LIKE ?)'; $params = ["%$search%", "%$search%"]; }

$stmt = $db->prepare('SELECT id, title, summary, image, is_active, views, created_at FROM news WHERE ' . implode(' AND ', $where) . ' ORDER BY created_at DESC');
$stmt->execute($params);
$news_list = $stmt->fetchAll();

require '../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm tin tức..." value="<?= htmlspecialchars($search) ?>" style="width:220px">
        <button class="btn btn-sm btn-secondary" type="submit"><i class="fas fa-search"></i></button>
        <?php if ($search): ?><a href="index.php" class="btn btn-sm btn-outline-secondary">Xóa lọc</a><?php endif; ?>
    </form>
    <a href="add.php" class="btn btn-orange btn-sm"><i class="fas fa-plus me-1"></i>Viết tin tức</a>
</div>

<div class="data-table">
    <div class="table-header"><span style="font-size:14px;color:#64748b">Tổng: <strong><?= count($news_list) ?></strong> bài viết</span></div>
    <div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead><tr>
            <th style="width:70px">Ảnh</th>
            <th>Tiêu đề</th>
            <th>Tóm tắt</th>
            <th>Lượt xem</th>
            <th>Ngày đăng</th>
            <th>Trạng thái</th>
            <th style="width:120px">Thao tác</th>
        </tr></thead>
        <tbody>
        <?php foreach ($news_list as $n): ?>
        <tr>
            <td><?php if ($n['image']): ?><img src="../<?= htmlspecialchars($n['image']) ?>" class="thumb" alt=""><?php else: ?><div class="thumb d-flex align-items-center justify-content-center bg-light text-muted" style="width:56px;height:40px;border-radius:6px;font-size:18px"><i class="fas fa-image"></i></div><?php endif; ?></td>
            <td><strong style="font-size:14px"><?= htmlspecialchars($n['title']) ?></strong></td>
            <td style="font-size:12px;color:#64748b;max-width:200px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis"><?= htmlspecialchars($n['summary'] ?? '') ?></td>
            <td style="font-size:13px"><i class="fas fa-eye" style="color:#94a3b8"></i> <?= number_format($n['views']) ?></td>
            <td style="font-size:13px;color:#64748b"><?= date('d/m/Y', strtotime($n['created_at'])) ?></td>
            <td><span class="badge <?= $n['is_active'] ? 'badge-active' : 'badge-inactive' ?>"><?= $n['is_active'] ? 'Hiển thị' : 'Ẩn' ?></span></td>
            <td>
                <a href="edit.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-primary" title="Sửa"><i class="fas fa-pen"></i></a>
                <a href="delete.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-danger" title="Xóa" data-confirm="Xóa bài viết '<?= htmlspecialchars($n['title']) ?>'?"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$news_list): ?><tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Chưa có bài viết nào</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
