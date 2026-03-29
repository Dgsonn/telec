<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$page_title = 'Thêm tin tức';

$errors = [];
$data = ['title' => '', 'summary' => '', 'content' => '', 'is_active' => 1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['title']     = trim($_POST['title'] ?? '');
    $data['summary']   = trim($_POST['summary'] ?? '');
    $data['content']   = trim($_POST['content'] ?? '');
    $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
    if (!$data['title']) $errors[] = 'Vui lòng nhập tiêu đề bài viết.';

    // Tạo slug đơn giản từ timestamp (tránh lỗi iconv trên hosting)
    $slug = 'tin-tuc-' . time() . '-' . rand(1000, 9999);

    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) { $errors[] = 'File ảnh không hợp lệ.'; }
        elseif ($_FILES['image']['size'] > 5*1024*1024) { $errors[] = 'Ảnh quá lớn (tối đa 5MB).'; }
        else {
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
            $filename = 'news_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename)) {
                $image_path = 'uploads/' . $filename;
            } else { $errors[] = 'Lỗi upload ảnh.'; }
        }
    }

    if (!$errors) {
        $db = getDB();
        $stmt = $db->prepare('INSERT INTO news (title, slug, summary, content, image, is_active) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$data['title'], $slug, $data['summary'], $data['content'], $image_path, $data['is_active']]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Thêm bài viết thành công!'];
        header('Location: index.php');
        exit;
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
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Nội dung bài viết</h6>
            <div class="mb-3">
                <label class="form-label">Tiêu đề bài viết <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($data['title']) ?>" required placeholder="VD: Xu hướng năng lượng mặt trời 2025">
            </div>
            <div class="mb-3">
                <label class="form-label">Tóm tắt (hiển thị ngoài danh sách)</label>
                <textarea name="summary" class="form-control" rows="2" placeholder="Vài câu ngắn tóm tắt nội dung bài viết..."><?= htmlspecialchars($data['summary']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Nội dung chi tiết</label>
                <textarea name="content" id="editor" class="form-control" rows="16" placeholder="Nhập nội dung bài viết đầy đủ tại đây..."><?= htmlspecialchars($data['content']) ?></textarea>
                <div class="form-text">Hỗ trợ HTML cơ bản: &lt;b&gt;, &lt;i&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;h3&gt;, &lt;a&gt;, &lt;img&gt;, v.v.</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Ảnh đại diện</h6>
            <input type="file" name="image" class="form-control" accept="image/*" id="imgInput">
            <div class="mt-2 text-center" id="imgPreview" style="display:none"><img id="previewImg" src="" style="max-width:100%;max-height:160px;border-radius:8px;"></div>
            <div class="form-text">Khuyến nghị tỉ lệ 16:9. Tối đa 5MB.</div>
        </div>
        <div class="form-card mb-3">
            <h6 class="mb-3" style="color:#1e293b;font-weight:700">Xuất bản</h6>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="chkActive" value="1" <?= $data['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="chkActive" style="font-size:14px">Hiển thị ngay sau khi lưu</label>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-orange"><i class="fas fa-save me-2"></i>Đăng bài viết</button>
            <a href="index.php" class="btn btn-outline-secondary">Hủy</a>
        </div>
    </div>
</div>
</form>
<?php $extra_js = '<script>document.getElementById("imgInput").addEventListener("change",function(){const f=this.files[0];if(f){const r=new FileReader();r.onload=e=>{document.getElementById("previewImg").src=e.target.result;document.getElementById("imgPreview").style.display="block";};r.readAsDataURL(f);}});</script>'; ?>
<?php require '../includes/footer.php'; ?>
