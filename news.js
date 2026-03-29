/**
 * T-ELEC ENERGY - NEWS JS
 * Load tin tức từ CMS database qua API
 */

// HÀM HIỆU ỨNG SCROLL REVEAL
function initScrollReveal() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
}

// HIỆU ỨNG NAV MARKER
function initNavMarker() {
    const marker            = document.querySelector('.nav-marker');
    const navItems          = document.querySelectorAll('.nav-item');
    const navLinksContainer = document.querySelector('.nav-links');
    if (!marker || !navItems.length) return;

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

    const nav = document.querySelector('nav');
    if (nav) window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 50));
}

// HÀM TÌM KIẾM
function initSearch() {
    const searchInput = document.getElementById('newsSearch');
    if (!searchInput) return;
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.news-card').forEach(card => {
            const title = card.querySelector('h3')?.innerText.toLowerCase() || '';
            card.style.display = title.includes(term) ? 'flex' : 'none';
            if (title.includes(term)) card.classList.add('active');
        });
    });
}

// MENU MOBILE
function initMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks   = document.querySelector('.nav-links');
    if (!menuToggle || !navLinks) return;

    const closeMenu = () => {
        navLinks.classList.remove('nav-active');
        const icon = menuToggle.querySelector('i');
        icon.classList.replace('fa-times', 'fa-bars');
        icon.style.color = '#121212';
        document.querySelectorAll('.sub-menu').forEach(sub => {
            sub.classList.remove('mobile-active');
            sub.style.display = 'none';
        });
    };

    menuToggle.addEventListener('click', () => {
        navLinks.classList.toggle('nav-active');
        const icon = menuToggle.querySelector('i');
        if (navLinks.classList.contains('nav-active')) {
            icon.classList.replace('fa-bars', 'fa-times');
            icon.style.color = '#fff';
        } else {
            icon.classList.replace('fa-times', 'fa-bars');
            icon.style.color = '#121212';
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
                    document.querySelectorAll('.sub-menu').forEach(s => {
                        s.classList.remove('mobile-active');
                        s.style.display = 'none';
                    });
                    if (!isActive) {
                        subMenu.classList.add('mobile-active');
                        subMenu.style.display = 'block';
                    }
                }
            });
            subMenu.querySelectorAll('li a').forEach(sl => {
                sl.addEventListener('click', () => { if (window.innerWidth <= 768) closeMenu(); });
            });
        } else {
            link.addEventListener('click', () => { if (window.innerWidth <= 768) closeMenu(); });
        }
    });
}

// TẠO THẺ TIN TỨC
function createNewsCard(item) {
    const date = new Date(item.created_at).toLocaleDateString('vi-VN');
    const views = item.views ? Number(item.views).toLocaleString('vi-VN') : '0';

    let imageHtml = '';
    if (item.image) {
        imageHtml = `
            <div class="news-card-img">
                <img src="${item.image}" alt="${item.title}" loading="lazy">
                <span class="date-badge">${date}</span>
            </div>`;
    } else {
        imageHtml = `
            <div class="news-card-no-img">
                <i class="fas fa-newspaper" style="font-size:48px;color:rgba(255,255,255,0.5)"></i>
            </div>`;
    }

    return `
        <div class="news-card reveal" onclick="window.location.href='tin-tuc.php?id=${item.id}'">
            ${imageHtml}
            <div class="news-card-body">
                <h3>${item.title}</h3>
                <p class="news-card-summary">${item.summary || ''}</p>
                <div class="news-card-footer">
                    <span class="news-card-views">
                        <i class="fas fa-eye"></i> ${views} lượt xem
                    </span>
                    <span class="news-card-btn">
                        Đọc thêm <i class="fas fa-arrow-right"></i>
                    </span>
                </div>
            </div>
        </div>`;
}

// LOAD TIN TỨC TỪ API
async function loadNews() {
    const newsGrid  = document.getElementById('news-grid');
    const totalEl   = document.getElementById('total-count');

    try {
        const res  = await fetch('api/news.php?limit=50');
        const json = await res.json();

        if (!json.success || !json.data || !json.data.length) {
            newsGrid.innerHTML = '<p style="color:#94a3b8;font-size:14px;padding:20px 0;grid-column:1/-1">Chưa có tin tức nào.</p>';
            if (totalEl) totalEl.textContent = '0';
            return;
        }

        newsGrid.innerHTML = json.data.map(item => createNewsCard(item)).join('');
        if (totalEl) totalEl.textContent = json.data.length;

        initScrollReveal();

    } catch (e) {
        newsGrid.innerHTML = '<p style="color:#94a3b8;font-size:14px;padding:20px 0;grid-column:1/-1">Không thể tải tin tức. Vui lòng thử lại sau.</p>';
        if (totalEl) totalEl.textContent = '0';
    }
}

// KHỞI CHẠY
document.addEventListener('DOMContentLoaded', () => {
    initNavMarker();
    initSearch();
    initMobileMenu();
    loadNews();
});
