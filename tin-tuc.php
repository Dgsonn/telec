<?php
require_once 'admin/config/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: news.html'); exit; }

$db = getDB();
$stmt = $db->prepare('SELECT * FROM news WHERE id = ? AND is_active = 1');
$stmt->execute([$id]);
$article = $stmt->fetch();
if (!$article) { header('Location: news.html'); exit; }

$db->prepare('UPDATE news SET views = views + 1 WHERE id = ?')->execute([$id]);

// Related news
$related = $db->prepare('SELECT id, title, image, summary, created_at FROM news WHERE is_active = 1 AND id != ? ORDER BY created_at DESC LIMIT 3');
$related->execute([$id]);
$related_news = $related->fetchAll();

// Reading time (estimate)
$read_time = max(1, ceil(mb_strlen(strip_tags($article['content'] ?? '')) / 500));

// Share URLs
$current_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$fb_share = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($current_url);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($article['title']) ?> | TELEC Energy</title>
<meta name="description" content="<?= htmlspecialchars(mb_substr(strip_tags($article['summary'] ?? $article['content'] ?? ''), 0, 160)) ?>">
<link rel="icon" type="image/png" href="./img/favicon-t.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Raleway:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
/* ==========================================
   RESET & BASE
   ========================================== */
:root { --orange: #F97316; --blue: #1e293b; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #374151; line-height: 1.7; overflow-x: hidden; }

/* ==========================================
   NAV
   ========================================== */
nav { position: fixed; top: 0; left: 0; width: 100%; padding: 10px 6%; display: flex; justify-content: space-between; align-items: center; z-index: 9999; background: white; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
.nav-logo img { height: 90px; width: auto; transform: translateY(4px); }
.nav-links { display: flex; align-items: center; list-style: none; gap: 8px; position: relative; padding: 8px 20px; }
.nav-marker { position: absolute; height: 34px; width: 0; top: 50%; transform: translateY(-50%); left: 0; background: #1e293b; border-radius: 20px; z-index: 1; opacity: 0; transition: all .4s cubic-bezier(.23,1,.32,1); }
.nav-item > a { display: flex; align-items: center; text-decoration: none; color: #1e293b; font-family: 'Raleway', sans-serif; font-weight: 700; font-size: 13px; text-transform: uppercase; padding: 8px 16px; position: relative; z-index: 2; white-space: nowrap; }
.nav-item:hover > a { color: white; }
.sub-menu { display: none; position: absolute; top: 100%; left: 0; background: white; border-radius: 10px; box-shadow: 0 8px 30px rgba(0,0,0,.15); padding: 8px 0; min-width: 200px; z-index: 9999; }
.nav-item:hover .sub-menu { display: block; }
.sub-menu ul { list-style: none; padding: 0; margin: 0; }
.sub-menu ul li a { display: block; padding: 10px 20px; color: #374151; text-decoration: none; font-size: 13px; font-weight: 500; text-transform: none; transition: background .2s; }
.sub-menu ul li a:hover { background: #f8fafc; color: var(--orange); }

/* ==========================================
   ARTICLE BANNER
   ========================================== */
.news-article-banner { margin-top: 110px; position: relative; width: 100%; height: 360px; overflow: hidden; }
.news-article-banner img { width: 100%; height: 100%; object-fit: cover; display: block; }
.banner-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,.15), rgba(0,0,0,.65)); display: flex; align-items: flex-end; padding: 36px 6%; }
.banner-overlay h1 { color: white; font-size: clamp(18px,3vw,34px); font-weight: 800; line-height: 1.3; text-shadow: 0 2px 10px rgba(0,0,0,.5); max-width: 860px; }
.banner-no-img { width: 100%; height: 100%; background: linear-gradient(135deg, #1e293b 0%, #F97316 100%); display: flex; align-items: center; justify-content: center; padding: 24px; text-align: center; }
.banner-no-img h1 { color: white; font-size: clamp(18px,3vw,34px); font-weight: 800; line-height: 1.3; }

/* ==========================================
   BREADCRUMB
   ========================================== */
.breadcrumb-bar { background: white; padding: 12px 6%; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #94a3b8; }
.breadcrumb-bar a { color: var(--orange); text-decoration: none; font-weight: 600; }
.breadcrumb-bar a:hover { text-decoration: underline; }
.breadcrumb-sep { margin: 0 6px; color: #cbd5e1; }
.breadcrumb-current { color: #64748b; max-width: 400px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; vertical-align: bottom; }

/* ==========================================
   CONTAINER & WRAPPER
   ========================================== */
.container { max-width: 1100px; margin: 0 auto; padding: 40px 24px 60px; }
.news-wrapper { display: grid; grid-template-columns: 1fr 300px; gap: 32px; }
@media(max-width:900px) { .news-wrapper { grid-template-columns: 1fr; } }

/* ==========================================
   MAIN CONTENT (LEFT)
   ========================================== */
.main-content { background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }

/* META ROW */
.article-meta { display: flex; align-items: center; gap: 20px; color: #94a3b8; font-size: 13px; margin-bottom: 24px; flex-wrap: wrap; }
.article-meta span { display: flex; align-items: center; gap: 6px; font-weight: 500; }
.article-meta i { color: var(--orange); }

/* SUMMARY BLOCKQUOTE */
.article-summary { border-left: 4px solid var(--orange); padding: 14px 20px; background: #fff7f0; border-radius: 0 10px 10px 0; margin-bottom: 28px; font-style: italic; color: #64748b; font-size: 15px; line-height: 1.75; }

/* ARTICLE CONTENT */
.article-content { font-size: 15px; color: #374151; line-height: 1.9; }
.article-content p { margin-bottom: 16px; }
.article-content h2 { font-size: 20px; font-weight: 700; color: var(--blue); margin: 28px 0 12px; padding-left: 14px; border-left: 4px solid var(--orange); }
.article-content h3 { font-size: 17px; font-weight: 700; color: var(--blue); margin: 22px 0 10px; padding-left: 14px; border-left: 3px solid var(--orange); }
.article-content img { max-width: 100%; height: auto; border-radius: 10px; margin: 16px 0; display: block; }
.article-content ul, .article-content ol { padding-left: 24px; margin-bottom: 16px; }
.article-content li { margin-bottom: 6px; }
.article-content a { color: var(--orange); text-decoration: underline; }
.article-content a:hover { color: #EA580C; }
.article-content blockquote { border-left: 4px solid var(--orange); padding: 12px 18px; background: #f8fafc; margin: 20px 0; color: #64748b; font-style: italic; border-radius: 0 8px 8px 0; }
.article-content table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 14px; }
.article-content table th, .article-content table td { border: 1px solid #e2e8f0; padding: 10px 14px; text-align: left; }
.article-content table th { background: #f8fafc; font-weight: 700; color: var(--blue); }

/* ==========================================
   SIDEBAR (RIGHT)
   ========================================== */
.sidebar-info { position: sticky; top: 120px; height: fit-content; display: flex; flex-direction: column; gap: 20px; }

.sidebar-box { background: white; border-radius: 16px; padding: 22px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.sidebar-box h3 { font-size: 13px; font-weight: 800; color: var(--blue); margin-bottom: 16px; padding-bottom: 10px; border-bottom: 2px solid var(--orange); letter-spacing: .08em; text-transform: uppercase; }

.info-item { display: flex; justify-content: space-between; gap: 10px; padding: 10px 0; border-bottom: 1px solid #f8fafc; font-size: 13px; }
.info-item:last-child { border-bottom: none; }
.info-label { color: #94a3b8; font-weight: 500; flex-shrink: 0; }
.info-value { color: var(--blue); font-weight: 600; text-align: right; }

/* SHARE BUTTONS */
.btn-share-fb { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; border-radius: 10px; background: var(--orange); color: white; font-size: 13px; font-weight: 700; text-decoration: none; margin-bottom: 10px; transition: background .3s; }
.btn-share-fb:hover { background: #EA580C; color: white; }
.btn-copy-link { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; border-radius: 10px; background: #f0f4ff; color: var(--blue); font-size: 13px; font-weight: 700; border: 1px solid #dde7ff; cursor: pointer; transition: background .3s, color .3s; font-family: 'Plus Jakarta Sans', sans-serif; }
.btn-copy-link:hover { background: var(--blue); color: white; }

/* CTA BUTTON */
.btn-call { width: 100%; background: var(--orange); color: white; border: none; padding: 14px; cursor: pointer; font-weight: 800; font-size: 15px; border-radius: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; transition: background .3s; font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: .04em; }
.btn-call:hover { background: #EA580C; color: white; }

/* ==========================================
   RELATED NEWS
   ========================================== */
.related-section { max-width: 1100px; margin: 0 auto; padding: 0 24px 60px; }
.related-section h3 { font-size: 20px; font-weight: 800; color: var(--blue); margin-bottom: 24px; padding-left: 14px; border-left: 5px solid var(--orange); }
.related-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
@media(max-width:768px) { .related-grid { grid-template-columns: 1fr; } }
@media(max-width:1024px) and (min-width:769px) { .related-grid { grid-template-columns: repeat(2, 1fr); } }

.related-card { border-radius: 14px; overflow: hidden; background: white; box-shadow: 0 2px 10px rgba(0,0,0,.06); text-decoration: none; color: inherit; transition: transform .25s, box-shadow .25s; display: block; border: 1px solid #f1f5f9; }
.related-card:hover { transform: translateY(-5px); box-shadow: 0 10px 28px rgba(0,0,0,.12); border-color: var(--orange); }
.related-card img { width: 100%; height: 170px; object-fit: cover; display: block; }
.related-card-no-img { width: 100%; height: 170px; background: linear-gradient(135deg, #F97316 0%, #1e293b 100%); display: flex; align-items: center; justify-content: center; }
.related-card-body { padding: 16px; }
.related-card-body h4 { font-size: 14px; font-weight: 700; color: var(--blue); margin: 0 0 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; }
.related-card-body span { font-size: 12px; color: #94a3b8; display: flex; align-items: center; gap: 5px; }

/* ==========================================
   FOOTER
   ========================================== */
.main-footer { background: #1a1a1a; color: #fff; padding: 60px 0 20px 0; font-family: 'Plus Jakarta Sans', sans-serif; }
.footer-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1.5fr 1fr 1.5fr; gap: 40px; }
.footer-col h4 { color: var(--orange); font-size: 1rem; margin-bottom: 25px; position: relative; text-transform: uppercase; font-weight: 700; }
.footer-col h4::after { content: ''; position: absolute; left: 0; bottom: -8px; width: 50px; height: 2px; background: var(--orange); }
.footer-slogan { font-size: 1rem; font-weight: 900; color: var(--orange); margin: 15px 0; letter-spacing: 1px; }
.footer-desc { color: #ccc; line-height: 1.6; font-size: 0.9rem; margin-bottom: 20px; }
.footer-links { list-style: none; padding: 0; }
.footer-links li { margin-bottom: 12px; }
.footer-links a { color: #ccc; text-decoration: none; transition: 0.3s; font-size: 0.9rem; }
.footer-links a:hover { color: var(--orange); padding-left: 5px; }
.footer-contact ul { list-style: none; padding: 0; }
.footer-contact li { display: flex; align-items: flex-start; gap: 15px; margin-bottom: 15px; color: #ccc; font-size: 0.9rem; }
.footer-contact i { color: var(--orange); margin-top: 4px; width: 20px; text-align: center; flex-shrink: 0; }
.footer-social { display: flex; gap: 12px; margin-top: 8px; }
.footer-social a { width: 35px; height: 35px; background: #333; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: 0.3s; text-decoration: none; font-size: 14px; }
.footer-social a:hover { background: var(--orange); transform: translateY(-3px); }
.footer-bottom { margin-top: 50px; padding: 20px 24px 0; border-top: 1px solid #333; text-align: center; font-size: 0.85rem; color: #777; }
.footer-bottom a { color: var(--orange); text-decoration: none; }

/* ==========================================
   MOBILE MENU
   ========================================== */
.menu-toggle { display: none; color: #1e293b; font-size: 26px; cursor: pointer; z-index: 10000; }

@media (max-width: 768px) {
    .menu-toggle { display: block !important; margin-right: 15px; }
    .menu-toggle i.fa-bars { color: #1e293b !important; }
    .menu-toggle i.fa-times { color: #fff !important; font-size: 28px; }

    .nav-links {
        position: fixed !important; top: 0 !important; right: -100% !important;
        width: 75% !important; height: 100vh !important;
        background: rgba(15,15,15,0.98) !important; backdrop-filter: blur(15px) !important;
        flex-direction: column !important; justify-content: flex-start !important;
        align-items: flex-start !important; padding: 90px 30px 30px !important;
        transition: right 0.4s cubic-bezier(0.19,1,0.22,1) !important;
        overflow-y: auto !important; z-index: 9998 !important; gap: 0 !important;
    }
    .nav-links.nav-active { right: 0 !important; }
    .nav-item { width: 100% !important; margin-bottom: 5px !important; }
    .nav-item > a {
        color: #fff !important; font-size: 15px !important;
        display: flex !important; justify-content: space-between !important;
        align-items: center !important; width: 100% !important;
        padding: 15px 0 !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important;
    }
    .nav-marker { display: none !important; }
    .sub-menu {
        position: relative !important; display: none !important;
        box-shadow: none !important; background: transparent !important;
        border-radius: 0 !important; padding: 0 !important; min-width: unset !important;
        transform: none !important; opacity: 1 !important; visibility: visible !important;
    }
    .sub-menu.mobile-active { display: block !important; }
    .sub-menu ul li a { color: rgba(255,255,255,0.7) !important; padding: 10px 15px !important; }
    .sub-menu ul li a:hover { background: transparent !important; color: var(--orange) !important; }

    .news-article-banner { margin-top: 70px !important; height: 220px !important; }
    .news-wrapper { grid-template-columns: 1fr !important; }
    .sidebar-info { position: relative !important; top: 0 !important; }
    .container { padding: 24px 16px 40px; }
    .main-content { padding: 20px; }

    .footer-container { grid-template-columns: 1fr !important; }
}

@media (max-width: 992px) {
    .footer-container { grid-template-columns: 1fr 1fr; }
    .news-wrapper { grid-template-columns: 1fr; }
    .sidebar-info { position: relative; top: 0; }
}
</style>
</head>
<body>

<!-- NAV -->
<nav>
    <a href="index.html" class="nav-logo">
        <img src="./img/logo-telec.png" alt="TELEC Logo">
    </a>
    <div class="menu-toggle"><i class="fas fa-bars"></i></div>
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

<!-- BANNER -->
<div class="news-article-banner">
    <?php if (!empty($article['image'])): ?>
    <img src="<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
    <div class="banner-overlay">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
    </div>
    <?php else: ?>
    <div class="banner-no-img">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
    </div>
    <?php endif; ?>
</div>

<!-- BREADCRUMB -->
<div class="breadcrumb-bar">
    <a href="index.html">Trang chủ</a>
    <span class="breadcrumb-sep">&rsaquo;</span>
    <a href="news.html">Tin tức</a>
    <span class="breadcrumb-sep">&rsaquo;</span>
    <span class="breadcrumb-current"><?= htmlspecialchars(mb_substr($article['title'], 0, 60)) . (mb_strlen($article['title']) > 60 ? '...' : '') ?></span>
</div>

<!-- MAIN CONTAINER -->
<div class="container">
    <div class="news-wrapper">

        <!-- LEFT: ARTICLE CONTENT -->
        <div class="main-content">
            <!-- META ROW -->
            <div class="article-meta">
                <span><i class="fas fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($article['created_at'])) ?></span>
                <span><i class="fas fa-eye"></i> <?= number_format($article['views']) ?> lượt xem</span>
                <span><i class="fas fa-clock"></i> <?= $read_time ?> phút đọc</span>
            </div>

            <!-- SUMMARY -->
            <?php if (!empty($article['summary'])): ?>
            <div class="article-summary">
                <?= htmlspecialchars($article['summary']) ?>
            </div>
            <?php endif; ?>

            <!-- CONTENT -->
            <div class="article-content">
                <?= $article['content'] ?? '<p>Nội dung đang được cập nhật...</p>' ?>
            </div>
        </div>

        <!-- RIGHT: SIDEBAR -->
        <div class="sidebar-info">

            <!-- BOX 1: ARTICLE INFO -->
            <div class="sidebar-box">
                <h3>Thông tin bài viết</h3>
                <div class="info-item">
                    <span class="info-label">Ngày đăng:</span>
                    <span class="info-value"><?= date('d/m/Y', strtotime($article['created_at'])) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Lượt xem:</span>
                    <span class="info-value"><?= number_format($article['views']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Thời gian đọc:</span>
                    <span class="info-value"><?= $read_time ?> phút</span>
                </div>
            </div>

            <!-- BOX 2: SHARE -->
            <div class="sidebar-box">
                <h3>Chia sẻ bài viết</h3>
                <a href="<?= $fb_share ?>" target="_blank" rel="noopener" class="btn-share-fb">
                    <i class="fab fa-facebook-f"></i> Chia sẻ Facebook
                </a>
                <button id="copy-btn" class="btn-copy-link" onclick="copyArticleLink()">
                    <i class="fas fa-link"></i> Sao chép link
                </button>
            </div>

            <!-- BOX 3: CTA -->
            <div class="sidebar-box">
                <a href="tel:0946233297" class="btn-call">
                    <i class="fas fa-phone-alt"></i> GỌI TƯ VẤN NGAY
                </a>
            </div>

        </div>
    </div>
</div>

<!-- RELATED NEWS -->
<?php if (!empty($related_news)): ?>
<div class="related-section">
    <h3>Tin tức liên quan</h3>
    <div class="related-grid">
        <?php foreach ($related_news as $r): ?>
        <a href="tin-tuc.php?id=<?= $r['id'] ?>" class="related-card">
            <?php if (!empty($r['image'])): ?>
            <img src="<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['title']) ?>">
            <?php else: ?>
            <div class="related-card-no-img">
                <i class="fas fa-newspaper" style="font-size:36px;color:rgba(255,255,255,.45)"></i>
            </div>
            <?php endif; ?>
            <div class="related-card-body">
                <h4><?= htmlspecialchars($r['title']) ?></h4>
                <span><i class="fas fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($r['created_at'])) ?></span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- FOOTER -->
<footer id="contact" class="main-footer">
    <div class="footer-container">
        <!-- Cột 1: Giới thiệu & Logo -->
        <div class="footer-col footer-about">
            <div class="footer-logo">
                <svg width="200" height="60" viewBox="0 0 800 300" xmlns="http://www.w3.org/2000/svg">
                    <g fill="#F97316">
                        <rect x="20" y="100" width="40" height="10" rx="5" />
                        <rect x="50" y="120" width="50" height="10" rx="5" />
                        <rect x="80" y="140" width="60" height="10" rx="5" />
                        <rect x="40" y="160" width="80" height="10" rx="5" />
                        <rect x="70" y="180" width="70" height="10" rx="5" />
                        <rect x="100" y="200" width="50" height="10" rx="5" />
                        <path d="M160 70h160v40h-55v110h-50v-110h-55z" />
                    </g>
                    <text x="340" y="220" font-family="Arial Black, sans-serif" font-size="120" fill="white" style="font-weight: 900;">ELEC</text>
                </svg>
            </div>
            <h3 class="footer-slogan">BETTER ELECTRICITY - BETTER LIFE</h3>
            <p class="footer-desc">Chuyên cung cấp giải pháp Solar - Smarthome hàng đầu Việt Nam. Mang công nghệ xanh và tiện nghi đến ngôi nhà của bạn.</p>
            <div class="footer-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>

        <!-- Cột 2: Danh mục sản phẩm -->
        <div class="footer-col">
            <h4>Sản phẩm</h4>
            <ul class="footer-links">
                <li><a href="inverter.html">Hệ thống Biến tần</a></li>
                <li><a href="solar-lithium.html">Pin Solar & Lithium</a></li>
                <li><a href="https://lumi.vn">Nhà thông minh Lumi</a></li>
                <li><a href="projects.html">Dự án tiêu biểu</a></li>
                <li><a href="news.html">Tin tức & Sự kiện</a></li>
            </ul>
        </div>

        <!-- Cột 3: Thông tin liên hệ -->
        <div class="footer-col footer-contact">
            <h4>Thông tin liên hệ</h4>
            <ul>
                <li>
                    <i class="fas fa-map-marker-alt"></i>
                    <span><strong>VP:</strong> Số 48 LK7, KĐT Tổng cục V, Tân Triều, Thanh Trì, Hà Nội</span>
                </li>
                <li>
                    <i class="fas fa-building"></i>
                    <span><strong>Trụ sở:</strong> Đường 57A, Thị trấn Lâm, Ý Yên, Nam Định</span>
                </li>
                <li>
                    <i class="fas fa-phone-alt"></i>
                    <span data-contact="phone">0946.233.297 - 0335.109.032</span>
                </li>
                <li>
                    <i class="fas fa-envelope"></i>
                    <span data-contact="email">Telec.ltd2025@gmail.com</span>
                </li>
                <li>
                    <i class="fas fa-globe"></i>
                    <span>www.telec.com.vn</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© <?= date('Y') ?> T-ELEC Energy. All rights reserved. | <a href="https://telec.com.vn/">Better Electricity - Better Life</a></p>
    </div>
</footer>

<script src="contact-loader.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Nav marker
    const marker            = document.querySelector('.nav-marker');
    const navItems          = document.querySelectorAll('.nav-item');
    const navLinksContainer = document.querySelector('.nav-links');
    if (marker && navItems.length) {
        navItems.forEach(item => {
            item.addEventListener('mouseenter', e => {
                marker.style.left    = e.currentTarget.offsetLeft + 'px';
                marker.style.width   = e.currentTarget.offsetWidth + 'px';
                marker.style.opacity = '1';
            });
        });
        if (navLinksContainer) {
            navLinksContainer.addEventListener('mouseleave', () => {
                marker.style.opacity = '0';
                marker.style.width   = '0';
            });
        }
    }

    // Scroll class on nav
    const nav = document.querySelector('nav');
    if (nav) window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 50));

    // Mobile menu
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks   = document.querySelector('.nav-links');
    if (!menuToggle || !navLinks) return;

    const closeMenu = () => {
        navLinks.classList.remove('nav-active');
        const icon = menuToggle.querySelector('i');
        icon.classList.replace('fa-times', 'fa-bars');
        icon.style.color = '';
        document.querySelectorAll('.sub-menu').forEach(s => s.classList.remove('mobile-active'));
    };

    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('nav-active');
        const icon = menuToggle.querySelector('i');
        if (navLinks.classList.contains('nav-active')) {
            icon.classList.replace('fa-bars', 'fa-times');
            icon.style.color = '#fff';
        } else {
            icon.classList.replace('fa-times', 'fa-bars');
            icon.style.color = '';
        }
    });

    document.querySelectorAll('.nav-item').forEach(item => {
        const link    = item.querySelector('a');
        const subMenu = item.querySelector('.sub-menu');
        if (subMenu) {
            link.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const isActive = subMenu.classList.contains('mobile-active');
                    document.querySelectorAll('.sub-menu').forEach(s => s.classList.remove('mobile-active'));
                    if (!isActive) subMenu.classList.add('mobile-active');
                }
            });
        } else if (link) {
            link.addEventListener('click', () => { if (window.innerWidth <= 768) closeMenu(); });
        }
    });

    document.addEventListener('click', (e) => { if (!e.target.closest('nav')) closeMenu(); });
});

// Copy link to clipboard
function copyArticleLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const btn = document.getElementById('copy-btn');
        btn.innerHTML = '<i class="fas fa-check"></i> Đã sao chép!';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-link"></i> Sao chép link';
        }, 2000);
    }).catch(() => {
        // Fallback for older browsers
        const el = document.createElement('input');
        el.value = window.location.href;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        const btn = document.getElementById('copy-btn');
        btn.innerHTML = '<i class="fas fa-check"></i> Đã sao chép!';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-link"></i> Sao chép link';
        }, 2000);
    });
}
</script>
</body>
</html>
