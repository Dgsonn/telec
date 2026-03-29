<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Sửa bài viết';
$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare('SELECT * FROM news WHERE id = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) { $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Bài viết không tồn tại.']; header('Location: index.php'); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['title']     = trim($_POST['title'] ?? '');
    $data['summary']   = trim($_POST['summary'] ?? '');
    $data['content']   = trim($_POST['content'] ?? '');
    $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
    if (!$data['title']) $errors[] = 'Vui lòng nhập tiêu đề.';

    $image_path = $data['image'];
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) { $errors[] = 'File ảnh không hợp lệ.'; }
        elseif ($_FILES['image']['size'] > 5*1024*1024) { $errors[] = 'Ảnh quá lớn.'; }
        else {
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
            $filename = 'news_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename)) {
                if ($data['image'] && file_exists('../' . $data['image'])) @unlink('../' . $data['image']);
                $image_path = 'uploads/' . $filename;
            } else { $errors[] = 'Lỗi upload ảnh.'; }
        }
    }

    if (!$errors) {
        $stmt = $db->prepare('UPDATE news SET title=?,summary=?,content=?,image=?,is_active=?,updated_at=NOW() WHERE id=?');
        $stmt->execute([$data['title'], $data['summary'], $data['content'], $image_path, $data['is_active'], $id]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Cập nhật bài viết thành công!'];
        header('Location: index.php');
        exit;
    }
}
require '../includes/header.php';
?>
<div class="mb-3"><a href="index.php" class="text-decoration-none" style="font-size:14px;color:#64748b"><i class="fas fa-arrow-left me-1"></i>Quay lại</a></div>
<?php if ($errors): ?><div class="alert alert-danger cms-alert"><ul class="mb-0 ps-3"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="row g-3">
    <div class="col-lg-8">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Nội dung bài viết</h6>
            <div class="mb-3"><label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" required></div>
            <div class="mb-3"><label class="form-label">Tóm tắt</label>
                <textarea name="summary" class="form-control" rows="2"><?= htmlspecialchars($data['summary'] ?? '') ?></textarea></div>
            <div class="mb-3"><label class="form-label">Nội dung chi tiết</label>
                <textarea name="content" id="editor" class="form-control" rows="16"><?= htmlspecialchars($data['content'] ?? '') ?></textarea>
                <div class="form-text">Hỗ trợ HTML cơ bản.</div></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Ảnh đại diện</h6>
            <?php if ($data['image']): ?><div class="text-center mb-2"><img src="../<?= htmlspecialchars($data['image']) ?>" style="max-width:100%;max-height:140px;border-radius:8px;border:1px solid #e2e8f0"></div><?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*" id="imgInput">
            <div class="mt-2 text-center" id="imgPreview" style="display:none"><img id="previewImg" src="" style="max-width:100%;max-height:120px;border-radius:8px;"></div>
        </div>
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Xuất bản</h6>
            <div class="mb-2" style="font-size:12px;color:#64748b">Ngày đăng: <?= date('d/m/Y H:i', strtotime($data['created_at'])) ?></div>
            <div class="mb-2" style="font-size:12px;color:#64748b">Lượt xem: <?= number_format($data['views']) ?></div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" <?= $data['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="chkActive" style="font-size:14px">Hiển thị</label>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-orange"><i class="fas fa-save me-2"></i>Lưu thay đổi</button>
            <a href="index.php" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </div>
</div>
</form>
<?php $extra_js = '<script>document.getElementById("imgInput").addEventListener("change",function(){const f=this.files[0];if(f){const r=new FileReader();r.onload=e=>{document.getElementById("previewImg").src=e.target.result;document.getElementById("imgPreview").style.display="block";};r.readAsDataURL(f);}});</script>'; ?>
<?php require '../includes/footer.php'; ?>
