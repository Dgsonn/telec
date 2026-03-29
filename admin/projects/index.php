<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Quản lý Dự án';

$db = getDB();
$search = trim($_GET['q'] ?? '');
$filter_cat = $_GET['cat'] ?? '';
$where = ['1=1']; $params = [];
if ($search) { $where[] = '(title LIKE ? OR client_name LIKE ? OR location LIKE ?)'; $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]); }
if ($filter_cat) { $where[] = 'category = ?'; $params[] = $filter_cat; }

$stmt = $db->prepare('SELECT * FROM projects WHERE ' . implode(' AND ', $where) . ' ORDER BY sort_order ASC, created_at DESC');
$stmt->execute($params);
$projects = $stmt->fetchAll();
$cat_labels = ['solar' => 'Điện mặt trời', 'smarthome' => 'Smart Home', 'other' => 'Khác'];

require '../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm dự án..." value="<?= htmlspecialchars($search) ?>" style="width:200px">
        <select name="cat" class="form-select form-select-sm" style="width:180px">
            <option value="">-- Tất cả loại --</option>
            <?php foreach ($cat_labels as $k => $v): ?>
            <option value="<?= $k ?>" <?= $filter_cat === $k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-sm btn-secondary" type="submit"><i class="fas fa-search"></i> Lọc</button>
        <?php if ($search || $filter_cat): ?><a href="index.php" class="btn btn-sm btn-outline-secondary">Xóa lọc</a><?php endif; ?>
    </form>
    <a href="add.php" class="btn btn-orange btn-sm"><i class="fas fa-plus me-1"></i>Thêm dự án</a>
</div>

<div class="data-table">
    <div class="table-header">
        <span style="font-size:14px;color:#64748b">Tổng: <strong><?= count($projects) ?></strong> dự án</span>
    </div>
    <div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead><tr>
            <th style="width:60px">Ảnh</th>
            <th>Tiêu đề</th>
            <th>Khách hàng</th>
            <th>Địa điểm</th>
            <th>Loại</th>
            <th>Công suất</th>
            <th>Trạng thái</th>
            <th style="width:120px">Thao tác</th>
        </tr></thead>
        <tbody>
        <?php foreach ($projects as $p): ?>
        <tr>
            <td>
                <?php if ($p['image']): ?>
                <img src="../<?= htmlspecialchars($p['image']) ?>" class="thumb" alt="">
                <?php else: ?>
                <div class="thumb d-flex align-items-center justify-content-center bg-light text-muted" style="width:56px;height:40px;border-radius:6px;font-size:18px"><i class="fas fa-image"></i></div>
                <?php endif; ?>
            </td>
            <td><strong style="font-size:14px"><?= htmlspecialchars($p['title']) ?></strong></td>
            <td style="font-size:13px"><?= htmlspecialchars($p['client_name'] ?? '') ?></td>
            <td style="font-size:13px;color:#64748b"><?= htmlspecialchars($p['location'] ?? '') ?></td>
            <td><span class="badge bg-<?= $p['category'] === 'solar' ? 'success' : ($p['category'] === 'smarthome' ? 'primary' : 'secondary') ?>"><?= $cat_labels[$p['category']] ?? $p['category'] ?></span></td>
            <td style="font-size:13px"><?= htmlspecialchars($p['capacity'] ?? '') ?></td>
            <td><span class="badge <?= $p['is_active'] ? 'badge-active' : 'badge-inactive' ?>"><?= $p['is_active'] ? 'Hiển thị' : 'Ẩn' ?></span></td>
            <td>
                <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" title="Sửa"><i class="fas fa-pen"></i></a>
                <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" title="Xóa"
                   data-confirm="Xóa dự án '<?= htmlspecialchars($p['title']) ?>'?"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$projects): ?><tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Chưa có dự án nào</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
