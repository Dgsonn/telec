<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Quản lý Sản phẩm';

$db = getDB();

// Tìm kiếm & lọc
$search = trim($_GET['q'] ?? '');
$filter_cat = $_GET['cat'] ?? '';
$where = ['1=1'];
$params = [];
if ($search) { $where[] = 'name LIKE ?'; $params[] = "%$search%"; }
if ($filter_cat) { $where[] = 'category = ?'; $params[] = $filter_cat; }

$sql = 'SELECT * FROM products WHERE ' . implode(' AND ', $where) . ' ORDER BY sort_order ASC, created_at DESC';
$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$cat_labels = ['inverter' => 'Inverter', 'solar' => 'Năng lượng mặt trời', 'smarthome' => 'Smart Home', 'other' => 'Khác'];
$cat_colors = ['inverter' => 'warning', 'solar' => 'success', 'smarthome' => 'primary', 'other' => 'secondary'];
require '../includes/header.php';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm theo tên..." value="<?= htmlspecialchars($search) ?>" style="width:200px">
        <select name="cat" class="form-select form-select-sm" style="width:180px">
            <option value="">-- Tất cả danh mục --</option>
            <?php foreach ($cat_labels as $k => $v): ?>
            <option value="<?= $k ?>" <?= $filter_cat === $k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-sm btn-secondary" type="submit"><i class="fas fa-search"></i> Lọc</button>
        <?php if ($search || $filter_cat): ?>
        <a href="index.php" class="btn btn-sm btn-outline-secondary">Xóa lọc</a>
        <?php endif; ?>
    </form>
    <a href="add.php" class="btn btn-orange btn-sm"><i class="fas fa-plus me-1"></i>Thêm sản phẩm</a>
</div>

<div class="data-table">
    <div class="table-header">
        <span style="font-size:14px;color:#64748b">Tổng: <strong><?= count($products) ?></strong> sản phẩm</span>
    </div>
    <div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead><tr>
            <th style="width:60px">Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Thương hiệu</th>
            <th>Giá</th>
            <th>Trạng thái</th>
            <th style="width:120px">Thao tác</th>
        </tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td>
                <?php if ($p['image']): ?>
                <img src="../<?= htmlspecialchars($p['image']) ?>" class="thumb" alt="">
                <?php else: ?>
                <div class="thumb d-flex align-items-center justify-content-center bg-light text-muted" style="width:56px;height:40px;border-radius:6px;font-size:18px"><i class="fas fa-image"></i></div>
                <?php endif; ?>
            </td>
            <td>
                <strong style="font-size:14px"><?= htmlspecialchars($p['name']) ?></strong>
                <?php if ($p['description']): ?>
                <div style="font-size:12px;color:#94a3b8;max-width:200px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis"><?= htmlspecialchars($p['description']) ?></div>
                <?php endif; ?>
            </td>
            <td><span class="badge bg-<?= $cat_colors[$p['category']] ?? 'secondary' ?>"><?= $cat_labels[$p['category']] ?? $p['category'] ?></span></td>
            <td style="font-size:13px"><?= htmlspecialchars($p['brand'] ?? '') ?></td>
            <td style="font-size:13px"><?= htmlspecialchars($p['price_label'] ?? '') ?></td>
            <td>
                <span class="badge <?= $p['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                    <?= $p['is_active'] ? 'Hiển thị' : 'Ẩn' ?>
                </span>
            </td>
            <td>
                <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" title="Sửa"><i class="fas fa-pen"></i></a>
                <a href="delete.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" title="Xóa"
                   data-confirm="Bạn có chắc muốn xóa sản phẩm '<?= htmlspecialchars($p['name']) ?>'?"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$products): ?>
        <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>Chưa có sản phẩm nào</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
