/**
 * T-ELEC ENERGY - PROJECTS JS
 * Load dự án từ CMS database qua API
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

// HÀM TÌM KIẾM
function initSearch() {
    const searchInput = document.getElementById('productSearch');
    if (!searchInput) return;
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(card => {
            const name = card.querySelector('.product-name')?.innerText.toLowerCase() || '';
            card.style.display = name.includes(term) ? 'flex' : 'none';
            if (name.includes(term)) card.classList.add('active');
        });
    });
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

// TẠO THẺ DỰ ÁN
function createProjectCard(p) {
    const img  = p.image || 'img/z7628849502887_fc48529b4c55a578a2e8812f149a884d.jpg';
    const link = `du-an.php?id=${p.id}`;
    return `
        <div class="product-item reveal" onclick="window.location.href='${link}'" style="cursor:pointer">
            <div class="img-box">
                <img src="${img}" alt="${p.title}" loading="lazy">
            </div>
            <div class="product-name">${p.title}</div>
            <div class="product-bottom">
                <span class="price" style="color:var(--orange);font-weight:700">${p.capacity || 'Tìm hiểu thêm'}</span>
                <div class="btn-mini" style="color:var(--blue);font-size:20px"><i class="fas fa-arrow-right"></i></div>
            </div>
        </div>`;
}

// LOAD DỰ ÁN TỪ API
async function loadProjects() {
    const smGrid = document.getElementById('smarthome-grid');
    const solGrid = document.getElementById('solar-grid');

    try {
        const res  = await fetch('api/projects.php?limit=50');
        const json = await res.json();

        if (!json.success || !json.data.length) {
            showEmpty(smGrid);
            showEmpty(solGrid);
            return;
        }

        const groups = { smarthome: [], solar: [], other: [] };
        json.data.forEach(p => {
            const cat = groups[p.category] !== undefined ? p.category : 'other';
            groups[cat].push(p);
        });

        renderGroup(smGrid, groups.smarthome);
        renderGroup(solGrid, groups.solar);
        initScrollReveal();

    } catch (e) {
        showEmpty(smGrid);
        showEmpty(solGrid);
    }
}

function renderGroup(grid, items) {
    if (!grid) return;
    if (!items || !items.length) {
        showEmpty(grid);
        return;
    }
    grid.innerHTML = items.map(p => createProjectCard(p)).join('');
}

function showEmpty(grid) {
    if (!grid) return;
    grid.innerHTML = '<p style="color:#94a3b8;font-size:14px;padding:20px 0">Chưa có dự án nào.</p>';
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
    initNavMarker();
    initSearch();
    initMobileMenu();
    loadProjects();
});
