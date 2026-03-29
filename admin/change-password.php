<?php
require_once 'includes/auth.php';
require_once 'config/db.php';
$page_title = 'Đổi mật khẩu';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current  = $_POST['current_password'] ?? '';
    $new      = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    $db = getDB();
    $stmt = $db->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $user = $stmt->fetch();

    if (!password_verify($current, $user['password'])) {
        $errors[] = 'Mật khẩu hiện tại không đúng.';
    }
    if (strlen($new) < 6) {
        $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    }
    if ($new !== $confirm) {
        $errors[] = 'Xác nhận mật khẩu không khớp.';
    }

    if (!$errors) {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $db->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hashed, $_SESSION['admin_id']]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Đổi mật khẩu thành công!'];
        header('Location: dashboard.php');
        exit;
    }
}

require 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="form-card">
            <h6 class="mb-4" style="color:#1e293b;font-weight:700"><i class="fas fa-key me-2" style="color:var(--orange)"></i>Đổi mật khẩu đăng nhập</h6>

            <?php if ($errors): ?>
            <div class="alert alert-danger cms-alert"><ul class="mb-0 ps-3"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="new_password" class="form-control" required minlength="6">
                    <div class="form-text">Tối thiểu 6 ký tự.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-orange w-100"><i class="fas fa-save me-2"></i>Đổi mật khẩu</button>
            </form>

            <div class="mt-3 p-3" style="background:#f0fdf4;border-radius:8px;border-left:3px solid #16a34a;">
                <p class="mb-1" style="font-size:13px;font-weight:600;color:#15803d"><i class="fas fa-shield-alt me-1"></i>Bảo mật tài khoản</p>
                <ul class="mb-0 ps-3" style="font-size:12px;color:#15803d">
                    <li>Nên dùng mật khẩu có chữ hoa, chữ thường, số và ký tự đặc biệt.</li>
                    <li>Không chia sẻ mật khẩu với người khác.</li>
                    <li>Đổi mật khẩu định kỳ 3-6 tháng/lần.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
