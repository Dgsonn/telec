<?php
require_once 'admin/config/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: projects.html'); exit; }

$db = getDB();
$stmt = $db->prepare('SELECT * FROM projects WHERE id = ? AND is_active = 1');
$stmt->execute([$id]);
$project = $stmt->fetch();
if (!$project) { header('Location: projects.html'); exit; }

// Dự án liên quan
$related = $db->prepare('SELECT id, title, image, capacity, created_at FROM projects WHERE is_active = 1 AND id != ? AND category = ? ORDER BY created_at DESC LIMIT 3');
$related->execute([$id, $project['category']]);
$related_projects = $related->fetchAll();

$cat_labels = ['solar' => 'Điện mặt trời', 'smarthome' => 'Smart Home', 'other' => 'Khác'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($project['title']) ?> | TELEC Energy</title>
<link rel="icon" type="image/png" href="./img/favicon-t.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root { --orange: #F97316; --blue: #1e293b; }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #374151; line-height: 1.7; overflow-x: hidden; }

/* NAV */
nav { position: fixed; top: 0; left: 0; width: 100%; padding: 10px 6%; display: flex; justify-content: space-between; align-items: center; z-index: 9999; background: white; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
.nav-logo img { height: 90px; width: auto; transform: translateY(4px); }
.nav-links { display: flex; align-items: center; list-style: none; gap: 8px; position: relative; padding: 8px 20px; }
.nav-marker { position: absolute; height: 34px; width: 0; top: 50%; transform: translateY(-50%); left: 0; background: #1e293b; border-radius: 20px; z-index: 1; opacity: 0; transition: all .4s cubic-bezier(.23,1,.32,1); }
.nav-item > a { display: flex; align-items: center; text-decoration: none; color: #1e293b; font-family: 'Raleway', sans-serif; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: .05em; padding: 8px 16px; position: relative; z-index: 2; white-space: nowrap; }
.nav-item:hover > a { color: white; }
.sub-menu { display: none; position: absolute; top: 100%; left: 0; background: white; border-radius: 10px; box-shadow: 0 8px 30px rgba(0,0,0,.15); padding: 8px 0; min-width: 200px; z-index: 9999; }
.nav-item:hover .sub-menu { display: block; }
.sub-menu ul { list-style: none; padding: 0; margin: 0; }
.sub-menu ul li a { display: block; padding: 10px 20px; color: #374151; text-decoration: none; font-size: 13px; font-weight: 500; text-transform: none; transition: background .2s; }
.sub-menu ul li a:hover { background: #f8fafc; color: var(--orange); }

/* BANNER */
.project-banner { margin-top: 110px; width: 100%; height: 320px; position: relative; overflow: hidden; }
.project-banner img { width: 100%; height: 100%; object-fit: cover; }
.project-banner .banner-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,.2), rgba(0,0,0,.6)); display: flex; align-items: flex-end; padding: 32px 6%; }
.banner-no-img { width: 100%; height: 100%; background: linear-gradient(135deg, #1e293b 0%, #F97316 100%); display: flex; align-items: center; justify-content: center; }
.project-banner h1 { color: white; font-size: clamp(18px,3vw,32px); font-weight: 800; line-height: 1.3; text-shadow: 0 2px 8px rgba(0,0,0,.4); max-width: 800px; }

/* BREADCRUMB */
.breadcrumb-bar { background: white; padding: 12px 6%; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #94a3b8; }
.breadcrumb-bar a { color: #F97316; text-decoration: none; font-weight: 600; }
.breadcrumb-bar a:hover { text-decoration: underline; }

/* CONTAINER */
.container { max-width: 1100px; margin: 0 auto; padding: 40px 24px 60px; }
.project-wrapper { display: grid; grid-template-columns: 1fr 320px; gap: 32px; }
@media(max-width:900px) { .project-wrapper { grid-template-columns: 1fr; } }

/* MAIN CONTENT */
.main-content { background: white; border-radius: 16px; padding: 32px; box-shadow: 0 2px 12px rgba(0,0,0,.06); }
.main-content h2 { font-size: 18px; font-weight: 700; color: #1e293b; margin: 28px 0 12px; padding-left: 12px; border-left: 4px solid var(--orange); }
.main-content h2:first-child { margin-top: 0; }
.main-content p { margin-bottom: 14px; color: #374151; line-height: 1.8; }
.main-content .project-img { width: 100%; border-radius: 10px; margin: 12px 0; display: block; }
.solution-list { list-style: none; padding: 0; margin-bottom: 16px; }
.solution-list li { display: flex; align-items: flex-start; gap: 10px; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
.solution-list li:last-child { border-bottom: none; }
.solution-list li i { color: var(--orange); margin-top: 2px; flex-shrink: 0; }

/* SIDEBAR INFO */
.sidebar-info { position: sticky; top: 120px; background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,.06); height: fit-content; }
.sidebar-info h3 { font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid var(--orange); letter-spacing: .05em; }
.info-item { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f8fafc; font-size: 14px; }
.info-label { color: #94a3b8; font-weight: 500; flex-shrink: 0; }
.info-value { color: #1e293b; font-weight: 600; text-align: right; }
.btn-call { width: 100%; background: var(--orange); color: white; border: none; padding: 14px; margin-top: 20px; cursor: pointer; font-weight: 700; font-size: 15px; border-radius: 8px; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; }
.btn-call:hover { background: #EA580C; color: white; }

/* RELATED */
.related-section { margin-top: 40px; }
.related-section h3 { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 20px; }
.related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
.related-card { border-radius: 12px; overflow: hidden; background: white; box-shadow: 0 2px 8px rgba(0,0,0,.06); text-decoration: none; color: inherit; transition: transform .2s, box-shadow .2s; display: block; }
.related-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
.related-card img { width: 100%; height: 160px; object-fit: cover; }
.related-card-no-img { width: 100%; height: 160px; background: linear-gradient(135deg,#f97316,#1e293b); display: flex; align-items: center; justify-content: center; }
.related-card-body { padding: 14px; }
.related-card-body h4 { font-size: 14px; font-weight: 600; color: #1e293b; margin: 0 0 6px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.related-card-body span { font-size: 12px; color: #94a3b8; }

/* FOOTER */
.main-footer { background: #1a1a1a; color: #fff; padding: 60px 0 20px 0; font-family: 'Raleway', sans-serif; }
.footer-container { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1.5fr 1fr 1.5fr; gap: 40px; }
.footer-col h4 { color: var(--orange); font-size: 1.1rem; margin-bottom: 25px; position: relative; text-transform: uppercase; font-weight: 800; letter-spacing: .08em; }
.footer-col h4::after { content: ''; position: absolute; left: 0; bottom: -8px; width: 40px; height: 2px; background: var(--orange); }
.footer-slogan { font-size: .95rem; font-weight: 800; color: var(--orange); margin: 12px 0; letter-spacing: 1px; }
.footer-desc { color: #ccc; line-height: 1.6; font-size: .88rem; margin-bottom: 18px; }
.footer-links { list-style: none; padding: 0; }
.footer-links li { margin-bottom: 10px; }
.footer-links a { color: #ccc; text-decoration: none; font-size: .9rem; transition: .3s; }
.footer-links a:hover { color: var(--orange); padding-left: 4px; }
.footer-contact ul { list-style: none; padding: 0; }
.footer-contact li { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; color: #ccc; font-size: .88rem; }
.footer-contact i { color: var(--orange); margin-top: 3px; width: 16px; text-align: center; flex-shrink: 0; }
.footer-social { display: flex; gap: 12px; }
.footer-social a { width: 34px; height: 34px; background: #333; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: .3s; text-decoration: none; font-size: 14px; }
.footer-social a:hover { background: var(--orange); transform: translateY(-3px); }
.footer-bottom { margin-top: 40px; padding: 18px 24px 0; border-top: 1px solid #333; text-align: center; font-size: .82rem; color: #777; }
.footer-bottom a { color: var(--orange); text-decoration: none; }
@media(max-width:768px) { .footer-container { grid-template-columns: 1fr !important; } }

/* MOBILE MENU */
.menu-toggle { display: none; color: #1e293b; font-size: 26px; cursor: pointer; z-index: 10000; }

@media (max-width: 768px) {
    .menu-toggle { display: block !important; margin-right: 15px; }
    .menu-toggle i.fa-bars { color: #1e293b !important; }
    .menu-toggle i.fa-times { color: #fff !important; font-size: 28px; }

    .nav-links {
        position: fixed !important;
        top: 0 !important; right: -100% !important;
        width: 75% !important; height: 100vh !important;
        background: rgba(15,15,15,0.98) !important;
        backdrop-filter: blur(15px) !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        align-items: flex-start !important;
        padding: 90px 30px 30px !important;
        transition: right 0.4s cubic-bezier(0.19,1,0.22,1) !important;
        overflow-y: auto !important;
        z-index: 9998 !important;
        gap: 0 !important;
    }
    .nav-links.nav-active { right: 0 !important; }
    .nav-item { width: 100% !important; margin-bottom: 5px !important; }
    .nav-item > a {
        color: #fff !important; font-size: 15px !important;
        display: flex !important; justify-content: space-between !important;
        align-items: center !important; width: 100% !important;
        padding: 15px 0 !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
    }
    .nav-marker { display: none !important; }
    .sub-menu {
        position: relative !important; display: none !important;
        box-shadow: none !important; background: transparent !important;
        border-radius: 0 !important; padding: 0 !important; min-width: unset !important;
    }
    .sub-menu.mobile-active { display: block !important; }
    .sub-menu ul li a { color: rgba(255,255,255,0.7) !important; padding: 10px 15px !important; }
    .sub-menu ul li a:hover { background: transparent !important; color: var(--orange) !important; }

    .project-banner { margin-top: 70px !important; height: 220px !important; }
    .project-wrapper { grid-template-columns: 1fr !important; }
    .sidebar-info { position: relative !important; top: 0 !important; }
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
<div class="project-banner">
    <?php if ($project['image']): ?>
    <img src="<?= htmlspecialchars($project['image']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
    <div class="banner-overlay">
        <h1><?= htmlspecialchars($project['title']) ?></h1>
    </div>
    <?php else: ?>
    <div class="banner-no-img">
        <h1 style="color:white;font-size:clamp(18px,3vw,32px);font-weight:800;text-align:center;padding:24px"><?= htmlspecialchars($project['title']) ?></h1>
    </div>
    <?php endif; ?>
</div>

<!-- BREADCRUMB -->
<div class="breadcrumb-bar">
    <a href="projects.html">Dự án</a> &rsaquo;
    <a href="projects.html#<?= $project['category'] ?>"><?= htmlspecialchars($cat_labels[$project['category']] ?? 'Dự án') ?></a> &rsaquo;
    <?= htmlspecialchars($project['title']) ?>
</div>

<div class="container">
    <div class="project-wrapper">
        <!-- NỘI DUNG CHÍNH -->
        <div class="main-content">
            <?php if ($project['content']): ?>
                <?= $project['content'] ?>
            <?php else: ?>
                <h2>1. Thông tin dự án</h2>
                <p>Công trình được Công ty TNHH Kỹ Thuật Điện TELEC triển khai thiết kế và thi công
                <?php if ($project['category'] === 'solar'): ?>
                    hệ thống điện năng lượng mặt trời nhằm tối ưu chi phí điện năng và hướng tới giải pháp sử dụng năng lượng sạch, bền vững.
                <?php elseif ($project['category'] === 'smarthome'): ?>
                    hệ thống điện thông minh (Smart Home) mang lại không gian sống hiện đại, tiện nghi và an toàn.
                <?php else: ?>
                    giải pháp kỹ thuật điện tối ưu cho khách hàng.
                <?php endif; ?>
                </p>

                <?php if ($project['description']): ?>
                <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
                <?php endif; ?>

                <h2>2. Quy trình triển khai</h2>
                <ul class="solution-list">
                    <li><i class="fas fa-check-circle"></i> Khảo sát và tư vấn giải pháp phù hợp với công trình</li>
                    <li><i class="fas fa-check-circle"></i> Thiết kế hệ thống kỹ thuật tối ưu</li>
                    <li><i class="fas fa-check-circle"></i> Thi công lắp đặt theo tiêu chuẩn kỹ thuật</li>
                    <li><i class="fas fa-check-circle"></i> Kiểm tra vận hành và bàn giao hệ thống</li>
                    <li><i class="fas fa-check-circle"></i> Bảo trì và hỗ trợ sau lắp đặt</li>
                </ul>

                <h2>3. Hiệu quả mang lại</h2>
                <ul class="solution-list">
                    <li><i class="fas fa-star"></i> Tối ưu chi phí vận hành lâu dài</li>
                    <li><i class="fas fa-star"></i> Nâng cao chất lượng và tiện nghi cuộc sống</li>
                    <li><i class="fas fa-star"></i> Công nghệ hiện đại, tuổi thọ cao</li>
                    <li><i class="fas fa-star"></i> Được bảo hành và hỗ trợ bởi đội ngũ TELEC</li>
                </ul>
            <?php endif; ?>
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar-info">
            <h3>THÔNG TIN DỰ ÁN</h3>
            <div class="info-item">
                <span class="info-label">Đơn vị:</span>
                <span class="info-value">TELEC Co., Ltd</span>
            </div>
            <div class="info-item">
                <span class="info-label">Loại:</span>
                <span class="info-value"><?= htmlspecialchars($cat_labels[$project['category']] ?? $project['category']) ?></span>
            </div>
            <?php if ($project['client_name']): ?>
            <div class="info-item">
                <span class="info-label">Chủ đầu tư:</span>
                <span class="info-value"><?= htmlspecialchars($project['client_name']) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($project['location']): ?>
            <div class="info-item">
                <span class="info-label">Địa điểm:</span>
                <span class="info-value"><?= htmlspecialchars($project['location']) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($project['capacity']): ?>
            <div class="info-item">
                <span class="info-label">Công suất:</span>
                <span class="info-value"><?= htmlspecialchars($project['capacity']) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($project['project_date']): ?>
            <div class="info-item">
                <span class="info-label">Năm hoàn thành:</span>
                <span class="info-value"><?= date('Y', strtotime($project['project_date'])) ?></span>
            </div>
            <?php endif; ?>
            <a href="tel:0946233297" class="btn-call"><i class="fas fa-phone-alt"></i> GỌI TƯ VẤN</a>
        </div>
    </div>

    <!-- DỰ ÁN LIÊN QUAN -->
    <?php if ($related_projects): ?>
    <div class="related-section">
        <h3>Dự án cùng chuyên mục</h3>
        <div class="related-grid">
            <?php foreach ($related_projects as $r): ?>
            <a href="du-an.php?id=<?= $r['id'] ?>" class="related-card">
                <?php if ($r['image']): ?>
                <img src="<?= htmlspecialchars($r['image']) ?>" alt="<?= htmlspecialchars($r['title']) ?>">
                <?php else: ?>
                <div class="related-card-no-img"><i class="fas fa-solar-panel" style="font-size:36px;color:rgba(255,255,255,.4)"></i></div>
                <?php endif; ?>
                <div class="related-card-body">
                    <h4><?= htmlspecialchars($r['title']) ?></h4>
                    <span><?= htmlspecialchars($r['capacity'] ?: '') ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<footer id="contact" class="main-footer">
    <div class="footer-container">
        <div class="footer-col footer-about">
            <div style="margin-bottom:12px">
                <svg width="180" height="54" viewBox="0 0 800 300" xmlns="http://www.w3.org/2000/svg">
                    <g fill="#FF8C42">
                        <rect x="20" y="100" width="40" height="10" rx="5"/>
                        <rect x="50" y="120" width="50" height="10" rx="5"/>
                        <rect x="80" y="140" width="60" height="10" rx="5"/>
                        <rect x="40" y="160" width="80" height="10" rx="5"/>
                        <rect x="70" y="180" width="70" height="10" rx="5"/>
                        <rect x="100" y="200" width="50" height="10" rx="5"/>
                        <path d="M160 70h160v40h-55v110h-50v-110h-55z"/>
                    </g>
                    <text x="340" y="220" font-family="Arial Black, sans-serif" font-size="120" fill="white" style="font-weight:900">ELEC</text>
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
        <div class="footer-col">
            <h4>Sản phẩm</h4>
            <ul class="footer-links">
                <li><a href="inverter.html">Hệ thống Biến tần</a></li>
                <li><a href="solar-lithium.html">Pin Solar & Lithium</a></li>
                <li><a href="smarthome.html">Nhà thông minh</a></li>
                <li><a href="projects.html">Dự án tiêu biểu</a></li>
                <li><a href="news.html">Tin tức</a></li>
            </ul>
        </div>
        <div class="footer-col footer-contact">
            <h4>Thông tin liên hệ</h4>
            <ul>
                <li><i class="fas fa-map-marker-alt"></i><span><strong>VP:</strong> Số 48 LK7, KĐT Tổng cục V, Tân Triều, Thanh Trì, Hà Nội</span></li>
                <li><i class="fas fa-building"></i><span><strong>Trụ sở:</strong> Đường 57A, Thị trấn Lâm, Ý Yên, Nam Định</span></li>
                <li><i class="fas fa-phone-alt"></i><span data-contact="phone">0946.233.297 - 0335.109.032</span></li>
                <li><i class="fas fa-envelope"></i><span data-contact="email">Telec.ltd2025@gmail.com</span></li>
                <li><i class="fas fa-globe"></i><span>www.telec.com.vn</span></li>
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
</script>
</body>
</html>
