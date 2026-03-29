-- =============================================
-- TELEC CMS - Database Schema
-- =============================================

-- Database đã được tạo sẵn trên hosting, không cần lệnh CREATE DATABASE

-- Bảng quản trị viên
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tài khoản mặc định: admin / telec@2024
INSERT INTO `users` (`username`, `password`, `full_name`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên');
-- Mật khẩu mặc định: password (đổi ngay sau khi cài đặt!)
-- Để tạo mật khẩu mới: php -r "echo password_hash('matkhau_moi', PASSWORD_DEFAULT);"

-- Bảng sản phẩm
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `category` ENUM('inverter','solar','smarthome','other') NOT NULL DEFAULT 'other',
    `brand` VARCHAR(100),
    `description` TEXT,
    `specs` TEXT COMMENT 'Thông số kỹ thuật (JSON hoặc text)',
    `image` VARCHAR(255),
    `price_label` VARCHAR(100) COMMENT 'VD: Liên hệ, 15.000.000đ',
    `link_detail` VARCHAR(255) COMMENT 'Link trang chi tiết (nếu có)',
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bảng dự án
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `client_name` VARCHAR(255),
    `location` VARCHAR(255),
    `category` ENUM('solar','smarthome','other') DEFAULT 'solar',
    `description` TEXT,
    `image` VARCHAR(255),
    `capacity` VARCHAR(100) COMMENT 'VD: 30kW Hybrid',
    `project_date` DATE,
    `link_detail` VARCHAR(255) COMMENT 'Link trang chi tiết dự án',
    `is_active` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bảng tin tức
CREATE TABLE IF NOT EXISTS `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE,
    `summary` VARCHAR(500) COMMENT 'Tóm tắt ngắn',
    `content` LONGTEXT,
    `image` VARCHAR(255),
    `is_active` TINYINT(1) DEFAULT 1,
    `views` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bảng thông tin liên hệ (key-value)
CREATE TABLE IF NOT EXISTS `contact_info` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `key_name` VARCHAR(100) UNIQUE NOT NULL,
    `label` VARCHAR(100) NOT NULL,
    `value` TEXT NOT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Dữ liệu mặc định cho contact_info
INSERT INTO `contact_info` (`key_name`, `label`, `value`) VALUES
('phone_main', 'Số điện thoại chính', '0909 999 999'),
('phone_zalo', 'Số Zalo', '0909 999 999'),
('email', 'Email', 'contact@telec.vn'),
('facebook_url', 'Link Facebook', 'https://facebook.com/telec'),
('zalo_url', 'Link Zalo', 'https://zalo.me/0909999999'),
('youtube_url', 'Link YouTube', ''),
('address', 'Địa chỉ', 'Hà Nội, Việt Nam'),
('working_hours', 'Giờ làm việc', 'Thứ 2 - Thứ 7: 8:00 - 17:30');
