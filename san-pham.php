<?php
require_once 'admin/config/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.html'); exit; }

$db = getDB();
$stmt = $db->prepare('SELECT * FROM products WHERE id = ? AND is_active = 1');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { header('Location: index.html'); exit; }

// Sản phẩm liên quan
$related = $db->prepare('SELECT id, name, image, brand, price_label FROM products WHERE is_active = 1 AND id != ? AND category = ? ORDER BY sort_order ASC, created_at DESC LIMIT 4');
$related->execute([$id, $product['category']]);
$related_products = $related->fetchAll();

$cat_labels = ['inverter' => 'Inverter / Biến tần', 'solar' => 'Năng lượng mặt trời', 'smarthome' => 'Smart Home', 'other' => 'Khác'];
$cat_pages  = ['inverter' => 'inverter.html', 'solar' => 'solar-lithium.html', 'smarthome' => 'smarthome.html', 'other' => 'index.html'];

// Parse specs thành mảng key-value
$specs_rows = [];
if ($product['specs']) {
    foreach (explode("\n", $product['specs']) as $line) {
        $line = trim($line);
        if (!$line) continue;
        if (strpos($line, ':') !== false) {
            [$k, $v] = explode(':', $line, 2);
            $specs_rows[] = [trim($k), trim($v)];
        } else {
            $specs_rows[] = ['', $line];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($product['name']) ?> | TELEC Energy</title>
<link rel="icon" type="image/png" href="./img/favicon-t.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Raleway:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
.sp-wrap { max-width: 1100px; margin: 0 auto; padding: 40px 24px 60px; }
.sp-back { display: inline-flex; align-items: center; gap: 8px; color: #F97316; font-size: 14px; font-weight: 600; text-decoration: none; margin-bottom: 24px; }
.sp-back:hover { color: #EA580C; }
.sp-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start; }
@media(max-width:768px){ .sp-layout { grid-template-columns: 1fr; } }
.sp-img-box { border-radius: 16px; overflow: hidden; background: #f8fafc; border: 1px solid #e2e8f0; text-align: center; padding: 20px; }
.sp-img-box img { max-width: 100%; max-height: 380px; object-fit: contain; }
.sp-img-placeholder { height: 300px; background: linear-gradient(135deg,#f97316,#1e293b); display: flex; align-items: center; justify-content: center; border-radius: 12px; }
.sp-img-placeholder i { font-size: 80px; color: rgba(255,255,255,.3); }
.sp-info { }
.sp-badge { display: inline-block; background: #fff7ed; color: #ea580c; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 20px; margin-bottom: 12px; border: 1px solid #fed7aa; }
.sp-brand { font-size: 13px; color: #64748b; margin-bottom: 8px; }
.sp-name { font-size: clamp(20px,3vw,30px); font-weight: 800; color: #1e293b; line-height: 1.3; margin-bottom: 16px; }
.sp-price { font-size: 22px; font-weight: 800; color: #F97316; margin-bottom: 20px; }
.sp-desc { font-size: 15px; color: #374151; line-height: 1.8; margin-bottom: 24px; }
.sp-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.btn-contact { display: inline-flex; align-items: center; gap: 8px; background: #F97316; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 15px; }
.btn-contact:hover { background: #EA580C; color: white; }
.btn-zalo { display: inline-flex; align-items: center; gap: 8px; background: #0068FF; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 15px; }
.btn-zalo:hover { background: #0057D9; color: white; }
.sp-specs { margin-top: 40px; }
.sp-specs h3 { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #f97316; }
.specs-table { width: 100%; border-collapse: collapse; }
.specs-table tr:nth-child(even) { background: #f8fafc; }
.specs-table td { padding: 10px 14px; font-size: 14px; color: #374151; border: 1px solid #e2e8f0; }
.specs-table td:first-child { font-weight: 600; color: #1e293b; width: 40%; }
.related-section { max-width: 1100px; margin: 0 auto; padding: 0 24px 60px; border-top: 1px solid #f1f5f9; padding-top: 40px; }
.related-section h3 { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 20px; }
.related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
.related-card { border: 1px solid #f1f5f9; border-radius: 12px; overflow: hidden; text-decoration: none; color: inherit; transition: transform .2s, box-shadow .2s; }
.related-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
.related-card img { width: 100%; height: 140px; object-fit: cover; }
.related-card-no-img { width: 100%; height: 140px; background: linear-gradient(135deg,#f97316,#1e293b); display: flex; align-items: center; justify-content: center; }
.related-card-body { padding: 12px; }
.related-card-body h4 { font-size: 13px; font-weight: 600; color: #1e293b; line-height: 1.4; margin: 0 0 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.related-card-body .price { font-size: 12px; color: #F97316; font-weight: 700; }
</style>
</head>
<body class="not-loaded">

<!-- NAV -->
<nav>
    <a href="index.html" class="nav-logo">
        <img src="./img/logo-telec.png" alt="TELEC Logo" style="height:110px;width:auto;transform:translateY(5px)">
    </a>
    <div class="menu-toggle" id="mobile-menu"><i class="fas fa-bars"></i></div>
    <ul class="nav-links">
        <div class="nav-marker"></div>
        <li class="nav-item"><a href="index.html#about">Giới thiệu</a></li>
        <li class="nav-item">
            <a href="index.html#products">Sản phẩm</a>
            <div class="sub-menu"><ul>
                <li><a href="inverter.html">Hệ thống Inverter</a></li>
                <li><a href="solar-lithium.html">Pin Solar & Lithium</a></li>
                <li><a href="smarthome.html">Giải Pháp Smart Home</a></li>
            </ul></div>
        </li>
        <li class="nav-item"><a href="projects.html">Dự án</a></li>
        <li class="nav-item"><a href="news.html">Tin tức</a></li>
        <li class="nav-item"><a href="index.html#contact">Liên hệ</a></li>
    </ul>
</nav>

<div style="padding-top:130px"></div>

<div class="sp-wrap">
    <a href="<?= $cat_pages[$product['category']] ?? 'index.html' ?>" class="sp-back">
        <i class="fas fa-arrow-left"></i> Quay lại <?= htmlspecialchars($cat_labels[$product['category']] ?? 'Sản phẩm') ?>
    </a>

    <div class="sp-layout">
        <!-- ẢNH -->
        <div class="sp-img-box">
            <?php if ($product['image']): ?>
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            <?php else: ?>
            <div class="sp-img-placeholder"><i class="fas fa-box-open"></i></div>
            <?php endif; ?>
        </div>

        <!-- THÔNG TIN -->
        <div class="sp-info">
            <div class="sp-badge"><?= htmlspecialchars($cat_labels[$product['category']] ?? $product['category']) ?></div>
            <?php if ($product['brand']): ?>
            <div class="sp-brand"><i class="fas fa-tag"></i> <?= htmlspecialchars($product['brand']) ?></div>
            <?php endif; ?>
            <h1 class="sp-name"><?= htmlspecialchars($product['name']) ?></h1>
            <div class="sp-price"><?= htmlspecialchars($product['price_label'] ?: 'Liên hệ để báo giá') ?></div>
            <?php if ($product['description']): ?>
            <div class="sp-desc"><?= nl2br(htmlspecialchars($product['description'])) ?></div>
            <?php endif; ?>
            <div class="sp-actions">
                <a href="tel:0946233297" class="btn-contact"><i class="fas fa-phone-alt"></i> Gọi tư vấn</a>
                <a href="#" data-contact="zalo" class="btn-zalo"><i class="fab fa-telegram"></i> Zalo</a>
            </div>
        </div>
    </div>

    <!-- THÔNG SỐ KỸ THUẬT -->
    <?php if ($specs_rows): ?>
    <div class="sp-specs">
        <h3><i class="fas fa-list-alt me-2"></i>Thông số kỹ thuật</h3>
        <table class="specs-table">
            <?php foreach ($specs_rows as [$k, $v]): ?>
            <tr>
                <td><?= htmlspecialchars($k) ?></td>
                <td><?= htmlspecialchars($v) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- SẢN PHẨM LIÊN QUAN -->
<?php if ($related_products): ?>
<div class="related-section">
    <h3>Sản phẩm liên quan</h3>
    <div class="related-grid">
        <?php foreach ($related_products as $r): ?>
        <a href="san-pham.php?id=<?= $r['id'] ?>" class="related-card">
            <?php if ($r['image']): ?>
            <img src="<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['name']) ?>">
            <?php else: ?>
            <div class="related-card-no-img"><i class="fas fa-box-open" style="font-size:32px;color:rgba(255,255,255,.4)"></i></div>
            <?php endif; ?>
            <div class="related-card-body">
                <h4><?= htmlspecialchars($r['name']) ?></h4>
                <span class="price"><?= htmlspecialchars($r['price_label'] ?: 'Liên hệ') ?></span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<footer id="contact" class="main-footer" style="margin-top:0">
    <div class="footer-bottom">
        <p>© <?= date('Y') ?> T-ELEC Energy. All rights reserved. | <a href="https://telec.com.vn/">Better Electricity - Better Life</a></p>
    </div>
</footer>

<script src="script.js"></script>
<script src="contact-loader.js"></script>
</body>
</html>
