<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));
// Base path tới thư mục admin/
$admin_base = ($current_dir === 'admin') ? './' : '../';
$root_base  = ($current_dir === 'admin') ? '../' : '../../';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — ' : '' ?>TELEC Admin</title>
<link rel="icon" href="<?= $root_base ?>img/favicon-t.png" type="image/png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
:root {
    --orange: #F97316;
    --orange-dark: #EA580C;
    --sidebar-w: 250px;
}
body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; }

/* Sidebar */
#sidebar {
    position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-w);
    background: #1e293b; color: #cbd5e1; z-index: 1000;
    display: flex; flex-direction: column; transition: transform .3s;
}
.sidebar-brand {
    padding: 20px 20px 16px;
    border-bottom: 1px solid #334155;
    display: flex; align-items: center; gap: 10px;
}
.sidebar-brand img { height: 36px; filter: brightness(10); }
.sidebar-brand span { font-size: 18px; font-weight: 700; color: #fff; letter-spacing: .5px; }
.sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
.nav-section-title {
    padding: 10px 20px 4px; font-size: 10px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 1px; color: #64748b;
}
.sidebar-nav a {
    display: flex; align-items: center; gap: 10px; padding: 10px 20px;
    color: #94a3b8; text-decoration: none; font-size: 14px;
    border-left: 3px solid transparent; transition: all .2s;
}
.sidebar-nav a:hover { color: #fff; background: #334155; }
.sidebar-nav a.active { color: #fff; background: #1d4ed8; border-left-color: var(--orange); }
.sidebar-nav a i { width: 18px; text-align: center; }
.sidebar-footer {
    padding: 16px 20px; border-top: 1px solid #334155; font-size: 13px; color: #64748b;
}
.sidebar-footer a { color: #f87171; text-decoration: none; }
.sidebar-footer a:hover { color: #fca5a5; }

/* Main content */
#main-content { margin-left: var(--sidebar-w); min-height: 100vh; }
.top-bar {
    background: #fff; padding: 14px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 100;
}
.top-bar .page-title { font-size: 18px; font-weight: 600; color: #1e293b; margin: 0; }
.top-bar .admin-badge { font-size: 13px; color: #64748b; }
.top-bar .admin-badge span { color: var(--orange); font-weight: 600; }
.content-area { padding: 24px; }

/* Cards */
.stat-card { background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; }
.stat-card .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-card .stat-num { font-size: 28px; font-weight: 700; color: #1e293b; }
.stat-card .stat-label { font-size: 13px; color: #64748b; }

/* Table */
.data-table { background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
.data-table .table-header { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
.data-table .table { margin: 0; }
.data-table .table th { background: #f8fafc; font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: #64748b; border-top: none; font-weight: 600; }
.data-table .table td { vertical-align: middle; font-size: 14px; }
.data-table img.thumb { width: 56px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0; }

/* Badges */
.badge-active { background: #dcfce7; color: #16a34a; }
.badge-inactive { background: #fef2f2; color: #dc2626; }

/* Form card */
.form-card { background: #fff; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; }
.form-label { font-size: 13px; font-weight: 600; color: #374151; }
.form-control, .form-select { font-size: 14px; border-radius: 8px; border-color: #d1d5db; }
.form-control:focus, .form-select:focus { border-color: var(--orange); box-shadow: 0 0 0 3px rgba(249,115,22,.15); }

/* Buttons */
.btn-orange { background: var(--orange); border-color: var(--orange); color: #fff; }
.btn-orange:hover { background: var(--orange-dark); border-color: var(--orange-dark); color: #fff; }
.btn-sm { font-size: 12px; }

/* Alert */
.cms-alert { border-radius: 8px; font-size: 14px; }

/* Mobile */
@media(max-width:768px) {
    #sidebar { transform: translateX(-100%); }
    #sidebar.open { transform: translateX(0); }
    #main-content { margin-left: 0; }
    .sidebar-toggle { display: flex !important; }
}
.sidebar-toggle { display: none; align-items: center; background: none; border: none; font-size: 20px; color: #1e293b; }
</style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-brand">
        <img src="<?= $root_base ?>img/logo-telec.png" alt="logo">
        <span>TELEC Admin</span>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-title">Tổng quan</div>
        <a href="<?= $admin_base ?>dashboard.php" class="<?= $current_page === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>

        <div class="nav-section-title">Nội dung</div>
        <a href="<?= $admin_base ?>products/index.php" class="<?= $current_dir === 'products' ? 'active' : '' ?>">
            <i class="fas fa-boxes-stacked"></i> Sản phẩm
        </a>
        <a href="<?= $admin_base ?>projects/index.php" class="<?= $current_dir === 'projects' ? 'active' : '' ?>">
            <i class="fas fa-hard-hat"></i> Dự án
        </a>
        <a href="<?= $admin_base ?>news/index.php" class="<?= $current_dir === 'news' ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i> Tin tức
        </a>

        <div class="nav-section-title">Cài đặt</div>
        <a href="<?= $admin_base ?>contact/index.php" class="<?= $current_dir === 'contact' ? 'active' : '' ?>">
            <i class="fas fa-phone"></i> Thông tin liên hệ
        </a>
        <a href="<?= $admin_base ?>change-password.php" class="<?= $current_page === 'change-password' ? 'active' : '' ?>">
            <i class="fas fa-key"></i> Đổi mật khẩu
        </a>
    </nav>
    <div class="sidebar-footer">
        Đăng nhập: <strong style="color:#e2e8f0"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></strong><br>
        <a href="<?= $admin_base ?>logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
    </div>
</div>

<!-- Main -->
<div id="main-content">
    <div class="top-bar">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="page-title"><?= isset($page_title) ? htmlspecialchars($page_title) : 'Dashboard' ?></h5>
        </div>
        <div class="admin-badge"><i class="fas fa-user-shield me-1"></i>Xin chào, <span><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span></div>
    </div>
    <div class="content-area">
<?php if (isset($_SESSION['flash'])): ?>
    <div class="alert cms-alert alert-<?= $_SESSION['flash']['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php unset($_SESSION['flash']); endif; ?>
