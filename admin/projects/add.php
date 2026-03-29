<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Thêm dự án';

$errors = [];
$data = ['title' => '', 'client_name' => '', 'location' => '', 'category' => 'solar', 'description' => '', 'capacity' => '', 'project_date' => '', 'link_detail' => '', 'is_active' => 1, 'sort_order' => 0];
$cat_labels = ['solar' => 'Điện mặt trời', 'smarthome' => 'Smart Home', 'other' => 'Khác'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $k => $v) {
        $data[$k] = is_int($v) ? (int)($_POST[$k] ?? $v) : trim($_POST[$k] ?? '');
    }
    $data['is_active']  = isset($_POST['is_active']) ? 1 : 0;
    $data['sort_order'] = (int)($_POST['sort_order'] ?? 0);
    if (!$data['title']) $errors[] = 'Vui lòng nhập tiêu đề dự án.';

    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) { $errors[] = 'File ảnh không hợp lệ.'; }
        elseif ($_FILES['image']['size'] > 8 * 1024 * 1024) { $errors[] = 'Ảnh quá lớn (tối đa 8MB).'; }
        else {
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
            $filename = 'project_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename)) {
                $image_path = 'uploads/' . $filename;
            } else { $errors[] = 'Lỗi upload ảnh.'; }
        }
    }

    if (!$errors) {
        try {
            $db = getDB();
            $stmt = $db->prepare('INSERT INTO projects (title, client_name, location, category, description, content, image, capacity, project_date, link_detail, is_active, sort_order)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)');
            $project_date = $data['project_date'] ?: null;
            $content = trim($_POST['content'] ?? '');
            $stmt->execute([$data['title'], $data['client_name'], $data['location'], $data['category'], $data['description'], $content, $image_path, $data['capacity'], $project_date, $data['link_detail'], $data['is_active'], $data['sort_order']]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Thêm dự án thành công!'];
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
<div class="alert alert-danger cms-alert"><ul class="mb-0 ps-3"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="row g-3">
    <div class="col-lg-8">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Thông tin dự án</h6>
            <div class="mb-3">
                <label class="form-label">Tiêu đề dự án <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" required placeholder="VD: Mr Thắng – Hệ thống 30kW Hybrid">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label class="form-label">Khách hàng</label>
                    <input type="text" name="client_name" class="form-control" value="<?= htmlspecialchars($data['client_name']) ?>" placeholder="VD: Mr Thắng">
                </div>
                <div class="col-sm-6">
                    <label class="form-label">Địa điểm</label>
                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($data['location']) ?>" placeholder="VD: Ninh Bình">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-sm-4">
                    <label class="form-label">Loại dự án</label>
                    <select name="category" class="form-select">
                        <?php foreach ($cat_labels as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $data['category'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Công suất</label>
                    <input type="text" name="capacity" class="form-control" value="<?= htmlspecialchars($data['capacity']) ?>" placeholder="VD: 30kW Hybrid">
                </div>
                <div class="col-sm-4">
                    <label class="form-label">Ngày thi công</label>
                    <input type="date" name="project_date" class="form-control" value="<?= htmlspecialchars($data['project_date']) ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả ngắn</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Mô tả tóm tắt dự án..."><?= htmlspecialchars($data['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Nội dung chi tiết <small class="text-muted">(HTML – hiển thị trong trang du-an.php)</small></label>
                <textarea name="content" class="form-control" rows="10" placeholder="Dán nội dung HTML hoặc viết mô tả chi tiết dự án..."></textarea>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Ảnh đại diện dự án</h6>
            <input type="file" name="image" class="form-control" accept="image/*" id="imgInput">
            <div class="mt-2 text-center" id="imgPreview" style="display:none"><img id="previewImg" src="" style="max-width:100%;max-height:160px;border-radius:8px;"></div>
            <div class="form-text">Tối đa 8MB.</div>
        </div>
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Cài đặt</h6>
            <div class="mb-3">
                <label class="form-label">Link trang chi tiết</label>
                <input type="text" name="link_detail" class="form-control" value="<?= htmlspecialchars($data['link_detail']) ?>" placeholder="project/solar_mrthang.html">
            </div>
            <div class="mb-3">
                <label class="form-label">Thứ tự</label>
                <input type="number" name="sort_order" class="form-control" value="<?= $data['sort_order'] ?>" min="0">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" <?= $data['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="chkActive" style="font-size:14px">Hiển thị trên website</label>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-orange"><i class="fas fa-save me-2"></i>Lưu dự án</button>
            <a href="index.php" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </div>
</div>
</form>
<?php $extra_js = '<script>document.getElementById("imgInput").addEventListener("change",function(){const f=this.files[0];if(f){const r=new FileReader();r.onload=e=>{document.getElementById("previewImg").src=e.target.result;document.getElementById("imgPreview").style.display="block";};r.readAsDataURL(f);}});</script>'; ?>
<?php require '../includes/footer.php'; ?>
