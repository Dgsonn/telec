<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Sửa sản phẩm';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$row = $db->prepare('SELECT * FROM products WHERE id = ?');
$row->execute([$id]);
$data = $row->fetch();
if (!$data) { $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Sản phẩm không tồn tại.']; header('Location: index.php'); exit; }

$cat_labels = ['inverter' => 'Inverter / Biến tần', 'solar' => 'Năng lượng mặt trời', 'smarthome' => 'Smart Home', 'other' => 'Khác'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name']        = trim($_POST['name'] ?? '');
    $data['category']    = $_POST['category'] ?? 'other';
    $data['brand']       = trim($_POST['brand'] ?? '');
    $data['description'] = trim($_POST['description'] ?? '');
    $data['specs']       = trim($_POST['specs'] ?? '');
    $data['price_label'] = trim($_POST['price_label'] ?? '');
    $data['link_detail'] = trim($_POST['link_detail'] ?? '');
    $data['is_active']   = isset($_POST['is_active']) ? 1 : 0;
    $data['sort_order']  = (int)($_POST['sort_order'] ?? 0);

    if (!$data['name']) $errors[] = 'Vui lòng nhập tên sản phẩm.';

    $image_path = $data['image'];
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','webp','gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'File ảnh không hợp lệ.';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $errors[] = 'File ảnh quá lớn (tối đa 5MB).';
        } else {
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename)) {
                // Xóa ảnh cũ
                if ($data['image'] && file_exists('../' . $data['image'])) @unlink('../' . $data['image']);
                $image_path = 'uploads/' . $filename;
            } else { $errors[] = 'Lỗi upload ảnh.'; }
        }
    }

    if (!$errors) {
        $stmt = $db->prepare('UPDATE products SET name=?,category=?,brand=?,description=?,specs=?,image=?,price_label=?,link_detail=?,is_active=?,sort_order=?,updated_at=NOW() WHERE id=?');
        $stmt->execute([$data['name'], $data['category'], $data['brand'], $data['description'], $data['specs'], $image_path, $data['price_label'], $data['link_detail'], $data['is_active'], $data['sort_order'], $id]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Cập nhật sản phẩm thành công!'];
        header('Location: index.php');
        exit;
    }
}

require '../includes/header.php';
?>

<div class="mb-3"><a href="index.php" class="text-decoration-none" style="font-size:14px;color:#64748b"><i class="fas fa-arrow-left me-1"></i>Quay lại danh sách</a></div>

<?php if ($errors): ?>
<div class="alert alert-danger cms-alert">
    <ul class="mb-0 ps-3"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="row g-3">
    <div class="col-lg-8">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Thông tin sản phẩm</h6>
            <div class="mb-3">
                <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Danh mục</label>
                    <select name="category" class="form-select">
                        <?php foreach ($cat_labels as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $data['category'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Thương hiệu</label>
                    <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($data['brand'] ?? '') ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả ngắn</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Thông số kỹ thuật</label>
                <textarea name="specs" class="form-control" rows="5"><?= htmlspecialchars($data['specs'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Ảnh sản phẩm</h6>
            <?php if ($data['image']): ?>
            <div class="text-center mb-2">
                <img src="../<?= htmlspecialchars($data['image']) ?>" alt="" style="max-width:100%;max-height:140px;border-radius:8px;border:1px solid #e2e8f0">
                <div style="font-size:12px;color:#94a3b8;margin-top:4px">Ảnh hiện tại</div>
            </div>
            <?php endif; ?>
            <label class="form-label">Thay ảnh mới (tùy chọn)</label>
            <input type="file" name="image" class="form-control" accept="image/*" id="imgInput">
            <div class="mt-2 text-center" id="imgPreview" style="display:none">
                <img id="previewImg" src="" alt="" style="max-width:100%;max-height:120px;border-radius:8px;">
            </div>
        </div>

        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Thông tin bổ sung</h6>
            <div class="mb-3">
                <label class="form-label">Giá / Liên hệ</label>
                <input type="text" name="price_label" class="form-control" value="<?= htmlspecialchars($data['price_label'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Link chi tiết</label>
                <input type="text" name="link_detail" class="form-control" value="<?= htmlspecialchars($data['link_detail'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="form-control" value="<?= $data['sort_order'] ?>" min="0">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" <?= $data['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="chkActive" style="font-size:14px">Hiển thị trên website</label>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-orange"><i class="fas fa-save me-2"></i>Lưu thay đổi</button>
            <a href="index.php" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </div>
</div>
</form>

<?php $extra_js = '<script>
document.getElementById("imgInput").addEventListener("change", function(){
    const file = this.files[0];
    if(file){ const r = new FileReader(); r.onload = e => { document.getElementById("previewImg").src = e.target.result; document.getElementById("imgPreview").style.display="block"; }; r.readAsDataURL(file); }
});
</script>'; ?>
<?php require '../includes/footer.php'; ?>
