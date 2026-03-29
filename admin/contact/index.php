<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Thông tin liên hệ';
$db = getDB();

$contacts = $db->query('SELECT * FROM contact_info ORDER BY id ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('UPDATE contact_info SET value = ? WHERE key_name = ?');
    foreach ($_POST['contact'] as $key => $val) {
        // Sanitize key_name để tránh injection
        if (preg_match('/^[a-z_]+$/', $key)) {
            $stmt->execute([trim($val), $key]);
        }
    }
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Cập nhật thông tin liên hệ thành công!'];
    header('Location: index.php');
    exit;
}

require '../includes/header.php';
?>

<div class="row">
    <div class="col-lg-7">
        <div class="form-card">
            <h6 class="mb-4" style="color:#1e293b;font-weight:700">
                <i class="fas fa-address-card me-2" style="color:var(--orange)"></i>Cập nhật thông tin liên hệ
            </h6>
            <form method="POST">
                <?php foreach ($contacts as $c): ?>
                <div class="mb-4">
                    <label class="form-label">
                        <?= htmlspecialchars($c['label']) ?>
                        <?php if (str_contains($c['key_name'], 'url')): ?>
                        <span class="badge bg-secondary ms-1" style="font-size:10px">URL / Link</span>
                        <?php elseif (str_contains($c['key_name'], 'phone')): ?>
                        <span class="badge" style="background:#fef3c7;color:#92400e;font-size:10px">Số điện thoại</span>
                        <?php endif; ?>
                    </label>
                    <?php if ($c['key_name'] === 'address'): ?>
                    <textarea name="contact[<?= htmlspecialchars($c['key_name']) ?>]" class="form-control" rows="2"><?= htmlspecialchars($c['value']) ?></textarea>
                    <?php else: ?>
                    <input type="text" name="contact[<?= htmlspecialchars($c['key_name']) ?>]" class="form-control"
                        value="<?= htmlspecialchars($c['value']) ?>"
                        placeholder="<?= str_contains($c['key_name'], 'url') ? 'https://...' : '' ?>">
                    <?php endif; ?>
                    <?php if ($c['updated_at']): ?>
                    <div class="form-text">Cập nhật lần cuối: <?= date('d/m/Y H:i', strtotime($c['updated_at'])) ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-orange"><i class="fas fa-save me-2"></i>Lưu thay đổi</button>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="form-card">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">
                <i class="fas fa-eye me-2" style="color:#3b82f6"></i>Xem trước trên website
            </h6>
            <?php
            $info = [];
            foreach ($contacts as $c) $info[$c['key_name']] = $c['value'];
            ?>
            <div style="background:#f8fafc;border-radius:10px;padding:16px;font-size:14px;">
                <div class="mb-2 d-flex align-items-center gap-2">
                    <i class="fas fa-phone" style="color:var(--orange);width:18px"></i>
                    <span><?= htmlspecialchars($info['phone_main'] ?? '') ?></span>
                </div>
                <div class="mb-2 d-flex align-items-center gap-2">
                    <i class="fab fa-facebook" style="color:#1877f2;width:18px"></i>
                    <?php if (!empty($info['facebook_url'])): ?>
                    <a href="<?= htmlspecialchars($info['facebook_url']) ?>" target="_blank" style="color:#1877f2"><?= htmlspecialchars($info['facebook_url']) ?></a>
                    <?php else: ?><span class="text-muted">Chưa có</span><?php endif; ?>
                </div>
                <div class="mb-2 d-flex align-items-center gap-2">
                    <i class="fas fa-comment-dots" style="color:#0068ff;width:18px"></i>
                    <?php if (!empty($info['zalo_url'])): ?>
                    <a href="<?= htmlspecialchars($info['zalo_url']) ?>" target="_blank" style="color:#0068ff"><?= htmlspecialchars($info['zalo_url']) ?></a>
                    <?php else: ?><span class="text-muted">Chưa có</span><?php endif; ?>
                </div>
                <?php if (!empty($info['youtube_url'])): ?>
                <div class="mb-2 d-flex align-items-center gap-2">
                    <i class="fab fa-youtube" style="color:#ff0000;width:18px"></i>
                    <a href="<?= htmlspecialchars($info['youtube_url']) ?>" target="_blank" style="color:#ff0000"><?= htmlspecialchars($info['youtube_url']) ?></a>
                </div>
                <?php endif; ?>
                <div class="mb-2 d-flex align-items-center gap-2">
                    <i class="fas fa-envelope" style="color:#64748b;width:18px"></i>
                    <span><?= htmlspecialchars($info['email'] ?? '') ?></span>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <i class="fas fa-map-marker-alt" style="color:#ef4444;width:18px;margin-top:2px"></i>
                    <span><?= htmlspecialchars($info['address'] ?? '') ?></span>
                </div>
            </div>

            <div class="mt-3 p-3" style="background:#fff7ed;border-radius:8px;border-left:3px solid var(--orange)">
                <p class="mb-1" style="font-size:13px;font-weight:600;color:#92400e"><i class="fas fa-lightbulb me-1"></i>Ghi chú</p>
                <ul class="mb-0 ps-3" style="font-size:12px;color:#92400e">
                    <li>Thay đổi số điện thoại sẽ cập nhật nút gọi điện trên website.</li>
                    <li>Link Facebook/Zalo nên dùng đường link đầy đủ (https://...).</li>
                    <li>Sau khi lưu, nội dung cập nhật ngay lập tức.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
