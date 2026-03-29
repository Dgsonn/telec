/**
 * T-ELEC ENERGY - SOLAR & LITHIUM JS
 * Load sản phẩm từ CMS database qua API
 */

// HÀM TẠO THẺ SẢN PHẨM (nhận dữ liệu từ DB)
function createProductCard(product, isFeatured = false) {
    const animClass = isFeatured ? 'active' : 'reveal';
    const featBadge = isFeatured ? 'featured-item' : '';
    const img = product.image
        ? product.image
        : `https://placehold.co/400x400/f1f5f9/1e293b?font=montserrat&text=${encodeURIComponent((product.name || '').substring(0, 15))}`;
    const price = product.price_label || 'Liên hệ';
    return `
        <div class="product-item ${animClass} ${featBadge}">
            <div class="img-box">
                <img src="${img}" alt="${product.name}" loading="lazy">
            </div>
            <div class="product-name">${product.name}</div>
            <div class="product-bottom">
                <span class="price">${price}</span>
                <a href="san-pham.php?id=${product.id}" class="btn-mini"><i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>`;
}

// Chuẩn hóa tên brand để so sánh
function normBrand(str) {
    return (str || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9]/g, '');
}

// LOAD SẢN PHẨM TỪ API
async function loadProducts() {
    try {
        const res      = await fetch('api/products.php?cat=solar&limit=200');
        const json     = await res.json();
        const products = (json.success && json.data.length) ? json.data : [];

        // Render vào từng brand-grid: khớp nếu brand bắt đầu bằng grid-key hoặc ngược lại
        document.querySelectorAll('.brand-section').forEach(section => {
            const grid = section.querySelector('.product-grid[id]');
            if (!grid) return;
            const gridKey = normBrand(grid.id.replace(/-grid$/, ''));
            const items   = products.filter(p => {
                const pKey = normBrand(p.brand);
                return pKey && (pKey === gridKey || pKey.startsWith(gridKey) || gridKey.startsWith(pKey));
            });
            if (items.length) {
                grid.innerHTML = items.map(p => createProductCard(p)).join('');
                section.style.display = '';
            } else {
                section.style.display = 'none';
            }
        });

        // Đề xuất hôm nay
        const featuredGrid    = document.getElementById('featured-grid');
        const featuredSection = featuredGrid ? featuredGrid.closest('section') : null;
        if (featuredGrid) {
            if (products.length) {
                const shuffled = [...products].sort(() => 0.5 - Math.random());
                featuredGrid.innerHTML = shuffled.slice(0, 3).map(p => createProductCard(p, true)).join('');
                if (featuredSection) featuredSection.style.display = '';
            } else {
                if (featuredSection) featuredSection.style.display = 'none';
            }
        }

        reveal();
    } catch (e) {
        console.warn('Không thể tải sản phẩm từ server.');
    }
}

// HIỆU ỨNG NAV MARKER
function initNavMarker() {
    const marker            = document.querySelector('.nav-marker');
    const navItems          = document.querySelectorAll('.nav-item');
    const navLinksContainer = document.querySelector('.nav-links');

    if (!marker || navItems.length === 0) return;

    navItems.forEach(item => {
        item.addEventListener('mouseenter', (e) => {
            const target = e.currentTarget;
            marker.style.left    = target.offsetLeft + "px";
            marker.style.width   = target.offsetWidth + "px";
            marker.style.opacity = "1";
        });
    });

    if (navLinksContainer) {
        navLinksContainer.addEventListener('mouseleave', () => {
            marker.style.opacity = "0";
            marker.style.width   = "0";
        });
    }

    const nav = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 50);
    });
}

// LOGIC TÌM KIẾM
function initSearch() {
    const searchInput = document.getElementById('productSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(card => {
            const name = card.querySelector('.product-name').innerText.toLowerCase();
            if (name.includes(term)) {
                card.style.display   = 'flex';
                card.style.opacity   = '1';
                card.style.transform = 'translateY(0)';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

// HIỆU ỨNG REVEAL
function reveal() {
    document.querySelectorAll('.reveal').forEach(el => {
        if (el.getBoundingClientRect().top < window.innerHeight - 50) {
            el.classList.add('active');
        }
    });
}

// PARALLAX CHỮ T NỀN
function initParallax() {
    window.addEventListener('scroll', () => {
        document.querySelectorAll('.bg-scroll-t').forEach(t => {
            const rect = t.parentElement.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                t.style.transform = `translateY(calc(-50% + ${rect.top * 0.1}px))`;
            }
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

// KHỞI CHẠY
document.addEventListener('DOMContentLoaded', () => {
    loadProducts();
    initNavMarker();
    initSearch();
    initParallax();
    initMobileMenu();
    window.addEventListener('scroll', reveal);
    reveal();
});
