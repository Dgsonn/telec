<?php
require_once 'includes/auth.php';
require_once 'config/db.php';
$page_title = 'Dashboard';

$db = getDB();
$counts = [
    'products' => $db->query('SELECT COUNT(*) FROM products WHERE is_active=1')->fetchColumn(),
    'projects' => $db->query('SELECT COUNT(*) FROM projects WHERE is_active=1')->fetchColumn(),
    'news'     => $db->query('SELECT COUNT(*) FROM news WHERE is_active=1')->fetchColumn(),
];
$recent_products = $db->query('SELECT name, category, created_at FROM products ORDER BY created_at DESC LIMIT 5')->fetchAll();
$recent_projects = $db->query('SELECT title, location, created_at FROM projects ORDER BY created_at DESC LIMIT 5')->fetchAll();

$cat_labels = ['inverter' => 'Inverter', 'solar' => 'Năng lượng mặt trời', 'smarthome' => 'Smart Home', 'other' => 'Khác'];
require 'includes/header.php';
?>

<!-- Stat cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fef3c7"><i class="fas fa-boxes-stacked" style="color:#d97706"></i></div>
            <div>
                <div class="stat-num"><?= $counts['products'] ?></div>
                <div class="stat-label">Sản phẩm đang hiển thị</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#dbeafe"><i class="fas fa-hard-hat" style="color:#2563eb"></i></div>
            <div>
                <div class="stat-num"><?= $counts['projects'] ?></div>
                <div class="stat-label">Dự án đang hiển thị</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#dcfce7"><i class="fas fa-newspaper" style="color:#16a34a"></i></div>
            <div>
                <div class="stat-num"><?= $counts['news'] ?></div>
                <div class="stat-label">Tin tức đang hiển thị</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fce7f3"><i class="fas fa-globe" style="color:#db2777"></i></div>
            <div>
                <div class="stat-num">●</div>
                <div class="stat-label" style="color:#16a34a;font-weight:600">Website đang hoạt động</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Recent products -->
    <div class="col-lg-6">
        <div class="data-table">
            <div class="table-header">
                <strong style="font-size:15px">Sản phẩm mới nhất</strong>
                <a href="products/add.php" class="btn btn-orange btn-sm"><i class="fas fa-plus me-1"></i>Thêm</a>
            </div>
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th>Tên sản phẩm</th><th>Danh mục</th><th>Ngày thêm</th>
                </tr></thead>
                <tbody>
                <?php foreach ($recent_products as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><span class="badge bg-secondary"><?= $cat_labels[$p['category']] ?? $p['category'] ?></span></td>
                    <td style="color:#64748b;font-size:13px"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$recent_products): ?>
                <tr><td colspan="3" class="text-center text-muted py-3">Chưa có sản phẩm nào</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="p-2 text-end"><a href="products/index.php" class="text-decoration-none" style="font-size:13px;color:#F97316">Xem tất cả <i class="fas fa-arrow-right"></i></a></div>
        </div>
    </div>

    <!-- Recent projects -->
    <div class="col-lg-6">
        <div class="data-table">
            <div class="table-header">
                <strong style="font-size:15px">Dự án mới nhất</strong>
                <a href="projects/add.php" class="btn btn-orange btn-sm"><i class="fas fa-plus me-1"></i>Thêm</a>
            </div>
            <table class="table table-hover mb-0">
                <thead><tr>
                    <th>Tiêu đề</th><th>Địa điểm</th><th>Ngày</th>
                </tr></thead>
                <tbody>
                <?php foreach ($recent_projects as $pr): ?>
                <tr>
                    <td><?= htmlspecialchars($pr['title']) ?></td>
                    <td style="color:#64748b;font-size:13px"><?= htmlspecialchars($pr['location'] ?? '') ?></td>
                    <td style="color:#64748b;font-size:13px"><?= date('d/m/Y', strtotime($pr['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (!$recent_projects): ?>
                <tr><td colspan="3" class="text-center text-muted py-3">Chưa có dự án nào</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="p-2 text-end"><a href="projects/index.php" class="text-decoration-none" style="font-size:13px;color:#F97316">Xem tất cả <i class="fas fa-arrow-right"></i></a></div>
        </div>
    </div>
</div>

<!-- Quick links -->
<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="form-card">
            <strong style="font-size:14px">Truy cập nhanh</strong>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="products/add.php" class="btn btn-orange btn-sm"><i class="fas fa-plus me-1"></i>Thêm sản phẩm</a>
                <a href="projects/add.php" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Thêm dự án</a>
                <a href="news/add.php" class="btn btn-sm btn-success"><i class="fas fa-plus me-1"></i>Viết tin tức</a>
                <a href="contact/index.php" class="btn btn-sm btn-secondary"><i class="fas fa-phone me-1"></i>Cập nhật liên hệ</a>
                <a href="<?= $root_base ?>index.html" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-external-link-alt me-1"></i>Xem trang web</a>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
