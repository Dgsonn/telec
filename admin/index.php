<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}
require_once 'config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $db  = getDB();
        $stmt = $db->prepare('SELECT id, username, password, full_name FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_name'] = $user['full_name'] ?: $user['username'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng nhập — TELEC Admin</title>
<link rel="icon" href="../img/favicon-t.png" type="image/png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
body {
    min-height: 100vh; background: #1e293b;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Segoe UI', sans-serif;
}
.login-card {
    background: #fff; border-radius: 16px; padding: 40px;
    width: 100%; max-width: 400px; box-shadow: 0 20px 60px rgba(0,0,0,.4);
}
.login-logo { text-align: center; margin-bottom: 28px; }
.login-logo img { height: 56px; }
.login-logo h4 { margin-top: 12px; font-weight: 700; color: #1e293b; font-size: 20px; }
.login-logo p { color: #64748b; font-size: 13px; margin: 0; }
.form-control { border-radius: 8px; padding: 10px 14px; border-color: #e2e8f0; font-size: 14px; }
.form-control:focus { border-color: #F97316; box-shadow: 0 0 0 3px rgba(249,115,22,.15); }
.btn-login { background: #F97316; border: none; color: #fff; border-radius: 8px; padding: 11px; font-weight: 600; font-size: 15px; width: 100%; }
.btn-login:hover { background: #EA580C; }
.input-group-text { background: #f8fafc; border-color: #e2e8f0; color: #64748b; }
</style>
</head>
<body>
<div class="login-card">
    <div class="login-logo">
        <img src="../img/logo-telec.png" alt="TELEC">
        <h4>Quản trị hệ thống</h4>
        <p>Đăng nhập để quản lý website TELEC</p>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-danger py-2 mb-3" style="font-size:14px;border-radius:8px;">
        <i class="fas fa-circle-exclamation me-1"></i><?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label class="form-label" style="font-size:13px;font-weight:600;color:#374151;">Tên đăng nhập</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="admin" required autofocus
                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label" style="font-size:13px;font-weight:600;color:#374151;">Mật khẩu</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
        </button>
    </form>
    <p class="text-center mt-3 mb-0" style="font-size:12px;color:#94a3b8;">
        © <?= date('Y') ?> TELEC &mdash; Hệ thống quản trị nội bộ
    </p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
