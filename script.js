window.addEventListener('DOMContentLoaded', () => {
    // 1. Logic ẩn/hiện Intro
    setTimeout(() => {
        document.body.classList.remove('not-loaded');
        document.body.classList.add('loaded');
        reveal();
    }, 4200);

    const nav = document.querySelector('nav');
    const marker = document.querySelector('.nav-marker');
    const navItems = document.querySelectorAll('.nav-item');
    const navLinksContainer = document.querySelector('.nav-links');
    
    const parallaxT = document.getElementById('parallax-t');
    const aboutSection = document.getElementById('about');

    // 2. Các hiệu ứng khi Cuộn trang
    window.addEventListener('scroll', () => {
        // Đổi màu thanh Menu
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }

        // Trượt chữ T
        if (parallaxT && aboutSection) {
            const scrollPos = window.scrollY;
            const sectionTop = aboutSection.offsetTop;
            const sectionHeight = aboutSection.offsetHeight;

            if (scrollPos > sectionTop - window.innerHeight && scrollPos < sectionTop + sectionHeight) {
                let yOffset = (scrollPos - sectionTop) * 0.2; 
                parallaxT.style.transform = `translateY(calc(-50% + ${yOffset}px))`;
            }
        }

        // Gọi hàm hiện nội dung
        reveal();
    });

    // 3. Hàm hiệu ứng hiện dần nội dung (Reveal)
    function reveal() {
        const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
        for (let i = 0; i < reveals.length; i++) {
            let windowHeight = window.innerHeight;
            let elementTop = reveals[i].getBoundingClientRect().top;
            let elementVisible = 100;
            
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add('active');
            }
        }
    }

    // 4. Hiệu ứng Marker trượt (Pill effect) tinh gọn
    navItems.forEach(item => {
        item.addEventListener('mouseenter', (e) => {
            const target = e.currentTarget;
            marker.style.left = target.offsetLeft + "px";
            marker.style.width = target.offsetWidth + "px";
            marker.style.opacity = "1";
        });
    });

    if(navLinksContainer) {
        navLinksContainer.addEventListener('mouseleave', () => {
            marker.style.opacity = "0";
            marker.style.width = "0";
        });
    }
});

// =========================================
// --- LOGIC CHO MOBILE MENU (ĐIỆN THOẠI) ---
// =========================================
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.getElementById('mobile-menu');
    const navLinks = document.querySelector('.nav-links');

    if (mobileMenuBtn && navLinks) {
        const icon = mobileMenuBtn.querySelector('i');

        // Hàm ĐÓNG TẤT CẢ
        const closeMenu = () => {
            navLinks.classList.remove('active');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
            // Ép buộc dọn dẹp sạch sẽ các khoảng trống của menu con
            document.querySelectorAll('.sub-menu').forEach(sub => {
                sub.classList.remove('mobile-active');
                sub.style.display = 'none'; // Xóa sổ hoàn toàn khoảng trống
            });
        };

        // 1. Nút 3 gạch
        mobileMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            navLinks.classList.toggle('active');
            
            if (navLinks.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                closeMenu(); // Nếu đóng menu chính thì dọn dẹp luôn menu con
            }
        });

        // 2. Bấm ra ngoài
        document.addEventListener('click', (e) => {
            if (navLinks.classList.contains('active') && !navLinks.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                closeMenu();
            }
        });

        // 3. Xử lý chạm vào các mục
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            const link = item.querySelector('a');
            const subMenu = item.querySelector('.sub-menu');

            if (subMenu) {
                link.addEventListener('click', (e) => {
                    if (window.innerWidth <= 768) {
                        e.preventDefault(); 
                        
                        // Kiểm tra xem menu này đang mở hay đang đóng
                        const isCurrentlyActive = subMenu.classList.contains('mobile-active');
                        
                        // ĐÓNG TẤT CẢ menu con khác lại (Accordion effect)
                        document.querySelectorAll('.sub-menu').forEach(sub => {
                            sub.classList.remove('mobile-active');
                            sub.style.display = 'none'; // Xóa khoảng trống
                        });

                        // Nếu menu nãy chưa mở, thì bây giờ xổ nó xuống
                        if (!isCurrentlyActive) {
                            subMenu.classList.add('mobile-active');
                            subMenu.style.display = 'block'; // Hiện khung mới
                        }
                    }
                });

                // Bấm vào mục con bên trong
                const subLinks = subMenu.querySelectorAll('li a');
                subLinks.forEach(subLink => {
                    subLink.addEventListener('click', () => {
                        if (window.innerWidth <= 768) closeMenu();
                    });
                });
            } else {
                // Bấm vào mục không có menu con
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) closeMenu();
                });
            }
        });
    }
});