/**
 * TELEC - Contact Loader
 * Tự động load thông tin liên hệ từ database và cập nhật footer
 */
(function () {
    fetch('api/contact.php')
        .then(r => r.json())
        .then(res => {
            if (!res.success) return;
            const d = res.data;

            // Cập nhật số điện thoại
            document.querySelectorAll('[data-contact="phone"]').forEach(el => {
                if (d.phone_main) el.textContent = d.phone_main;
            });

            // Cập nhật email
            document.querySelectorAll('[data-contact="email"]').forEach(el => {
                if (d.email) el.textContent = d.email;
            });

            // Cập nhật địa chỉ
            document.querySelectorAll('[data-contact="address"]').forEach(el => {
                if (d.address) el.textContent = d.address;
            });

            // Cập nhật link Facebook
            document.querySelectorAll('[data-contact="facebook"]').forEach(el => {
                if (d.facebook_url) el.href = d.facebook_url;
            });

            // Cập nhật link Zalo
            document.querySelectorAll('[data-contact="zalo"]').forEach(el => {
                if (d.zalo_url) el.href = d.zalo_url;
            });

            // Cập nhật link YouTube
            document.querySelectorAll('[data-contact="youtube"]').forEach(el => {
                if (d.youtube_url) el.href = d.youtube_url;
            });

            // Cập nhật link tel: trên nút gọi điện
            document.querySelectorAll('[data-contact="phone-link"]').forEach(el => {
                if (d.phone_main) {
                    el.href = 'tel:' + d.phone_main.replace(/[^0-9]/g, '');
                }
            });
        })
        .catch(() => {}); // Giữ nguyên nội dung tĩnh nếu lỗi
})();
