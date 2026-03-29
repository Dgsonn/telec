<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Thêm sản phẩm';

$errors = [];
$data = ['name' => '', 'category' => 'inverter', 'brand' => '', 'description' => '', 'specs' => '', 'price_label' => '', 'link_detail' => '', 'is_active' => 1, 'sort_order' => 0];
$cat_labels = ['inverter' => 'Inverter / Biến tần', 'solar' => 'Năng lượng mặt trời', 'smarthome' => 'Smart Home', 'other' => 'Khác'];

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

    // Upload ảnh
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','webp','gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'File ảnh không hợp lệ (chỉ chấp nhận jpg, png, webp, gif).';
        } elseif ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $errors[] = 'File ảnh quá lớn (tối đa 5MB).';
        } else {
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
            $filename   = 'product_' . time() . '_' . uniqid() . '.' . $ext;
            $dest       = UPLOAD_DIR . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image_path = 'uploads/' . $filename;
            } else {
                $errors[] = 'Lỗi upload ảnh, vui lòng thử lại.';
            }
        }
    }

    if (!$errors) {
        try {
            $db = getDB();
            $stmt = $db->prepare('INSERT INTO products (name, category, brand, description, specs, image, price_label, link_detail, is_active, sort_order)
                VALUES (?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$data['name'], $data['category'], $data['brand'], $data['description'], $data['specs'], $image_path, $data['price_label'], $data['link_detail'], $data['is_active'], $data['sort_order']]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Thêm sản phẩm thành công!'];
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Lỗi database: ' . $e->getMessage();
        }
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
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required placeholder="VD: Inverter SUNGROW SG10RT">
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
                    <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($data['brand']) ?>" placeholder="VD: SUNGROW">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả ngắn</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Mô tả tóm tắt về sản phẩm..."><?= htmlspecialchars($data['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Thông số kỹ thuật</label>
                <textarea name="specs" class="form-control" rows="5" placeholder="Công suất: 10kW&#10;Hiệu suất: 98.4%&#10;Điện áp vào: 200-1000V..."><?= htmlspecialchars($data['specs']) ?></textarea>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Ảnh sản phẩm</h6>
            <input type="file" name="image" class="form-control" accept="image/*" id="imgInput">
            <div class="mt-2 text-center" id="imgPreview" style="display:none">
                <img id="previewImg" src="" alt="" style="max-width:100%;max-height:160px;border-radius:8px;border:1px solid #e2e8f0">
            </div>
            <div class="form-text">Định dạng: JPG, PNG, WebP. Tối đa 5MB.</div>
        </div>

        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Thông tin bổ sung</h6>
            <div class="mb-3">
                <label class="form-label">Giá / Liên hệ</label>
                <input type="text" name="price_label" class="form-control" value="<?= htmlspecialchars($data['price_label']) ?>" placeholder="VD: Liên hệ để báo giá">
            </div>
            <div class="mb-3">
                <label class="form-label">Link chi tiết (tùy chọn)</label>
                <input type="text" name="link_detail" class="form-control" value="<?= htmlspecialchars($data['link_detail']) ?>" placeholder="inverter.html">
            </div>
            <div class="mb-3">
                <label class="form-label">Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="form-control" value="<?= $data['sort_order'] ?>" min="0">
                <div class="form-text">Số nhỏ hơn hiển thị trước.</div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" <?= $data['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="chkActive" style="font-size:14px">Hiển thị trên website</label>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-orange"><i class="fas fa-save me-2"></i>Lưu sản phẩm</button>
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
